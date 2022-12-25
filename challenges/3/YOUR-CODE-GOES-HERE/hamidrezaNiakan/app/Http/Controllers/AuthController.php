<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
	/**
	 * Create new use as passenger
	 *
	 * @param \App\Http\Requests\RegisterRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function register ( RegisterRequest $request ) {
		$user = User::query()
					->create([
								 'cellphone' => $request->get('cellphone') ,
								 'name' => $request->get('name') ,
								 'lastname' => $request->get('lastname') ,
								 'password' => Hash::make($request->get('password')) ,
							 ]);
		$user[ 'token' ] = $user->createToken('api-token')->plainTextToken;
		
		return response()->json([
									'user' => UserResource::make($user) ,
								]);
	}
	
	/**
	 * Display current user that is login
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function user ( Request $request ) {
		$user = $request->user();
		$user[ 'token' ] = $request->user()
								   ->currentAccessToken()->token;
		
		return response()->json([
									'user' => UserResource::make($user) ,
								]);
	}
}
