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
        $arrFiat = array();
        $datos_cg = array();
        $datos_ac = array();
        $datos_an = array();

        $utils = new UtilsController;

        /*
        $datos_gf =  SubiteDatos::on('GF')->select('ID','Marca','Concesionario','CodEstado','Avance','AvanceCalculado')->whereNull('CodEstado')->orWhere('CodEstado', '<>', 5)->get();
       
        $datos_cg =  SubiteDatos::on('CG')->select('ID','Marca','Concesionario','CodEstado','Avance','FechaVtoCuota2')->whereNull('CodEstado')->orWhere('CodEstado', '<>', 5)->get()->toArray();
        $datos_ac =  SubiteDatos::on('AC')->select('ID','Marca','Concesionario','CodEstado','Avance','FechaVtoCuota2')->whereNull('CodEstado')->orWhere('CodEstado', '<>', 5)->get()->toArray();
        $datos_an =  SubiteDatos::on('AN')->select('ID','Marca','Concesionario','CodEstado','Avance','FechaVtoCuota2')->whereNull('CodEstado')->orWhere('CodEstado', '<>', 5)->get()->toArray();
        
        $arrFiat = array_merge($arrFiat, $datos_cg, $datos_ac, $datos_an);
        */

        $periodoActPrimerDia = '2021-5-1';
        $periodoAct = 20210531; 

        $res_ac = DB::connection('AC')->select("CALL hnweb_subitereportecompras('".$periodoAct."');");
        $res_aut = DB::connection('AN')->select("CALL hnweb_subitereportecompras('".$periodoAct."');");
        $res_cg = DB::connection('CG')->select("CALL hnweb_subitereportecompras('".$periodoAct."');");

        $datos_gf = DB::connection('GF')->select("CALL hnweb_subitereportecompras_cli('".$periodoAct."');");

        $arrFiat = array_merge($res_ac, $res_aut, $res_cg);


        

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

        foreach ($datos_gf as $dato) {

            if (!($utils->enOtraSociedadOPropioMerge($dato->Nombres, $dato->Apellido)) && ($dato->CodEstado != 5)  && ($dato->HaberNeto > 14999)){

                $oDet = new \stdClass();

                $oDet->Marca = $dato->Marca;

                if($dato->AvanceCalculado != null){
                    $oDet->Avance = $dato->AvanceCalculado;
                }else{
                    $oDet->Avance = $dato->Avance;
                }
                
                if ($oDet->Avance < 84){

                    //return 'ID: '.$dato->ID.', FechaUltObs: '.$dato->FechaUltObs.', Avance: '.$oDet->Avance;
                    //return $utils->seEstaTrabajando($dato->FechaUltObs);

                    $lstTabla_Cartera_Marcas[$dato->Marca]->CantDatos += 1;

                    if ($oDet->Avance < 45){

                        $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->Cantidad += 1;

                        if($dato->HaberNeto < 30000){
                            $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->CantHNBajo += 1;
                        }else{
                            if($utils->seEstaTrabajando($dato->FechaUltObs)){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->CantTrabajados += 1;
                            }
                        }

                    }elseif ($oDet->Avance >= 45 && $oDet->Avance < 60) {

                        $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->Cantidad += 1;

                        if($dato->HaberNeto < 30000){
                            $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->CantHNBajo += 1;
                        }else{
                            if($utils->seEstaTrabajando($dato->FechaUltObs)){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->CantTrabajados += 1;
                            }
                        }

                    }elseif ($oDet->Avance >= 60 && $oDet->Avance < 84) {

                        $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->Cantidad += 1;

                        if($dato->HaberNeto < 30000){
                            $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->CantHNBajo += 1;
                        }else{
                            if($utils->seEstaTrabajando($dato->FechaUltObs)){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->CantTrabajados += 1;
                            }
                        }
                    }
                }

            }

        }

        
        $fcav = strtotime($periodoActPrimerDia);

        foreach ($arrFiat as $dato) {

            if (!($utils->enOtraSociedadOPropioMerge($dato->Nombres, $dato->Apellido)) && ($dato->CodEstado != 5) && ($dato->HaberNeto > 14999)){

                $oDet = new \stdClass();

                $oDet->Marca = $dato->Marca;
                $fvc2 = strtotime($dato->FechaVtoCuota2);

                if ($dato->FechaVtoCuota2 === NULL){
                    $oDet->Avance = 0;
                }else{
                    $oDet->Avance = $utils->getAvanceAutomaticoAFecha($fcav, $fvc2);
                }

                if ($oDet->Avance < 84){

                    $lstTabla_Cartera_Marcas[$dato->Marca]->CantDatos += 1;

                    if ($oDet->Avance < 45){

                        $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->Cantidad += 1;

                        if($dato->HaberNeto < 15000){
                            $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->CantHNBajo += 1;
                        }else{
                            if($utils->seEstaTrabajando($dato->FechaUltObs)){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Menor45->CantTrabajados += 1;
                            }
                        }

                    }elseif ($oDet->Avance >= 45 && $oDet->Avance < 60) {

                        $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->Cantidad += 1;

                        if($dato->HaberNeto < 15000){
                            $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->CantHNBajo += 1;
                        }else{
                            if($utils->seEstaTrabajando($dato->FechaUltObs)){
                                $lstTabla_Cartera_Marcas[$dato->Marca]->Entre45y60->CantTrabajados += 1;
                            }
                        }

                    }elseif ($oDet->Avance >= 60 && $oDet->Avance < 84) {

                        $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->Cantidad += 1;

                        if($dato->HaberNeto < 15000){
                            $lstTabla_Cartera_Marcas[$dato->Marca]->Mayor60->CantHNBajo += 1;
                        }else{
                            if($utils->seEstaTrabajando($dato->FechaUltObs)){
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

            if ($tabla->Codigo == 3){ // Para Peugeot se contabilizan todos los casos como posibles trabajables porque no se hace el corte por Avance
                $row['CasosTrabajables'] = $tabla->Menor45->Cantidad + $tabla->Entre45y60->Cantidad + $tabla->Mayor60->Cantidad;
            }else{
                $row['CasosTrabajables'] = $tabla->Entre45y60->Cantidad + $tabla->Mayor60->Cantidad;
            }
            
            $row['TotalesHNBajo'] = $tabla->Menor45->CantHNBajo + $tabla->Entre45y60->CantHNBajo + $tabla->Mayor60->CantHNBajo;
            $row['TotalesTrabajados'] = $tabla->Menor45->CantTrabajados + $tabla->Entre45y60->CantTrabajados + $tabla->Mayor60->CantTrabajados;

            array_push($arrAux, $row);
        }

        $lst['Reporte'] = $arrAux;

        return $lst;
    }


    public function getReporteDetallePendientesCarteraDashboard(Request $request)
    {
        $arrFiat = array();

        $utils = new UtilsController;

        $periodoActPrimerDia = '2021-5-1';
        $periodoAct = 20210531; 

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

            if (!($utils->enOtraSociedadOPropioMerge($dato->Nombres, $dato->Apellido)) && ($dato->CodEstado != 5) && ($dato->HaberNeto > 14999)){

                $oDet = new \stdClass();

                $oPend = new \stdClass();

                $oDet->Marca = $dato->Marca;

                if($dato->AvanceCalculado != null){
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

        foreach ($arrFiat as $dato) {

            if (!($utils->enOtraSociedadOPropioMerge($dato->Nombres, $dato->Apellido)) && ($dato->CodEstado != 5) && ($dato->HaberNeto > 14999)){

                $oDet = new \stdClass();

                $oDet->Marca = $dato->Marca;
                $fvc2 = strtotime($dato->FechaVtoCuota2);

                if ($dato->FechaVtoCuota2 === NULL){
                    $oDet->Avance = 0;
                }else{
                    $oDet->Avance = $utils->getAvanceAutomaticoAFecha($fcav, $fvc2);
                }

                if ($oDet->Avance < 84){

                    $oPend = new \stdClass();

                    if ($oDet->Avance >= 45 && $oDet->Avance < 60) {

                        if($dato->HaberNeto > 15000){
                            
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
                        if($dato->HaberNeto > 15000){  
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