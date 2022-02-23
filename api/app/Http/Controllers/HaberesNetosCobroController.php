<?php

namespace App\Http\Controllers;

use App\HaberNeto;
use App\DetalleCobrosHN;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\UtilsController;

class HaberesNetosCobroController extends Controller
{
   
  

    public function cobrarHN(Request $request){

        $hn_cobrado = array();

        $uC = new UtilsController;
        $db = $uC->getDabaseName($request->Marca, $request->Concesionario);

        if ($db == 'GF'){
            $hn_cobrado = DB::connection($db)->select("CALL hnweb_grabar_cobro_hn(".$request->ID.", ".$request->ID_Dato.", ".$request->MontoCobroReal.", '".$request->FechaCobroReal."', '".$request->UsuarioAlta."');");   
        }else{
            $hn_cobrado = DB::connection($db)->select("CALL hnweb_grabar_cobro_hn(".$request->ID.", ".$request->MontoCobroReal.", '".$request->FechaCobroReal."');");      
        }

        return $hn_cobrado;
    }

    public function grabarNuevoCobro(Request $request){

        $nuevo_cobro = array();

        $uC = new UtilsController;
        $db = $uC->getDabaseName($request->Marca, $request->Concesionario);

        if ($db == 'GF'){
            $nuevo_cobro = DB::connection($db)->select("CALL hnweb_grabar_nuevo_cobro_hn(".$request->ID_HN.", ".$request->ID_Dato.", ".$request->MontoCobrado.", '".$request->FechaCobrado."', '".$request->UsuarioAlta."');");   
        }else{
            $nuevo_cobro = DB::connection($db)->select("CALL hnweb_grabar_nuevo_cobro_hn(".$request->ID_HN.", ".$request->MontoCobrado.", '".$request->FechaCobrado."', '".$request->UsuarioAlta."');");      
        }

        return $nuevo_cobro;
    }

    public function getCobrosHN(Request $request){

        $uC = new UtilsController;
        $db = $uC->getDabaseName($request->Marca, $request->Concesionario);

        return DetalleCobrosHN::on($db)->where('ID_HN', $request->ID_HN)->get();

    }


}