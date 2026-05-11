<?php

namespace App\Policies;

use App\Models\Annonce;
use App\Models\User;

class AnnoncePolicy
{
    // L'utilisateur ymken lih y modifier ghi les annonces dyalo
    public function update(User $user, Annonce $annonce): bool
    {
        return $user->id === $annonce->id_utilisateur;
    }

    // L'utilisateur ymken lih y supprimer ghi les annonces dyalo
    public function delete(User $user, Annonce $annonce): bool
    {
        return $user->id === $annonce->id_utilisateur;
    }
}
