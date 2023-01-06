<?php

namespace App\Http\Requests\Travel;

use App\Models\TravelSpot;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TravelArrivedRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::user()->can('markAsArrived', TravelSpot::find(request()->spot));
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'travel_id' => ['required', 'numeric', 'exists:travels,id'],
            'spot_id' => ['required', 'numeric', 'exists:travels_spots,id'],
        ];
    }

    /**
     *
     * @param $keys
     * @return array
     */
    public function all( $keys = null ) : array
    {
        $request = request();
        //set travel_id and spot_id for use in controller and rules in request
        $request['travel_id'] = (int) $request->travel;
        $request['spot_id'] = (int) $request->spot;
        return $request->toArray();
    }
}
