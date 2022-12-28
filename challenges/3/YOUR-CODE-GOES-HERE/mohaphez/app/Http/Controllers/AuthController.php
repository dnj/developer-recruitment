<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class AuthController extends Controller
{
	public function register(RegisterRequest $request)
	{
		$user = User::create($request->validated());
		$user->token = $user->createToken('app-token')->plainTextToken;

		return UserResource::make($user)->response()->setStatusCode(200);
	}

	public function user()
	{
		return UserResource::make(auth()->user());
	}
}
