<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\StockAlert;
use App\Services\TCPClient;

class StockController extends Controller
{
    public function index()
    {
        return response()->json(Stock::with('product')->get());
    }

    public function sendStockAlert(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'current_stock' => 'required|integer|min:0',
            'threshold' => 'required|integer|min:1'
        ]);

        StockAlert::create([
            'product_id' => $request->product_id,
            'current_stock' => $request->current_stock,
            'threshold' => $request->threshold
        ]);


        $data = [
            'action' => 'send_stock_alert',
            'supermarket_id' => env('SUPERMARKET_ID'),
            'product_id' => $request->product_id,
            'current_stock' => $request->current_stock,
            'threshold' => $request->threshold
        ];

        TCPClient::sendTCPRequest(env('ADMIN_SERVER_IP'), 9000, $data);

        return response()->json(['message' => 'Stock alert sent successfully']);
    }
}
