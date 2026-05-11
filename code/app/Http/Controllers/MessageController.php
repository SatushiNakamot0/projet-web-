<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Boîte de réception — liste des conversations
    public function index()
    {
        $userId = Auth::id();

        $messages = Message::where('id_expediteur', $userId)
            ->orWhere('id_destinataire', $userId)
            ->with(['expediteur', 'destinataire'])
            ->latest('date_envoi')
            ->get();

        // Grouper par conversation (l'autre utilisateur)
        $conversations = [];

        foreach ($messages as $message) {
            $otherUserId = ($message->id_expediteur == $userId) ? $message->id_destinataire : $message->id_expediteur;

            if (!isset($conversations[$otherUserId])) {
                $otherUser = ($message->id_expediteur == $userId) ? $message->destinataire : $message->expediteur;

                $unreadCount = Message::where('id_expediteur', $otherUserId)
                                      ->where('id_destinataire', $userId)
                                      ->where('lu', false)
                                      ->count();

                $conversations[$otherUserId] = [
                    'user' => $otherUser,
                    'last_message' => $message,
                    'unread_count' => $unreadCount
                ];
            }
        }

        return view('messages.index', compact('conversations'));
    }

    // Afficher la conversation avec un utilisateur
    public function show(User $user)
    {
        $userId = Auth::id();
        $otherUserId = $user->id;

        // Marquer les messages reçus comme lus
        Message::where('id_expediteur', $otherUserId)
               ->where('id_destinataire', $userId)
               ->where('lu', false)
               ->update(['lu' => true]);

        $messages = Message::where(function($query) use ($userId, $otherUserId) {
                $query->where('id_expediteur', $userId)
                      ->where('id_destinataire', $otherUserId);
            })
            ->orWhere(function($query) use ($userId, $otherUserId) {
                $query->where('id_expediteur', $otherUserId)
                      ->where('id_destinataire', $userId);
            })
            ->with(['annonce'])
            ->orderBy('date_envoi', 'asc')
            ->get();

        return view('messages.show', compact('user', 'messages'));
    }

    // Envoyer un message
    public function store(Request $request, User $user)
    {
        $request->validate([
            'contenu' => 'required|string|max:1000',
            'id_annonce' => 'nullable|exists:annonces,id'
        ]);

        $id_annonce = $request->id_annonce;

        // Ila ma3tawnach id_annonce, nakhdo dyal dernier message f la conversation
        if (!$id_annonce) {
            $lastMessage = Message::where(function($q) use ($user) {
                $q->where('id_expediteur', Auth::id())->where('id_destinataire', $user->id);
            })->orWhere(function($q) use ($user) {
                $q->where('id_expediteur', $user->id)->where('id_destinataire', Auth::id());
            })->latest('id')->first();

            if ($lastMessage) {
                $id_annonce = $lastMessage->id_annonce;
            } else {
                $firstAnnonce = \App\Models\Annonce::where('id_utilisateur', $user->id)->first();
                $id_annonce = $firstAnnonce ? $firstAnnonce->id : 1;
            }
        }

        Message::create([
            'id_expediteur' => Auth::id(),
            'id_destinataire' => $user->id,
            'id_annonce' => $id_annonce,
            'contenu' => $request->contenu,
            'objet' => $request->id_annonce ? "À propos d'une annonce" : "Message direct",
            'date_envoi' => now(),
            'lu' => false,
        ]);

        return redirect()->route('messages.show', $user)->with('success', 'Message envoyé.');
    }
}
