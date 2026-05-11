<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    // Liste dyal ga3 les annonces (Admin)
    public function index()
    {
        $annonces = Annonce::with(['utilisateur', 'categorie', 'photos'])
            ->latest('date_publication')
            ->get();

        return view('admin.moderation.index', compact('annonces'));
    }

    public function show(Annonce $annonce)
    {
        $annonce->load(['utilisateur', 'categorie', 'photos']);
        return view('admin.moderation.show', compact('annonce'));
    }

    // Approuver l'annonce -> publiée
    public function approve(Annonce $annonce)
    {
        $annonce->update([
            'statut' => 'publiee',
            'motif_rejet' => null
        ]);

        return redirect()->route('admin.moderation.index')->with('success', 'Annonce approuvée et publiée ✅');
    }

    // Rejeter l'annonce avec motif
    public function reject(Request $request, Annonce $annonce)
    {
        $request->validate([
            'motif_rejet' => 'required|string|max:1000'
        ]);

        $annonce->update([
            'statut' => 'rejetee',
            'motif_rejet' => $request->motif_rejet
        ]);

        return redirect()->route('admin.moderation.index')->with('success', 'Annonce rejetée ❌');
    }

    public function destroy(Annonce $annonce)
    {
        $annonce->delete();
        return redirect()->route('admin.moderation.index')->with('success', 'Annonce supprimée définitivement.');
    }
}
