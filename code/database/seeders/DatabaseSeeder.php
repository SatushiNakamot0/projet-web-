<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    // Nlanciou les seeders lkhrin w ncreaw l'utilisateurs
    public function run(): void
    {
        // 1) Nzidow les catégories lwel
        $this->call(CategorieSeeder::class);

        // 2) Nzidow compte admin pour les tests
        User::firstOrCreate(
            ['email' => 'admin@annonces.ma'],
            [
                'nom'      => 'Admin',
                'prenom'   => 'Istrateur',
                'mot_de_passe'  => Hash::make('password123'),
                'role'      => 'admin',
                'statut'    => 'actif',
                'email_verified_at' => now(),
            ]
        );

        // 3) Nzidow membre test bach njarbaw les fonctionnalités
        User::firstOrCreate(
            ['email' => 'membre@annonces.ma'],
            [
                'nom'      => 'Test',
                'prenom'   => 'Membre',
                'mot_de_passe'  => Hash::make('password123'),
                'role'      => 'membre',
                'statut'    => 'actif',
                'email_verified_at' => now(),
            ]
        );

        // 4) Nzidow les annonces exemples mn dossier Pictures
        $this->call(AnnonceSeeder::class);

    }
}
