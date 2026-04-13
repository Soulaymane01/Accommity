<?php

namespace App\Http\Requests\Annonces;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnnonceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorize handle in AnnoncePolicy
    }

    public function rules(): array
    {
        // Similar to store but all fields can be somewhat optional if patch, but require some.
        return [
            'id_categorie' => ['required', 'uuid', 'exists:categorie_geographiques,id_categorie'],
            'id_politique' => ['required', 'uuid', 'exists:politique_annulations,id_politique'],
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'photo_url' => ['nullable', 'string'],
            'type_logement' => ['required', 'string'],
            'adresse' => ['required', 'string'],
            'capacite' => ['required', 'integer', 'min:1'],
            'tarif_nuit' => ['required', 'numeric', 'min:0'],
            'mode_reservation' => ['required', 'string'],
            'equipements' => ['nullable', 'string'],
            'reglement_interieur' => ['nullable', 'string'],
        ];
    }
}
