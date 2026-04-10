<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Reservations\Reservation;
use App\Models\Utilisateurs\User;
use App\Models\Annonces\Annonce;
use App\Models\Administration\TicketLitige;
use App\Enums\TicketLitigeStatut;
use Illuminate\Support\Facades\DB;

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

    public function details()
    {
        return $this->hasOne(NoteDetaillee::class, 'id_evaluation', 'id_evaluation');
    }

    // UML Methods pour Voyageur & Hote
    
    public static function creer($idAuteur, $idCible, $idReservation, $idAnnonce, $typeAuteur, $commentaire, $notesDetaillees)
    {
        // On récupère la réservation pour valider l'état
        $reservation = Reservation::find($idReservation);
        if (!$reservation || $reservation->statut->value !== 'Terminée') {
            throw new \Exception("Impossible d'évaluer une réservation non terminée.");
        }

        DB::beginTransaction();
        try {
            // Moyenne calculée
            $moyenne = collect($notesDetaillees)->average();

            $evaluation = self::create([
                'id_auteur' => $idAuteur,
                'id_cible' => $idCible,
                'id_reservation' => $idReservation,
                'id_annonce' => $idAnnonce,
                'type_auteur' => $typeAuteur,
                'note' => $moyenne,
                'commentaire' => $commentaire,
                'est_signale' => false,
                'date_creation' => now(),
            ]);

            // Insertion dans NoteDetaillee
            $evaluation->details()->create([
                'proprete' => $notesDetaillees['proprete'] ?? 5,
                'communication' => $notesDetaillees['communication'] ?? 5,
                'emplacement' => $notesDetaillees['emplacement'] ?? 5,
                'rapport_qualite_prix' => $notesDetaillees['rapport_qualite_prix'] ?? 5,
                'exactitude' => $notesDetaillees['exactitude'] ?? 5,
            ]);

            // Appel au recalcul de la note globale de l'annonce si applicable
            if ($idAnnonce) {
                $annonce = Annonce::find($idAnnonce);
                if ($annonce) {
                    $annonce->calculerNoteGlobale();
                }
            }

            DB::commit();
            return $evaluation;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function modifier($nouveauCommentaire, $nouvellesNotes)
    {
        DB::beginTransaction();
        try {
            $moyenne = collect($nouvellesNotes)->average();
            
            $this->update([
                'note' => $moyenne,
                'commentaire' => $nouveauCommentaire,
                'date_modification' => now()
            ]);

            $this->details()->update([
                'proprete' => $nouvellesNotes['proprete'] ?? 5,
                'communication' => $nouvellesNotes['communication'] ?? 5,
                'emplacement' => $nouvellesNotes['emplacement'] ?? 5,
                'rapport_qualite_prix' => $nouvellesNotes['rapport_qualite_prix'] ?? 5,
                'exactitude' => $nouvellesNotes['exactitude'] ?? 5,
            ]);

            // Re-calculer note globale de l'annonce
            if ($this->id_annonce) {
                $annonce = Annonce::find($this->id_annonce);
                if ($annonce) {
                    $annonce->calculerNoteGlobale();
                }
            }
            DB::commit();
            return $this;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function supprimer()
    {
        $idAnnonce = $this->id_annonce;
        
        // Supprimer supprime en cascade les notes détaillées via FK schema "onDelete('cascade')"
        $this->delete();

        if ($idAnnonce) {
            $annonce = Annonce::find($idAnnonce);
            if ($annonce) {
                $annonce->calculerNoteGlobale();
            }
        }
    }

    // Récupérer les évaluations reçues par un utilisateur (ex: moi)
    public static function listerEvaluation($idCible)
    {
        return self::with(['auteur', 'details', 'annonce'])
                   ->where('id_cible', $idCible)
                   ->latest('date_creation')
                   ->paginate(10);
    }

    // Récupérer les évaluations données/laissées par un utilisateur
    public static function getEvaluations($idAuteur)
    {
        return self::with(['cible', 'details', 'annonce', 'reservation'])
                   ->where('id_auteur', $idAuteur)
                   ->latest('date_creation')
                   ->paginate(10);
    }
    
    // Signaler une évaluation reçue
    public function signalerEvaluation($motif)
    {
        $this->update([
            'est_signale' => true,
            'motif_signalement' => $motif
        ]);
        
        return $this;
    }

    // Génère le ticket après un signalement
    public function genererTicket($idDeclarant, $motif)
    {
        return TicketLitige::create([
            'id_evaluation' => $this->id_evaluation,
            'id_declarant' => $idDeclarant,
            'id_admin' => null, // Non assigné initialement
            'motif' => $motif,
            'statut' => TicketLitigeStatut::EN_COURS,
            'date_creation' => now(),
        ]);
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
