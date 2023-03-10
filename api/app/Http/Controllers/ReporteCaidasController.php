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

        $strOficiales = '';
        $strCE = '';
        $strOf = '';
        $strOf_CG = '';
        $strOf_AN = '';
        $strOf_AC = '';
        
        $pasada = 1;
        if ($selectedsCE != 0){
            foreach ($selectedsCE as $selected) {
                if ($pasada == 1){
                    $strCE = $selected['Codigo'];
                }else{
                    $strCE = $strCE.','.$selected['Codigo'];
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
                    if ($selected['CodigoAutoCervo'] != null){
                        $strOf_AC = $selected['CodigoAutoCervo'];
                    }
                    if ($selected['CodigoAutoNet'] != null){
                        $strOf_AN = $selected['CodigoAutoNet'];
                    }
                    if ($selected['CodigoCarGroup'] != null){
                        $strOf_CG = $selected['CodigoCarGroup'];
                    }
                }else{
                    $strOf = $strOf.','.$selected['Codigo'];
                    if ($selected['CodigoAutoCervo'] != null){
                        $strOf_AC = $strOf_AC.','.$selected['CodigoAutoCervo'];
                    }
                    if ($selected['CodigoAutoNet'] != null){
                        $strOf_AN = $strOf_AN.','.$selected['CodigoAutoNet'];
                    }
                    if ($selected['CodigoCarGroup'] != null){
                        $strOf_CG = $strOf_CG.','.$selected['CodigoCarGroup'];
                    }
                }
                $pasada ++;
            }
        }else{
            $strOficiales = 'NULL';
            $strOf = 'NULL';
            $strOf_CG = 'NULL';
            $strOf_AN = 'NULL';
            $strOf_AC = 'NULL';

        }

        //return 'GF: '.$strOf.' CG: '.$strOf_CG.' AN: '.$strOf_AN.' AC:'.$strOf_AC;
        $strOficiales = $strOf.','.$strOf_CG.','.$strOf_AN.','.$strOf_AC;

       //return $strOficiales;

        $periodoMes = date("m");
        $periodoAnio = date("Y");

        //$queryStrReport = "CALL hnweb_reporte_ventas_caidas_RB_New(".$periodoMes.", ".$periodoAnio.", '".$strCE."', '".$strOf."', '".$strOf_CG."', '".$strOf_AN."', '".$strOf_AC."');"; 
        $queryStrReport = "CALL hnweb_reporte_ventas_caidas_RB(".$periodoMes.", ".$periodoAnio.", '".$strCE."', '".$strOficiales."');"; 
        //return $queryStrReport;
        //$queryStrReport_Valores = "CALL hnweb_reporte_ventas_caidas_RB_Valores(".$periodoMes.", ".$periodoAnio.", '".$strCE."', '".$strOf."', '".$strOf_CG."', '".$strOf_AN."', '".$strOf_AC."');"; 
        $queryStrReport_Valores = "CALL hnweb_reporte_ventas_caidas_RB_Valores_New(".$periodoMes.", ".$periodoAnio.", '".$strCE."', '".$strOficiales."');";  
        //return $queryStrReport;
        $db = "GF";
        
        $reporte = DB::connection($db)->select($queryStrReport);
        $reporteValores = DB::connection($db)->select($queryStrReport_Valores); 
        $datos =  SubiteDatos::on($db)->whereYear('fechaventacaida', '=', $periodoAnio)->whereMonth('fechaventacaida', '=', $periodoMes)->get();

        $lst = array();
        
        $lst['Reporte'] = $reporte;
        $lst['ReporteValores'] = $reporteValores;
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