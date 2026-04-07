<?php

namespace App\Repositories\Interfaces;

use App\Models\Reservations\Reservation;

interface ReservationRepositoryInterface
{
    public function findById(string $id): ?Reservation;
    public function create(array $data): Reservation;
    public function update(string $id, array $data): bool;
    public function delete(string $id): bool;
    public function findByHote(string $idHote);
    public function findByVoyageur(string $idVoyageur);
    public function getEnAttenteByHote(string $idHote);
}
