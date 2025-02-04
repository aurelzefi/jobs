<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Alert;
use App\Models\Job;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlertRequest extends FormRequest
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
            'keywords' => ['required', 'string'],
            'has_all_keywords' => ['boolean'],
            'city' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(Alert::TYPES)],
            'job_types' => ['required', 'array'],
            'job_types.*' => ['required', 'string', Rule::in(Job::TYPES)],
            'job_styles' => ['required', 'array'],
            'job_styles.*' => ['required', 'string', Rule::in(Job::STYLES)],
        ];
    }

    public function fields(): array
    {
        return $this->only([
            'country_id',
            'name',
            'has_all_keywords',
            'city',
            'type',
            'job_types',
            'job_styles',
        ]);
    }

    public function keywords(): array
    {
        return explode(',', $this->input('keywords'));
    }
}
