<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\DailyIncomeReport;
use App\Listeners\SendIncomeReportToAdmin;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        DailyIncomeReport::class => [
            SendIncomeReportToAdmin::class,
        ],
    ];


    public function boot()
    {
        parent::boot();
    }


    public function shouldDiscoverEvents()
    {
        return false;
    }
}
