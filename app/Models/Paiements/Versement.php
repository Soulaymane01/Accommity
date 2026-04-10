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

    // UML Methods
    public static function getVersements()
    {
        return self::with(['reservation.annonce', 'hote'])->latest('date_versement')->paginate(15);
    }

    public static function filtrerVersement($statut)
    {
        if ($statut && $statut !== 'tous') {
            $enumVal = StatutVersement::tryFrom($statut);
            return self::with(['reservation.annonce', 'hote'])
                ->where('statut', $enumVal ?? $statut)
                ->latest('date_versement')
                ->paginate(15);
        }
        return self::getVersements();
    }

    public static function getVersementById($id)
    {
        return self::with(['reservation.annonce', 'hote'])->where('id_versement', $id)->first();
    }
}
