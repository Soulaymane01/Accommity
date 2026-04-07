<?php

namespace App\Enums;

enum StatutReservation: string
{
    case EN_ATTENTE = 'En attente';
    case CONFIRMEE = 'Confirmée';
    case EN_COURS = 'En cours';
    case TERMINEE = 'Terminée';
    case ANNULEE = 'Annulée';
    case REFUSEE = 'Refusée';
    case EXPIREE = 'Expirée';
}
