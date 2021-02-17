<?php

namespace App\Http\Controllers;

use App\SubiteDatos;
use App\EstadoGestion;
use Illuminate\Http\Request;
use DB;
use \stdClass;

class ReporteCaidasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function getReporte(Request $request)
    {
  
        $periodo = $request->Periodo;
        $selectedsCE = $request->SelectedsCE;
        $selectedsOf = $request->SelectedsOf;

        $strCE = '';
        $strOf = '';

        $pasada = 1;
        if ($selectedsCE != 0){
            foreach ($selectedsCE as $selected) {
                if ($pasada == 1){
                    $strCE = $selected['Codigo'];
                }else{
                    $strCE = $strCE.', '.$selected['Codigo'];
                }
                $pasada ++;
            }
        }else{
            $strCE = 'NULL';
        }

        $pasada = 1;
        if ($selectedsOf != 0){
            foreach ($selectedsOf as $selected) {
                if ($pasada == 1){
                    $strOf = $selected['Codigo'];
                }else{
                    $strOf = $strOf.', '.$selected['Codigo'];
                }
                $pasada ++;
            }
        }else{
            $strOf = 'NULL';
        }
       
        /*
        $periodoMes = substr($periodo, 4, strlen($periodo));
        $periodoAnio = substr($periodo, 0, 4);
        */

        $periodoMes = date("m");
        $periodoAnio = date("Y");

        $queryStrReport = "CALL hnweb_reporte_ventas_caidas_RB(".$periodoMes.", ".$periodoAnio.", '".$strCE."', '".$strOf."');"; 

        $db = "GF";
        
        $reporte = DB::connection($db)->select($queryStrReport);
        $datos =  SubiteDatos::on($db)->whereYear('fechaventacaida', '=', $periodoAnio)->whereMonth('fechaventacaida', '=', $periodoMes)->get();

        $lst = array();
        
        $lst['Reporte'] = $reporte;
        $lst['Datos'] = $datos;


        return $lst;
    }


    //public function index(Request $request)
    /*
    public function getReporte(Request $request)
    {
  
       $periodo = $request->periodo;
       
        $periodoMes = substr($periodo, 4, strlen($periodo));
        $periodoAnio = substr($periodo, 0, 4);

        $queryStrReport = "CALL hnweb_reporte_ventas_caidas(".$periodoMes.", ".$periodoAnio.");"; 

        $db = "GF";
        
        $reporte = DB::connection($db)->select($queryStrReport);
        $datos =  SubiteDatos::on($db)->whereYear('fechaventacaida', '=', $periodoAnio)->whereMonth('fechaventacaida', '=', $periodoMes)->get();

        $lst = array();
        
        $lst['Reporte'] = $reporte;
        $lst['Datos'] = $datos;


        return $lst;
    }
    */


}