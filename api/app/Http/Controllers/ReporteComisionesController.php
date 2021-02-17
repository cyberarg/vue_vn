<?php

namespace App\Http\Controllers;

use App\SubiteDatos;
use App\HaberNeto;
use App\EstadoGestion;
use Illuminate\Http\Request;
use DB;
use \stdClass;

class ReporteComisionesController extends Controller
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
        /*
        $periodoMes = substr($periodo, 4, strlen($periodo));
        $periodoAnio = substr($periodo, 0, 4);
        */

        $periodoMes = $periodo * 3;
        $periodoAnio = date("Y");
        
        //$periodoMes = 9;
        //$periodoAnio = 2020;

        //$queryStrReport = "CALL hnweb_reportecomisiones(".$periodoMes.", ".$periodoAnio.");";
        $queryStrReport = "CALL hnweb_reportecomisiones_trimestres_v3(".$periodoMes.", ".$periodoAnio.");";
        

        $db = "GF";
        
        $reporte = DB::connection($db)->select($queryStrReport);
        $datos =  HaberNeto::on($db)->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();

        $lst = array();
        
        $lst['Reporte'] = $reporte;
        $lst['Datos'] = $datos;


        return $lst;
    }

    public function getReporteAnual(Request $request)
    {
        //dd($request->periodo);
       $periodo = $request->periodo;
       
        $periodoMes = substr($periodo, 4, strlen($periodo));
        $periodoAnio = substr($periodo, 0, 4);

        //$queryStrReport = "CALL hnweb_reportecomisionesanual(".$periodoAnio.");";
        $queryStrReport = "CALL hnweb_reportecomisiones_anual(".$periodoAnio.");";
        

        $db = "GF";
        
        $reporte = DB::connection($db)->select($queryStrReport);
        $datos =  HaberNeto::on($db)->whereYear('FechaAltaRegistro', '=', $periodoAnio)->get();

        $lst = array();
        
        $lst['Reporte'] = $reporte;
        $lst['Datos'] = $datos;


        return $lst;
    }


}