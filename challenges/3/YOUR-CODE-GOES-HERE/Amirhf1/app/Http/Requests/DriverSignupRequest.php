<?php

namespace App\Http\Requests;

use App\Models\Driver;
use Illuminate\Foundation\Http\FormRequest;

class DriverSignupRequest extends FormRequest
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
            'car_plate' => ['required', 'regex:/^[1-9]{2}[بجدژسصطقلتع][1-9]{5}$/u'],
            'car_model' => ['required'],
        ];
    }
}
