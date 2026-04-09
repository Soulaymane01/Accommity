<?php

namespace App\Enums;

enum MethodePaiement: string
{
    case CARTE_BANCAIRE = 'carte_bancaire';
    case PAYPAL = 'paypal';
    case AUTRE = 'autre';
}
