<?php

namespace App\Models\Utilisateurs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VerificationIdentite extends Model
{
    protected $table = 'verification_identites';
    protected $primaryKey = 'id_verification';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_verification',
        'id_utilisateur',
        'type_piece',
        'chemin_document',
        'statut',
        'date_soumission',
        'motif_rejet',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'id_utilisateur', 'id_utilisateur');
    }

    // --- METHODES UML: DIAGRAMME DE CLASSES --- //

    /**
     * Soumettre les documents d'identité pour vérification.
     * @param string $idUtilisateur
     * @param string $typePiece
     * @param string $cheminDocument
     * @return self
     */
    public static function soumettreDocuments(string $idUtilisateur, string $typePiece, string $cheminDocument): self
    {
        return self::create([
            'id_verification' => Str::uuid()->toString(),
            'id_utilisateur' => $idUtilisateur,
            'type_piece' => $typePiece,
            'chemin_document' => $cheminDocument,
            'statut' => 'En cours de traitement',
            'date_soumission' => now(),
        ]);
    }

    /**
     * Enregistrer ou mettre à jour le dossier.
     */
    public function enregistrerDossier(): void
    {
        $this->save();
    }

    /**
     * Visualiser le dossier de vérification actuel.
     * @param string $idUtilisateur
     * @return VerificationIdentite|null
     */
    public static function visualiserDossier(string $idUtilisateur): ?self
    {
        return self::where('id_utilisateur', $idUtilisateur)->latest('date_soumission')->first();
    }

    /**
     * Valider la vérification (admin).
     */
    public function validerVerification(): void
    {
        $this->statut = 'Validé';
        $this->enregistrerDossier();
    }

    /**
     * Rejeter la vérification (admin).
     */
    public function rejeterVerification(string $motif): void
    {
        $this->statut = 'Rejeté';
        $this->motif_rejet = $motif;
        $this->enregistrerDossier();
    }
}
