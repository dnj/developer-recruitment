<?php

namespace App\Http\Requests\Travel;

use App\Models\Travel;
use App\Models\TravelSpot;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DestroyTravelSpotRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::user()
                   ->can('destroy', TravelSpot::find(request()->travel));
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
        $request['travel_id'] = (int) $request->travel;
        $request['spot_id'] = (int) $request->spot;
        return $request->toArray();
    }
}
