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
            'password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|confirmed',
        ];
    }

    public function validatePassword(): void
    {
        if (! Hash::check($this->input('password'), $this->user()->password)) {
            throw ValidationException::withMessages([
                'password' => __('The current password does not match.'),
            ]);
        }
    }
}
