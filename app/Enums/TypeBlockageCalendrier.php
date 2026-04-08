<?php

namespace App\Enums;

enum TypeBlockageCalendrier: string
{
    case DISPONIBLE = 'Disponible';
    case BLOQUE_RESERVATION = 'Bloque Reservation';
    case BLOQUE_MANUEL = 'Bloque Manuel';
}
