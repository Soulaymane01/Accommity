<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservations\Reservation;
use App\Enums\StatutReservation;
use Carbon\Carbon;
use App\Facades\AppNotification;
use App\Enums\TypeAlerte;
use App\Models\Utilisateurs\User;

class SendReminders extends Command
{
    protected $signature = 'reservations:reminders';
    protected $description = 'Envoie les rappels 48h (hôte) et 24h (voyageur) avant le check-in et check-out.';

    public function handle()
    {
        $this->info('Recherche des rappels à envoyer...');

        $demain = Carbon::now()->addHours(24)->toDateString();
        $apresDemain = Carbon::now()->addHours(48)->toDateString();

        // ---------------------------------------------------------
        // 1. Rappels CHECK-IN
        // ---------------------------------------------------------
        // Voyageur (24h avant)
        $reservations24hIn = Reservation::where('statut', StatutReservation::CONFIRMEE)
            ->whereDate('date_arrivee', $demain)
            ->get();

        foreach ($reservations24hIn as $res) {
            $voyageur = User::find($res->id_voyageur);
            if ($voyageur) {
                AppNotification::creerNotification($voyageur, 'Check-in demain ! 🎒', 'Préparez vos valises, votre séjour commence demain !', TypeAlerte::Rappel);
            }
        }

        // Hôte (48h avant)
        $reservations48hIn = Reservation::where('statut', StatutReservation::CONFIRMEE)
            ->whereDate('date_arrivee', $apresDemain)
            ->get();

        foreach ($reservations48hIn as $res) {
            $hote = User::find($res->id_hote);
            if ($hote) {
                AppNotification::creerNotification($hote, 'Arrivée dans 48h 🛎️', 'Attention, votre voyageur arrive dans 48h. Assurez-vous que le logement est prêt.', TypeAlerte::Rappel);
            }
        }

        // ---------------------------------------------------------
        // 2. Rappels CHECK-OUT
        // ---------------------------------------------------------
        // Voyageur (24h avant)
        $reservations24hOut = Reservation::where('statut', StatutReservation::EN_COURS)
            ->whereDate('date_depart', $demain)
            ->get();

        foreach ($reservations24hOut as $res) {
            $voyageur = User::find($res->id_voyageur);
            if ($voyageur) {
                AppNotification::creerNotification($voyageur, 'Check-out demain ⏳', 'Votre séjour se termine demain. N\'oubliez pas de bien vérifier vos affaires avant de partir.', TypeAlerte::Rappel);
            }
        }

        // Hôte (48h avant)
        $reservations48hOut = Reservation::where('statut', StatutReservation::EN_COURS)
            ->whereDate('date_depart', $apresDemain)
            ->get();

        foreach ($reservations48hOut as $res) {
            $hote = User::find($res->id_hote);
            if ($hote) {
                AppNotification::creerNotification($hote, 'Départ dans 48h 🧹', 'Votre voyageur quitte le logement dans 48h. Préparez la procédure de départ.', TypeAlerte::Rappel);
            }
        }

        $this->info("Terminé.");
    }
}
