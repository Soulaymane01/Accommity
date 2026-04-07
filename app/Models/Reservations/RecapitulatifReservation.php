<?php

namespace App\Models\Reservations;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RecapitulatifReservation extends Model
{
    use HasUuids;

    protected $table = 'recapitulatif_reservations';
    protected $primaryKey = 'id_recapitulatif';
    const CREATED_AT = 'date_generation';
    const UPDATED_AT = null; // No updated date in schema

    protected $fillable = [
        'id_reservation',
        'details_sejour',
        'montant_total',
        'frais_service',
        'montant_hote',
        'coordonnees_voyageur',
        'coordonnees_hote',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'id_reservation', 'id_reservation');
    }

    // UML Method
    public function getDetails(): string
    {
        return $this->details_sejour;
    }
}
