<?php

namespace App\Http\Controllers;

use App\DatoSubite;
use App\EstadoGestion;
use Illuminate\Http\Request;
use DB;
use \stdClass;

class ReporteAsignacionController extends Controller
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
       $marca =  $request->Marca;
       $concesionario =  $request->Concesionario;

        $periodoMes = substr($periodo, 4, strlen($periodo));
        $periodoAnio = substr($periodo, 0, 4);

        $fiatTotal = false;
        switch ($marca){
            case 2:
                 switch ($concesionario){
                     case 0:
                         $fiatTotal = true;
                     case 4:
                     //AutoCervo
                     $db = "AC";
                     $emp = "AutoCervo";
                     break;
                     case 5:
                         //AutoNet
                         $db = "AN";
                         $emp = "AutoNet";
                     break;
                     case 6:
                         //Car Group
                         $db = "CG";
                         $emp = "Car Group";
                     break;
                 }
            break;
            case 5:
                //Busco en la DB de Gestion Financiera pa7_gf
                $db = "GF";
                $emp = "Volkswagen";
                switch ($concesionario){
                    case 1:
                        $emp .= " - SAUMA";
                    case 2:
                        $emp .= " - IRUÑA";
                    break;
                    case 3:
                        $emp .= " - AMENDOLA";
                    break;
                    case 7:
                        $emp .= " - LUXCAR";
                    break;
                }
            break;
         break;
        }

        if ($marca == 2){
            $result = DB::connection($db)->select("CALL hnweb_subitereporteasigperiodo(".$periodoMes.", ".$periodoAnio.");");
        }else{
            $result = DB::connection($db)->select("CALL hnweb_subitereporteasigperiodo(".$concesionario.", ".$periodoMes.", ".$periodoAnio.");");
            //$totaldatos = DB::select("CALL hnweb_subitereporte_totales_marca_conc(".$marca.", ".$concesionario.");");
        }
        
        //dd($result);
      
        $lst = array();
        $list = array();
        $datos = array();
        $datos = $result;

        foreach ($result as $r) {
            $oEstado = $r->CodEstado; 
            $CodOficialActual = $r->CodOficialActual;
            //$obj = new EstadoGestion();
            $obj =new \stdClass();
            $obj->CodOficial = $r->CodOficialOrigen;
            
            if(!isset($obj->NomOficial)){
                $obj->NomOficial = "";
            }
            if(!isset($obj->CodOficialOrigen)){
                $obj->CodOficialOrigen = 0;
            }
            if(!isset($obj->NomOficialOrigen)){
                $obj->NomOficialOrigen= "";
            }
            if(!isset($obj->SinGestionar)){
                $obj->SinGestionar = 0; 
            }
            if(!isset($obj->TelefonoMal)){
                $obj->TelefonoMal = 0; 
            }
            if(!isset($obj->DejeMensaje)){
                $obj->DejeMensaje = 0;
            }if(!isset($obj->EntrevistaPendiente)){
                $obj->EntrevistaPendiente = 0;
            }
            if(!isset($obj->NoLeInteresa)){
                $obj->NoLeInteresa = 0; 
            }
            if(!isset($obj->VendePlan)){
                $obj->VendePlan = 0;
            }
            if(!isset($obj->PlanSubite)){
                $obj->PlanSubite = 0;
            }
            if(!isset($obj->Empresa)){
                $obj->Empresa = 0; 
            }
            if(!isset($obj->EnGestion)){
                $obj->EnGestion = 0;
            }
            if(!isset($obj->PasarAVenta)){
                $obj->PasarAVenta = 0; 
            }
            if(!isset($obj->EnOtroOficial)){
                $obj->EnOtroOficial = 0; 
            }
            if(!isset($obj->Asignados)){
                $obj->Asignados = 0; 
            }
            if(!isset($obj->CodEstado)){
                $obj->CodEstado = 0; 
            }
            
            if ($obj->CodOficial == 0){
                $obj->NomOficial = "Sin Oficial Asignado";
             }else{
                $obj->NomOficial = $r->NomOficialOrigen;
             }

             $obj->Empresa = $emp;

             $encontro = false;

             foreach ($list as $o) {
                 if ($o->CodOficial == $obj->CodOficial){

                    if (($obj->CodOficial != $CodOficialActual) && ($obj->CodOficial != 0)){
                        //$o->EnOtroOficial += 1;
                        $o->EnOtroOficial += 1;
                        $oEstado = 9;
                    }else{
                        switch($oEstado){
                            case 0:
                                $o->SinGestionar += 1;
                            break;
                            case 1:
                                $o->TelefonoMal += 1;
                            break;
                                case 2:
                                $o->DejeMensaje += 1;
                            break;
                                case 3:
                                $o->EntrevistaPendiente += 1;
                            break;
                                case 4:
                                $o->NoLeInteresa += 1;
                            break;
                                case 5:
                                $o->VendePlan += 1;
                            break;
                                case 6:
                                $o->PlanSubite += 1;
                            break;
                                case 7:
                                $o->EnGestion += 1;
                            break;
                                case 8:
                                $o->PasarAVenta += 1;
                                break;
                        }    
                        
                        $o->CodEstado = $oEstado;
                        $o->Asignados = $o->SinGestionar + $o->TelefonoMal + $o->DejeMensaje + $o->EntrevistaPendiente + $o->NoLeInteresa + $o->VendePlan + $o->PlanSubite + $o->EnGestion + $o->PasarAVenta;

                    }    
                    $encontro = true;    
                break;
                    
                 }
             }

             if ($encontro == false){
                if (($obj->CodOficial != $CodOficialActual) && ($obj->CodOficial != 0)){
                //if ($obj->CodOficial != $CodOficialActual){
                    //$obj->EnOtroOficial += 1;
                    $obj->EnOtroOficial += 1;
                    $oEstado = 9;
                }else{
                    switch($oEstado){
                        case 0:
                            $obj->SinGestionar += 1;
                        break;
                        case 1:
                            $obj->TelefonoMal += 1;
                        break;
                            case 2:
                            $obj->DejeMensaje += 1;
                        break;
                            case 3:
                            $obj->EntrevistaPendiente += 1;
                        break;
                            case 4:
                            $obj->NoLeInteresa += 1;
                        break;
                            case 5:
                            $obj->VendePlan += 1;
                        break;
                            case 6:
                            $obj->PlanSubite += 1;
                        break;
                            case 7:
                            $obj->EnGestion += 1;
                        break;
                            case 8:
                            $obj->PasarAVenta += 1;
                            break;
                    }  
                }

                //$obj->CodEstado = $oEstado;

                //array_push($list, $obj);
                $obj->Asignados = $obj->SinGestionar + $obj->TelefonoMal + $obj->DejeMensaje + $obj->EntrevistaPendiente + $obj->NoLeInteresa + $obj->VendePlan + $obj->PlanSubite + $obj->EnGestion + $obj->PasarAVenta;
                $list[] = $obj;
             }
        
        
        }
        $lst['Reporte'] = $list;
        $lst['Datos'] = $datos;


        return $lst;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}