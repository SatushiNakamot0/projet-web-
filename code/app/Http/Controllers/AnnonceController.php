<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Categorie;
use App\Models\Photo;
use App\Mail\AnnonceSoumise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AnnonceController extends Controller
{
    // Afficher ga3 les annonces publiées (Visiteur + Membre)
    public function index(Request $request)
    {
        $query = Annonce::active()->with(['photos', 'categorie']);

        if ($request->filled('q')) {
            $query->where('titre', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('categorie')) {
            $query->where('id_categorie', $request->categorie);
        }
        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        $tri = $request->get('tri', 'recent');
        match ($tri) {
            'prix_asc'  => $query->orderBy('prix', 'asc'),
            'prix_desc' => $query->orderBy('prix', 'desc'),
            default     => $query->latest('date_publication'),
        };

        $categories = Categorie::all();
        $villes = [];

        if ($request->routeIs('home') || $request->path() === '/') {
            $annonces = $query->latest('date_publication')->take(6)->get();
            return view('annonces.index-home', compact('annonces', 'categories', 'villes'));
        }

        $annonces = $query->paginate(12)->withQueryString();
        return view('annonces.index', compact('annonces', 'categories', 'villes'));
    }

    // Annonces dyal l'utilisateur connecté ("Mes annonces")
    public function mesAnnonces(Request $request)
    {
        $query = Auth::user()->annonces()->with(['photos', 'categorie']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $annonces = $query->latest('date_publication')->paginate(10)->withQueryString();

        $stats = [
            'total'    => Auth::user()->annonces()->count(),
            'actives'  => Auth::user()->annonces()->where('statut', 'publiee')->count(),
            'vendues'  => 0,
        ];

        return view('annonces.mes-annonces', compact('annonces', 'stats'));
    }

    // Formulaire de création
    public function create()
    {
        $categories = Categorie::all();
        return view('annonces.create', compact('categories'));
    }

    // Enregistrer une nouvelle annonce
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'        => 'required|string|max:255',
            'description'  => 'required|string',
            'prix'         => 'nullable|numeric|min:0',
            'id_categorie' => 'required|exists:categories,id',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $annonce = Annonce::create([
            'id_utilisateur' => Auth::id(),
            'id_categorie'   => $validated['id_categorie'],
            'titre'          => $validated['titre'],
            'description'    => $validated['description'],
            'prix'           => $validated['prix'] ?? null,
            'statut'         => 'en_attente',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('photos', 'public');
            Photo::create([
                'id_annonce'  => $annonce->id,
                'url'         => '/storage/' . $path,
                'nom_fichier' => $request->file('image')->getClientOriginalName(),
                'ordre'       => 1,
            ]);
        }

        try {
            Mail::to($request->user())->send(new AnnonceSoumise($annonce));
        } catch (\Throwable $e) {
            \Log::error("Failed to send email: " . $e->getMessage());
        }

        return redirect()->route('annonces.show', $annonce)->with('success', 'Annonce publiée avec succès, en attente de modération.');
    }

    // Afficher les détails d'une annonce
    public function show(Annonce $annonce)
    {
        if ($annonce->statut !== 'publiee') {
            $user = Auth::user();
            if (!$user || ($user->id !== $annonce->id_utilisateur && !$user->isAdmin())) {
                abort(403, 'Cette annonce n\'est pas encore disponible.');
            }
        }

        $annonce->load(['photos', 'categorie', 'utilisateur']);

        $similaires = Annonce::with(['categorie', 'photos'])
            ->active()
            ->where('id_categorie', $annonce->id_categorie)
            ->where('id', '!=', $annonce->id)
            ->latest('date_publication')
            ->take(4)
            ->get();

        return view('annonces.show', compact('annonce', 'similaires'));
    }

    // Modifier une annonce
    public function edit(Annonce $annonce)
    {
        Gate::authorize('update', $annonce);
        $categories = Categorie::all();
        return view('annonces.edit', compact('annonce', 'categories'));
    }

    public function update(Request $request, Annonce $annonce)
    {
        Gate::authorize('update', $annonce);

        $validated = $request->validate([
            'titre'        => 'required|string|max:255',
            'description'  => 'required|string',
            'prix'         => 'nullable|numeric|min:0',
            'id_categorie' => 'required|exists:categories,id',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $annonce->update([
            'titre'        => $validated['titre'],
            'description'  => $validated['description'],
            'prix'         => $validated['prix'] ?? null,
            'id_categorie' => $validated['id_categorie'],
            'statut'       => 'en_attente',
        ]);

        if ($request->hasFile('image')) {
            if ($annonce->photos->count() > 0) {
                $oldPhoto = $annonce->photos->first();
                $path = str_replace('/storage/', '', $oldPhoto->url);
                Storage::disk('public')->delete($path);
                $oldPhoto->delete();
            }

            $path = $request->file('image')->store('photos', 'public');
            Photo::create([
                'id_annonce'  => $annonce->id,
                'url'         => '/storage/' . $path,
                'nom_fichier' => $request->file('image')->getClientOriginalName(),
                'ordre'       => 1,
            ]);
        }

        return redirect()->route('annonces.show', $annonce)->with('success', 'Annonce mise à jour avec succès.');
    }

    // Supprimer une annonce
    public function destroy(Annonce $annonce)
    {
        Gate::authorize('delete', $annonce);

        foreach ($annonce->photos as $photo) {
            $path = str_replace('/storage/', '', $photo->url);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $annonce->delete();

        return redirect()->route('annonces.mine')->with('success', 'Annonce supprimée avec succès.');
    }
}
