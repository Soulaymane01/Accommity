<?php

namespace App\Repositories\Interfaces;

use App\Models\Annonces\Annonce;
use Illuminate\Database\Eloquent\Collection;

interface AnnonceRepositoryInterface
{
    public function findById($id): ?Annonce;
    public function create(array $data): Annonce;
    public function update($id, array $data): bool;
    public function delete($id): bool;
    public function getAnnoncesParHote($idHote): Collection;
    public function rechercherAnnoncesDisponibles(array $criteria): Collection;
    public function getAnnoncesDisponibles(): Collection;
}
