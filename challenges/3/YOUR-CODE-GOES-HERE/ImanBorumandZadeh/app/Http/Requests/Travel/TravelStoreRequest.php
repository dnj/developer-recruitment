<?php

namespace App\Http\Requests\Travel;

use App\Enums\TravelStatus;
use App\Models\Travel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class TravelStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::user()->can('create', Travel::class);
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
            'status' => ['required', 'string', new Enum(TravelStatus::class)]
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


    /**
     * add default status to request
     * @param $keys
     * @return array
     */
    public function all( $keys = null ) : array
    {
        $request = request();
        $request['status'] = TravelStatus::SEARCHING_FOR_DRIVER->value;
        return $request->toArray();
    }
}
