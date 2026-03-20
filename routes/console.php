<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Programar recordatorio de WhatsApp cada día a las 10:00 AM
Schedule::command('appointments:send-whatsapp-reminders')->dailyAt('10:00');
