<?php

namespace App\Enums;

enum TypeRecu: string
{
    case PAIEMENT = 'paiement';
    case REMBOURSEMENT = 'remboursement';
    case VERSEMENT = 'versement';
}
