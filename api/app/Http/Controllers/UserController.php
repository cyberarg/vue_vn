<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use DB;
use Hash;

class UserController extends Controller
{
    
    
    public function changePassword(Request $request){
        $login = $request->username;
        $password = $request->password;
        $newpassword = $request->newpassword;

        $passHash = Hash::make('$password');
        $newPasswordHashed = bcrypt($newpassword);

        $respuesta = array();

        $user = User::findOrFail($login);

        if ($user){

            if (Hash::check($password, $user->password)){
            
                $user->password = $newPasswordHashed;
                $user->changepassword = 1;
        
                $user->save();
        
                if ($user){
                    $respuesta['Status'] = "success";
                    $respuesta['Msg'] = "La contraseña se modificó correctamente. Deberá iniciar sesión con sus nuevas credenciales.";
                }
                
            }else{
                $respuesta['Status'] = "error";
                $respuesta['Msg'] = "La contraseña actual ingresada es incorrecta.";
            }
        }else{
            $respuesta['Status'] = "error";
            $respuesta['Msg'] = "Ocurrió un error al intengar guardar su nueva contraseña. Por favor, intente nuevamente mas tarde.";
        }
       
        return $respuesta;
    }


}
