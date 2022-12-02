<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class TravelStoreRequest extends FormRequest
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
            'spots' => ['required', 'array', 'min:2'],
            'spots.*.position' => ['required', 'integer', 'min:0'],
            'spots.*.latitude' => ['required','min:-90', 'max:90'],
            'spots.*.longitude' => ['required','min:-180', 'max:180'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function(Validator $validator) {
            $validated = $validator->validated();
            $positions = array_map('intval', array_column($validated['spots'], 'position'));
            sort($positions);
            if ($positions !== range(0, count($validated['spots']) - 1)) {
                $validator->errors()->add('spots', 'Spot position must be a numerical sequence starting with 0');
            }
        });
    }
}
