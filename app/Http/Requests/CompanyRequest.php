<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'country_id' => ['required', Rule::exists('countries', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:1024'],
            'description' => ['required', 'string'],
            'website' => ['nullable', 'string', 'url', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
        ];
    }

    public function fields(): array
    {
        return array_merge($this->only([
            'country_id',
            'name',
            'description',
            'website',
            'address',
            'city',
        ]), [
            'logo' => $this->logo(),
        ]);
    }

    protected function logo()
    {
        if ($this->hasFile('logo')) {
            return $this->file('logo')->store('images', 'public');
        }
    }
}
