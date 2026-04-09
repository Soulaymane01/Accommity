<?php

namespace App\Enums;

enum StatutRemboursement: string
{
    case INITIE = 'initié';
    case TRAITE = 'traité';
    case REFUSE = 'refusé';
}
