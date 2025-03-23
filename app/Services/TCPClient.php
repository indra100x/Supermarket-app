<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class TCPClient
{
    public static function sendTCPRequest($host, $port, $data, $maxRetries = 3)
    {
        $attempt = 0;
        $success = false;
        $response = null;

        while ($attempt < $maxRetries && !$success) {
            $attempt++;
            try {
                Log::info("Attempt #$attempt: Sending TCP request to $host:$port");

                $socket = @stream_socket_client("tcp://$host:$port", $errno, $errstr, 5);
                if (!$socket) {
                    throw new \Exception("TCP connection failed: $errstr ($errno)");
                }

                fwrite($socket, json_encode($data));
                stream_set_timeout($socket, 5);

                $response = fread($socket, 1024);
                fclose($socket);

                if (!$response) {
                    throw new \Exception("Empty response received from $host:$port");
                }

                $success = true;
            } catch (\Exception $e) {
                Log::error("TCP Request Error (Attempt #$attempt): " . $e->getMessage());

                if ($attempt < $maxRetries) {
                    sleep(pow(2, $attempt));
                }
            }
        }

        if (!$success) {
            Log::critical("TCP Request Failed after $maxRetries attempts: $host:$port");
        }

        return json_decode($response, true);
    }
}
