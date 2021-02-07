<?php

namespace App\Paypal;

use PayPalHttp\HttpResponse;

class Response
{
    protected $paypalResponse;

    public function __construct(HttpResponse $response)
    {
        $this->paypalResponse = json_decode(json_encode($response), true);
    }

    public function content(): array
    {
        return $this->paypalResponse['result'];
    }

    public function id(): string
    {
        return $this->paypalResponse['result']['id'];
    }

    public function captureId(): string
    {
        return $this->paypalResponse['result']['purchase_units'][0]['payments']['captures'][0]['id'];
    }
}
