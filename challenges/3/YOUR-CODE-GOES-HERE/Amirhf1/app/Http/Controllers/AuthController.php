<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\Auth\RegisterResource;
use App\Http\Resources\User\UserResource;
use App\Services\Auth\AuthService;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    protected AuthService $authService;

    protected UserService $userService;

    public function __construct(AuthService $authService, UserService $userService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
    }

    /**
     * @param  RegisterRequest  $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $register = $this->authService->register($request);

        return (new RegisterResource($register))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function user(UserRequest $request)
    {
        $user = $request->user();

        $this->userService->user($user);

        return new UserResource($user);
    }
}
