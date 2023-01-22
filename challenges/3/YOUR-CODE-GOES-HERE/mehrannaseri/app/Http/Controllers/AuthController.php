<?php
namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
	public function register(RegisterRequest $request) : JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);
        if($user)
            $user->token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => UserResource::make($user)
        ]);
	}

	public function user() :JsonResponse
    {
        $user = auth()->user();

        return response()->json([
            'user' => UserResource::make($user)
        ]);
	}
}
