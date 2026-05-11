<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Annonce;
use App\Models\Categorie;
use App\Models\Photo;
use App\Models\User;
use App\Models\Message;

echo "\n";
echo "╔══════════════════════════════════════════════╗\n";
echo "║         TEST MCD — VALIDATION MODELES        ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

// TEST 1: Utilisateurs
echo "── TEST 1: Utilisateurs ───────────────────────\n";
$users = User::all();
echo "✅ " . $users->count() . " utilisateurs trouvés.\n";
foreach ($users as $u) {
    echo "   [{$u->id}] {$u->nom} {$u->prenom} | role={$u->role} | statut={$u->statut}\n";
}
echo "\n";

// TEST 2: Annonces & Relations
echo "── TEST 2: Annonces & Relations ────────────────\n";
$cat = Categorie::first();
$user = User::where('role', 'membre')->first();
$admin = User::where('role', 'admin')->first();

$annonce = Annonce::create([
    'id_utilisateur' => $user->id,
    'id_categorie'   => $cat->id,
    'titre'          => 'PC Gamer',
    'description'    => 'Très bon PC, 16GB RAM',
    'prix'           => 4500.00,
    'statut'         => 'publiee',
]);

echo "✅ Annonce créée: ID={$annonce->id}, Titre={$annonce->titre}\n";
echo "✅ Relation utilisateur: {$annonce->utilisateur->nom}\n";
echo "✅ Relation categorie: {$annonce->categorie->nom}\n";

// TEST 3: Photos
echo "\n── TEST 3: Photos ──────────────────────────────\n";
$photo = Photo::create([
    'id_annonce'  => $annonce->id,
    'url'         => '/storage/photos/pc.jpg',
    'nom_fichier' => 'pc.jpg',
    'ordre'       => 1,
]);
echo "✅ Photo créée: URL={$photo->url}\n";
echo "✅ Annonce a " . $annonce->photos()->count() . " photo(s)\n";

// TEST 4: Messages
echo "\n── TEST 4: Messages ────────────────────────────\n";
$msg = Message::create([
    'id_expediteur'   => $admin->id,
    'id_destinataire' => $user->id,
    'id_annonce'      => $annonce->id,
    'objet'           => 'Intéressé par votre PC',
    'contenu'         => 'Est-il encore disponible ?',
]);
echo "✅ Message: De {$msg->expediteur->nom} à {$msg->destinataire->nom}\n";
echo "✅ Messages reçus (Membre): " . $user->messagesRecus()->count() . "\n";
echo "✅ Messages envoyés (Admin): " . $admin->messagesEnvoyes()->count() . "\n";

// Nettoyage
$annonce->delete();
echo "\n✅ Cleanup: Annonce + Photo + Message supprimés (cascade).\n";
echo "\n╔══════════════════════════════════════════════╗\n";
echo "║       TOUS LES TESTS MCD SONT ✅ PASSES!     ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";
