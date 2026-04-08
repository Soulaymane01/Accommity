<?php

namespace App\Policies;

use App\Models\Utilisateurs\User;
use App\Models\Annonces\Annonce;

class AnnoncePolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Annonce $annonce)
    {
        return true; // Any user can view an announcement
    }

    public function create(User $user)
    {
        return $user->est_hote;
    }

    public function update(User $user, Annonce $annonce)
    {
        return $annonce->verifierProprietaire($user->id_utilisateur);
    }

    public function delete(User $user, Annonce $annonce)
    {
        return $annonce->verifierProprietaire($user->id_utilisateur);
    }
}
