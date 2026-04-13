<?php

namespace App\Services\Annonces;

use App\Models\Annonces\Calendrier;
use App\Enums\TypeBlockageCalendrier;
use App\Models\Reservations\Reservation;

class CalendrierService
{
    public function initialiserCalendrier($idAnnonce)
    {
        return Calendrier::create([
            'id_annonce' => $idAnnonce,
            'date_debut' => today(),
            'date_fin' => today()->addYears(2),
            'est_disponible' => true,
            'type_blockage' => TypeBlockageCalendrier::DISPONIBLE
        ]);
    }

    public function getDisponibilites($idAnnonce)
    {
        return Calendrier::where('id_annonce', $idAnnonce)->get();
    }

    public function bloquerDatesManuel($idAnnonce, $dateDebut, $dateFin)
    {
        // On vérifie s'il y a conflit de réservation
        $conflit = Reservation::where('id_annonce', $idAnnonce)
            ->whereIn('statut', ['Confirmée']) // Use enum properly in real case
            ->where(function ($query) use ($dateDebut, $dateFin) {
                $query->whereBetween('date_arrivee', [$dateDebut, $dateFin])
                    ->orWhereBetween('date_depart', [$dateDebut, $dateFin]);
            })->exists();

        if ($conflit) {
            throw new \Exception("Dates déjà réservées — blocage impossible");
        }

        // Créer un nouveau créneau bloqué
        return Calendrier::create([
            'id_annonce' => $idAnnonce,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'est_disponible' => false,
            'type_blockage' => TypeBlockageCalendrier::BLOQUE_MANUEL,
        ]);
    }

    public function debloquerDates($idAnnonce, $dateDebut, $dateFin)
    {
        $deleted = Calendrier::where('id_annonce', $idAnnonce)
            ->where('date_debut', $dateDebut)
            ->where('date_fin', $dateFin)
            ->where('type_blockage', TypeBlockageCalendrier::BLOQUE_MANUEL)
            ->delete();

        if ($deleted) {
            return true;
        }
        throw new \Exception("Ce créneau n'est pas bloqué manuellement ou n'existe pas.");
    }

    public function supprimerCalendrier($idAnnonce)
    {
        Calendrier::where('id_annonce', $idAnnonce)->delete();
    }
}
