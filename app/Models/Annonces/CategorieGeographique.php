<?php

namespace App\Models\Annonces;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CategorieGeographique extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id_categorie';
    public $timestamps = false;

    protected $fillable = [
        'ville',
        'region',
        'pays',
    ];

    // Relations
    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'id_categorie', 'id_categorie');
    }

    // UML Methods
    public function ajouterCategorie() {}
    public function modifierCategorie() {}
    
    public static function rechercherParLocalisation($ville, $region, $pays) 
    {
        return self::where('ville', $ville)
             ->orWhere('region', $region)
             ->orWhere('pays', $pays)
             ->get();
    }
}
