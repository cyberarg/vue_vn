<?php

namespace App\Http\Controllers;

use App\SubiteDatos;
use App\EstadoGestion;
use Illuminate\Http\Request;
use DB;
use \stdClass;

class ReporteComprasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    //public function index(Request $request)
    public function getReporte(Request $request)
    {
        //dd($request->periodo);
       $periodo = $request->periodo;
       
        $periodoMes = substr($periodo, 4, strlen($periodo));
        $periodoAnio = substr($periodo, 0, 4);

        //$queryStrReport = "CALL hnweb_reportecompras(".$periodoMes.", ".$periodoAnio.");";
        $queryStrReport = "CALL hnweb_reportecompras_objetivos(".$periodoMes.", ".$periodoAnio.");"; 

        $db = "GF";
        
        $reporte = DB::connection($db)->select($queryStrReport);
        $datos =  SubiteDatos::on($db)->where('CodEstado', 5)->whereYear('fechacompra', '=', $periodoAnio)->whereMonth('fechacompra', '=', $periodoMes)->get();

        $lst = array();
        
        $lst['Reporte'] = $reporte;
        $lst['Datos'] = $datos;


        return $lst;
    }


}