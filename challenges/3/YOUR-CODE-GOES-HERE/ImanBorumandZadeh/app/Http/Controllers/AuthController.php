<?php
namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller {


    /**
     * register user
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register( RegisterRequest $request) : JsonResponse
    {
        return $this->apiResponse([
            'user' => new UserResource(User::create($request->validated()))
         ]);
	}


    /**
     * retrieve user form auth
     * @return JsonResponse
     */
    public function user() : JsonResponse
    {
        return $this->apiResponse([
           'user' =>  auth()->user()
        ]);
	}
}
