<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\TcpListenCommand;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
Artisan::command('tcp:listen', function () {
    Artisan::call(TcpListenCommand::class);
})->describe('Starts the TCP server for receiving supply requests');


    Schedule::call(function () {
        event(new \App\Events\DailyIncomeReport(now()->toDateString()));
    })->dailyAt('00:00');




