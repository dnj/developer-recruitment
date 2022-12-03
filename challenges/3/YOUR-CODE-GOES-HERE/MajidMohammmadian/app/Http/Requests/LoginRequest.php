<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed $cellphone
 * @property mixed $password
 */
class LoginRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'cellphone' => ['required', 'regex:/^09\d{9}$/'],
            'password' => ['required', 'min:6'],
        ];
    }
}
