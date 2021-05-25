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

        //$detalle_RB_RB = HaberNeto::on('RB')->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();
        //$detalle_RB_GF = HaberNeto::on('GF')->where('ComproGiama', '=', 1)->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();
    
        $detalle_CE_GF = HaberNeto::on('GF')->where('ComproGiama', '=', 0)->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();

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
        $lst['Detalle_CE'] = $detalle_CE_GF;

       // $lst['Detalle_RB_RB'] = $detalle_RB_RB;
       // $lst['Detalle_RB_CE'] = $detalle_RB_GF;


        return $lst;
    }

    public function getDetalleConcesionario(Request $request ){

        $periodo = $request->periodo;
        $codConcesionario = $request->CodConcesionario;
        $comproGiama = $request->ComproGiama;
       
        $periodoMes = substr($periodo, 4, strlen($periodo));
        $periodoAnio = substr($periodo, 0, 4);

        if ($codConcesionario == 8){ // Para RB llamo a un SP distinto
            $queryStr_Detail = "CALL hnweb_detalle_facturacion_rb(".$periodoMes.", ".$periodoAnio.");";
        }else{
            $queryStr_Detail = "CALL hnweb_detalle_facturacion_ce(".$periodoMes.", ".$periodoAnio.", ". $codConcesionario.", ".$comproGiama.");";
        }
          
        $result = DB::select($queryStr_Detail); 
    
        $lst = array();
       
        $lst['Detalle_CE'] = $result;

        return  $lst;

    }

    public function getDetalleGeneral(Request $request ){

        $periodo = $request->periodo;
        $selectedsCE = explode(",", $request->ConcesionariosSelecteds);

        /*
        $codConcesionario = $request->CodConcesionario;
        $comproGiama = $request->ComproGiama;
        */
       
        $periodoMes = substr($periodo, 4, strlen($periodo));
        $periodoAnio = substr($periodo, 0, 4);
        
        $lst = array();
        $arr_results = array();
        $lst_result = array();

        foreach ($selectedsCE as $selected) {
            $result = array();

            if ($selected == 8){ // Para RB llamo a un SP distinto
                $queryStr_Detail = "CALL hnweb_detalle_facturacion_rb(".$periodoMes.", ".$periodoAnio.");";
            }else{
                $queryStr_Detail = "CALL hnweb_detalle_facturacion_ce(".$periodoMes.", ".$periodoAnio.", ". $selected.", 0);"; // AGREGAR CLAUSE IFNULL
            }

            $result = DB::select($queryStr_Detail);

            array_push($lst_result, $result);

        }
       
        $lst['Detalle_Gral'] = $lst_result;

        return  $lst;

    }

    public function esConcesionarioComisionable($codConcesionario)
    {
        switch($codConcesionario){
            case 1:
            case 2:
            case 3:
            case 7:
                return true;
            default:
                return false;
        }

    }

    public function getNombresConcesionarioComision(){
        return 'Sauma + Iruña + Amendola + Luxcar';
    }
    
    public function getDetalle(Request $request)
    {
    
        $periodo = $request->periodo;
       
        $periodoMes = substr($periodo, 4, strlen($periodo));
        $periodoAnio = substr($periodo, 0, 4);
        
        $lstCEs = array();
        $lstRBs = array();
        $lstHNs = array();

        $lstRB_AN = array();
        $lstRB_AC = array();
        $lstRB_CG = array();

        $lstDetalle_FC = array();

        $lstTabla_CE = array();

        $lstTabla_CE_RB = array();
        $lstTabla_CE_CE = array();

        $lstComisionTerceros = array();

        $acum_AN = 0;
        $acum_AC = 0;
        $acum_CG = 0;
        $acum_CE_RB = 0;

        $lstAcumulados = array();

        $portentajeClientes = 0.07;
        $portentajePropios = 0.05;

        $portentajeComisionTerceros = 0.02;
        $comisionTerceros = 0;
        $totalComisiones = 0;
       
        $detalle_CE = HaberNeto::on('GF')->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();

        $utils = new UtilsController;


        $concesionarios = $utils->getCodeNameConcesionariosFacturacion();

       // return $concesionarios;

        foreach ($concesionarios as $ce) {
            $oCE = new \stdClass();
            $oCE_RB = new \stdClass();
            $oCE_CE = new \stdClass();

            $oCE->ID = $ce->ID;
            $oCE->Nombre = $ce->Nombre;

            $oCE_RB->ID = $ce->ID;
            $oCE_RB->Nombre = $ce->Nombre;

            $oCE_CE->ID = $ce->ID;
            $oCE_CE->Nombre = $ce->Nombre;

            $oCE->Casos = 0;
            $oCE->HN = 0;
            $oCE->AFacturar = 0;

            $oCE_RB->Casos = 0;
            $oCE_RB->HN = 0;
            $oCE_RB->AFacturar = 0;

            $oCE_CE->Casos = 0;
            $oCE_CE->HN = 0;
            $oCE_CE->AFacturar = 0;

            $lstTabla_CE[$ce->ID] = $oCE;
            $lstTabla_CE_RB[$ce->ID] = $oCE_RB;
            $lstTabla_CE_CE[$ce->ID] = $oCE_CE;
        } 


        foreach ($detalle_CE as $det) {
            $oDet = new \stdClass();
            $oConc = new \stdClass();
            $oDet_Detalle = new \stdClass();

            $oDet->Grupo = $det->Grupo;
            $oDet->Orden = $det->Orden;
            $oDet->Avance = $det->Avance;
            $oDet->HaberNeto = $det->HaberNetoOriginal;
            $oDet->Concesionario = $det->Concesionario;
            
            if ($det->Concesionario == 10){ // Si es Alizze, va el 5%
                $oDet->AFacturar = $det->HaberNetoOriginal * $portentajePropios;
            }else{
                $oDet->AFacturar = $det->HaberNetoOriginal * $portentajeClientes;
            }
            
            $oDet->ComproGiama = $det->ComproGiama;

            $lstTabla_CE[$det->Concesionario]->Casos += 1;
            $lstTabla_CE[$det->Concesionario]->HN += $det->HaberNetoOriginal;
            $lstTabla_CE[$det->Concesionario]->AFacturar += $oDet->AFacturar;


            if ($this->esConcesionarioComisionable($det->Concesionario)){
                $comisionTerceros += ($det->HaberNetoOriginal * $portentajeComisionTerceros);
            }

            if ($det->ComproGiama == 1){
                $oDet->Titular = 'RB';
                $acum_CE_RB += $oDet->AFacturar;
                $oDet_Detalle->Nombre = 'RB';
                $oDet->NombreConcesionario = $oDet_Detalle->Nombre;

                $lstTabla_CE_RB[$det->Concesionario]->Casos += 1;
                $lstTabla_CE_RB[$det->Concesionario]->HN += $det->HaberNetoOriginal;
                $lstTabla_CE_RB[$det->Concesionario]->AFacturar += $oDet->AFacturar;

                array_push($lstRBs,$oDet);
            }else{
                $oDet->Titular = 'CONCESIONARIO';
                $oDet_Detalle->Nombre = $utils->getNameConcesionario($det->Concesionario);
                $oDet->NombreConcesionario = $oDet_Detalle->Nombre;

                $lstTabla_CE_CE[$det->Concesionario]->Casos += 1;
                $lstTabla_CE_CE[$det->Concesionario]->HN += $det->HaberNetoOriginal;
                $lstTabla_CE_CE[$det->Concesionario]->AFacturar += $oDet->AFacturar;

                array_push($lstCEs,$oDet);
            }

            
            //array_push($lstHNs,$oDet);
        }

        $arrConision['Comisionista'] = 'José Demarco';
        $arrConision['Porcentaje'] = ($portentajeComisionTerceros * 100).'%';
        $arrConision['Concesionarios'] = $this->getNombresConcesionarioComision();
        $arrConision['Total'] = $comisionTerceros;
        $arrConision['Aclaracion'] = 'Sobre HN comprados';
        array_push($lstComisionTerceros,$arrConision);

        $totalComisiones = $comisionTerceros;
        

        $detalle_RB_RB = HaberNeto::on('RB')->whereYear('FechaAltaRegistro', '=', $periodoAnio)->whereMonth('FechaAltaRegistro', '=', $periodoMes)->get();

        foreach ($detalle_RB_RB as $det_RB) {
            $oDet = new \stdClass();
            $oDet_Detalle_Emp = new \stdClass();

            $oDet->Grupo = $det_RB->Grupo;
            $oDet->Orden = $det_RB->Orden;
            $oDet->Avance = $det_RB->Avance;
            $oDet->HaberNeto = $det_RB->HaberNetoOriginal;
            $oDet->Concesionario = $det_RB->Concesionario;
            
            $oDet->AFacturar = $det_RB->HaberNetoOriginal * $portentajePropios; 
            $oDet->Titular = 'RB';


            switch($det_RB->EmpresaOrigenGyO){
                case 3: //AN
                    $acum_AN += $oDet->AFacturar;
                    $oDet_Detalle_Emp->Nombre = 'AutoNet';
                    $codCe = 5;
                    array_push($lstRB_AN,$oDet);
                break;
                case 6: //AC
                    $acum_AC += $oDet->AFacturar;
                    $oDet_Detalle_Emp->Nombre = 'AutoCervo';
                    $codCe = 4;
                    array_push($lstRB_AC,$oDet); 
                break;
                case 8: //CG
                    $acum_CG += $oDet->AFacturar;
                    $oDet_Detalle_Emp->Nombre = 'CarGroup';
                    $codCe = 6;
                    array_push($lstRB_CG,$oDet);
                break;
            }

            $lstTabla_CE[$codCe]->Casos += 1;
            $lstTabla_CE[$codCe]->HN += $det->HaberNetoOriginal;
            $lstTabla_CE[$codCe]->AFacturar += $oDet->AFacturar;


            $lstTabla_CE_RB[$codCe]->Casos += 1;
            $lstTabla_CE_RB[$codCe]->HN += $det->HaberNetoOriginal;
            $lstTabla_CE_RB[$codCe]->AFacturar += $oDet->AFacturar;

            
           // array_push($lstHNs,$oDet);
        }


        $lstAcumulados['AutoNet'] = $acum_AN;
        $lstAcumulados['AutoCervo'] = $acum_AC;
        $lstAcumulados['CarGroup'] = $acum_CG;
        $lstAcumulados['RB_CE'] = $acum_CE_RB;


        $lst = array();

        $lst['ComisionesTerceros'] = $lstComisionTerceros;
        $lst['TotalComisiones'] = $totalComisiones;

        $lst['Tabla_Gral'] = $lstTabla_CE;

        $lst['Tabla_RB'] = $lstTabla_CE_RB;
        $lst['Tabla_CE'] = $lstTabla_CE_CE;


        $lst['Acumulados'] = $lstAcumulados;
       
        $lst['Reporte_CE'] = $lstCEs;
        $lst['Reporte_CE_RB'] = $lstRBs;
        $lst['Detalle_RB_AN'] = $lstRB_AN;
        $lst['Detalle_RB_AC'] = $lstRB_AC;
        $lst['Detalle_RB_CG'] = $lstRB_CG;


        return $lst;
    }
   



}