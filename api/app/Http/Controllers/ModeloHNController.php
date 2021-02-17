<?php

namespace App\Http\Controllers;

use App\ModeloHN;
use Illuminate\Http\Request;
use DB;

class ModeloHNController extends Controller
{
    public function getModeloControl(Request $request){

        switch($request->Concesionario){
            case 4:
                $db = 'AC';
            break;
            case 5:
                $db = 'AN';
            break;
            case 6:
                $db = 'CG';
            break;
            default:
                $db = 'GF';
            break;
        }

        return ModeloHN::on($db)->where('Concesionario', $request->Concesionario)->get();
    }

    public function grabarModelo(Request $request){
        switch($request->Concesionario){
            case 4:
                $db = 'AC';
            break;
            case 5:
                $db = 'AN';
            break;
            case 6:
                $db = 'CG';
            break;
            default:
                $db = 'GF';
            break;
        }

        $newModelo = new ModeloHN();

        $newModelo->setConnection($db);
        $newModelo->Marca = $request->Marca;
        $newModelo->Concesionario = $request->Concesionario;
        $newModelo->GraboModelo = 1;
        $newModelo->ObjCotizDolar = $request->ObjCotizDolar;
        $newModelo->ObjSupuestoCobro = $request->ObjSupuestoCobro;
        $newModelo->ObjInversionInicial = $request->ObjInversionInicial;
        $newModelo->ObjCantCasos = $request->ObjCantCasos;
        $newModelo->ObjHNPromedio = $request->ObjHNPromedio;
        $newModelo->ObjCostoTotal = $request->ObjCostoTotal;
        $newModelo->ObjDuration = 18;
        $newModelo->ObjTIRCompuesta = $request->ObjTIRCompuesta;

        $newModelo->save();

        return $newModelo;
        

    }
}
