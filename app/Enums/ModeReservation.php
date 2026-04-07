<?php

namespace App\Enums;

enum ModeReservation: string
{
    case INSTANTANEE = 'réservation instantanée';
    case DEMANDE = 'demande de réservation';
}
