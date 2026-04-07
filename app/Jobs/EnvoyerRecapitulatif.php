<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservations\Reservation;

class EnvoyerRecapitulatif implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function handle(): void
    {
        // Logique pour generer et envoyer le recapitulatif par email
        // $recap = $this->reservation->recapitulatif()->create([...]);
        // EnvoyerEmailRecapitulatif::dispatch($recap);
    }
}
