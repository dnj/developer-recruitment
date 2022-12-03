<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

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
}
