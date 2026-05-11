<?php

namespace Database\Seeders;

use App\Models\Annonce;
use App\Models\Photo;
use App\Models\User;
use App\Models\Categorie;
use Illuminate\Database\Seeder;

class AnnonceSeeder extends Seeder
{
    public function run(): void
    {
        $membre = User::where('email', 'membre@annonces.ma')->first();
        if (!$membre) {
            $this->command->error('Utilisateur membre@annonces.ma introuvable.');
            return;
        }

        $categories = Categorie::all()->keyBy('nom');
        if ($categories->isEmpty()) {
            $this->command->error('Aucune catégorie trouvée.');
            return;
        }

        $annonces = [
            [
                'titre'       => 'Villa de standing à proximité d\'Issen – Terrain 13 000 m²',
                'description' => "À seulement quelques minutes d'Issen, cette magnifique villa vous offre un cadre de vie paisible et spacieux, au cœur d'un environnement naturel exceptionnel.\nSuperficie du terrain : 13 000 m²\nSurface construite : 500 m² par étage, soit 1 000 m² au total sur 2 niveaux\nLa propriété est idéale pour un projet résidentiel, touristique ou familial haut de gamme.",
                'prix'        => null,
                'categorie'   => 'Immobilier',
                'image'       => 'pic1.jpg',
            ],
            [
                'titre'       => 'Dell Latitude 7410 Core i7 – 16GB RAM – 256GB SSD',
                'description' => "Le Dell Latitude 7410 CORE-I7 (I7-10610U)/1.8GHZ-16GB-256GB SSD WIN11PRO\nPour des performances fiables.\nPrix : 3 500 Dhs.\nLivraison partout au Maroc.",
                'prix'        => 3500.00,
                'categorie'   => 'Électronique',
                'image'       => 'pic2.jpg',
            ],
            [
                'titre'       => 'iPhone 12 Mini – Nouveau 128GB avec Garantie 3 mois',
                'description' => "iPhone 12 mini Nouveau 128 GB 2200dh, 64 GB 2000dh\nBatterie à partir de 90% avec Garantie 3 mois et Livraison disponible.",
                'prix'        => 2200.00,
                'categorie'   => 'Électronique',
                'image'       => 'pic4.jpg',
            ],
            [
                'titre'       => 'Appartement Garden Taddart – Studios à partir de 885 000 DH',
                'description' => "Projet GARDEN TADDART, situé au quartier Bachkou.\nStudios de 36 à 64m² et Appartement de 2 chambres de 77m².\nMarbre au sol, parquet aux chambres, cuisine équipée, climatisation centrale.",
                'prix'        => 885000.00,
                'categorie'   => 'Immobilier',
                'image'       => 'pic5.jpg',
            ],
            [
                'titre'       => 'Bureau de direction en bois – Design moderne avec rangements',
                'description' => "Bureau de direction de haute qualité en bois avec finitions modernes.\nDesign professionnel avec de nombreux rangements intégrés.\nMatériau : Bois mélaminé haute qualité, couleur Noyer.",
                'prix'        => 4500.00,
                'categorie'   => 'Maison & Jardin',
                'image'       => 'PIC6.jpg',
            ],
            [
                'titre'       => 'Renault Megane – Très bon état, faible kilométrage',
                'description' => "Renault Megane en excellent état, modèle récent avec faible kilométrage.\nMoteur diesel économique, climatisation automatique, jantes alliage.\nPrix négociable.",
                'prix'        => 125000.00,
                'categorie'   => 'Véhicules',
                'image'       => 'pic7.jpg',
            ],
            [
                'titre'       => 'Batterie de cuisine CEM – 7 pièces antiadhésif',
                'description' => "Set complet de 7 pièces CEM.\nRevêtement antiadhésif, tous feux sauf induction, PFOA Free.",
                'prix'        => 450.00,
                'categorie'   => 'Maison & Jardin',
                'image'       => 'pic8.jpg',
            ],
            [
                'titre'       => 'Paniers en rotin naturel – Lot de 2 avec anses',
                'description' => "Lot de 2 paniers en rotin naturel avec anses tressées.\nGrand panier Ø 40 cm, petit panier Ø 30 cm.\nIdéal pour salon, terrasse ou jardin.",
                'prix'        => 350.00,
                'categorie'   => 'Maison & Jardin',
                'image'       => 'pic9.jpg',
            ],
            [
                'titre'       => 'Cache-pot trapèze en résine tressée – Gris anthracite',
                'description' => "Cache-pot design en forme de trapèze, résine tressée effet rotin.\nRésistant aux intempéries, léger et durable.",
                'prix'        => 280.00,
                'categorie'   => 'Maison & Jardin',
                'image'       => 'pic10.jpg',
            ],
            [
                'titre'       => 'Samsung TV Full HD – Écran 40 pouces neuf',
                'description' => "Téléviseur Samsung Full HD 1080p, 40 pouces.\nConnectivité HDMI, USB, Tuner TNT, son Dolby Digital.\nNeuf dans son emballage avec garantie.",
                'prix'        => 2800.00,
                'categorie'   => 'Électronique',
                'image'       => 'PIC11.jpg',
            ],
            [
                'titre'       => 'Lit coffre rangement 140x190 – Sur commande 4 jours',
                'description' => "Lit coffre sur commande, livraison en 4 jours.\nLit 140/190 — 2 000 DH, Lit 160/190 — 2 300 DH.\nPlusieurs tailles et coloris disponibles.",
                'prix'        => 2000.00,
                'categorie'   => 'Maison & Jardin',
                'image'       => null,
            ],
        ];

        foreach ($annonces as $data) {
            $categorie = $categories->get($data['categorie']);
            if (!$categorie) continue;

            $annonce = Annonce::create([
                'id_utilisateur' => $membre->id,
                'id_categorie'   => $categorie->id,
                'titre'          => $data['titre'],
                'description'    => $data['description'],
                'prix'           => $data['prix'],
                'statut'         => 'publiee',
            ]);

            if ($data['image']) {
                Photo::create([
                    'id_annonce'  => $annonce->id,
                    'url'         => '/storage/photos/' . $data['image'],
                    'nom_fichier' => $data['image'],
                    'ordre'       => 1,
                ]);
            }
        }
    }
}
