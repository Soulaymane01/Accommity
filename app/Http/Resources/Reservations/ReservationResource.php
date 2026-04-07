<?php

namespace App\Http\Resources\Reservations;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_reservation' => $this->id_reservation,
            'id_annonce' => $this->id_annonce,
            'statut' => $this->statut?->value,
            'mode_reservation' => $this->mode_reservation?->value,
            'date_arrivee' => $this->date_arrivee->format('Y-m-d'),
            'date_depart' => $this->date_depart->format('Y-m-d'),
            'nb_voyageurs' => $this->nb_voyageurs,
            'montant_total' => $this->montant_total,
            'frais_service' => $this->frais_service,
            'message_optionnel' => $this->message_optionnel,
            'date_creation' => $this->date_creation,
        ];
    }
}
