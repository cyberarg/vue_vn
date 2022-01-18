<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class MeController extends Controller
{
    
    public function __construct(){
       //$this->middleware(['auth:api']);
    }


    public function __invoke(Request $request){

           //dd($request); 
//$user = User::where('login' = 'admin');
       // $user = $request->user();
        //dd($request);
        return response()->json([
            'login' => $user->login,
            'Nombre' => $user->Nombre,
            
        ]);
        
    }
}
