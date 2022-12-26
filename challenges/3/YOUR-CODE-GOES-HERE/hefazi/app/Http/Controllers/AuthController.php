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
//        $user = User::create($request->all());
        $user = new User();
        $user->cellphone = $request->cellphone;
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(UserResource::make($user));
    }

    public function user(Request $request)
    {
        return response()->json(UserResource::make($request->user()));
    }
}
