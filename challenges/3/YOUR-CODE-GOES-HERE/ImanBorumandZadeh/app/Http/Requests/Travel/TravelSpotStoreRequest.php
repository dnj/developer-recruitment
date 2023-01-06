<?php

namespace App\Http\Requests\Travel;

use Illuminate\Foundation\Http\FormRequest;

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
            'position' => ['required', 'integer', 'min:1'],
            'latitude' => ['required','min:-90', 'max:90'],
            'longitude' => ['required','min:-180', 'max:180'],
        ];
    }
}
