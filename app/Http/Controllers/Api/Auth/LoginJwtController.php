<?php

namespace App\Http\Controllers\Api\Auth;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginJwtController extends Controller
{
    public function login(Request $request){
        
        $credentials = $request->all(['email','password']);

        Validator::make($credentials,[
            'email' => 'required|string',
            'password' => 'required|string'
        ])->validate();

        if(!$token = auth('api')->attempt($credentials)){
            return response()->json(ApiMessages::getErrorMessage('Unauthorized'),401);
        }
  
        return response()->json(
            ApiMessages::getSuccessMessage(config('constants.jwt.login_success'),['token'=>$token]));

    }

    public function logout(){

        auth('api')->logout();

        return response()->json(ApiMessages::getSuccessMessage(config('constants.jwt.logouts_success')),200);
    }

    public function refresh(){
        $token = auth('api')->refresh();
        return response()->json(
            ApiMessages::getSuccessMessage(config('constants.jwt.refresh'),['token'=>$token]));

    }
}
