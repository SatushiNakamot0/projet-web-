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
        $users = User::where('id', '!=', auth()->id())->latest('date_inscription')->get();
        return response()->json($users);
    }

    // "Activer / désactiver / supprimer compte" - Mise à jour du statut
    public function updateStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'statut' => 'required|in:actif,suspendu,banni'
        ]);

        $user->update(['statut' => $validated['statut']]);

        return response()->json([
            'message' => 'Statut de l\'utilisateur mis à jour.',
            'user' => $user
        ]);
    }

    // Suppression définitive du compte
    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return response()->json(['message' => 'Impossible de supprimer un autre administrateur.'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé définitivement.']);
    }
}
