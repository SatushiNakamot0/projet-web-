<?php

namespace Database\Seeders;

use App\Models\Annonce;
use App\Models\Photo;
use App\Models\User;
use App\Models\Categorie;
use Illuminate\Database\Seeder;

class AnnonceSeeder extends Seeder
{
    // N3emrou la table annonces b des exemples réels mn Pictures
    public function run(): void
    {
        // Njibou l'utilisateur membre bach nkhdmou bih
        $membre = User::where('email', 'membre@annonces.ma')->first();
        if (!$membre) {
            $this->command->error('❌ Utilisateur membre@annonces.ma introuvable. Lancez DatabaseSeeder d\'abord.');
            return;
        }

        // Njibou les catégories
        $categories = Categorie::all()->keyBy('nom');
        if ($categories->isEmpty()) {
            $this->command->error('❌ Aucune catégorie trouvée. Lancez CategorieSeeder d\'abord.');
            return;
        }

        // Les annonces exemples basées sur les images du dossier Pictures
        $annonces = [
            [
                'titre'       => 'Villa de standing à proximité d\'Issen – Terrain 13 000 m²',
                'description' => "À seulement quelques minutes d'Issen, cette magnifique villa vous offre un cadre de vie paisible et spacieux, au cœur d'un environnement naturel exceptionnel.\nSuperficie du terrain : 13 000 m²\nSurface construite : 500 m² par étage, soit 1 000 m² au total sur 2 niveaux\nLa propriété est idéale pour un projet résidentiel, touristique ou familial haut de gamme. Elle bénéficie d'un vaste terrain plat, offrant de nombreuses possibilités d'aménagement : jardin paysager, piscine, espace détente.\nCaractéristiques principales :\n• Emplacement calme et facilement accessible\n• Construction solide et spacieuse\n• Potentiel d'aménagement important\n• Environnement verdoyant\nCette villa représente une opportunité rare dans la région pour ceux qui recherchent espace, confort et tranquillité.\nPour plus d'informations ou pour organiser une visite, merci de nous contacter.",
                'prix'        => null,
                'categorie'   => 'Immobilier',
                'image'       => 'pic1.jpg',
            ],
            [
                'titre'       => 'Dell Latitude 7410 Core i7 – 16GB RAM – 256GB SSD',
                'description' => "Le Dell Latitude 7410 CORE-I7 (I7-10610U)/1.8GHZ-16GB-256GB SSD WIN11PRO\nPour des performances fiables, est un excellent choix pour les professionnels recherchant un ordinateur portable fiable, performant et sécurisé pour des tâches bureautiques, de gestion de projets ou de développement léger.\n\n💥 Offre en promo très intéressante à ne pas manquer\nLivré avec une souris Logitech sans fil et écouteur Samsung original.\n\nPrix : 3 500 Dhs.\n\n✅ Livraison partout au Maroc\n✅ Paiement à la livraison possible\n\nDisponible immédiatement.\nContact rapide par Avito ou WhatsApp.",
                'prix'        => 3500.00,
                'categorie'   => 'Électronique',
                'image'       => 'pic2.jpg',
            ],
            [
                'titre'       => 'iPhone 12 Mini – Nouveau 128GB avec Garantie 3 mois',
                'description' => "iPhone 12 mini Nouveau 128 GB 2200dh, 64 GB 2000dh\nBatterie à partir de 90% avec Garantie 3 mois et Livraison disponible 🚚📦✅\n\nPlusieurs coloris disponibles : Rouge, Bleu, Blanc.\nÉtat neuf, jamais utilisé.\n\nContactez-nous pour plus d'informations.",
                'prix'        => 2200.00,
                'categorie'   => 'Électronique',
                'image'       => 'pic4.jpg',
            ],
            [
                'titre'       => 'Appartement Garden Taddart – Studios à partir de 885 000 DH',
                'description' => "Notre équipe est fière d'annoncer le lancement de la commercialisation du projet GARDEN TADDART, situé au quartier Bachkou, à quelques pas du boulevard Taddart.\n\nGARDEN TADDART est un immeuble contemporain, composé de Studios de 36 à 64m² et d'un Appartement de 2 chambres de 77m², aux agencements bien pensés et aux finitions irréprochables.\n\n✔️ Marbre au sol\n✔️ Parquet aux chambres\n✔️ Cuisine équipée\n✔️ Portes iso planes avec cadres en bois massif\n✔️ Serrures électroniques\n✔️ Climatisation centrale\n\nVous recherchez un investissement gagnant ? Vous l'avez trouvé 😉\n\nContactez nous dès à présent pour réserver une visite sur place et concrétisez votre projet immobilier 🏙️",
                'prix'        => 885000.00,
                'categorie'   => 'Immobilier',
                'image'       => 'pic5.jpg',
            ],
            [
                'titre'       => 'Bureau de direction en bois – Design moderne avec rangements',
                'description' => "Bureau de direction de haute qualité en bois avec finitions modernes.\nDesign professionnel avec de nombreux rangements intégrés : tiroirs, placards et caissons.\nIdéal pour bureau d'entreprise, cabinet ou espace de travail à domicile.\n\nMatériau : Bois mélaminé haute qualité\nCouleur : Noyer\nDimensions : Grand format en L\n\nDisponible en magasin, livraison possible.\nContactez-nous pour plus d'informations.",
                'prix'        => 4500.00,
                'categorie'   => 'Maison & Jardin',
                'image'       => 'PIC6.jpg',
            ],
            [
                'titre'       => 'Renault Megane – Très bon état, faible kilométrage',
                'description' => "Renault Megane en excellent état, modèle récent avec faible kilométrage.\nVéhicule très bien entretenu, carnet d'entretien à jour.\n\nCaractéristiques :\n• Moteur diesel économique\n• Boîte manuelle\n• Climatisation automatique\n• Direction assistée\n• Vitres électriques\n• Jantes alliage\n\nCouleur noire, intérieur propre et soigné.\nPrix négociable.\n\nContactez-nous pour essai et visite.",
                'prix'        => 125000.00,
                'categorie'   => 'Véhicules',
                'image'       => 'pic7.jpg',
            ],
            [
                'titre'       => 'Batterie de cuisine CEM – 7 pièces antiadhésif',
                'description' => "Batterie de cuisine CEM de qualité supérieure, set complet de 7 pièces.\n\nContenu :\n• 1 Marmite avec couvercle – Ø 26 cm\n• 1 Marmite avec couvercle – Ø 24 cm\n• 1 Marmite avec couvercle – Ø 20 cm\n• 1 Poêle – Ø 26 cm\n\nCaractéristiques :\n✅ Revêtement antiadhésif sûr\n✅ Tous feux sauf induction\n✅ PFOA Free\n✅ Garantie 100% satisfaction\n\nIdéale pour une cuisine quotidienne saine et facile.\nLivraison disponible.",
                'prix'        => 450.00,
                'categorie'   => 'Maison & Jardin',
                'image'       => 'pic8.jpg',
            ],
            [
                'titre'       => 'Paniers en rotin naturel – Lot de 2 avec anses',
                'description' => "Magnifique lot de 2 paniers en rotin naturel avec anses tressées.\nParfaits pour la décoration intérieure, le rangement ou comme cache-pots.\n\nDimensions :\n• Grand panier : Ø 40 cm\n• Petit panier : Ø 30 cm\n\nMatériau : Rotin naturel tressé à la main\nAvec doublure intérieure en plastique.\n\nIdéal pour salon, terrasse ou jardin.\nLivraison possible.",
                'prix'        => 350.00,
                'categorie'   => 'Maison & Jardin',
                'image'       => 'pic9.jpg',
            ],
            [
                'titre'       => 'Cache-pot trapèze en résine tressée – Gris anthracite',
                'description' => "Cache-pot design en forme de trapèze, résine tressée effet rotin.\nCouleur gris anthracite, parfait pour intérieur et extérieur.\n\nCaractéristiques :\n• Matériau : Résine synthétique haute qualité\n• Résistant aux intempéries\n• Léger et durable\n• Base large pour stabilité\n\nIdéal pour plantes vertes, fleurs ou arbustes.\nAjoutez une touche moderne à votre décoration.\n\nLivraison disponible partout au Maroc.",
                'prix'        => 280.00,
                'categorie'   => 'Maison & Jardin',
                'image'       => 'pic10.jpg',
            ],
            [
                'titre'       => 'Samsung TV Full HD – Écran 40 pouces neuf',
                'description' => "Téléviseur Samsung Full HD, écran 40 pouces.\nQualité d'image exceptionnelle avec couleurs vives et contrastes profonds.\n\nCaractéristiques :\n• Résolution : Full HD 1080p\n• Taille : 40 pouces\n• Connectivité : HDMI, USB, Tuner TNT intégré\n• Son : Dolby Digital\n• Design fin et moderne\n\nIdéal pour salon, chambre ou bureau.\nNeuf dans son emballage d'origine avec garantie constructeur.\n\nLivraison et installation possibles.",
                'prix'        => 2800.00,
                'categorie'   => 'Électronique',
                'image'       => 'PIC11.jpg',
            ],
            [
                'titre'       => 'Lit coffre rangement 140x190 – Sur commande 4 jours',
                'description' => "Lit coffre sur commande, livraison en 4 jours.\n\nTarifs selon les dimensions :\n• Lit rangement 140/190 — 2 000 DH\n• Lit 160/190 — 2 300 DH\n• Lit 160/2m — 2 500 DH\n• Lit une place coffre 90/190 — 1 200 DH\n\nPlusieurs tailles et coloris disponibles.\nLivraison et montage disponibles.\n\nPour plus d'infos, contactez-nous sur WhatsApp.",
                'prix'        => 2000.00,
                'categorie'   => 'Maison & Jardin',
                'image'       => null, // Pas d'image disponible pour pic3
            ],
        ];

        foreach ($annonces as $data) {
            $categorie = $categories->get($data['categorie']);
            if (!$categorie) {
                $this->command->warn("⚠️ Catégorie '{$data['categorie']}' introuvable, on skip.");
                continue;
            }

            $annonce = Annonce::create([
                'id_utilisateur' => $membre->id,
                'id_categorie'   => $categorie->id,
                'titre'          => $data['titre'],
                'description'    => $data['description'],
                'prix'           => $data['prix'],
                'statut'         => 'publiee',
            ]);

            // Ajouter la photo si elle existe
            if ($data['image']) {
                Photo::create([
                    'id_annonce'  => $annonce->id,
                    'url'         => '/storage/photos/' . $data['image'],
                    'nom_fichier' => $data['image'],
                    'ordre'       => 1,
                ]);
            }
        }

        $this->command->info('✅ ' . count($annonces) . ' annonces exemples ajoutées avec succès !');
    }
}
