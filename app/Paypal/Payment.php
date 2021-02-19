<?php

declare(strict_types=1);

namespace App\Paypal;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class Payment
{
    protected $id;

    protected $type;

    protected $amount;

    protected function client(): PayPalHttpClient
    {
        return new PayPalHttpClient(
            new SandboxEnvironment(
                config('services.paypal.client_id'),
                config('services.paypal.client_secret')
            )
        );
    }

    public function withId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function withType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function withAmount(int $amount): self
    {
        $this->amount = $amount;

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
        $request = new OrdersCaptureRequest($this->id);

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
                'locale' => app()->getLocale(),
                'user_action' => 'PAY_NOW',
            ],
            'purchase_units' => [
                [
                    'description' => sprintf('%s - %s Job Post', config('app.name'), ucfirst($this->type)),
                    'amount' => [
                        'currency_code' => config('services.paypal.currency'),
                        'value' => $this->amount / 100,
                    ]
                ],
            ],
        ];
    }
}
