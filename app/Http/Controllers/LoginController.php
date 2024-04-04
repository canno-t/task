<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Responses\LoginResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{


    public function login(LoginRequest $request){
        if(Auth::attempt([
            'email'=>$request['email'],
            'password'=>$request['password']
        ])){
             return LoginResponse::setResponse(true)
                 ->addParam('token',
                     $request->user()->getUserToken())//jeśli user ma już token to zwraca istniejący
                    ->returnResponse();
        }
        return LoginResponse::setResponse(false, 'user credentials do not mach')
            ->returnResponse();
    }
}
