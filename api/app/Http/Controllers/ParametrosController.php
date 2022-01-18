<?php

namespace App\Http\Controllers;

use App\Concesionario;
use Illuminate\Http\Request;
use DB;

class ParametrosController extends Controller
{

    public function getConcesionarios(Request $request)
    {
        $arrayCE = array();
        if ($request->Marca == 0){
            $arrayCE = DB::select('SELECT ID, Nombre, MarcaDefault FROM hnweb_concesionarios;');  
            return $arrayCE;
            //return Concesionario::orderBy('MarcaDefault', 'asc')->orderBy('Nombre', 'asc')->get();
        }
        
        if ($request->Marca != 0 && $request->Concesionario == 0){
            return Concesionario::where('MarcaDefault', $request->Marca)->get();
        }

        return Concesionario::where('ID', $request->Concesionario)->get();
        
    }


}
