<?php

namespace App\Policies;

use App\Models\Annonce;
use App\Models\User;

class AnnoncePolicy
{
    // Nvériyiw wesh had l'utilisateur 3ndo l7a9 ymodifier l'annonce
    public function update(User $user, Annonce $annonce): bool
    {
        // Ymken l'utilisateur ymodifier ghi les annonces dyalo
        return $user->id === $annonce->id_utilisateur;
    }

    // Nvériyiw wesh had l'utilisateur 3ndo l7a9 ysupprimer l'annonce
    public function delete(User $user, Annonce $annonce): bool
    {
        // Ymken l'utilisateur ysupprimer ghi les annonces dyalo
        return $user->id === $annonce->id_utilisateur;
    }
}
