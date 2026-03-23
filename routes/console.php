<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Deduct daily ad budget from seller collections
Schedule::command('campaigns:deduct-daily')->dailyAt('00:05');

// Generate payout requests every 7 days (runs daily, command checks 7-day intervals per seller)
Schedule::command('payouts:generate-weekly')->dailyAt('01:00');

// Refresh seller reputation cache once daily
Schedule::command('sellers:recalculate-reputation')->dailyAt('02:00');
