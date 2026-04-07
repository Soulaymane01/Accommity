<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Reservations\MinuterieService;
use App\Models\Reservations\Reservation;
use App\Jobs\ExpirerDemande;

class ExpireReservations extends Command
{
    protected $signature = 'reservations:expire';
    protected $description = 'Expire les demandes de réservation en attente depuis plus de 24h.';

    public function handle(MinuterieService $minuterieService)
    {
        $this->info('Début de l\'expiration des réservations...');
        $expireesIds = $minuterieService->getDemandesExpirees();
        
        $count = 0;
        foreach ($expireesIds as $id) {
            $reservation = Reservation::find($id);
            if ($reservation) {
                // Dispatch Job
                ExpirerDemande::dispatch($reservation);
                $minuterieService->annulerMinuterie($id);
                $count++;
            }
        }
        
        $this->info("Terminé. $count demandes expirées traitées.");
    }
}
