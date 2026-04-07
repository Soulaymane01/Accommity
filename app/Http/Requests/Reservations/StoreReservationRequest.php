<?php

namespace App\Http\Requests\Reservations;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
        // Verification is done in ReservationPolicy or service
    }

    public function rules(): array
    {
        return [
            'id_annonce' => ['required', 'uuid', 'exists:annonces,id_annonce'],
            'date_arrivee' => ['required', 'date', 'after_or_equal:today'],
            'date_depart' => ['required', 'date', 'after:date_arrivee'],
            'nb_voyageurs' => ['required', 'integer', 'min:1'],
            'message_optionnel' => ['nullable', 'string', 'max:500'],
        ];
    }
}
