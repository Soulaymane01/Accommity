<?php

namespace App\Models\Utilisateurs;

use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    protected $table = 'profils';
    protected $primaryKey = 'id_profil';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_profil',
        'id_utilisateur',
        'photo_url',
        'description',
        'note_moyenne',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'id_utilisateur', 'id_utilisateur');
    }

    // --- METHODES UML: DIAGRAMME DE CLASSES --- //

    /**
     * Initialiser un profil vide pour un utilisateur.
     */
    public static function initialiserProfil(string $idUtilisateur): self
    {
        return self::create([
            'id_profil' => \Illuminate\Support\Str::uuid()->toString(),
            'id_utilisateur' => $idUtilisateur,
            'note_moyenne' => 0,
        ]);
    }

    /**
     * Ajouter ou modifier la photo.
     */
    public function ajouterPhoto(string $photoUrl): void
    {
        $this->photo_url = $photoUrl;
        $this->save();
    }

    /**
     * Ajouter la description.
     */
    public function ajouterDescription(string $description): void
    {
        $this->description = $description;
        $this->save();
    }
}
