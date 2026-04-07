<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservations\Reservation;
use App\Services\Reservations\StatutService;
use App\Enums\StatutReservation;

class ExpirerDemande implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function handle(StatutService $statutService): void
    {
        if ($this->reservation->statut === StatutReservation::EN_ATTENTE) {
            $statutService->appliquerTransition($this->reservation, StatutReservation::EXPIREE);
            // Optionally emit event ReservationExpiree / Dispatch refund (none needed for request)
        }
    }
}
