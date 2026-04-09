<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class NoteDetaillee extends Model
{
    use HasFactory, HasUuids;

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
        'proprete' => 'decimal:2',
        'communication' => 'decimal:2',
        'emplacement' => 'decimal:2',
        'rapport_qualite_prix' => 'decimal:2',
        'exactitude' => 'decimal:2',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'id_evaluation', 'id_evaluation');
    }
}
