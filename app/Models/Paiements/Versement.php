<?php

namespace App\Models\Paiements;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservations\Reservation;
use App\Models\Utilisateurs\User;
use App\Enums\StatutVersement;

class Versement extends Model
{
    use HasFactory;

    protected $table = 'versements';
    protected $primaryKey = 'id_versement';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_versement',
        'id_hote',
        'id_reservation',
        'montant',
        'statut',
        'date_versement',
        'reference_bancaire'
    ];

    protected $casts = [
        'statut' => StatutVersement::class,
        'date_versement' => 'datetime',
        'montant' => 'decimal:2'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'id_reservation', 'id_reservation');
    }

    public function hote()
    {
        return $this->belongsTo(User::class, 'id_hote', 'id_utilisateur');
    }
}
