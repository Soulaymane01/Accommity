<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class NoteDetaillee extends Model
{
    use HasUuids;

    protected $table = 'note_detaillees';
    protected $primaryKey = 'id_note';
    public $timestamps = false;

    protected $fillable = [
        'id_evaluation',
        'proprete',
        'communication',
        'emplacement',
        'rapport_qualite_prix',
        'exactitude',
    ];

    protected $casts = [
        'proprete'              => 'decimal:2',
        'communication'         => 'decimal:2',
        'emplacement'           => 'decimal:2',
        'rapport_qualite_prix'  => 'decimal:2',
        'exactitude'            => 'decimal:2',
    ];

    // ───────────────────────────────────────────────
    // RELATION (Diagramme de Classes)
    // Evaluation "1" *-- "1" NoteDetaillee (composition)
    // ───────────────────────────────────────────────

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'id_evaluation', 'id_evaluation');
    }

    /**
     * Calcule la note globale pondérée (RG30).
     * moyenne de : propreté, communication, emplacement, rapport qualité/prix, exactitude.
     */
    public function calculerMoyenne(): float
    {
        return round(
            ($this->proprete + $this->communication + $this->emplacement
                + $this->rapport_qualite_prix + $this->exactitude) / 5,
            2
        );
    }
}
