<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DailyIncomeReport
{
    use Dispatchable, SerializesModels;

    public $date;

    public function __construct($date)
    {
        $this->date = $date;
    }
}
