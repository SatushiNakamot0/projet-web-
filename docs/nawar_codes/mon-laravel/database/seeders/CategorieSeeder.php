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
            ['nom' => 'Ventes',       'icone' => '💰'],
            ['nom' => 'Emploi',           'icone' => '💼'],
            ['nom' => 'Services',         'icone' => '🔧'],
        ];

        foreach ($categories as $cat) {
            Categorie::firstOrCreate(
                ['slug' => Str::slug($cat['nom'])],
                ['nom' => $cat['nom'], 'icone' => $cat['icone']]
            );
        }
    }
}