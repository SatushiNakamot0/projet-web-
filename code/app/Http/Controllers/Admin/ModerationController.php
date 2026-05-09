<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    // Lli kaytklf b affichage dyal "Liste annonces en attente"
    public function index()
    {
        // Nrécupérer ga3 les annonces lli mazal en attente
        $annonces = Annonce::enAttente()->with(['utilisateur', 'categorie'])->latest('date_publication')->get();
        return response()->json($annonces);
    }

    // "Consulter une annonce"
    public function show(Annonce $annonce)
    {
        return response()->json($annonce->load(['utilisateur', 'categorie', 'photos']));
    }

    // "Approuver -> Annonce publiée"
    public function approve(Annonce $annonce)
    {
        $annonce->update([
            'statut' => 'publiee',
            'motif_rejet' => null // N7iydo l'motif ila kan deja mrejetté qbl
        ]);

        return response()->json(['message' => 'Annonce approuvée et publiée avec succès.', 'annonce' => $annonce]);
    }

    // "Rejeter -> Annonce rejetée" 
    public function reject(Request $request, Annonce $annonce)
    {
        $request->validate([
            'motif_rejet' => 'required|string|max:1000'
        ]);

        $annonce->update([
            'statut' => 'rejetee',
            'motif_rejet' => $request->motif_rejet
        ]);

        return response()->json(['message' => 'Annonce rejetée.', 'annonce' => $annonce]);
    }
}
