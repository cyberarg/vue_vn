<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Session;
use Auth;
use JWTAuth;

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
       
/*
      $credentials = $request->only('login', 'password');
      try {
          // verify the credentials and create a token for the user
          if (! $access_token = JWTAuth::attempt($credentials)) {
              //return response()->json(['error' => 'invalid_credentials'], 401);
              return response(null, 401);
          }
      } catch (JWTException $e) {
          // something went wrong
          return response()->json(['error' => 'could_not_create_token'], 500);
      }
      
      $user = Auth::user();
       
        
      return response()->json(compact('access_token', 'user'));  
*/
    }
}
