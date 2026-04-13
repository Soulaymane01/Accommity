<?php

namespace App\Models\Paiements;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Utilisateurs\User;
use App\Enums\MotifRemboursement;

class Remboursement extends Model
{
    use HasFactory;

    protected $table = 'remboursements';
    protected $primaryKey = 'id_remboursement';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_remboursement',
        'id_reservation',
        'id_voyageur',
        'montant',
        'date_remboursement',
        'motif'
    ];

    protected $casts = [
        'motif' => MotifRemboursement::class,
        'date_remboursement' => 'datetime',
        'montant' => 'decimal:2'
    ];

    public function reservation()
    {
        return $this->belongsTo(\App\Models\Reservations\Reservation::class, 'id_reservation', 'id_reservation');
    }

    public function voyageur()
    {
        return $this->belongsTo(User::class, 'id_voyageur', 'id_utilisateur');
    }

    // UML Methods
    public static function getRemboursements()
    {
        return self::with(['reservation.annonce', 'voyageur'])->latest('date_remboursement')->paginate(15);
    }

    public static function filtrerRemboursement($motif) // Schema has 'motif' enum for refund not statut, checking schema: 'motif', ['annulation_voyageur', 'annulation_hote', 'expiration_demande']
    {
        if ($motif && $motif !== 'tous') {
            $enumVal = MotifRemboursement::tryFrom($motif);
            return self::with(['reservation.annonce', 'voyageur'])
                ->where('motif', $enumVal ?? $motif)
                ->latest('date_remboursement')
                ->paginate(15);
        }
        return self::getRemboursements();
    }

    public static function getRemboursementById($id)
    {
        return self::with(['reservation.annonce', 'voyageur'])->where('id_remboursement', $id)->first();
    }
}
