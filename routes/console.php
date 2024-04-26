<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Artisan::command('schedule:fiftylikes', function (Schedule $schedule) {
    $schedule->command('email:fiftylikes')->hourly();
})->describe('Schedule the email for users who reached fifty likes');