<?php

namespace App\Http\Controllers;

use App\SubiteDatos;
use App\EstadoGestion;
use Illuminate\Http\Request;
use DB;
use \stdClass;
use DateTime;

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
        $queryStrReport = "CALL hnweb_reportecompras_objetivos_v3(".$periodoMes.", ".$periodoAnio.");"; 

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
    
                //if ($dato->FechaVtoCuota2 === NULL){
                if (is_null($dato->FechaVtoCuota2)){
                    $oDet->Avance = 0;
                }else{
                    $oDet->Avance = $utils->getAvanceAutomaticoFiat($fvc2);
                }
            }else{

                //if($dato->AvanceCalculado != null){
                if (!is_null($dato->AvanceCalculado)){
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

                    if ($dato->Marca == 5 || $dato->Marca == 3 ){
                        if($dato->HaberNeto < 30000){
                            $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->CantHNBajo += 1;
                        }else{
                            if($utils->seEstaTrabajando(strtotime($dato->FechaUltObs))){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->CantTrabajados += 1;
                            }
                        }
                    }else{
                        if($dato->HaberNeto < 15000){
                            $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->CantHNBajo += 1;
                        }else{
                            if($utils->seEstaTrabajando(strtotime($dato->FechaUltObs))){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->CantTrabajados += 1;
                            }
                        }
                    }
                    
                    

                }elseif ($oDet->Avance >= 45 && $oDet->Avance < 60) {

                    $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->Cantidad += 1;

                    if ($dato->Marca == 5 || $dato->Marca == 3 ){
                        if($dato->HaberNeto < 30000){
                            $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->CantHNBajo += 1;
                        }else{
                            if($utils->seEstaTrabajando(strtotime($dato->FechaUltObs))){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->CantTrabajados += 1;
                            }
                        }
                    }else{
                        if($dato->HaberNeto < 15000){
                            $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->CantHNBajo += 1;
                        }else{
                            if($utils->seEstaTrabajando(strtotime($dato->FechaUltObs))){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->CantTrabajados += 1;
                            }
                        }
                    }
                    
                    
                }else{
                    $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->Cantidad += 1;

                    if ($dato->Marca == 5 || $dato->Marca == 3 ){
                        if($dato->HaberNeto < 30000){
                            $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->CantHNBajo += 1;
                        }else{
                            if($utils->seEstaTrabajando(strtotime($dato->FechaUltObs))){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->CantTrabajados += 1;
                            }
                        }
                    }else{
                        if($dato->HaberNeto < 15000){
                            $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->CantHNBajo += 1;
                        }else{
                            if($utils->seEstaTrabajando(strtotime($dato->FechaUltObs))){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->CantTrabajados += 1;
                            }
                        }
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

        $mostrarDetalleCEs = 0;
        if (isset($request->detalleCEs)){
            $mostrarDetalleCEs = $request->detalleCEs;
        }

        $arrFiat = array();
        $datos_cg = array();
        $datos_ac = array();
        $datos_an = array();

        $utils = new UtilsController;

        $hoy = new DateTime('NOW');
        $hoy = $hoy->format('Y-m-d');

        $periodoActPrimerDia = date('Y-m-01', strtotime($hoy));
        $periodoAct = date('Y-m-t', strtotime($hoy));

        
        $res_ac = DB::connection('AC')->select("CALL hnweb_subitereportecompras('".$periodoAct."');");
        $res_aut = DB::connection('AN')->select("CALL hnweb_subitereportecompras('".$periodoAct."');");
        $res_cg = DB::connection('CG')->select("CALL hnweb_subitereportecompras('".$periodoAct."');");

        $datos_gf = DB::connection('GF')->select("CALL hnweb_subitereportecompras_cli('".$periodoAct."');");

        $arrFiat = array_merge($res_ac, $res_aut, $res_cg);
       
        //$arrFiat = DB::connection('CG')->select("CALL hnweb_subitereportecompras('".$periodoAct."');");

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

        //if ($mostrarDetalleCEs == 1){
            $concesionarios = $utils->getConcesionariosReporteCarteraDashboard();

            $lstTabla_Cartera_CEs = array();

            foreach ($concesionarios as $ce) {
                $oCE = new \stdClass();
                $oDetalleMenor_CE = new \stdClass();
                $oDetalleEntre_CE = new \stdClass();
                $oDetalleMayor_CE = new \stdClass();
              
                $oCE->Codigo = $ce->ID;
                $oCE->Nombre = $ce->Nombre;
                $oCE->Marca = $ce->MarcaDefault;
    
                $oCE->CantDatos = 0;
    
                $oDetalleMenor_CE->Cantidad = 0;
                $oDetalleMenor_CE->CantTrabajados = 0;
                $oDetalleMenor_CE->CantHNBajo = 0;
    
                $oDetalleEntre_CE->Cantidad = 0;
                $oDetalleEntre_CE->CantTrabajados = 0;
                $oDetalleEntre_CE->CantHNBajo = 0;
    
                $oDetalleMayor_CE->Cantidad = 0;
                $oDetalleMayor_CE->CantTrabajados = 0;
                $oDetalleMayor_CE->CantHNBajo = 0;
    
    
                $oCE->Menor45 = $oDetalleMenor_CE;
                $oCE->Entre45y60 = $oDetalleEntre_CE;
                $oCE->Mayor60 = $oDetalleMayor_CE;
    
                $lstTabla_Cartera_CEs[$ce->ID] = $oCE;
    
            }
        //}
        

        foreach ($datos_gf as $dato) {

            //if (!($utils->enOtraSociedadOPropioMerge($dato->Nombres, $dato->Apellido)) && ($dato->CodEstado != 5)  && ($dato->HaberNeto > 14999)){
            if (!($utils->enOtraSociedadOPropioMerge($dato->Nombres, $dato->Apellido)) && ($dato->CodEstado != 5)){    

                $oDet = new \stdClass();

                $oDet->Marca = $dato->Marca;

                //if($dato->AvanceCalculado != null){
                if (!is_null($dato->AvanceCalculado)){
                    $oDet->Avance = $dato->AvanceCalculado;
                }else{
                    $oDet->Avance = $dato->Avance;
                }
                
                if ($oDet->Avance < 84){

                    //return 'ID: '.$dato->ID.', FechaUltObs: '.$dato->FechaUltObs.', Avance: '.$oDet->Avance;
                    //return $utils->seEstaTrabajando($dato->FechaUltObs);

                    $lstTabla_Cartera_Marcas[$dato->Marca]->CantDatos += 1;

                    $lstTabla_Cartera_CEs[$dato->Concesionario]->CantDatos += 1;

                    if ($oDet->Avance < 45){

                        $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->Cantidad += 1;

                        $lstTabla_Cartera_CEs[$dato->Concesionario]->Menor45->Cantidad += 1;

                        if($dato->HaberNeto < 30000){
                            $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->CantHNBajo += 1;

                            $lstTabla_Cartera_CEs[$dato->Concesionario]->Menor45->CantHNBajo += 1;
                        }else{
                            if($utils->seEstaTrabajando($dato->FechaUltObs)){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->CantTrabajados += 1;

                                $lstTabla_Cartera_CEs[$dato->Concesionario]->Menor45->CantTrabajados += 1;
                            }
                        }

                    }elseif ($oDet->Avance >= 45 && $oDet->Avance < 60) {

                        $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->Cantidad += 1;

                        $lstTabla_Cartera_CEs[$dato->Concesionario]->Entre45y60->Cantidad += 1;

                        if($dato->HaberNeto < 30000){
                            $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->CantHNBajo += 1;

                            $lstTabla_Cartera_CEs[$dato->Concesionario]->Entre45y60->CantHNBajo += 1;
                        }else{
                            if($utils->seEstaTrabajando($dato->FechaUltObs)){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->CantTrabajados += 1;

                                $lstTabla_Cartera_CEs[$dato->Concesionario]->Entre45y60->CantTrabajados += 1;
                            }
                        }

                    }elseif ($oDet->Avance >= 60 && $oDet->Avance < 84) {

                        $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->Cantidad += 1;

                        $lstTabla_Cartera_CEs[$dato->Concesionario]->Mayor60->Cantidad += 1;

                        if($dato->HaberNeto < 30000){
                            $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->CantHNBajo += 1;

                            $lstTabla_Cartera_CEs[$dato->Concesionario]->Mayor60->CantHNBajo += 1;
                        }else{
                            if($utils->seEstaTrabajando($dato->FechaUltObs)){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->CantTrabajados += 1;

                                $lstTabla_Cartera_CEs[$dato->Concesionario]->Mayor60->CantTrabajados += 1;
                            }
                        }
                    }
                }

            }

        }

        $parPMaxFiat = 9000;
        
        $fcav = strtotime($periodoActPrimerDia);

        foreach ($arrFiat as $dato) {

            $oDet = new \stdClass();

            $oDet->Marca = $dato->Marca;
            $fvc2 = strtotime($dato->FechaVtoCuota2);

            // if ($dato->FechaVtoCuota2 === NULL){
            if (is_null($dato->FechaVtoCuota2)){
                $oDet->Avance = 0;
            }else{
                $oDet->Avance = $utils->getAvanceAutomaticoAFecha($fcav, $fvc2);
            }

            $pmaxCompra = $utils->getPrecioMaximoCompra($oDet->Avance, $dato->HaberNeto);

           // if (!($utils->enOtraSociedadOPropioMerge($dato->Nombres, $dato->Apellido)) && ($dato->CodEstado != 5) && ($dato->HaberNeto > 14999)){
            if (!($utils->enOtraSociedadOPropioMerge($dato->Nombres, $dato->Apellido)) && ($dato->CodEstado != 5)){
            
                if ($oDet->Avance < 84){

                    $lstTabla_Cartera_Marcas[$dato->Marca]->CantDatos += 1;

                    $lstTabla_Cartera_CEs[$dato->Concesionario]->CantDatos += 1;

                    if ($oDet->Avance < 45){

                        $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->Cantidad += 1;

                        $lstTabla_Cartera_CEs[$dato->Concesionario]->Menor45->Cantidad += 1;

                        //if($dato->HaberNeto < 15000){
                        if($pmaxCompra < $parPMaxFiat){
                            $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->CantHNBajo += 1;

                            $lstTabla_Cartera_CEs[$dato->Concesionario]->Menor45->CantHNBajo += 1;
                        }else{
                            if($utils->seEstaTrabajando($dato->FechaUltObs)){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->CantTrabajados += 1;

                                $lstTabla_Cartera_CEs[$dato->Concesionario]->Menor45->CantTrabajados += 1;
                            }
                        }

                    }elseif ($oDet->Avance >= 45 && $oDet->Avance < 60) {

                        $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->Cantidad += 1;

                        $lstTabla_Cartera_CEs[$dato->Concesionario]->Entre45y60->Cantidad += 1;

                        //if($dato->HaberNeto < 15000){
                        if($pmaxCompra < $parPMaxFiat){
                            $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->CantHNBajo += 1;

                            $lstTabla_Cartera_CEs[$dato->Concesionario]->Entre45y60->CantHNBajo += 1;
                        }else{
                            if($utils->seEstaTrabajando($dato->FechaUltObs)){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->CantTrabajados += 1;

                                $lstTabla_Cartera_CEs[$dato->Concesionario]->Entre45y60->CantTrabajados += 1;
                            }
                        }

                    }elseif ($oDet->Avance >= 60 && $oDet->Avance < 84) {

                        $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->Cantidad += 1;

                        $lstTabla_Cartera_CEs[$dato->Concesionario]->Mayor60->Cantidad += 1;

                        //if($dato->HaberNeto < 15000){
                        if($pmaxCompra < $parPMaxFiat){
                            $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->CantHNBajo += 1;

                            $lstTabla_Cartera_CEs[$dato->Concesionario]->Mayor60->CantHNBajo += 1;
                        }else{
                            if($utils->seEstaTrabajando($dato->FechaUltObs)){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->CantTrabajados += 1;

                                $lstTabla_Cartera_CEs[$dato->Concesionario]->Mayor60->CantTrabajados += 1;
                            }
                        }
                    }
                }

            }

        }

        $lst = array();
        $row = array();
        $arrAux = array();
        
        foreach ($lstTabla_Cartera_Marcas as $tabla) {
            $row['NomMarca'] = strtoupper($tabla->Nombre);
            $row['EsFilaMarca'] = $mostrarDetalleCEs;
            $row['CantDatos'] = $tabla->CantDatos;
            $row['Menor45'] = $tabla->Menor45;
            $row['Entre45y60'] = $tabla->Entre45y60;
            $row['Mayor60'] = $tabla->Mayor60;

            if ($tabla->Codigo == 3){ // Para Peugeot se contabilizan todos los casos como posibles trabajables porque no se hace el corte por Avance
                //$row['CasosTrabajables'] = $tabla->Menor45->Cantidad + $tabla->Entre45y60->Cantidad + $tabla->Mayor60->Cantidad;
                $row['CasosTrabajables'] = ($tabla->Menor45->Cantidad - $tabla->Menor45->CantHNBajo) + ($tabla->Entre45y60->Cantidad - $tabla->Entre45y60->CantHNBajo) + ($tabla->Mayor60->Cantidad - $tabla->Mayor60->CantHNBajo);
                $row['TotalesTrabajados'] = $tabla->Menor45->CantTrabajados + $tabla->Entre45y60->CantTrabajados + $tabla->Mayor60->CantTrabajados;
            }else{
                //$row['CasosTrabajables'] = $tabla->Entre45y60->Cantidad + $tabla->Mayor60->Cantidad;
                $row['CasosTrabajables'] = ($tabla->Entre45y60->Cantidad - $tabla->Entre45y60->CantHNBajo) + ($tabla->Mayor60->Cantidad - $tabla->Mayor60->CantHNBajo);
                $row['TotalesTrabajados'] = $tabla->Entre45y60->CantTrabajados + $tabla->Mayor60->CantTrabajados;
            }
            
            $row['TotalesHNBajo'] = $tabla->Menor45->CantHNBajo + $tabla->Entre45y60->CantHNBajo + $tabla->Mayor60->CantHNBajo;

           // $row['TotalesTrabajados'] = $tabla->Menor45->CantTrabajados + $tabla->Entre45y60->CantTrabajados + $tabla->Mayor60->CantTrabajados;
            
            array_push($arrAux, $row);

            if ($mostrarDetalleCEs == 1){
                foreach ($lstTabla_Cartera_CEs as $tabla_ce) {
                    if ($tabla_ce->Marca == $tabla->Codigo){

                        $rowCe['NomMarca'] = " - ".ucwords(strtolower($tabla_ce->Nombre));
                        $rowCe['EsFilaMarca'] = 0;
                        $rowCe['CantDatos'] = $tabla_ce->CantDatos;
                        $rowCe['Menor45'] = $tabla_ce->Menor45;
                        $rowCe['Entre45y60'] = $tabla_ce->Entre45y60;
                        $rowCe['Mayor60'] = $tabla_ce->Mayor60;
            
                        if ($tabla_ce->Codigo == 3){ // Para Peugeot se contabilizan todos los casos como posibles trabajables porque no se hace el corte por Avance
                            //$row['CasosTrabajables'] = $tabla->Menor45->Cantidad + $tabla->Entre45y60->Cantidad + $tabla->Mayor60->Cantidad;
                            $rowCe['CasosTrabajables'] = ($tabla_ce->Menor45->Cantidad - $tabla_ce->Menor45->CantHNBajo) + ($tabla_ce->Entre45y60->Cantidad - $tabla_ce->Entre45y60->CantHNBajo) + ($tabla_ce->Mayor60->Cantidad - $tabla_ce->Mayor60->CantHNBajo);
                            $rowCe['TotalesTrabajados'] = $tabla_ce->Menor45->CantTrabajados + $tabla_ce->Entre45y60->CantTrabajados + $tabla_ce->Mayor60->CantTrabajados;
                        }else{
                            //$row['CasosTrabajables'] = $tabla->Entre45y60->Cantidad + $tabla->Mayor60->Cantidad;
                            $rowCe['CasosTrabajables'] = ($tabla_ce->Entre45y60->Cantidad - $tabla_ce->Entre45y60->CantHNBajo) + ($tabla_ce->Mayor60->Cantidad - $tabla_ce->Mayor60->CantHNBajo);
                            $rowCe['TotalesTrabajados'] = $tabla_ce->Entre45y60->CantTrabajados + $tabla_ce->Mayor60->CantTrabajados;
                        }
                        
                        $rowCe['TotalesHNBajo'] = $tabla_ce->Menor45->CantHNBajo + $tabla_ce->Entre45y60->CantHNBajo + $tabla_ce->Mayor60->CantHNBajo;
            
                    // $row['TotalesTrabajados'] = $tabla->Menor45->CantTrabajados + $tabla->Entre45y60->CantTrabajados + $tabla->Mayor60->CantTrabajados;
            
                        array_push($arrAux, $rowCe);
                    }
                }
            }
        }

        

        $lst['Reporte'] = $arrAux;

        return $lst;
    }


    public function getReporteDetallePendientesCarteraDashboard(Request $request)
    {
        $arrFiat = array();

        $utils = new UtilsController;
        /*
        $periodoActPrimerDia = '2021-5-1';
        $periodoAct = 20210531; 
        */
        $hoy = new DateTime('NOW');
        $hoy = $hoy->format('Y-m-d');

        $periodoActPrimerDia = date('Y-m-01', strtotime($hoy));
        $periodoAct = date('Y-m-t', strtotime($hoy));

        $res_ac = DB::connection('AC')->select("CALL hnweb_subitereportecompras('".$periodoAct."');");
        $res_aut = DB::connection('AN')->select("CALL hnweb_subitereportecompras('".$periodoAct."');");
        $res_cg = DB::connection('CG')->select("CALL hnweb_subitereportecompras('".$periodoAct."');");

        $datos_gf = DB::connection('GF')->select("CALL hnweb_subitereportecompras_cli('".$periodoAct."');");

        $arrFiat = array_merge($res_ac, $res_aut, $res_cg);

        $acumMenos45 = 0;
        $acumEntre45y60 = 0;
        $acumMas60 = 0;

        $marcas = $utils->getMarcasReporteCarteraDashboard();
        $lstTabla_Cartera_Marcas = array();

        foreach ($marcas as $marca) {
            $oMar = new \stdClass();
            $oDetalleEntre = new \stdClass();
            $oDetalleMayor = new \stdClass();
          
            $oMar->Codigo = $marca->Codigo;
            $oMar->Nombre = $marca->Nombre;

            $oMar->CantDatos = 0;

            $oDetalleEntre->Cantidad = 0;
            $oDetalleEntre->lstPendientes = array();

            $oDetalleMayor->Cantidad = 0;
            $oDetalleMayor->lstPendientes = array();

            $oMar->Entre45y60 = $oDetalleEntre;
            $oMar->Mayor60 = $oDetalleMayor;

            $lstTabla_Cartera_Marcas[$marca->Codigo] = $oMar;

        }

        foreach ($datos_gf as $dato) {

            //if (!($utils->enOtraSociedadOPropioMerge($dato->Nombres, $dato->Apellido)) && ($dato->CodEstado != 5) && ($dato->HaberNeto > 14999)){
            if (!($utils->enOtraSociedadOPropioMerge($dato->Nombres, $dato->Apellido)) && ($dato->CodEstado != 5) && ($dato->HaberNeto > 30000)){

                $oDet = new \stdClass();

                $oPend = new \stdClass();

                $oDet->Marca = $dato->Marca;

                if(!(is_null($dato->AvanceCalculado))){
                    $oDet->Avance = $dato->AvanceCalculado;
                }else{
                    $oDet->Avance = $dato->Avance;
                }

                if ($oDet->Avance < 84){

                    if ($oDet->Avance >= 45 && $oDet->Avance < 60) {

                        if($dato->HaberNeto > 30000){
                            
                            if(!($utils->seEstaTrabajando($dato->FechaUltObs))){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->Cantidad += 1;
                                
                                $oPend->ID = $dato->ID;
                                $oPend->Concesionario = $utils->getNombreCE($dato->Concesionario);
                                $oPend->Grupo = $dato->Grupo;
                                $oPend->Orden = $dato->Orden;
                                //$oPend->Solicitud = $dato->Solicitud;
                                $oPend->Apellido = $dato->Apellido;
                                $oPend->Nombres = $dato->Nombres;
                                $oPend->Avance = $oDet->Avance;
                                $oPend->HaberNeto = $dato->HaberNeto;
                                $oPend->Oficial = $dato->NomOficial;
                                $oPend->Estado = $dato->NomEstado;
                                $oPend->FechaUltObs = $dato->FechaUltObsMostrar;
                                $oPend->UsuarioObs = $dato->UsuarioObs;
                                $oPend->UltimaObs = $dato->UltObs;

                                $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->lstPendientes[] = $oPend;
                            }
                        }

                    }elseif ($oDet->Avance >= 60 && $oDet->Avance < 84) {
                        if($dato->HaberNeto > 30000){
                            if(!($utils->seEstaTrabajando($dato->FechaUltObs))){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->Cantidad += 1;
                                
                                $oPend->ID = $dato->ID;
                                $oPend->Concesionario = $utils->getNombreCE($dato->Concesionario);
                                $oPend->Grupo = $dato->Grupo;
                                $oPend->Orden = $dato->Orden;
                                //$oPend->Solicitud = $dato->Solicitud;
                                $oPend->Apellido = $dato->Apellido;
                                $oPend->Nombres = $dato->Nombres;
                                $oPend->Avance = $oDet->Avance;
                                $oPend->HaberNeto = $dato->HaberNeto;
                                $oPend->Oficial = $dato->NomOficial;
                                $oPend->Estado = $dato->NomEstado;
                                $oPend->FechaUltObs = $dato->FechaUltObsMostrar;
                                $oPend->UsuarioObs = $dato->UsuarioObs;
                                $oPend->UltimaObs = $dato->UltObs;

                                $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->lstPendientes[] = $oPend;
                            }
                        }
                    }
                }

            }

        }

        $fcav = strtotime($periodoActPrimerDia);
        
        $parPMaxFiat = 9000;

        foreach ($arrFiat as $dato) {

            $oDet = new \stdClass();

            $oDet->Marca = $dato->Marca;
            $fvc2 = strtotime($dato->FechaVtoCuota2);

            // if ($dato->FechaVtoCuota2 === NULL){
            if (is_null($dato->FechaVtoCuota2)){
                $oDet->Avance = 0;
            }else{
                $oDet->Avance = $utils->getAvanceAutomaticoAFecha($fcav, $fvc2);
            }

            $pmaxCompra = $utils->getPrecioMaximoCompra($oDet->Avance, $dato->HaberNeto);

            if (!($utils->enOtraSociedadOPropioMerge($dato->Nombres, $dato->Apellido)) && ($dato->CodEstado != 5) && ($pmaxCompra > $parPMaxFiat)){


                if ($oDet->Avance < 84){

                    $oPend = new \stdClass();

                    if ($oDet->Avance >= 45 && $oDet->Avance < 60) {

                       // if($dato->HaberNeto > 15000){
                        if($pmaxCompra > $parPMaxFiat){
                            
                            if(!($utils->seEstaTrabajando($dato->FechaUltObs))){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->Cantidad += 1;

                                $oPend->ID = $dato->ID;
                                $oPend->Concesionario = $utils->getNombreCE($dato->Concesionario);
                                $oPend->Grupo = $dato->Grupo;
                                $oPend->Orden = $dato->Orden;
                                $oPend->Solicitud = $dato->Solicitud;
                                $oPend->Apellido = $dato->Apellido;
                                $oPend->Nombres = $dato->Nombres;
                                $oPend->Avance = $oDet->Avance;
                                $oPend->HaberNeto = $dato->HaberNeto;
                                $oPend->Oficial = $dato->NomOficial;
                                $oPend->Estado = $dato->NomEstado;
                                $oPend->FechaUltObs = $dato->FechaUltObsMostrar;
                                $oPend->UsuarioObs = $dato->UsuarioObs;
                                $oPend->UltimaObs = $dato->UltObs;

                                $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->lstPendientes[] = $oPend;
                            }
                        }

                    }elseif ($oDet->Avance >= 60 && $oDet->Avance < 84) {
                        //if($dato->HaberNeto > 15000){  
                        if($pmaxCompra > $parPMaxFiat){
                            if(!($utils->seEstaTrabajando($dato->FechaUltObs))){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->Cantidad += 1;

                                $oPend->ID = $dato->ID;
                                $oPend->Concesionario = $utils->getNombreCE($dato->Concesionario);
                                $oPend->Grupo = $dato->Grupo;
                                $oPend->Orden = $dato->Orden;
                                $oPend->Solicitud = $dato->Solicitud;
                                $oPend->Apellido = $dato->Apellido;
                                $oPend->Nombres = $dato->Nombres;
                                $oPend->Avance = $oDet->Avance;
                                $oPend->HaberNeto = $dato->HaberNeto;
                                $oPend->Oficial = $dato->NomOficial;
                                $oPend->Estado = $dato->NomEstado;
                                $oPend->FechaUltObs = $dato->FechaUltObsMostrar;
                                $oPend->UsuarioObs = $dato->UsuarioObs;
                                $oPend->UltimaObs = $dato->UltObs;

                                $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->lstPendientes[] = $oPend;
                            }
                        }
                    }
                }

            }

        }

        $lst = array();
        $row = array();
        $arrAux = array();
        
        foreach ($lstTabla_Cartera_Marcas as $tabla) {

            $row['NomMarca'] = $tabla->Nombre;
           // $row['CantDatos'] = $tabla->CantDatos;
            $row['Entre45y60'] = $tabla->Entre45y60;
            $row['Mayor60'] = $tabla->Mayor60;

            array_push($arrAux, $row);
        }

        $lst['Detalle'] = $arrAux;

        return $lst;
    }


}