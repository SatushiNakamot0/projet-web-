<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nom' => 'Immobilier',       'icone' => '🏠'],
            ['nom' => 'Véhicules',        'icone' => '🚗'],
            ['nom' => 'Électronique',     'icone' => '📱'],
            ['nom' => 'Vêtements',        'icone' => '👗'],
            ['nom' => 'Maison & Jardin',  'icone' => '🛋️'],
            ['nom' => 'Emploi',           'icone' => '💼'],
            ['nom' => 'Services',         'icone' => '🔧'],
            ['nom' => 'Sports & Loisirs', 'icone' => '⚽'],
            ['nom' => 'Animaux',          'icone' => '🐾'],
            ['nom' => 'Autres',           'icone' => '📦'],
        ];

        foreach ($categories as $cat) {
            Categorie::firstOrCreate(
                ['slug' => Str::slug($cat['nom'])],
                ['nom' => $cat['nom'], 'icone' => $cat['icone']]
            );
        }
    }
}