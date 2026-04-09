<?php

namespace App\Enums;

enum StatutVersement: string
{
    case EN_ATTENTE = 'en_attente';
    case TRAITE = 'traite';
    case ECHOUE = 'echoue';
}
