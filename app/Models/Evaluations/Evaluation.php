<?php

namespace App\Models\Evaluations;

use App\Models\Utilisateurs\User;
use App\Models\Reservations\Reservation;
use App\Models\Annonces\Annonce;
use App\Models\Administration\TicketLitige;
use App\Enums\TypeAuteur;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasUuids;

    protected $table = 'evaluations';
    protected $primaryKey = 'id_evaluation';
    const CREATED_AT = 'date_creation';
    const UPDATED_AT = 'date_modification';

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
    ];

    protected $casts = [
        'type_auteur'   => TypeAuteur::class,
        'note'          => 'decimal:2',
        'est_signale'   => 'boolean',
        'date_creation' => 'datetime',
        'date_modification' => 'datetime',
    ];

    // ───────────────────────────────────────────────
    // RELATIONS (Diagramme de Classes)
    // ───────────────────────────────────────────────

    /** Reservation "1" --> "0..*" Evaluation */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'id_reservation', 'id_reservation');
    }

    /** Utilisateur (auteur) "1" --> "0..*" Evaluation {role=auteur} */
    public function auteur()
    {
        return $this->belongsTo(User::class, 'id_auteur', 'id_utilisateur');
    }

    /** Utilisateur (cible) "1" --> "0..*" Evaluation {role=cible} */
    public function cible()
    {
        return $this->belongsTo(User::class, 'id_cible', 'id_utilisateur');
    }

    /** Annonce "1" --> "0..*" Evaluation */
    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'id_annonce', 'id_annonce');
    }

    /** Evaluation "1" *-- "1" NoteDetaillee (composition) */
    public function noteDetaillee()
    {
        return $this->hasOne(NoteDetaillee::class, 'id_evaluation', 'id_evaluation');
    }

    /** Evaluation "1" --> "0..1" TicketLitige */
    public function ticketLitige()
    {
        return $this->hasOne(TicketLitige::class, 'id_evaluation', 'id_evaluation');
    }

    // ───────────────────────────────────────────────
    // METHODES UML (Diagramme de Classes — PK_Evaluations)
    // ───────────────────────────────────────────────

    /**
     * lireDetails() : Evaluation
     */
    public function lireDetails(): self
    {
        return $this->load(['noteDetaillee', 'auteur', 'cible', 'annonce', 'reservation']);
    }

    /**
     * getEvaluations(utilisateur) : List<Evaluation>
     * Récupère les évaluations reçues par un utilisateur.
     */
    public static function getEvaluations(string $idUtilisateur)
    {
        return self::where('id_cible', $idUtilisateur)
            ->with(['noteDetaillee', 'auteur', 'reservation.annonce'])
            ->orderByDesc('date_creation')
            ->get();
    }

    /**
     * getEvaluationById(id) : Evaluation
     */
    public static function getEvaluationById(string $id): ?self
    {
        return self::with(['noteDetaillee', 'auteur', 'cible', 'annonce', 'reservation'])
            ->find($id);
    }

    /**
     * signalerEvaluation(motif) : void
     * Marque l'évaluation comme signalée.
     */
    public function signalerEvaluation(string $motif): void
    {
        $this->update([
            'est_signale'        => true,
            'motif_signalement'  => $motif,
        ]);
    }

    /**
     * getEvaluationsSignalees() : List<Evaluation>
     * Admin : récupère tous les avis signalés.
     */
    public static function getEvaluationsSignalees()
    {
        return self::where('est_signale', true)
            ->with(['noteDetaillee', 'auteur', 'cible', 'annonce', 'reservation'])
            ->orderByDesc('date_creation')
            ->get();
    }

    /**
     * conserverEvaluation() : void
     * Admin : enlève le flag de signalement (l'avis est conservé).
     */
    public function conserverEvaluation(): void
    {
        $this->update([
            'est_signale'       => false,
            'motif_signalement' => null,
        ]);
    }
}
