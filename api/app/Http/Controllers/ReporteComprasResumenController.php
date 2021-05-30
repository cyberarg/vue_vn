<?php

namespace App\Http\Controllers;

use App\DatoSubite;
use Illuminate\Http\Request;
use DB;
use DateTime;

class ReporteComprasResumenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function getPrimerDiaPeriodo($periodo){
        $periodoMes = substr($periodo, 4, strlen($periodo));
        $periodoAnio = substr($periodo, 0, 4);
        $periodoDia = "1";

        return $periodoAnio."-".$periodoMes."-".$periodoDia;
     }

    public function getUltimoDiaPeriodo($periodo){
        $periodoMes = substr($periodo, 4, strlen($periodo));
        $periodoAnio = substr($periodo, 0, 4);

        switch($periodoMes){
            case "1":
            case "3":
            case "5":
            case "7":
            case "8":
            case "10":
            case "12":
                $periodoDia = "31";
            break;
            case "2":
                if (($periodoAnio % 4) == 0){
                    $periodoDia = "29";
                }else{
                    $periodoDia = "28";
                }
            break;
            case "4":
            case "6":
            case "9":
            case "11":
                $periodoDia = "30";
            break;
        }

        if (strlen($periodoMes) == 1){
            $periodoMes = "0".$periodoMes;
        }

        return $periodoAnio."-".$periodoMes."-".$periodoDia;
    }

    public function getUltimoDiaPeriodoAnterior($periodoAct){
        $periodoMes = substr($periodoAct, 4, strlen($periodoAct));
        $periodoAnio = substr($periodoAct, 0, 4);

        if ($periodoMes == "12"){
            $periodoAnioAnt = $periodoAnio + 1;
            $periodoMesAnt =  "1";
        }else{
            $periodoAnioAnt = $periodoAnio;
            $periodoMesAnt =  $periodoMes + 1;
        }
        $strPerAnt = $periodoAnioAnt.$periodoMesAnt;

        return $this->getUltimoDiaPeriodo($strPerAnt);
    }


    public function getReporte(Request $request)
    {

        $periodo = $request->periodo;
        $concesionario = $request->concesionario;
        $marca = $request->marca;

        $utils = new UtilsController;

        $emp1 = "AutoCervo";
        $db1= "AC";
        $emp2 = "AutoNet";
        $db2= "AN";
        $emp3 = "CarGroupFusion";
        $db3= "CG";

        $estadoGestion = [];

        $estados = array();
        $empresa1= array();
        $empresa2= array();
        $empresa3= array();
        $est1 = array();
       $est2 = array();

       $fiatTotal = false;
       switch ($marca){
           case 2:
                $nombreOrigen = "SGA";
                switch ($concesionario){
                    case 0:
                        $fiatTotal = true;
                    case 4:
                    //AutoCervo
                    $db = "AC";
                    break;
                    case 5:
                        //AutoNet
                        $db = "AN";
                    break;
                    case 6:
                        //Car Group
                        $db = "CG";
                    break;
                }
           break;
           case 5:
            $nombreOrigen = "Base Total Volkswagen";
            //Busco en la DB de Gestion Financiera pa7_gf
            $db = "GF";
           break;
           default:
           $nombreOrigen = "Casos Importados";
           //Busco en la DB de Gestion Financiera pa7_gf
           $db = "GF";
        break;
       }


        $periodoAct = $this->getUltimoDiaPeriodo($periodo);
        $periodoActPrimerDia = $this->getPrimerDiaPeriodo($periodo);
        $periodoAnt = $this->getUltimoDiaPeriodoAnterior($periodo);

       //dd($periodoAct);

        $periodoActual = strtotime($periodoAct);
        $periodoAnterior = strtotime($periodoAnt);

        if ($concesionario == 0){
            $concesionario = 'NULL';
        }

       if ($fiatTotal){
            $res_ac = DB::connection($db1)->select("CALL hnweb_subitereportecompras('".$periodoAct."');");
            $res_aut = DB::connection($db2)->select("CALL hnweb_subitereportecompras('".$periodoAct."');");
            $res_cg = DB::connection($db3)->select("CALL hnweb_subitereportecompras('".$periodoAct."');");

            $result = array_merge($res_ac, $res_aut, $res_cg);
       }else{
           if ($marca == 2){
                $result = DB::connection($db)->select("CALL hnweb_subitereportecompras('".$periodoAct."');");
           }else{
                $result = DB::connection($db)->select("CALL hnweb_subitereportecompras_vw('".$periodoAct."', ".$concesionario.");");
           } 
       }
       
//dd($result);
        $list = array();
        $listUniverso = array();
        $listDetalle = array();
        $listMesActual = array();
        $listMesAnterior = array();
        $listCasosNuevos = array();
        $lstDatos = array();

        $lstDeb = array();

        
        $list[0]['Nombre'] = $nombreOrigen;
        $list[0]['Cantidad'] = 0;
        $list[1]['Nombre'] = 'Propios y Otras Sociedades';
        $list[1]['Cantidad'] = 0; 
        //$list[2]['Nombre'] = 'Otras Sociedades';
        //$list[2]['Cantidad'] = 0; 
        $list[2]['Nombre'] = 'Universo Compra';
        $list[2]['Cantidad'] = 0; 
        $list[3]['Nombre'] = 'Nuevos Casos Mes Actual - Cantidad';
        $list[3]['Cantidad'] = 0; 
        $list[4]['Nombre'] = 'Nuevos Casos Mes Actual - Haber Neto';
        $list[4]['Cantidad'] = 0; 

        $cant = 0;

        foreach ($result as $r) {
//dd($r);
            $oDet = new \stdClass();

            $oMarca = $r->Marca;
            $oUsu = $r->Login;
            $oCodOficial = $r->CodOficial;
            $oNomOficial = $r->NomOficial;
            $oCodEstado = $r->CodEstado;
            $oNomEstado = $r->NomEstado;
            $oCodOrigen = $r->CodOrigen;
            $oNomOrigen = $r->NomOrigen;
            $oSupervisor = $r->SupDeLaOP;

            $FechaCalculoAvance = $periodoActPrimerDia;

            //Cargo el objeto stdClass
            //Propiedades Nuevas para uso y filtros
            $oDet->AvanceAutomatico = -1;
            $oDet->EsPropio = -1;
            $oDet->EsOtrasSociedades = -1;
            $oDet->EsUniverso = -1;
            $oDet->EsSGA = -1;
            $oDet->EsCasoNuevo = -1;
            $oDet->EsMesActual = -1;
            $oDet->EsMesAnterior = -1;

            $oDet->ID = $r->ID;
            $oDet->Marca = $oMarca; 
            $oDet->Concesionario = $r->Concesionario; 
            $oDet->Grupo = $r->Grupo;
            $oDet->Orden = $r->Orden;
            $oDet->Solicitud = $r->Solicitud;
            $oDet->Apellido = $r->Apellido;
            $oDet->Nombres = $r->Nombres;
            
            if (isset($r->NroDoc)){
                $oDet->NroDoc = $r->NroDoc;
            }else{
                $oDet->NroDoc = '';
            }
            
            $oDet->Telefono1 = $r->Telefono1;
            $oDet->Telefono2 = $r->Telefono2;
            $oDet->Telefono3 = $r->Telefono3;
            $oDet->Telefono4 = $r->Telefono4;
            $oDet->Email1= $r->Email1;
            $oDet->Email2 = $r->Email2;
            $oDet->FechaVtoCuota2 = $r->FechaVtoCuota2;
            $oDet->Avance = $r->Avance;
            $oDet->HaberNeto= $r->HaberNeto;
            $oDet->CodOficial = $oCodOficial;
            $oDet->NomOficial = $oNomOficial;
            $oDet->CodEstado = $oCodEstado;
            $oDet->NomEstado = $oNomEstado;
            $oDet->CPG = $r->CPG;
            $oDet->CAD = $r->CAD;
            if ($r->Marca != 2){
                $oDet->Plan = $r->Plan;
                $oDet->AvanceCalculado = $r->AvanceCalculado;
            }
            
            $oDet->Empresa = $emp3;
            $oDet->FechaCompra = $r->FechaCompra;
            $oDet->FechaAltaRegistro = $r->FechaAltaRegistro;
            $oDet->PrecioCompra = $r->PrecioCompra;
            $oDet->Domicilio = $r->Domicilio;
            $oDet->CodOrigen = $oCodOrigen;
            $oDet->NomOrigen = $oNomOrigen;
            $oDet->Supervisor = $oSupervisor;
            $oDet->FechaUltimaAsignacion = $r->FechaUltimaAsignacion;
            $oDet->FechaUltObs = $r->FechaUltObs;
            $oDet->Retrabajar = $r->Retrabajar;
            $oDet->Vendido = $r->Vendido;
            $oDet->Motivo = $r->Motivo;
            $oDet->FechaCalculoAvance = $FechaCalculoAvance;

            //dd($oDet->FechaCompra);

            $fcav = strtotime($oDet->FechaCalculoAvance);
            $fvc2 = strtotime($oDet->FechaVtoCuota2);
            $fcomp = strtotime($oDet->FechaAltaRegistro);

           // dd($oDet->FechaCompra);
           
            if ($marca == 2){
                if ($r->FechaVtoCuota2 === NULL){
                    $oDet->AvanceAutomatico = 0;
                }else{
                    $oDet->AvanceAutomatico = $utils->getAvanceAutomaticoAFecha($fcav, $fvc2);
                    $oDet->Avance = $oDet->AvanceAutomatico;
                }
            }else{
                if ($oDet->AvanceCalculado === NULL){
                    $oDet->AvanceAutomatico = $oDet->Avance;
                    $oDet->Avance = $oDet->Avance;
                }else{
                    $oDet->AvanceAutomatico = $oDet->AvanceCalculado;
                    $oDet->Avance = $oDet->AvanceCalculado;
                   
                }
            }
            
            /*
           if ($oDet->FechaVtoCuota2 === NULL){
               if ($marca == 2){
                    $oDet->AvanceAutomatico = 0;
               }else{
                    if ($r->AvanceCalculado !== NULL){
                        $oDet->AvanceAutomatico = $r->AvanceCalculado;
                    }else{
                        $oDet->AvanceAutomatico = $r->Avance;
                    }
               }
                
           }else{
                $oDet->AvanceAutomatico = $this->getAvanceAutomatico($fcav, $fvc2);
                $oDet->Avance = $oDet->AvanceAutomatico;
           }
           */
           
            /*
            $debbug['GruOrd'] = $oDet->Grupo."-".$oDet->Orden;
            $debbug['AvanceAutomatico'] = $oDet->AvanceAutomatico;
            $debbug['FechaVtoCuota2'] = $oDet->FechaVtoCuota2;

            array_push($lstDeb, $debbug);
            */

           if ($oDet->AvanceAutomatico < 84 && $oDet->HaberNeto > 14999){
            //if ($oDet->AvanceAutomatico < 84 ){
                $cant += 1;
                $list[0]['Cantidad'] += 1;

                $oDet->EsSGA = 1;

                $perteneceUniverso = true;

                if($utils->esPropio($oDet->Nombres, $oDet->Apellido )){
                    $list[1]['Cantidad'] += 1;
                    $perteneceUniverso = false;
                    $oDet->EsUniverso = -1;
                    $oDet->EsPropio = 1;
                }
                if($utils->enOtraSociedad($oDet->Nombres, $oDet->Apellido )){
                    //$list[2]['Cantidad'] += 1;
                    $list[1]['Cantidad'] += 1;
                    $perteneceUniverso = false;
                    $oDet->EsUniverso = -1;
                    $oDet->EsOtrasSociedades = 1;
                }
                if($perteneceUniverso){
                    $list[2]['Cantidad'] += 1;
                    $oDet->EsUniverso = 1;
                    array_push($listUniverso, $oDet);
                }
                
                array_push($listDetalle, $oDet);    

                if ((date('m', $fcomp) === date('m', $periodoActual)) && (date('Y', $fcomp)  === date('Y', $periodoActual))  ){
                    $oDet->EsMesActual = 1;
                    $oDet->EsMesAnterior = 0;
                    array_push($listMesActual, $oDet); 
                }

                if ((date('m', $fcomp) === date('m', $periodoAnterior)) && (date('Y', $fcomp) === date('Y',$periodoAnterior))){
                    $oDet->EsMesActual = 0;
                    $oDet->EsMesAnterior = 1;
                    array_push($listMesAnterior, $oDet); 
                }

                if ($perteneceUniverso){
                    $oDet->EsUniverso = 1;
                }else{
                    $oDet->EsUniverso = -1;
                }

                array_push($lstDatos, $oDet); 

            } //if avance autom
            
            
            
        } //foreach

       // dd($listMesActual);

        foreach ($listMesActual as $new) {
            if(!($utils->esPropio($new->Nombres, $new->Apellido)) && !($utils->enOtraSociedad($new->Nombres, $new->Apellido))){
                $encontro = false;

                foreach ($listMesAnterior as $old) {
                    if ($new->Grupo === $old->Grupo && $new->Orden === $old->Orden){
                        $encontro = true;
                    break;
                    }
                }

                if (!$encontro){
                    array_push($listCasosNuevos, $new); 
                }
            }
        } //foreach mesactual

        $list[3]['Cantidad'] = count($listCasosNuevos);

        $casosNuevosHN = 0;

        foreach ($listCasosNuevos as $r) {
            $casosNuevosHN += $r->HaberNeto;
        }

        $list[4]['Cantidad'] = round($casosNuevosHN);

       // $lst['Debbug'] = $lstDeb;
        $lst['Datos'] = $lstDatos;
        $lst['Resumen'] = $list;
        $lst['MesActual'] = $this->getListMesActual($listCasosNuevos);
        $lst['Universo'] = $this->getListUniverso($listUniverso);

        //return $list;

        return $lst;


    } //function


    public function getListMesActual($lstMesActual)
    {

        $listMes = array();


        $listMes[0]['Capa'] = 0; 
        $listMes[0]['Tipo'] = 'Avance 0';
        $listMes[0]['Casos'] = 0;
        $listMes[0]['MontoHN'] = 0;
        $listMes[0]['TotalCasos'] = 0;
        $listMes[0]['TotalMontoHN'] = 0;

        $listMes[1]['Capa'] = 44; 
        $listMes[1]['Tipo'] = '1 a 44';
        $listMes[1]['Casos'] = 0; 
        $listMes[1]['MontoHN'] = 0;
        $listMes[1]['TotalCasos'] = 0;
        $listMes[1]['TotalMontoHN'] = 0;
       
        $listMes[2]['Capa'] = 60; 
        $listMes[2]['Tipo'] = '45 a 60';
        $listMes[2]['Casos'] = 0; 
        $listMes[2]['MontoHN'] = 0;
        $listMes[2]['TotalCasos'] = 0;
        $listMes[2]['TotalMontoHN'] = 0;
       
        $listMes[3]['Capa'] = 70; 
        $listMes[3]['Tipo'] = '61 a 70';
        $listMes[3]['Casos'] = 0; 
        $listMes[3]['MontoHN'] = 0;
        $listMes[3]['TotalCasos'] = 0;
        $listMes[3]['TotalMontoHN'] = 0;
       
        $listMes[4]['Capa'] = 77; 
        $listMes[4]['Tipo'] = '71 a 77';
        $listMes[4]['Casos'] = 0; 
        $listMes[4]['MontoHN'] = 0;
        $listMes[4]['TotalCasos'] = 0;
        $listMes[4]['TotalMontoHN'] = 0;
       
        $listMes[5]['Capa'] = 83; 
        $listMes[5]['Tipo'] = '78 a 83';
        $listMes[5]['Casos'] = 0; 
        $listMes[5]['MontoHN'] = 0;
        $listMes[5]['TotalCasos'] = 0;
        $listMes[5]['TotalMontoHN'] = 0;
       
        $listMes[6]['Capa'] = -1; 
        $listMes[6]['Tipo'] = 'Total';
        $listMes[6]['Casos'] = 0; 
        $listMes[6]['MontoHN'] = 0;
        $listMes[6]['TotalCasos'] = 0;
        $listMes[6]['TotalMontoHN'] = 0;
     

        foreach ($lstMesActual as $r) {
            
            switch ($r->AvanceAutomatico){
                case 0:
                    $listMes[0]['Casos'] += 1;
                    $listMes[0]['MontoHN'] += $r->HaberNeto;
                break;
                case (1 <= $r->AvanceAutomatico) && ( $r->AvanceAutomatico <= 44):
                    $listMes[1]['Casos'] += 1; 
                    $listMes[1]['MontoHN'] += $r->HaberNeto;
                break;

                case (45 <= $r->AvanceAutomatico) && ( $r->AvanceAutomatico <= 60):
                    $listMes[2]['Casos'] += 1; 
                    $listMes[2]['MontoHN'] += $r->HaberNeto;
                break;

                case (61 <= $r->AvanceAutomatico) && ( $r->AvanceAutomatico <= 70):
                    $listMes[3]['Casos'] += 1; 
                    $listMes[3]['MontoHN'] += $r->HaberNeto;
                break;
                case (71 <= $r->AvanceAutomatico) && ( $r->AvanceAutomatico <=  77):
                    $listMes[4]['Casos'] += 1; 
                    $listMes[4]['MontoHN'] += $r->HaberNeto;
                break;
                case (78 <= $r->AvanceAutomatico) && ( $r->AvanceAutomatico <=  83):
                    $listMes[5]['Casos'] += 1; 
                    $listMes[5]['MontoHN'] += $r->HaberNeto;
                break;

            }

        }

        $cantTotM = 0;
        $hnTotM = 0;

        for ($i=0; $i < count($listMes); $i++) { 
            $cantTotM += $listMes[$i]['Casos'];
            $hnTotM += $listMes[$i]['MontoHN'];
        }

        for ($i=0; $i < count($listMes); $i++) { 
            $listMes[$i]['TotalCasos'] = round($cantTotM);
            $listMes[$i]['TotalMontoHN'] = round($hnTotM);
        }

        $listMes[6]['Casos'] = round($cantTotM); 
        $listMes[6]['MontoHN'] = round($hnTotM); 

        return $listMes;

    }

    public function getListUniverso($list)
    {

        $listUniv = array();

        $arrSegmentos = array();

        $p = 0;
        $arrSegmentos[$p]['Capa'] = 0;
        $arrSegmentos[$p]['Nombre'] = 'Avance 0';
        $arrSegmentos[$p]['Desde'] = 0;
        $arrSegmentos[$p]['Hasta'] = 0;

        $p ++; //1
        $arrSegmentos[$p]['Capa'] = 20;
        $arrSegmentos[$p]['Nombre'] = '1 a 20';
        $arrSegmentos[$p]['Desde'] = 1;
        $arrSegmentos[$p]['Hasta'] = 20;

        $p ++; //2
        $arrSegmentos[$p]['Capa'] = 30;
        $arrSegmentos[$p]['Nombre'] = '21 a 30';
        $arrSegmentos[$p]['Desde'] = 21;
        $arrSegmentos[$p]['Hasta'] = 30;

        $p ++; //3
        $arrSegmentos[$p]['Capa'] = 40;
        $arrSegmentos[$p]['Nombre'] = '31 a 40';
        $arrSegmentos[$p]['Desde'] = 31;
        $arrSegmentos[$p]['Hasta'] = 40;

        $p ++; //4
        $arrSegmentos[$p]['Capa'] = 50;
        $arrSegmentos[$p]['Nombre'] = '41 a 50';
        $arrSegmentos[$p]['Desde'] = 41;
        $arrSegmentos[$p]['Hasta'] = 50;
        
        $p ++; //5
        $arrSegmentos[$p]['Capa'] = 60;
        $arrSegmentos[$p]['Nombre'] = '51 a 60';
        $arrSegmentos[$p]['Desde'] = 51;
        $arrSegmentos[$p]['Hasta'] = 60;

        $p ++; //6
        $arrSegmentos[$p]['Capa'] = 70;
        $arrSegmentos[$p]['Nombre'] = '61 a 70';
        $arrSegmentos[$p]['Desde'] = 61;
        $arrSegmentos[$p]['Hasta'] = 70;

        $p ++; //7
        $arrSegmentos[$p]['Capa'] = 77;
        $arrSegmentos[$p]['Nombre'] = '71 a 77';
        $arrSegmentos[$p]['Desde'] = 71;
        $arrSegmentos[$p]['Hasta'] = 77;
    
        $p ++; //8
        $arrSegmentos[$p]['Capa'] = 83;
        $arrSegmentos[$p]['Nombre'] =  '78 a 83';
        $arrSegmentos[$p]['Desde'] = 78;
        $arrSegmentos[$p]['Hasta'] = 83;
    
        for ($i=0; $i <= $p; $i++) { 
            $listUniv[$i]['Capa'] = $arrSegmentos[$i]['Capa'];
            $listUniv[$i]['Tipo'] = $arrSegmentos[$i]['Nombre'];
            $listUniv[$i]['Casos'] = 0; 
            $listUniv[$i]['MontoHN'] = 0;
            $listUniv[$i]['TotalCasos'] = 0;
            $listUniv[$i]['TotalMontoHN'] = 0;
        }


        foreach ($list as $r) {
            
            switch ($r->AvanceAutomatico){
                case 0:
                    $listUniv[0]['Casos'] += 1;
                    $listUniv[0]['MontoHN'] += round($r->HaberNeto);
                break;
                case (1 <= $r->AvanceAutomatico) && ( $r->AvanceAutomatico <= 20):
                    $listUniv[1]['Casos'] += 1; 
                    $listUniv[1]['MontoHN'] += round($r->HaberNeto);
                break;
                case (21 <= $r->AvanceAutomatico) && ( $r->AvanceAutomatico <= 30):
                    $listUniv[2]['Casos'] += 1; 
                    $listUniv[2]['MontoHN'] += round($r->HaberNeto);
                break;
                case (31 <= $r->AvanceAutomatico) && ( $r->AvanceAutomatico <=  40):
                    $listUniv[3]['Casos'] += 1; 
                    $listUniv[3]['MontoHN'] += round($r->HaberNeto);
                break;
                case (41 <= $r->AvanceAutomatico) && ( $r->AvanceAutomatico <=  50):
                    $listUniv[4]['Casos'] += 1; 
                    $listUniv[4]['MontoHN'] += round($r->HaberNeto);
                break;
                case (51 <= $r->AvanceAutomatico) && ( $r->AvanceAutomatico <=  60):
                    $listUniv[5]['Casos'] += 1; 
                    $listUniv[5]['MontoHN'] += round($r->HaberNeto);
                break;
                case (61 <= $r->AvanceAutomatico) && ( $r->AvanceAutomatico <=  70):
                    $listUniv[6]['Casos'] += 1; 
                    $listUniv[6]['MontoHN'] += round($r->HaberNeto);
                break;
                case (71 <= $r->AvanceAutomatico) && ( $r->AvanceAutomatico <=  77):
                    $listUniv[7]['Casos'] += 1; 
                    $listUniv[7]['MontoHN'] += round($r->HaberNeto);
                break;
                case (78 <= $r->AvanceAutomatico) && ( $r->AvanceAutomatico <=  83):
                    $listUniv[8]['Casos'] += 1; 
                    $listUniv[8]['MontoHN'] += round($r->HaberNeto);
                break;

            }

        }

        $cantTot = 0;
        $hnTot = 0;

        for ($i=0; $i < count($listUniv); $i++) { 
            $cantTot += $listUniv[$i]['Casos'];
            $hnTot += $listUniv[$i]['MontoHN'];
        }

        for ($i=0; $i < count($listUniv); $i++) { 
            $listUniv[$i]['TotalCasos'] = round($cantTot);
            $listUniv[$i]['TotalMontoHN'] = round($hnTot);
        }

        $listUniv[9]['Capa'] = -1; 
        $listUniv[9]['Tipo'] = 'Total'; 
        $listUniv[9]['Casos'] = round($cantTot); 
        $listUniv[9]['MontoHN'] = round($hnTot); 

        return $listUniv;

    }
   
    /*
    public function getAvanceAutomaticoAFecha($FechaCalculoAvance, $FechaVtoCuota2){

        $avance = 0;
        if (isset($FechaCalculoAvance)){
            $fecha = $FechaCalculoAvance;
        }else{
            $fecha = now();
        }

        if ($FechaVtoCuota2 === NULL){
            return 0;
        }else{
            $fvtoc2 = date_create(date('Y-m-d', $FechaVtoCuota2));
            $ff = date_create(date('Y-m-d', $fecha));       
    
            if (checkdate(date('m', $FechaVtoCuota2), date('d', $FechaVtoCuota2), date('Y', $FechaVtoCuota2))){
                $diff = date_diff($fvtoc2 , $ff);
                //$avance = ($diff->format('%y') * 12 + $diff->format('%m')) + 2;
                $avance = (($diff->format('%a') / 365) * 12) + 2;
                $avance = round($avance, 0);
                
            }
        }

        if ($avance > 84){
            $avance = 84;
        }

        return $avance;
    }
    */

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