<?php

namespace App\Http\Controllers;

use App\SubiteDatos;
use App\HaberNeto;
use App\EstadoGestion;
use Illuminate\Http\Request;
use DB;
use \stdClass;

class ReporteFacturacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    //public function index(Request $request)
    public function getReporte(Request $request)
    {
    
        $periodo = $request->periodo;
       
        $periodoMes = substr($periodo, 4, strlen($periodo));
        $periodoAnio = substr($periodo, 0, 4);
        
        $queryStrReport_CE = "CALL hnweb_reporte_facturacion_v2(".$periodoMes.", ".$periodoAnio.");";
        $queryStrReport_RB = "CALL hnweb_reporte_facturacion_v2_rb(".$periodoMes.", ".$periodoAnio.");";
          
        $reporte_CE = DB::select($queryStrReport_CE);
        $reporte_RB = DB::select($queryStrReport_RB);

        $detalle_RB_RB = HaberNeto::on('RB')->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();
        $detalle_RB_GF = HaberNeto::on('GF')->where('ComproGiama', '=', 1)->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();
    

        //$detalle_RB = $detalle_RB_RB->get();

        /*
        $detalle_CE_GF = HaberNeto::on('GF')->where('ComproGiama', '=', 0)->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();
        
        $detalle_CE_AN = HaberNeto::on('AN')->where('ComproGiama', '=', 0)->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();
        $detalle_CE_CG = HaberNeto::on('CG')->where('ComproGiama', '=', 0)->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();
        */
        //$datos =  HaberNeto::on($db)->whereNull('FechaCobroReal')->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();

        $lst = array();


        
        $lst['Reporte_CE'] = $reporte_CE;
        $lst['Reporte_RB'] = $reporte_RB;
        $lst['Detalle_RB_RB'] = $detalle_RB_RB;
        $lst['Detalle_RB_CE'] = $detalle_RB_GF;


        return $lst;
    }



    public function getDetalle(Request $request)
    {
    
        $periodo = $request->periodo;
       
        $periodoMes = substr($periodo, 4, strlen($periodo));
        $periodoAnio = substr($periodo, 0, 4);
        
        $lstCEs = array();
        $lstRBs = array();
        $lstHNs = array();
       
        $detalle_CE = HaberNeto::on('GF')->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();

        foreach ($detalle_CE as $det) {
            $oDet = new \stdClass();

            $oDet->Grupo = $det->Grupo;
            $oDet->Orden = $det->Orden;
            $oDet->HaberNeto = $det->HaberNetoOriginal;
            $oDet->Concesionario = $det->Concesionario;
            $oDet->Avance = $det->Avance;
            $oDet->AFacturar = $det->HaberNetoOriginal * 0.07;
            $oDet->ComproGiama = $det->ComproGiama;
            if ($det->ComproGiama == 1){
                $oDet->Titular = 'RB';
                array_push($lstRBs,$oDet);
            }else{
                $oDet->Titular = 'CONCESIONARIO';
                array_push($lstCEs,$oDet);
            }
            array_push($lstHNs,$oDet);
        }

        $detalle_RB_RB = HaberNeto::on('RB')->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();
        $detalle_RB_GF = HaberNeto::on('GF')->where('ComproGiama', '=', 1)->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();
    


        //$detalle_RB = $detalle_RB_RB->get();

        /*
        $detalle_CE_GF = HaberNeto::on('GF')->where('ComproGiama', '=', 0)->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();
        
        $detalle_CE_AN = HaberNeto::on('AN')->where('ComproGiama', '=', 0)->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();
        $detalle_CE_CG = HaberNeto::on('CG')->where('ComproGiama', '=', 0)->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();
        */
        //$datos =  HaberNeto::on($db)->whereNull('FechaCobroReal')->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();

        $lst = array();
        
        $lst['Reporte_CE'] = $reporte_CE;
        $lst['Reporte_RB'] = $reporte_RB;
        $lst['Detalle_RB_RB'] = $detalle_RB_RB;
        $lst['Detalle_RB_CE'] = $detalle_RB_GF;


        return $lst;
    }
   

}