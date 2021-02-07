<?php

namespace App\Paypal;

use PayPalHttp\HttpResponse;

class Order
{
    protected $response;

    public function __construct(HttpResponse $response)
    {
        $this->response = json_decode(json_encode($response), true);
    }

    public function content(): array
    {
        return $this->response['result'];
    }

    public function id(): string
    {
        return $this->response['result']['id'];
    }

    public function captureId(): string
    {
        return $this->response['result']['purchase_units'][0]['payments']['captures'][0]['id'];
    }
}
