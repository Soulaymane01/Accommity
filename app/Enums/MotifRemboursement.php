<?php

namespace App\Enums;

enum MotifRemboursement: string
{
    case ANNULATION_VOYAGEUR = 'annulation_voyageur';
    case ANNULATION_HOTE = 'annulation_hote';
    case EXPIRATION_DEMANDE = 'expiration_demande';
}
