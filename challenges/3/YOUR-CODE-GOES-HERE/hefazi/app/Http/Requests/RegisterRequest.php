<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required'],
            'lastname' => ['required'],
            'password' => ['required', 'min:6'],
        ];
    }
}
