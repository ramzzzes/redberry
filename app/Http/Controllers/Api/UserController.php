<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request,User $user)
    {
        try{
            $user->register($request->all());

            return response()->json(User::create($request->all()));
        }catch (\Exception $e){
            return response()->json($e->getMessage(),$e->getCode());
        }
    }

    public function login(Request $request,User $user){

        try{
            $token = $user->login($request->all());

            return response()->json([
                'token' => $token->accessToken,
                'expires' => $token->token->expires_at,
            ]);

        }catch (\Exception $e){
            return response()->json($e->getMessage(),$e->getCode());
        }
    }
}
