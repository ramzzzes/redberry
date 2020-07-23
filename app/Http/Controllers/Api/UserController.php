<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users,email|email',
                'password' => 'required|min:5',
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(),400);
            }

            return response()->json(User::create($request->all()));

        }catch (\Exception $e){
            return response()->json($e->getMessage(),$e->getCode());
        }
    }

    public function login(Request $request){

        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:5',
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(),400);
            }

            if(!Auth::attempt([
                'email' => $request->post('email'),
                'password' => $request->post('password'),
            ])){
                throw new \Exception('Invalid Credentials',400);
            }

            $token = Auth::user()->createToken('redberry');

            return response()->json([
                'token' => $token->accessToken,
                'expires' => $token->token->expires_at,
            ]);

        }catch (\Exception $e){
            return response()->json($e->getMessage(),$e->getCode());
        }
    }
}
