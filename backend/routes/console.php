<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('rss:import https://www.togofirst.com/feed')
    ->hourly()
    ->withoutOverlapping();

Schedule::command('rss:import https://www.republicoftogo.com/feed')
    ->hourly()
    ->withoutOverlapping();

Schedule::command('rss:import https://www.lemonde.fr/rss/une.xml')
    ->hourly()
    ->withoutOverlapping();
