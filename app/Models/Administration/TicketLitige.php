<?php

namespace App\Models\Administration;

use App\Models\Evaluations\Evaluation;
use App\Models\Utilisateurs\User;
use App\Enums\TicketLitigeStatut;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TicketLitige extends Model
{
    use HasUuids;

    protected $table = 'ticket_litiges';
    protected $primaryKey = 'id_ticket';
    public $timestamps = false;

    protected $fillable = [
        'id_evaluation',
        'id_declarant',
        'id_admin',
        'motif',
        'statut',
        'date_creation',
        'date_cloture',
    ];

    protected $casts = [
        'statut'        => TicketLitigeStatut::class,
        'date_creation' => 'datetime',
        'date_cloture'  => 'datetime',
    ];

    // ───────────────────────────────────────────────
    // RELATIONS (Diagramme de Classes)
    // ───────────────────────────────────────────────

    /** Evaluation "1" --> "0..1" TicketLitige */
    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'id_evaluation', 'id_evaluation');
    }

    /** Utilisateur "1" --> "0..*" TicketLitige {declarant} */
    public function declarant()
    {
        return $this->belongsTo(User::class, 'id_declarant', 'id_utilisateur');
    }

    /** Administrateur "1" --> "0..*" TicketLitige */
    public function admin()
    {
        return $this->belongsTo(Administrateur::class, 'id_admin', 'id_admin');
    }

    // ───────────────────────────────────────────────
    // METHODES UML (Diagramme de Classes — PK_Evaluations)
    // ───────────────────────────────────────────────

    /**
     * genererTicket(evaluation, declarant, motif) : TicketLitige
     * RO10 : Génère automatiquement un ticket de litige.
     */
    public static function genererTicket(string $idEvaluation, string $idDeclarant, string $motif): self
    {
        // Récupérer le premier admin disponible
        $admin = Administrateur::first();

        return self::create([
            'id_evaluation' => $idEvaluation,
            'id_declarant'  => $idDeclarant,
            'id_admin'      => $admin ? $admin->id_admin : null,
            'motif'         => $motif,
            'statut'        => TicketLitigeStatut::EN_COURS,
            'date_creation' => now(),
        ]);
    }

    /**
     * getLitiges() : List<TicketLitige>
     */
    public static function getLitiges()
    {
        return self::with(['evaluation.auteur', 'evaluation.cible', 'declarant', 'admin'])
            ->orderByDesc('date_creation')
            ->get();
    }

    /**
     * getLitigeById(id) : TicketLitige
     */
    public static function getLitigeById(string $id): ?self
    {
        return self::with(['evaluation.auteur', 'evaluation.cible', 'evaluation.noteDetaillee', 'declarant', 'admin'])
            ->find($id);
    }

    /**
     * modifierStatutLitige(statut) : void
     */
    public function modifierStatutLitige(TicketLitigeStatut $statut): void
    {
        $data = ['statut' => $statut];

        if ($statut === TicketLitigeStatut::CLOTURE) {
            $data['date_cloture'] = now();
        }

        $this->update($data);
    }

    /**
     * getStatsLitiges() : Map
     */
    public static function getStatsLitiges(): array
    {
        return [
            'en_cours' => self::where('statut', TicketLitigeStatut::EN_COURS)->count(),
            'clotures' => self::where('statut', TicketLitigeStatut::CLOTURE)->count(),
        ];
    }
}
