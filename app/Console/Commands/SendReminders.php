<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservations\Reservation;
use App\Enums\StatutReservation;
use Carbon\Carbon;

class SendReminders extends Command
{
    protected $signature = 'reservations:reminders';
    protected $description = 'Envoie les rappels 48h (hôte) et 24h (voyageur) avant le check-in.';

    public function handle()
    {
        $this->info('Recherche des rappels à envoyer...');
        
        $demain = Carbon::now()->addHours(24)->toDateString();
        $apresDemain = Carbon::now()->addHours(48)->toDateString();
        
        // Rappels voyageur (24h avant)
        $reservations24h = Reservation::where('statut', StatutReservation::CONFIRMEE)
            ->whereDate('date_arrivee', $demain)
            ->get();
            
        foreach($reservations24h as $res) {
            // Envoyer email/SMS au voyageur
        }
        
        // Rappels hote (48h avant)
        $reservations48h = Reservation::where('statut', StatutReservation::CONFIRMEE)
            ->whereDate('date_arrivee', $apresDemain)
            ->get();
            
        foreach($reservations48h as $res) {
            // Envoyer email/SMS à l'hôte
        }
        
        $this->info("Terminé.");
    }
}
