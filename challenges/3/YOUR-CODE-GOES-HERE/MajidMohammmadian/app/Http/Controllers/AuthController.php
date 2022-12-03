<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = new User;

        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->cellphone = $request->cellphone;
        $user->password = $request->password;

        $user->save();

        return response()->json(UserResource::make($user));
    }

    public function user(Request $request)
    {
        return response()->json(UserResource::make($request->user()));
    }

    public function login(LoginRequest $request)
    {
        $user = User::query()->where('cellphone', $request->cellphone)->first();

        auth()->login($user);

        if(!($token = $user->createToken("API TOKEN")->plainTextToken)) {
            return response()->json([
                'code' => 'UserNotLogin'
            ], 400);
        }


        return response()->json([
            'token' => $token
        ]);
    }
}
