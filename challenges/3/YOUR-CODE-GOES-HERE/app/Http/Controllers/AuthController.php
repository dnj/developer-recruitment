<?php
namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class AuthController extends Controller {

	public function register(RegisterRequest $request) : object {

		$create_user = User::query()->create([
            'cellphone'   => $request->cellphone,
            'name'        => $request->name,
            'lastname'    => $request->lastname ,
            'password'    => $request->password
        ]);

        if(isset($create_user)){

            return response()->json([
                'user' => $create_user
            ],HttpFoundationResponse::HTTP_OK) ;

        }else{

            return response()->json([
                'user' => $create_user
            ],HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY) ;

        }
	}

	public function user():object {
        $user = User::query()->first();

        return response()->json([
            'user' => $user
        ], HttpFoundationResponse::HTTP_OK) ;
	}
}
