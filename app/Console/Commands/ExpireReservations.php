<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Reservations\MinuterieService;
use App\Services\Reservations\ReservationService;

class ExpireReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire les demandes de réservation en attente depuis plus de 24h.';

    /**
     * Execute the console command.
     */
    public function handle(MinuterieService $minuterieService, ReservationService $reservationService)
    {
        $this->info('Vérification des demandes expirées...');
        
        $expiredIds = $minuterieService->getDemandesExpirees();
        
        foreach ($expiredIds as $id) {
            try {
                $reservationService->marquerExpirée($id);
                $this->info("Réservation {$id} marquée comme expirée.");
            } catch (\Exception $e) {
                $this->error("Erreur pour {$id}: {$e->getMessage()}");
            }
        }

        $this->info('Traitement terminé.');
    }
}
