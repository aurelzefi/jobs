<?php

namespace App\Paypal;

use App\Models\Order as EloquentOrder;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class Payment
{
    protected $order;

    protected function client(): PayPalHttpClient
    {
        return new PayPalHttpClient(
            new SandboxEnvironment(
                config('services.paypal.client_id'),
                config('services.paypal.client_secret')
            )
        );
    }

    public function forOrder(EloquentOrder $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function create(): Order
    {
        $request = new OrdersCreateRequest();

        $request->prefer('return=representation');

        $request->body = $this->payload();

        return new Order(
            $this->client()->execute($request)
        );
    }

    public function capture(): Order
    {
        $request = new OrdersCaptureRequest($this->order->paypal_order_id);

        return new Order(
            $this->client()->execute($request)
        );
    }

    protected function payload(): array
    {
        return [
            'intent' => 'CAPTURE',
            'application_context' => [
                'brand_name' => config('app.name'),
                'locale' => config('app.locale'),
                'user_action' => 'PAY_NOW',
            ],
            'purchase_units' => [
                [
                    'description' => sprintf('%s - %s Job Post', config('app.name'), ucfirst($this->order->type)),
                    'amount' => [
                        'currency_code' => 'EUR',
                        'value' => (string) $this->order->amount / 100,
                    ]
                ],
            ],
        ];
    }
}
