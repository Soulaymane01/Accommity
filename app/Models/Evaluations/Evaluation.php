<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Reservations\Reservation;
use App\Models\Utilisateurs\User;
use App\Models\Annonces\Annonce;

class Evaluation extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'evaluations';
    protected $primaryKey = 'id_evaluation';
    public $timestamps = false;

    protected $fillable = [
        'id_reservation',
        'id_auteur',
        'id_cible',
        'id_annonce',
        'type_auteur',
        'note',
        'commentaire',
        'est_signale',
        'motif_signalement',
        'date_creation',
        'date_modification',
    ];

    protected $casts = [
        'date_creation' => 'datetime',
        'date_modification' => 'datetime',
        'est_signale' => 'boolean',
        'note' => 'decimal:2',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'id_reservation', 'id_reservation');
    }

    public function auteur()
    {
        return $this->belongsTo(User::class, 'id_auteur', 'id_utilisateur');
    }

    public function cible()
    {
        return $this->belongsTo(User::class, 'id_cible', 'id_utilisateur');
    }

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'id_annonce', 'id_annonce');
    }

    // UML Methods pour "Avis Signalés"
    public static function getAvisSignales()
    {
        return self::with(['auteur', 'cible', ' reservation.annonce'])
                   ->where('est_signale', true)
                   ->latest('date_creation')
                   ->paginate(10);
    }

    public static function getAvisById($id)
    {
        return self::with(['auteur', 'cible', 'reservation.annonce'])->where('id_evaluation', $id)->first();
    }

    public function supprimerAvis()
    {
        // On supprime physiquement (ou soft delete si géré, mais dans ce schéma c'est physique)
        $this->delete();
    }

    public function conserverAvis()
    {
        // L'avis est conservé, on lève juste le flag de signalement
        $this->est_signale = false;
        $this->motif_signalement = null;
        $this->save();
    }
}
