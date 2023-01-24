<?php

namespace App\Http\Requests\Driver;

use App\Enums\DriverStatus;
use App\Models\Driver;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class DriverSignupRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::user()->can('signup', Driver::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'car_plate' => ['required', 'regex:/^[1-9]{2}[بجدژسصطقلتع][1-9]{5}$/u'],
            'car_model' => ['required'],
            'status' => ['required', 'string', new Enum(DriverStatus::class)]
        ];
    }


    /**
     * add default status to request
     * @param $keys
     * @return array
     */
    public function all( $keys = null ) : array
    {
        $request = request();
        $request['status'] = DriverStatus::NOT_WORKING->value; //default value
        return $request->toArray();
    }
}
