<?php

namespace App\Http\Requests\Travel;

use App\Enums\TravelStatus;
use App\Models\Travel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class PassengerOnBoardRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::user()->can('markAsPassengerOnBoard', Travel::find(request()->travel));
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'numeric', 'exists:travels,id'],
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
        $request['id'] = (int) $request->travel;
        return $request->toArray();
    }
}
