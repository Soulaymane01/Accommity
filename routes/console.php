<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduler tasks
use Illuminate\Support\Facades\Schedule;

Schedule::command('reservations:expire')->hourly();
Schedule::command('reservations:reminders')->dailyAt('08:00');
