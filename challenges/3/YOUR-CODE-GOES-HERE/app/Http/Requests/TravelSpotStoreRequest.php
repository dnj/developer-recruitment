<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Validator;

class TravelSpotStoreRequest extends FormRequest
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
            'position' => ['nullable', 'integer'],
            'latitude' => ['nullable','min:-90', 'max:90'],
            'longitude' => ['nullable','min:-180', 'max:180'],
        ];
    }
}
