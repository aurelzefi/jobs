<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Job;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', Rule::in($this->user()->companies()->pluck('id'))],
            'country_id' => ['required', Rule::exists('countries', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', Rule::in(Job::TYPES)],
            'style' => ['required', 'string', Rule::in(Job::STYLES)],
        ];
    }

    public function fields(): array
    {
        return $this->only([
            'company_id',
            'country_id',
            'title',
            'description',
            'city',
            'type',
            'style',
        ]);
    }
}
