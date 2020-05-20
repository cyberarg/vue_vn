<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Session;

class SignInController extends Controller
{
    public function __invoke(Request $request){
        if (!$access_token = auth('api')->attempt($request->only('login', 'password'))){
            return response(null, 401);
        }

        $user = new User;
        $user = User::find($request->login);
        session(['user' => $user ]);
        $access_token = auth('api')->login($user);
        
        return response()->json(compact('access_token', 'user'));

    }
}
