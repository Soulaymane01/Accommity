<?php

namespace App\Models\Paiements;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservations\Reservation;
use App\Enums\StatutPaiement;
use App\Enums\MethodePaiement;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';
    protected $primaryKey = 'id_paiement';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // Using custom date fields

    protected $fillable = [
        'id_paiement',
        'id_reservation',
        'id_voyageur',
        'montant',
        'statut',
        'date_transaction',
        'methode_paiement'
    ];

    protected $casts = [
        'statut' => StatutPaiement::class,
        'methode_paiement' => MethodePaiement::class,
        'date_transaction' => 'datetime',
        'montant' => 'decimal:2'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'id_reservation', 'id_reservation');
    }
}
