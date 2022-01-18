<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

use App\Http\Controllers\UtilsController;

class ReporteRentabilidadCarteraTotalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    
    public function getReporte(Request $request)
    {
        $data = array();
        $util = new UtilsController;

        $concesionario = $request->Concesionario;
        $db = $util->getDabaseNameByCE($concesionario);


        if ($concesionario == 8){
            $query_proyectado = "CALL hnweb_proyectado_renta_cartera_rb()";
        
            $query_historico = "CALL hnweb_historico_renta_cartera_rb()";

        }else{
            $query_proyectado = "SELECT IFNULL(SUM(HaberNetoSubiteUSD),0) AS HN_TotalACobrar,
            IFNULL(SUM(HaberNetoSubiteUSD),0) - IFNULL(SUM(MontoCompraDolares),0) AS RentabilidadTeorica 
            FROM haberesnetosok WHERE ComproGiama = 0 
            AND IFNULL(FechaCobroReal, '') = '' 
            AND Concesionario = ".$concesionario.";";
        
            $query_historico = "SELECT IFNULL(SUM(MontoCompraDolares),0) AS CostoHistorico, 
            IFNULL(SUM(MontoCobroDolares),0) AS CobroHistorico, 
            IFNULL(SUM(MontoCobroDolares),0) - IFNULL(SUM(MontoCompraDolares),0) AS RentabilidadHistorico
            FROM haberesnetosok
            WHERE ComproGiama = 0 AND IFNULL(FechaCobroReal, '') <> '' AND Concesionario = ".$concesionario.";";

        }
        
        $proyectado = DB::connection($db)->select($query_proyectado);

        $historico = DB::connection($db)->select($query_historico);
        
        $data['Datos_Proyectado'] = $proyectado;
        $data['Datos_Historico'] = $historico;

        return $data;

    }



}