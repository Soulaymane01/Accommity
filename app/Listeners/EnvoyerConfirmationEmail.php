<?php

namespace App\Listeners;

use App\Events\ReservationConfirmee;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EnvoyerConfirmationEmail implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ReservationConfirmee $event): void
    {
        // Logique d'envoi d'email via App\Notifications ou Mailables
        // e.g., Mail::to($event->reservation->voyageur->email)->send(...);
    }
}
