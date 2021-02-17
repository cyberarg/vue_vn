<?php

namespace App\Http\Controllers;

use App\SubiteDatos;
use App\EstadoGestion;
use Illuminate\Http\Request;
use DB;
use DateTime;
use \stdClass;

class TableroControlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    //public function index(Request $request)
    public function getDatos(Request $request)
    {
        //dd($request->periodo);
       $periodo = $request->periodo;
       
        $periodoMes = substr($periodo, 4, strlen($periodo));
        $periodoAnio = substr($periodo, 0, 4);

        $fechaHoy = new DateTime('NOW');
        $fechaHoyDB = $fechaHoy->format("Ymd");

        $yearHoy = $fechaHoy->format("Y");
        $monthHoy = $fechaHoy->format("n");
        $dayHoy = $fechaHoy->format("d");



        $queryStrReport = "CALL hnweb_tablerocontrol('".$fechaHoyDB."');";

        $db = "GF";
        
        $reporte = DB::connection($db)->select($queryStrReport);
        $datos =  SubiteDatos::on($db)->where('CodEstado', 5)->whereYear('fechacompra', '<', $fechaHoyDB)->get();

        $lst = array();
        
        $lst['Reporte'] = $reporte;
        $lst['Datos'] = $datos;


        return $lst;
    }


}