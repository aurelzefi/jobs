<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', $this->allowFreeOrders()],
        ];
    }

    public function creatingFreeOrder(): bool
    {
        return $this->input('type') === Order::TYPE_FREE;
    }

    protected function allowFreeOrders(): In
    {
        return Rule::in(collect(Order::TYPES)->push(Order::TYPE_FREE));
    }
}
