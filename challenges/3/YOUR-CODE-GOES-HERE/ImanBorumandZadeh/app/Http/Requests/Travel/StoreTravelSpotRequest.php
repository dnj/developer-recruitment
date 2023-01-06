<?php

namespace App\Http\Requests\Travel;

use App\Models\Travel;
use App\Models\TravelSpot;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTravelSpotRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::user()
                   ->can(
                       'create',
                       [TravelSpot::class, Travel::find(request()->travel)]
                   );
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'latitude' => ['required', 'min:-90', 'max:90'],
            'longitude' => ['required', 'min:-180', 'max:180'],
            'position' => ['required', 'integer', 'min:1', 'between:0,1'],
            'travel_id' => ['required', 'numeric', 'exists:travels,id'],
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
        return $request->toArray();
    }
}
