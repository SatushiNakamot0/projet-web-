<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorieSeeder extends Seeder
{
    // N3emrou la table categories b les données par défaut
    public function run(): void
    {
        $categories = [
            [
                'nom'         => 'Immobilier',
                'description' => 'Appartements, villas, terrains, bureaux...',
            ],
            [
                'nom'         => 'Véhicules',
                'description' => 'Voitures, motos, camions, pièces auto...',
            ],
            [
                'nom'         => 'Électronique',
                'description' => 'Téléphones, ordinateurs, TV, électroménager...',
            ],
            [
                'nom'         => 'Emploi',
                'description' => 'Offres d\'emploi, stages, freelance...',
            ],
            [
                'nom'         => 'Habillement',
                'description' => 'Vêtements, chaussures, accessoires...',
            ],
            [
                'nom'         => 'Maison & Jardin',
                'description' => 'Meubles, décoration, jardinage...',
            ],
            [
                'nom'         => 'Sports & Loisirs',
                'description' => 'Équipements sportifs, jeux, instruments de musique...',
            ],
            [
                'nom'         => 'Autres',
                'description' => 'Tout ce qui ne rentre pas dans les autres catégories',
            ],
        ];

        DB::table('categories')->insert($categories);

        $this->command->info('✅ Categories ajoutées avec succès!');
    }
}
