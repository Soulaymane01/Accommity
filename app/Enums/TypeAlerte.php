<?php

namespace App\Enums;

enum TypeAlerte: string
{
    case Reservation = 'reservation';
    case Paiement    = 'paiement';
    case Avis        = 'avis';
    case Rappel      = 'rappel';
    case Systeme     = 'systeme';
}
