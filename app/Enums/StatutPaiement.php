<?php

namespace App\Enums;

enum StatutPaiement: string
{
    case EN_ATTENTE = 'en_attente';
    case REUSSI = 'reussi';
    case ECHOUE = 'echoue';
    case REMBOURSE = 'rembourse';
}
