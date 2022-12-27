<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::query()->create([
                'cellphone' => $request->get('cellphone') ,
                'name'      => $request->get('name') ,
                'lastname'  => $request->get('lastname') ,
                'password'  => Hash::make($request->get('password'))
            ]);

        return response()->json([
            'user' => UserResource::make($user)
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'user' => UserResource::make($user)
        ]);
    }
}
