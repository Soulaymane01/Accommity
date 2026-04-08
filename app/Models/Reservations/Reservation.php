<?php

namespace App\Models\Reservations;

use App\Models\Annonces\Annonce;
use App\Models\Utilisateurs\User;
use App\Models\Annonces\PolitiqueAnnulation;
use App\Enums\StatutReservation;
use App\Enums\ModeReservation;
use App\Enums\TypeActeurAnnulation;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasUuids;

    protected $table = 'reservations';
    protected $primaryKey = 'id_reservation';
    const CREATED_AT = 'date_creation';
    const UPDATED_AT = 'date_modification';

    protected $fillable = [
        'id_annonce',
        'id_voyageur',
        'id_hote',
        'id_politique',
        'date_arrivee',
        'date_depart',
        'nb_voyageurs',
        'statut',
        'mode_reservation',
        'montant_total',
        'frais_service',
        'message_optionnel',
        'acteur_annulation',
        'motif_refus',
    ];

    protected $casts = [
        'date_arrivee' => 'date',
        'date_depart' => 'date',
        'statut' => StatutReservation::class,
        'mode_reservation' => ModeReservation::class,
        'acteur_annulation' => TypeActeurAnnulation::class,
    ];

    // Relations
    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'id_annonce', 'id_annonce');
    }

    public function voyageur()
    {
        return $this->belongsTo(User::class, 'id_voyageur', 'id_utilisateur');
    }

    public function hote()
    {
        return $this->belongsTo(User::class, 'id_hote', 'id_utilisateur');
    }

    public function politique()
    {
        return $this->belongsTo(PolitiqueAnnulation::class, 'id_politique', 'id_politique');
    }

    public function recapitulatif()
    {
        return $this->hasOne(RecapitulatifReservation::class, 'id_reservation', 'id_reservation');
    }

    // UML Methods stubbing
    public function setStatutEnAttente(): void
    {
        $this->update(['statut' => StatutReservation::EN_ATTENTE]);
    }

    public function setStatutConfirmee(): void
    {
        $this->update(['statut' => StatutReservation::CONFIRMEE]);
    }

    public function setStatutEnCours(): void
    {
        $this->update(['statut' => StatutReservation::EN_COURS]);
    }

    public function setStatutTerminee(): void
    {
        $this->update(['statut' => StatutReservation::TERMINEE]);
    }

    public function setStatutRefusee(): void
    {
        $this->update(['statut' => StatutReservation::REFUSEE]);
    }

    public function setStatutAnnulee(TypeActeurAnnulation $acteur): void
    {
        $this->update([
            'statut' => StatutReservation::ANNULEE,
            'acteur_annulation' => $acteur,
        ]);
    }

    public function expireReservation(): void
    {
        $this->update(['statut' => StatutReservation::EXPIREE]);
    }

    public function getMontantTotal(): float
    {
        return $this->montant_total;
    }

    public function getStatut(): StatutReservation
    {
        return $this->statut;
    }
}
