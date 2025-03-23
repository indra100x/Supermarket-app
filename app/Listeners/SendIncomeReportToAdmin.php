<?php

namespace App\Listeners;

use App\Events\DailyIncomeReport;
use App\Models\Sale;
use App\Services\TCPClient;
use Illuminate\Support\Facades\Log;

class SendIncomeReportToAdmin
{
    public function handle(DailyIncomeReport $event)
    {
        $totalIncome = Sale::whereDate('created_at', $event->date)->sum('total_price');

        $data = [
            'action' => 'send_income_report',
            'supermarket_id' => env('SUPERMARKET_ID'),
            'date' => $event->date,
            'total_income' => $totalIncome
        ];

        $response = TCPClient::sendTCPRequest(env('ADMIN_SERVER_IP'), 9000, $data);

        Log::info("Daily Income Report Sent", ['response' => $response]);
    }
}
