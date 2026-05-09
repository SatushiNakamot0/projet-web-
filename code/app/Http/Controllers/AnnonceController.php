<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Photo;
use App\Mail\AnnonceSoumise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AnnonceController extends Controller
{
    // Lmra dyal les visiteurs: afficher ga3 les annonces publiées
    public function index()
    {
        // Nbiyno ghi lli 'publiee', w nziydo pagination (15 par page)
        $annonces = Annonce::active()->with(['photos', 'categorie'])->latest('date_publication')->paginate(15);
        
        // F l'instant, kantsifto JSON bach ntestiw f backend
        return response()->json($annonces);
    }

    // Afficher les annonces dyal l'utilisateur connecté ("Mes annonces")
    public function mesAnnonces()
    {
        $annonces = Auth::user()->annonces()->with(['photos', 'categorie'])->latest('date_publication')->get();
        return response()->json($annonces);
    }

    // Ajouter une nouvelle annonce
    public function store(Request $request)
    {
        // 1. Validation des champs (kima f diagramme: "Valider les champs")
        $validated = $request->validate([
            'titre'        => 'required|string|max:255',
            'description'  => 'required|string',
            'prix'         => 'nullable|numeric|min:0',
            'id_categorie' => 'required|exists:categories,id',
            'photos'       => 'nullable|array',
            'photos.*'     => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // 2. Création de l'annonce en base de données
        $annonce = Annonce::create([
            'id_utilisateur' => Auth::id(),
            'id_categorie'   => $validated['id_categorie'],
            'titre'          => $validated['titre'],
            'description'    => $validated['description'],
            'prix'           => $validated['prix'] ?? null,
            'statut'         => 'en_attente',
        ]);

        // 3. Téléverser les photos (Ila kaynin)
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $file) {
                // Sauvegarder f storage/app/public/photos
                $path = $file->store('photos', 'public');

                Photo::create([
                    'id_annonce'  => $annonce->id,
                    'url'         => '/storage/' . $path,
                    'nom_fichier' => $file->getClientOriginalName(),
                    'ordre'       => $index + 1,
                ]);
            }
        }

        // 4. Notifier l'utilisateur par email (kima f diagramme: "Notifier confirmation")
        Mail::to($request->user())->send(new AnnonceSoumise($annonce));

        return response()->json([
            'message' => 'Annonce publiée avec succès, en attente de modération.',
            'annonce' => $annonce->load('photos'),
        ], 201);
    }

    // Afficher les détails d'une annonce ("Voir les détails")
    public function show(Annonce $annonce)
    {
        // Nbiyno l'annonce 7ta ila kant 'en_attente' ila kan l'utilisateur howa moulaha wla admin
        if ($annonce->statut !== 'publiee') {
            $user = Auth::user();
            if (!$user || ($user->id !== $annonce->id_utilisateur && !$user->isAdmin())) {
                abort(403, 'Cette annonce n\'est pas encore disponible.');
            }
        }

        return response()->json($annonce->load(['photos', 'categorie', 'utilisateur']));
    }

    // Modifier une annonce ("Modifier les champs")
    public function update(Request $request, Annonce $annonce)
    {
        // Nvériyiw l'autorisation (ghi moul l'annonce 3ndo l7a9 ymodifier)
        Gate::authorize('update', $annonce);

        $validated = $request->validate([
            'titre'        => 'required|string|max:255',
            'description'  => 'required|string',
            'prix'         => 'nullable|numeric|min:0',
            'id_categorie' => 'required|exists:categories,id',
        ]);

        // Ila l'annonce tmodifat, nreje3ouha "en_attente" bach l'admin yvériyiha tany
        $annonce->update([
            'titre'        => $validated['titre'],
            'description'  => $validated['description'],
            'prix'         => $validated['prix'] ?? null,
            'id_categorie' => $validated['id_categorie'],
            'statut'       => 'en_attente', 
        ]);

        return response()->json([
            'message' => 'Annonce mise à jour.',
            'annonce' => $annonce,
        ]);
    }

    // Supprimer une annonce
    public function destroy(Annonce $annonce)
    {
        // Nvériyiw l'autorisation (ghi moul l'annonce 3ndo l7a9 ysupprimer)
        Gate::authorize('delete', $annonce);

        // 1. Nmes7o les fichiers physiques dyal les photos mn serveur
        foreach ($annonce->photos as $photo) {
            $path = str_replace('/storage/', '', $photo->url);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        // 2. Nmes7o l'annonce mn base de données
        $annonce->delete();

        return response()->json(['message' => 'Annonce supprimée avec succès.']);
    }
}
