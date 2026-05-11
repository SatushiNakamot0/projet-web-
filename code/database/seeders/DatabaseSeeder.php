<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Catégories
        $this->call(CategorieSeeder::class);

        // 2) Compte Admin
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

        // 3) Compte Membre test
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

        // 4) Annonces exemples
        $this->call(AnnonceSeeder::class);
    }
}
