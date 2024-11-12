<?php

namespace App\Library;

use GuzzleHttp\Client;
use Log;
use Sentry;
use Throwable;

class Sms
{
    public static function send(string $to, string $message): void
    {
        $message = $message.' '.env('SMS_TEMPLATE_COMPANY_NAME');

        if (env('APP_ENV') == 'local') {
            Log::info($message);
        }

        if (env('SMS_KEY')) {
            try {
                (new Client)->get(env('SMS_URL').'/api/v2/sms/send', [
                    'headers' => [
                        'Authorization' => 'Bearer '.env('SMS_KEY'),
                    ],
                    'query' => [
                        'message' => $message,
                        'sender' => env('SMS_SENDER_ID'),
                        'to' => env('TEST_MOBILE', $to),
                        'service' => 'T',
                    ],
                    'timeout' => 30,
                ])->getBody()->__toString();
            } catch (Throwable $e) {
                Sentry::captureException($e);
            }
        }
    }

    public static function balance(): ?int
    {
        if (! env('SMS_KEY') || ! settings('sms_enabled')) {
            return null;
        }

        try {
            $response = json_decode(
                (new Client)->get(env('SMS_URL').'/api/v2/account/balance', [
                    'headers' => [
                        'Authorization' => 'Bearer '.env('SMS_KEY'),
                    ],
                    'timeout' => 30,
                ])->getBody()
            );

            if ($transactionalBalance = collect($response->data)->firstWhere('service', 'T')) {
                return $transactionalBalance->credits;
            }

            return null;
        } catch (Throwable $e) {
            Sentry::captureException($e);

            return null;
        }
    }
}
