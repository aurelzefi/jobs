<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(Order::TYPES)],
        ];
    }

    public function creatingFreeOrder(): bool
    {
        return $this->input('type') === Order::TYPE_FREE;
    }
}
