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

    public function getReporteCarteraDashboardStoredProcedure(Request $request)
    {
        $arrFiat = array();
        $datos_cg = array();
        $datos_ac = array();
        $datos_an = array();

        $utils = new UtilsController;

        $datos_totales =  DB::select('CALL hnweb_get_cartera_general()');

        $acumMenos45 = 0;
        $acumEntre45y60 = 0;
        $acumMas60 = 0;

        $lstTabla_Cartera_Marcas = array();

        $marcas = $utils->getMarcasReporteCarteraDashboard();

        foreach ($marcas as $marca) {
            $oMar = new \stdClass();
            $oDetalleMenor = new \stdClass();
            $oDetalleEntre = new \stdClass();
            $oDetalleMayor = new \stdClass();
          
            $oMar->Codigo = $marca->Codigo;
            $oMar->Nombre = $marca->Nombre;

            $oMar->CantDatos = 0;

            $oDetalleMenor->Cantidad = 0;
            $oDetalleMenor->CantTrabajados = 0;
            $oDetalleMenor->CantHNBajo = 0;

            $oDetalleEntre->Cantidad = 0;
            $oDetalleEntre->CantTrabajados = 0;
            $oDetalleEntre->CantHNBajo = 0;

            $oDetalleMayor->Cantidad = 0;
            $oDetalleMayor->CantTrabajados = 0;
            $oDetalleMayor->CantHNBajo = 0;

            /*
            $oMar->Menor45 = 0;
            $oMar->Entre45y60 = 0;
            $oMar->Mayor60 = 0;
            */

            $oMar->Menor45 = $oDetalleMenor;
            $oMar->Entre45y60 = $oDetalleEntre;
            $oMar->Mayor60 = $oDetalleMayor;

            $lstTabla_Cartera_Marcas[$marca->Codigo] = $oMar;

        }

        foreach ($datos_totales as $dato) {
            $oDet = new \stdClass();

            $oDet->Marca = $dato->Marca;

            if ($oDet->Marca == 2){ // SI son de Fiat, tengo que calcular el avance con el campo de FechaVtoCuota2, para el resto es directamente el campo AvanceCalculado

                $fvc2 = strtotime($dato->FechaVtoCuota2);
    
                if ($dato->FechaVtoCuota2 === NULL){
                    $oDet->Avance = 0;
                }else{
                    $oDet->Avance = $utils->getAvanceAutomaticoFiat($fvc2);
                }
            }else{

                if($dato->AvanceCalculado != null){
                    $oDet->Avance = $dato->AvanceCalculado;
                }else{
                    $oDet->Avance = $dato->Avance;
                }
            }

            //Filtro por Avance MENOR a 84

            if ($oDet->Avance < 84){

                $lstTabla_Cartera_Marcas[$dato->Marca]->CantDatos += 1;

                if ($oDet->Avance < 45){

                    $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->Cantidad += 1;

                    if($dato->HaberNeto < 15000){
                        $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->CantHNBajo += 1;
                    }
                    if($utils->seEstaTrabajando(strtotime($dato->FechaUltObs))){
                        $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->CantTrabajados += 1;
                    }

                }elseif ($oDet->Avance >= 45 && $oDet->Avance < 60) {

                    $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->Cantidad += 1;

                    if($dato->HaberNeto < 15000){
                        $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->CantHNBajo += 1;
                    }
                    if($utils->seEstaTrabajando(strtotime($dato->FechaUltObs))){
                        $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->CantTrabajados += 1;
                    }
                }else{
                    $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->Cantidad += 1;

                    if($dato->HaberNeto < 15000){
                        $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->CantHNBajo += 1;
                    }
                    if($utils->seEstaTrabajando(strtotime($dato->FechaUltObs))){
                        $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->CantTrabajados += 1;
                    }
                }
            }

        }


        $lst = array();
        $row = array();
        $arrAux = array();
        
        foreach ($lstTabla_Cartera_Marcas as $tabla) {
            $row['NomMarca'] = $tabla->Nombre;
            $row['CantDatos'] = $tabla->CantDatos;
            $row['Menor45'] = $tabla->Menor45;
            $row['Entre45y60'] = $tabla->Entre45y60;
            $row['Mayor60'] = $tabla->Mayor60;

            array_push($arrAux, $row);
        }

        $lst['Reporte'] = $arrAux;

        return $lst;
    }


    public function getReporteCarteraDashboard(Request $request)
    {
        $arrFiat = array();
        $datos_cg = array();
        $datos_ac = array();
        $datos_an = array();

        $utils = new UtilsController;

        $datos_gf =  SubiteDatos::on('GF')->select('ID','Marca','Concesionario','CodEstado','Avance','AvanceCalculado')->whereNull('CodEstado')->orWhere('CodEstado', '<>', 5)->get();

        $datos_cg =  SubiteDatos::on('CG')->select('ID','Marca','Concesionario','CodEstado','Avance','FechaVtoCuota2')->whereNull('CodEstado')->orWhere('CodEstado', '<>', 5)->get()->toArray();
        $datos_ac =  SubiteDatos::on('AC')->select('ID','Marca','Concesionario','CodEstado','Avance','FechaVtoCuota2')->whereNull('CodEstado')->orWhere('CodEstado', '<>', 5)->get()->toArray();
        $datos_an =  SubiteDatos::on('AN')->select('ID','Marca','Concesionario','CodEstado','Avance','FechaVtoCuota2')->whereNull('CodEstado')->orWhere('CodEstado', '<>', 5)->get()->toArray();

        $arrFiat = array_merge($arrFiat, $datos_cg, $datos_ac, $datos_an);

        $acumMenos45 = 0;
        $acumEntre45y60 = 0;
        $acumMas60 = 0;

        $lstTabla_Cartera_Marcas = array();

        $marcas = $utils->getMarcasReporteCarteraDashboard();

        foreach ($marcas as $marca) {
            $oMar = new \stdClass();
          
            $oMar->Codigo = $marca->Codigo;
            $oMar->Nombre = $marca->Nombre;

            $oMar->CantDatos = 0;
            $oMar->Menor45 = 0;
            $oMar->Entre45y60 = 0;
            $oMar->Mayor60 = 0;

            $lstTabla_Cartera_Marcas[$marca->Codigo] = $oMar;

        }

        foreach ($datos_gf as $gf) {
            $oDet = new \stdClass();

            $oDet->Marca = $gf->Marca;

            if($gf->AvanceCalculado != null){
                $oDet->Avance = $gf->AvanceCalculado;
            }else{
                $oDet->Avance = $gf->Avance;
            }
            
            /*
            switch ($gf->Marca){
                case 3: // PEUGEOT
                  
                break;
                case 5: // VW
                    
                break;
                case 9: // FORD
                   
                break;

            }
            */

            if ($gf->Marca != 9){ // SACO FORD
                $lstTabla_Cartera_Marcas[$gf->Marca]->CantDatos += 1;

                if ($oDet->Avance < 45){
                    $lstTabla_Cartera_Marcas[$gf->Marca]->Menor45 += 1;
                }elseif ($oDet->Avance >= 45 && $oDet->Avance < 60) {
                    $lstTabla_Cartera_Marcas[$gf->Marca]->Entre45y60 += 1;
                }else{
                    $lstTabla_Cartera_Marcas[$gf->Marca]->Mayor60 += 1;
                }
            }

        }


        foreach ($arrFiat as $dato) {
            $oDet = new \stdClass();

            $oDet->Marca = $dato['Marca'];
            $fvc2 = strtotime($dato['FechaVtoCuota2']);

            if ($dato['FechaVtoCuota2'] === NULL){
                $oDet->Avance = 0;
            }else{
                $oDet->Avance = $utils->getAvanceAutomaticoFiat($fvc2);
            }

            $lstTabla_Cartera_Marcas[$oDet->Marca]->CantDatos += 1;

            if ($oDet->Avance < 45){
                $lstTabla_Cartera_Marcas[$oDet->Marca]->Menor45 += 1;
            }elseif ($oDet->Avance >= 45 && $oDet->Avance < 60) {
                $lstTabla_Cartera_Marcas[$oDet->Marca]->Entre45y60 += 1;
            }else{
                $lstTabla_Cartera_Marcas[$oDet->Marca]->Mayor60 += 1;
            }

        }

        $lst = array();
        $row = array();
        $arrAux = array();
        
        foreach ($lstTabla_Cartera_Marcas as $tabla) {
            $row['NomMarca'] = $tabla->Nombre;
            $row['CantDatos'] = $tabla->CantDatos;
            $row['Menor45'] = $tabla->Menor45;
            $row['Entre45y60'] = $tabla->Entre45y60;
            $row['Mayor60'] = $tabla->Mayor60;

            array_push($arrAux, $row);
        }

        $lst['Reporte'] = $arrAux;

        return $lst;
    }


}