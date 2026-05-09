<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnnonceController extends Controller
{
    
    public function index(Request $request)
{
    $query = Annonce::with(['user', 'categorie'])->active();

    if ($request->filled('q')) {
        $query->search($request->q);
    }
    if ($request->filled('categorie')) {
        $query->where('categorie_id', $request->categorie);
    }
    if ($request->filled('ville')) {
        $query->where('ville', 'like', '%' . $request->ville . '%');
    }
    if ($request->filled('prix_max')) {
        $query->where('prix', '<=', $request->prix_max);
    }

    $tri = $request->get('tri', 'recent');
    match ($tri) {
        'prix_asc'  => $query->orderBy('prix', 'asc'),
        'prix_desc' => $query->orderBy('prix', 'desc'),
        default     => $query->latest(),
    };

    $categories = Categorie::all();
    $villes     = Annonce::active()->distinct()->pluck('ville')->filter()->sort()->values();

    
    if ($request->routeIs('home')) {
        $annonces = $query->latest()->take(6)->get();
        return view('annonces.index-home', compact('annonces', 'categories', 'villes'));
    }

    
    $annonces = $query->paginate(12)->withQueryString();
    return view('annonces.index', compact('annonces', 'categories', 'villes'));
}


    public function show(Annonce $annonce)
    {
        $annonce->load(['user', 'categorie']);

        $similaires = Annonce::with(['categorie'])
            ->active()
            ->where('categorie_id', $annonce->categorie_id)
            ->where('id', '!=', $annonce->id)
            ->latest()
            ->take(4)
            ->get();


        return view('annonces.show', compact('annonce', 'similaires'));
    }


        public function create(){
    $categories = Categorie::all();
    return view('annonces._form', compact('categories'));}



    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'        => 'required|string|min:5|max:255',
            'description'  => 'required|string|min:20',
            'prix'         => 'nullable|numeric|min:0',
            'ville'        => 'nullable|string|max:100',
            'categorie_id' => 'required|exists:categories,id',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'titre.required'        => 'Le titre est obligatoire.',
            'titre.min'             => 'Le titre doit contenir au moins 5 caractères.',
            'description.required'  => 'La description est obligatoire.',
            'description.min'       => 'La description doit contenir au moins 20 caractères.',
            'categorie_id.required' => 'Veuillez choisir une catégorie.',
            'image.image'           => 'Le fichier doit être une image.',
            'image.max'             => 'L\'image ne doit pas dépasser 2 Mo.',
        ]);

        $validated['user_id'] = Auth::id();

        // Upload image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('annonces', 'public');
        }

        $annonce = Annonce::create($validated);

        return redirect()
            ->route('annonces.show', $annonce)
            ->with('success', ' Votre annonce a été publiée avec succès !');
    }

    
    public function edit(Annonce $annonce){
    $this->authorize($annonce);
    $categories = Categorie::all();
    return view('annonces._form', compact('annonce', 'categories'));}


    public function update(Request $request, Annonce $annonce)
    {
        $this->authorize($annonce);

        $validated = $request->validate([
            'titre'        => 'required|string|min:5|max:255',
            'description'  => 'required|string|min:20',
            'prix'         => 'nullable|numeric|min:0',
            'ville'        => 'nullable|string|max:100',
            'categorie_id' => 'required|exists:categories,id',
            'statut'       => 'required|in:active,inactive,vendue',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);


        if ($request->hasFile('image')) {
            if ($annonce->image) {
                Storage::disk('public')->delete($annonce->image);
            }
            $validated['image'] = $request->file('image')->store('annonces', 'public');
        }

        $annonce->update($validated);

        return redirect()
            ->route('annonces.show', $annonce)
            ->with('success', ' Annonce mise à jour avec succès !');
    }


    public function destroy(Annonce $annonce)
    {
        $this->authorize($annonce);

        if ($annonce->image) {
            Storage::disk('public')->delete($annonce->image);
        }

        $annonce->delete();

        return redirect()
            ->route('annonces.mes')
            ->with('success', ' Annonce supprimée.');
    }


    public function mesAnnonces(Request $request)
    {
        $query = Annonce::with('categorie')
            ->where('user_id', Auth::id());

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $annonces = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total'    => Annonce::where('user_id', Auth::id())->count(),
            'actives'  => Annonce::where('user_id', Auth::id())->where('statut', 'active')->count(),
            'vendues'  => Annonce::where('user_id', Auth::id())->where('statut', 'vendue')->count(),
        ];

        return view('annonces.mes-annonces', compact('annonces', 'stats'));
    }


    protected function authorize(Annonce $annonce): void
    {
        if ($annonce->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }
    }
}
