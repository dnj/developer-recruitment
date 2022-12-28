<?php
namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

	public function register(RegisterRequest $req): \Illuminate\Http\JsonResponse
    {

        $user=new User();
        $user->cellphone=$req->cellphone;
        $user->name=$req->name;
        $user->lastname=$req->lastname;
        $user->password=Hash::make($req->password);
        $result=$user->save();

        //Create token for registered user
        if($result){

         $token= $user->createToken('api_token');

        }

        return response()->json(UserResource::make($user));
	}

	public function user(): \Illuminate\Http\JsonResponse
    {

        $user=auth('sanctum')->user();

        return response()->json(UserResource::make($user));

	}

}
