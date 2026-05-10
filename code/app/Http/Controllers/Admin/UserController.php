<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // "Gérer les utilisateurs" - Liste des utilisateurs
    public function index()
    {
        // On récupère tous les utilisateurs sauf nous-mêmes bach l'admin maybannich rasso
        $users = User::where('id', '!=', auth()->id())
            ->withCount('annonces')
            ->latest('date_inscription')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    // "Activer / désactiver / supprimer compte" - Mise à jour du statut
    public function updateStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'statut' => 'required|in:actif,suspendu,banni'
        ]);

        $user->update(['statut' => $validated['statut']]);

        return redirect()->route('admin.users.index')->with('success', 'Statut de ' . $user->prenom . ' mis à jour ✅');
    }

    // Suppression définitive du compte
    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Impossible de supprimer un administrateur.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé définitivement.');
    }
}
