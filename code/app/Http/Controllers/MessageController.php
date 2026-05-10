<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the user's conversations (Inbox).
     */
    public function index()
    {
        $userId = Auth::id();

        // On cherche tous les messages où l'utilisateur est expéditeur ou destinataire
        // Ensuite on groupe par l'autre utilisateur pour avoir une liste de conversations
        $messages = Message::where('id_expediteur', $userId)
            ->orWhere('id_destinataire', $userId)
            ->with(['expediteur', 'destinataire'])
            ->latest('date_envoi')
            ->get();

        // Grouper les messages par conversation (avec l'autre utilisateur)
        $conversations = [];
        
        foreach ($messages as $message) {
            $otherUserId = ($message->id_expediteur == $userId) ? $message->id_destinataire : $message->id_expediteur;
            
            if (!isset($conversations[$otherUserId])) {
                $otherUser = ($message->id_expediteur == $userId) ? $message->destinataire : $message->expediteur;
                
                // Compter les messages non lus dans cette conversation
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

    /**
     * Display the chat between logged in user and another user.
     */
    public function show(User $user)
    {
        $userId = Auth::id();
        $otherUserId = $user->id;

        // Marquer tous les messages reçus de cet utilisateur comme lus
        Message::where('id_expediteur', $otherUserId)
               ->where('id_destinataire', $userId)
               ->where('lu', false)
               ->update(['lu' => true]);

        // Récupérer l'historique de la conversation
        $messages = Message::where(function($query) use ($userId, $otherUserId) {
                $query->where('id_expediteur', $userId)
                      ->where('id_destinataire', $otherUserId);
            })
            ->orWhere(function($query) use ($userId, $otherUserId) {
                $query->where('id_expediteur', $otherUserId)
                      ->where('id_destinataire', $userId);
            })
            ->with(['annonce']) // Au cas où on veut afficher l'annonce liée
            ->orderBy('date_envoi', 'asc')
            ->get();

        return view('messages.show', compact('user', 'messages'));
    }

    /**
     * Store a newly created message in storage.
     */
    public function store(Request $request, User $user)
    {
        $request->validate([
            'contenu' => 'required|string|max:1000',
            'id_annonce' => 'nullable|exists:annonces,id'
        ]);

        $id_annonce = $request->id_annonce;

        // Si l'id_annonce n'est pas fourni (ex: l'utilisateur répond depuis la boîte de réception)
        // on récupère l'id_annonce du dernier message de la conversation
        if (!$id_annonce) {
            $lastMessage = Message::where(function($q) use ($user) {
                $q->where('id_expediteur', Auth::id())->where('id_destinataire', $user->id);
            })->orWhere(function($q) use ($user) {
                $q->where('id_expediteur', $user->id)->where('id_destinataire', Auth::id());
            })->latest('id')->first();

            if ($lastMessage) {
                $id_annonce = $lastMessage->id_annonce;
            } else {
                // S'il n'y a pas de message précédent, on prend la première annonce de l'utilisateur par défaut pour éviter l'erreur SQL
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
