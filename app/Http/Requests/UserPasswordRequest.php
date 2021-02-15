<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => 'required|string|min:6',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function validatePassword(): void
    {
        if (! Hash::check($this->input('current_password'), $this->user()->password)) {
            throw ValidationException::withMessages([
                'current_password' => __('The current password does not match.'),
            ]);
        }
    }
}
