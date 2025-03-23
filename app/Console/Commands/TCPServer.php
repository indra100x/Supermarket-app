<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Stock;
use App\Services\TCPClient;

class TCPServer extends Command
{
    protected $signature = 'tcp:listen';
    protected $description = 'Start a TCP server to receive supply requests';

    public function handle()
    {
        $host = "0.0.0.0";
        $port = 9001;

        $server = stream_socket_server("tcp://$host:$port", $errno, $errstr);
        if (!$server) {
            $this->error("TCP Server Error: $errstr ($errno)");
            return;
        }

        $this->info("TCP Server is running on port $port...");

        while ($client = stream_socket_accept($server)) {
            $data = fread($client, 1024);
            $request = json_decode($data, true);

            if (!$request || !isset($request['action'])) {
                fwrite($client, json_encode(['error' => 'Invalid request']));
                fclose($client);
                continue;
            }

            if ($request['action'] === 'supply_stock') {
                $response = $this->handleSupplyRequest($request);
            } elseif ($request['action'] === 'receive_stock') {
                $response = $this->handleReceiveStock($request);
            } else {
                $response = ['error' => 'Unknown action'];
            }

            fwrite($client, json_encode($response));
            fclose($client);
        }

        fclose($server);
    }

    private function handleSupplyRequest($request)
    {
        $product_id = $request['product_id'];
        $quantity = $request['quantity'];
        $receiver_supermarket_ip = $request['receiver_supermarket_ip'];

        Log::info("Received supply request: Product ID: $product_id | Quantity: $quantity | Receiver IP: $receiver_supermarket_ip");

        $stock = Stock::where('product_id', $product_id)->first();
        if (!$stock || $stock->quantity < $quantity) {
            return ['status' => 'failed', 'message' => 'Not enough stock'];
        }


        $stock->decrement('quantity', $quantity);


        $data = [
            'action' => 'receive_stock',
            'product_id' => $product_id,
            'quantity' => $quantity
        ];

        $response = TCPClient::sendTCPRequest($receiver_supermarket_ip, 9001, $data);

        if ($response && $response['status'] === 'success') {
            return ['status' => 'success', 'message' => 'Stock successfully transferred'];
        }

        // If the receiving supermarket fails, restore stock
        $stock->increment('quantity', $quantity);
        return ['status' => 'failed', 'message' => 'Failed to transfer stock'];
    }

    private function handleReceiveStock($request)
    {
        $product_id = $request['product_id'];
        $quantity = $request['quantity'];

        Log::info("Receiving stock: Product ID: $product_id | Quantity: $quantity");

        $stock = Stock::where('product_id', $product_id)->first();
        if ($stock) {
            $stock->increment('quantity', $quantity);
        } else {
            Stock::create(['product_id' => $product_id, 'quantity' => $quantity]);
        }

        return ['status' => 'success', 'message' => 'Stock received successfully'];
    }
}
