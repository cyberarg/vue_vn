<?php

namespace App\Http\Controllers;

use App\HaberNeto;
use App\HistoricoCompra;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\UtilsController;

class HaberesNetosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    public function getDatosHaberesNetos_Selecteds(Request $request){

        $seleccionados = $request->Seleccionados;

        $empresa = $request->Empresa;
        $anio = $request->Anio;
        $filtros = $request->Filtros;
        $rbConsolidado = $request->ConsolidadoRB;

        $arrResultados = array();
        $arrResultados['NoEncontrados'] = array();
        $arrResultados['ListHN'] = array();
        $arrResultados['ListHN_ComproGiama'] = array();
        $arrResultados['ListHN_CE'] =  array();
        
        $p = 0;
        foreach ($seleccionados as $seleccionado) {
            
            $array_result = array();
            $marca = $seleccionado['Marca'];
            $concesionario = $seleccionado['Codigo'];

            $array_result = $this->getHaberesNetosVigentes($marca, $concesionario, $rbConsolidado);

            if ($p == 0){
                $arrResultados['NoEncontrados'] = $array_result['NoEncontrados'];
                $arrResultados['ListHN'] = $array_result['ListHN']; 
                $arrResultados['ListHN_ComproGiama'] = $array_result['ListHN_ComproGiama'];
                $arrResultados['ListHN_CE'] = $array_result['ListHN_CE'];
                
            }else{
                $arrResultados['NoEncontrados'] = array_merge($arrResultados['NoEncontrados'], $array_result['NoEncontrados']);
                $arrResultados['ListHN'] = array_merge( $arrResultados['ListHN'], $array_result['ListHN']); 
                $arrResultados['ListHN_ComproGiama'] = array_merge($arrResultados['ListHN_ComproGiama'], $array_result['ListHN_ComproGiama']);
                $arrResultados['ListHN_CE'] = array_merge($arrResultados['ListHN_CE'], $array_result['ListHN_CE']);    
            }
           
            $p++;
        }

        return $arrResultados;

    }


    public function getDatosHaberesNetos(Request $request){

        $marca = $request->Marca;
        $concesionario = $request->Concesionario;

        $seleccionados = $request->Seleccionados;

        $empresa = $request->Empresa;
        $anio = $request->Anio;
        $filtros = $request->Filtros;
        $rbConsolidado = $request->ConsolidadoRB;
        return $this->getHaberesNetosVigentes($marca, $concesionario, $rbConsolidado);


    }

    public function getCalculoHN(Request $request){
        
        $marca = $request->marca;
        $plan = $request->plan;
        $cpg = $request->cpagas;
        $cad = $request->cadel;

        /*
        $marca = 5;
        $plan = '9B';
        $cpg = 37;
        $cad = 0;
        $hnFormula = 0;
        $hnReal = 0;
        */

        DB::statement("CALL hnweb_calculo_hn(".$marca.", '".$plan."', ".$cpg.", ".$cad.", @hnFormula, @hnReal);");

        $result = DB::select("SELECT @hnFormula AS HNFormula, @hnReal AS HNReal");
        return $result;
    }

    public function getCalculoHNGuido(Request $request){
        
        $marca = $request->marca;
        $plan = $request->plan;
        $cpg = $request->cpagas;
        $cad = $request->cadel;
        $valormovil = $request->valormovil;
        $descuento = $request->descuento;

        /*
        $marca = 5;
        $plan = '9B';
        $cpg = 37;
        $cad = 0;
        $hnFormula = 0;
        $hnReal = 0;
        */

        DB::statement("CALL hnweb_calculo_hn_guido(".$marca.", '".$plan."', ".$cpg.", ".$cad.", ".$valormovil.", ".$descuento.", @hnFormula, @hnReal);");

        $result = DB::select("SELECT @hnFormula AS HNFormula, @hnReal AS HNReal");
        return $result;
    }
    

    public function getModelosHN(Request $request){
        $res = DB::select('SELECT modelos.Codigo, modelos.Nombre FROM modelos WHERE  modelos.ParaCalcularHN = 1 AND modelos.marca = '.$request->marca.' ORDER BY modelos.Nombre ASC;');

        return $res;
    }

    public function getPlanesHN(Request $request){
        return DB::select('SELECT hnweb_codigos_planes.Plan AS Codigo, hnweb_codigos_planes.Plan AS Nombre FROM hnweb_codigos_planes WHERE hnweb_codigos_planes.marca = '.$request->marca.' AND hnweb_codigos_planes.CodigoModelo = '.$request->modelo.' ORDER BY hnweb_codigos_planes.Plan ASC;');
    }

    public function getPlanesSinModeloHN(Request $request){
        //return DB::select('SELECT hnweb_codigos_planes.Plan AS Codigo, hnweb_codigos_planes.Plan AS Nombre FROM hnweb_codigos_planes WHERE hnweb_codigos_planes.marca = '.$request->marca.' ORDER BY hnweb_codigos_planes.Plan ASC;');
  
        return DB::select('SELECT hnweb_codigos_planes.Plan AS Codigo, hnweb_codigos_planes.Plan AS Nombre, 
        hnweb_codigos_planes.CodigoModelo AS CodModelo, modelos.Nombre AS NomModelo
        FROM hnweb_codigos_planes 
        LEFT JOIN modelos ON modelos.Codigo = hnweb_codigos_planes.CodigoModelo AND modelos.Marca = '.$request->marca.'
        WHERE hnweb_codigos_planes.marca = '.$request->marca.' 
        ORDER BY hnweb_codigos_planes.Plan ASC;');
  
    }

    public function getListasHN(Request $request){


        return DB::select('SELECT lp.Codigo, lp.Descripcion AS Nombre, precios.Precio 
                            FROM listasprecios lp
                            LEFT JOIN precios ON precios.Codigo = lp.Codigo  AND precios.marca = lp.marca
                            WHERE lp.marca = '.$request->marca.' AND lp.codigo > 146 AND precios.CodigoModelo = '.$request->modelo);

        //return DB::select('SELECT Codigo, Descripcion as Nombre FROM listasprecios  WHERE marca = '.$request->marca.' AND codigo > 151;');

    }
    

    public function getHaberesNetosVigentes($marca, $concesionario, $rbConsolidado){
       
        $listHN = array();
        $list = array();
        $lstModelos = array();
        $util = new UtilsController;

        $lista = array();

        $listHN_ComproGiama = array();
        $listHN_CE = array();

        $idConc = $concesionario;
        

        $emp1 = "AutoCervo";
        $db1= "AC";
        $emp2 = "AutoNet";
        $db2= "AN";
        $emp3 = "CarGroupFusion";
        $db3= "CG";

        $codEmpresa = 0;

        if ($marca == 2){

            switch($concesionario){
                case 4:
                    $db = "AC";
                    $codEmpresa = 6;
                break;
                case 5:
                    $db = "AN";
                    $codEmpresa = 3;
                break;
                case 6:
                    $db = "CG";
                    $codEmpresa = 8;
                break;
                case 8:
                    $db = "RB";
                    $codEmpresa = 10;
                break;
                default:
                    $db = "GF";
                break;
            }
            
            if ($db == 'RB' && $rbConsolidado){
                $haberesNetos_RB = array();
                $haberesNetos_GF = array();
    
                $haberesNetos_RB = DB::connection('RB')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = ''");
    
                $haberesNetos_GF = DB::connection('GF')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = '' AND ComproGiama = 1");
    
                $haberesNetos = array_merge($haberesNetos_RB, $haberesNetos_GF);
            }else{
               
                $haberesNetos = HaberNeto::on($db)->whereNull('FechaCobroReal')->get();
               
            }

            $usaHNConcesionario = false;
        }else{

            if ($marca == 99){ //GIAMA
                $haberesNetos_RB = array();
                $haberesNetos_GF = array();
                $haberesNetos_AN = array();
                $haberesNetos_CG = array();

                $haberesNetos_RB = DB::connection('RB')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = ''");
                $haberesNetos_GF = DB::connection('GF')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = '' AND ComproGiama = 1");

                $haberesNetos_AN = DB::connection('AN')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = ''");
                $haberesNetos_CG = DB::connection('CG')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = ''");

                $haberesNetos = array_merge($haberesNetos_RB, $haberesNetos_GF, $haberesNetos_AN, $haberesNetos_CG);

                $usaHNConcesionario = false;
            }else{

                $usaHNConcesionario = true;
                $db = "GF";
                $codEmpresa = 1;
                $haberesNetos = HaberNeto::on($db)->where('Concesionario', $concesionario)->whereNull('FechaCobroReal')->get();
            }
        }
        
        if ($codEmpresa == 0){
            $esInterempresa = true;
        }else{
            $esInterempresa = false;
        }


        //$haberesNetos = $result;

        $errObtenerOp = array();

        foreach ($haberesNetos as $hn) {
            $oHN = new \stdClass();
            $oOp = new \stdClass();

            $oHN->MontoCobroReal = $hn->MontoCobroReal;

            if ($oHN->MontoCobroReal > 0 && $marca == 2){
                continue;
            }

            $oOp->Marca = $hn->Marca;
            $oOp->Concesionario = $hn->Concesionario;
            $oOp->Grupo = $hn->Grupo;
            $oOp->Orden = $hn->Orden;
            $oOp->Empresa = $hn->EmpresaOrigenGyO;

            if ($hn->Marca != 2){
                $oOp->Plan = $hn->Plan;
            }

            if($usaHNConcesionario){
                $oOp->TipoPlan = $hn->TipoPlan;
                $oOp->Plan = $hn->Plan;
                $oOp->Cliente = $hn->Cliente;
                $oOp->Modelo = $hn->CodModelo;
                $oOp->NomModelo = $hn->NomModelo;
                $oOp->Avance = $hn->Avance;
                $oOp->CPG = $hn->CuotasPagas;
                //$oOp->CPG = $hn->CuotasPagas;

                $oOp->Empresa = $hn->Concesionario;

                $oOp->CuotaTerminal = $hn->ValorAutoHoy;
                //$oOp->PrecioAutoActual = $hn->ValorAutoHoy;
                
                $oOp->Plazo = $hn->Plazo;
                $oOp->FechaVtoCuota = $hn->FechaVtoCuota;
            }else{

                if ($hn->Marca == 2){
                    $oEmpresaOrigenOp = $oOp->Empresa;
                    $itemBuscado = $this->searchGyOyMyE($list, $oOp->Grupo, $oOp->Orden, $oOp->Marca, $oOp->Empresa);
                }
            }

            $oOp->PrecioAutoActual = $hn->ValorAutoHoy;

            $oHN->Operacion = $oOp;
            $oHN->Id = $hn->Id;
            $oHN->ID_Dato = $hn->ID_Dato;
            $oHN->Rescindido = $hn->Rescindido;
            $oHN->Titular = $hn->Titular;
            $oHN->TipoCompra = $hn->TipoCompra;
            $oHN->GrupoTomaPlan = $hn->Grupo_TomaPlan;
            $oHN->OrdenTomaPlan = $hn->Orden_TomaPlan;
            $oHN->MontoCobroEstimado = $hn->MontoCobro;
            $oHN->FechaCobroReal = $hn->FechaCobroReal;
            $oHN->MontoCompra = $hn->MontoCompra;
            $oHN->FechaCompra = $hn->FechaAltaRegistro;
            //$oHN->FechaHoy = $hn->FechaHoy;
            $oHN->EnvioMailReclamoTerminal = $hn->EnvioMailReclamoTerminal;
            $oHN->HaberNetoSubite = $hn->HaberNetoSubite;
            $oHN->SeDesrescindio = $hn->SeDesrescindio;
            $oHN->Transferencia = $hn->Transferencia;
            $oHN->ConcesionarioPropio = $hn->ConcesionarioPropio;
            $oHN->ComproGiama = $hn->ComproGiama; 
            $oHN->TIRSpot = $hn->TIRSpot; 

            /*
            if ($hn->Marca == 2){
                $oHN->Empresa = $hn->Empresa;
            }else{
                $oOp->Empresa = $hn->Concesionario;
            }
            */

            $oHN->IdConcesionario = $hn->Concesionario;

            $oHN->MontoCompraDolares = $hn->MontoCompraDolares;
            //$oHN->MontoCobroRealDolares = $hn->MontoCobroRealDolares;
            $oHN->MontoCobroDolares = $hn->MontoCobroDolares;
            $oHN->HaberNetoSubiteUSD = $hn->HaberNetoSubiteUSD;

            $oHN->UtilidadActual = $hn->UtilidadActual;
            $oHN->DurationActual = $hn->DurationActual;
            $oHN->DurationCompra = $hn->DurationCompra;
            $oHN->TIRActual = $hn->TIRActual;
            $oHN->FechaCuota84 = $hn->FechaCuota84;
           
            if($hn->ComproGiama == 1){
                array_push($listHN_ComproGiama, $oHN);
            }else{
                array_push($listHN_CE, $oHN);
            }

            array_push($listHN, $oHN);
        }

        $lista['NoEncontrados'] = $errObtenerOp;
        $lista['ListHN'] = $listHN;
        $lista['ListHN_ComproGiama'] = $listHN_ComproGiama;
        $lista['ListHN_CE'] = $listHN_CE;
        return $lista;
        //dd($listHN);
    }


    public function getHaberesNetosCobrados_Selecteds(Request $request){

        $seleccionados = $request->Seleccionados;

        $empresa = $request->Empresa;
        $anio = $request->Anio;
        $filtros = $request->Filtros;
        $rbConsolidado = $request->ConsolidadoRB;

        $arrResultados = array();
        $arrResultados['NoEncontrados'] = array();
        $arrResultados['ListHNCobrados'] = array();
        $arrResultados['ListHN_C_ComproGiama'] = array();
        $arrResultados['ListHN_C_CE'] =  array();

        
        $p = 0;
        foreach ($seleccionados as $seleccionado) {
            
            $array_result = array();
            $marca = $seleccionado['Marca'];
            $concesionario = $seleccionado['Codigo'];

            $array_result = $this->getHaberesNetosCobrados_Pars($marca, $concesionario, $rbConsolidado);

            if ($p == 0){
                $arrResultados['NoEncontrados'] = $array_result['NoEncontrados'];
                $arrResultados['ListHNCobrados'] = $array_result['ListHNCobrados']; 
                $arrResultados['ListHN_C_ComproGiama'] = $array_result['ListHN_C_ComproGiama'];
                $arrResultados['ListHN_C_CE'] = $array_result['ListHN_C_CE'];
                
            }else{
                $arrResultados['NoEncontrados'] = array_merge($arrResultados['NoEncontrados'], $array_result['NoEncontrados']);
                $arrResultados['ListHNCobrados'] = array_merge( $arrResultados['ListHNCobrados'], $array_result['ListHNCobrados']); 
                $arrResultados['ListHN_C_ComproGiama'] = array_merge($arrResultados['ListHN_C_ComproGiama'], $array_result['ListHN_C_ComproGiama']);
                $arrResultados['ListHN_C_CE'] = array_merge($arrResultados['ListHN_C_CE'], $array_result['ListHN_C_CE']);    
            }
           
            $p++;
        }

        return $arrResultados;


    }


    public function getHaberesNetosCobrados_Pars($marca, $concesionario, $rbConsolidado){
       
        //$marca = $request->Marca;
        //$concesionario = $request->Concesionario;
        //$rbConsolidado = $request->ConsolidadoRB;

        $listHNCobrados = array();
        $list = array();
        $lstModelos = array();
        $util = new UtilsController;

        $lista = array();

        $listHN_C_ComproGiama = array();
        $listHN_C_CE = array();

        $idConc = $concesionario;
       

        $emp1 = "AutoCervo";
        $db1= "AC";
        $emp2 = "AutoNet";
        $db2= "AN";
        $emp3 = "CarGroupFusion";
        $db3= "CG";
    

        //$db = "CG";
        $codEmpresa = 0;
        if ($marca == 2){

            switch($concesionario){
                case 4:
                    $db = "AC";
                    $codEmpresa = 6;
                break;
                case 5:
                    $db = "AN";
                    $codEmpresa = 3;
                break;
                case 6:
                    $db = "CG";
                    $codEmpresa = 8;
                break;
                case 8:
                    $db = "RB";
                    $codEmpresa = 10;
                break;
                default:
                    $db = "GF";
                break;
            }
            

            if ($db == 'RB' && $rbConsolidado){
                $haberesNetos_RB = array();
                $haberesNetos_GF = array();
    
                $haberesNetos_RB = DB::connection('RB')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> ''");
    
                $haberesNetos_GF = DB::connection('GF')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> '' AND ComproGiama = 1");
    
                $haberesNetos = array_merge($haberesNetos_RB, $haberesNetos_GF);
            }else{
                //$haberesNetos =  DB::connection($db)->table('haberesnetosok')->whereNotNull('FechaCobroReal')->get(); 
                $haberesNetos = HaberNeto::on($db)->whereNotNull('FechaCobroReal')->get();
            }
            $usaHNConcesionario = false;

        }else{

            if ($marca == 99){ //GIAMA
                $haberesNetos_RB = array();
                $haberesNetos_GF = array();
                $haberesNetos_AN = array();
                $haberesNetos_CG = array();

                $haberesNetos_RB = DB::connection('RB')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> ''");
                $haberesNetos_GF = DB::connection('GF')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> '' AND ComproGiama = 1");

                $haberesNetos_AN = DB::connection('AN')->select("SELECT *  FROM haberesnetosok WHERE OcultarHN <> 1 IFNULL(FechaCobroReal, '') <> ''");
                $haberesNetos_CG = DB::connection('CG')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> ''");

                $haberesNetos = array_merge($haberesNetos_RB, $haberesNetos_GF, $haberesNetos_AN, $haberesNetos_CG);

                $usaHNConcesionario = false;
            }else{

                $usaHNConcesionario = true;
                $db = "GF";
                $codEmpresa = 1;
                $haberesNetos = HaberNeto::on($db)->where('Concesionario', $concesionario)->whereNotNull('FechaCobroReal')->get();
            }
        }

        if ($codEmpresa == 0){
            $esInterempresa = true;
        }else{
            $esInterempresa = false;
        }

        $lstModelos = $this->getValoresAutoAFecha(now(), $codEmpresa);
        
        //dd($haberesNetos);

        $errObtenerOp = array();

        foreach ($haberesNetos as $hn) {
            $oHN = new \stdClass();
            $oOp = new \stdClass();

            $oHN->MontoCobroReal = $hn->MontoCobroReal;

            /*
            if ($oHN->MontoCobroReal > 0){
                continue;
            }
            */

            $oOp->Marca = $hn->Marca;
            $oOp->Concesionario = $hn->Concesionario;
            $oOp->Grupo = $hn->Grupo;
            $oOp->Orden = $hn->Orden;
            $oOp->Empresa = $hn->EmpresaOrigenGyO;
            
            if ($hn->Marca != 2){
                $oOp->Plan = $hn->Plan;
            }
            

            if($usaHNConcesionario){
                $oOp->TipoPlan = $hn->TipoPlan;
                $oOp->Cliente = $hn->Cliente;
                $oOp->Modelo = $hn->CodModelo;
                $oOp->NomModelo = $hn->NomModelo;
                $oOp->Avance = $hn->Avance;
                $oOp->CPG = $hn->CuotasPagas;
                $oOp->Empresa = $hn->Concesionario;

                $oOp->PrecioAutoActual = $hn->ValorAutoHoy;

                $oOp->CuotaTerminal = $hn->Precio;
              
                
                $oOp->Plazo = $hn->Plazo;
                $oOp->FechaVtoCuota = $hn->FechaVtoCuota;
                $oHN->ComproGiama = $hn->ComproGiama; 
                $oHN->TIRSpot = $hn->TIRSpot; 
                $oHN->Empresa = $hn->Empresa;
              
          
            }else{
                /*
                $oHN->Empresa = $oOp->Empresa;
                $oEmpresaOrigenOp = $oOp->Empresa;
*/
                $oHN->Empresa = 8;
                $oEmpresaOrigenOp = 8;    

                $itemBuscado = $this->searchGyOyMyE($list, $oOp->Grupo, $oOp->Orden, $oOp->Marca, $oOp->Empresa);
                
                $oHN->Duration = $hn->DurationCobro;
                $oHN->TIR = $hn->TIRCobro;
           //dd($itemBuscado);
           

            }
            $oOp->PrecioAutoActual = $hn->ValorAutoHoy;

            $oHN->Operacion = $oOp;
            $oHN->Id = $hn->Id;
            $oHN->ID_Dato = $hn->ID_Dato;
            $oHN->Rescindido = $hn->Rescindido;
            $oHN->Titular = $hn->Titular;
            $oHN->TipoCompra = $hn->TipoCompra;
            $oHN->GrupoTomaPlan = $hn->Grupo_TomaPlan;
            $oHN->OrdenTomaPlan = $hn->Orden_TomaPlan;
            $oHN->MontoCobroEstimado = $hn->MontoCobro;
            $oHN->FechaCobroReal = $hn->FechaCobroReal;
            $oHN->MontoCompra = $hn->MontoCompra;
            $oHN->FechaCompra = $hn->FechaAltaRegistro;
 
            $oHN->EnvioMailReclamoTerminal = $hn->EnvioMailReclamoTerminal;
            $oHN->HaberNetoSubite = $hn->HaberNetoSubite;
            $oHN->SeDesrescindio = $hn->SeDesrescindio;
            $oHN->Transferencia = $hn->Transferencia;
            $oHN->ConcesionarioPropio = $hn->ConcesionarioPropio;
           
           // $oHN->Empresa = $hn->Empresa;
            //$oHN->IdConcesinario = $hn->IdConcesinario;
            
            $oHN->TIRSpot = $hn->TIRSpot; 
            //$oHN->Empresa = $hn->Empresa;
            //$oHN->IdConcesinario = $hn->IdConcesinario;
            $oHN->Duration = $hn->DurationCobro;
            $oHN->TIR = $hn->TIRCobro;

            $oHN->MontoCompraDolares = $hn->MontoCompraDolares;
            $oHN->MontoCobroDolares = $hn->MontoCobroDolares;
            $oHN->HaberNetoSubiteUSD = $hn->HaberNetoSubiteUSD;
            $oHN->Utilidad =  $hn->UtilidadCobro;
            $oHN->UtilidadUSD =  $hn->UtilidadCobroDolares;

            $oHN->HaberNetoSubite = $hn->HaberNetoSubite;

            $oHN->UtilidadActual = $hn->UtilidadActual;
            $oHN->DurationActual = $hn->DurationActual;
            $oHN->DurationCompra = $hn->DurationCompra;
            $oHN->TIRActual = $hn->TIRActual;
            $oHN->FechaCuota84 = $hn->FechaCuota84;
           
            if($hn->ComproGiama == 1){
                array_push($listHN_C_ComproGiama, $oHN);
            }else{
                array_push($listHN_C_CE, $oHN);
            }

            array_push($listHNCobrados, $oHN);
        }

        $lista['NoEncontrados'] = $errObtenerOp;
        $lista['ListHNCobrados'] = $listHNCobrados;

        $lista['ListHN_C_ComproGiama'] = $listHN_C_ComproGiama;
        $lista['ListHN_C_CE'] = $listHN_C_CE;
        
       // dd($listHNCobrados);
        return $lista;
        //dd($listHN);
    }

    public function getHaberesNetosCobrados(Request $request){
       
        $marca = $request->Marca;
        $concesionario = $request->Concesionario;
        $rbConsolidado = $request->ConsolidadoRB;

        $listHNCobrados = array();
        $list = array();
        $lstModelos = array();
        $util = new UtilsController;

        $lista = array();

        $listHN_C_ComproGiama = array();
        $listHN_C_CE = array();

        $idConc = $concesionario;
       

        $emp1 = "AutoCervo";
        $db1= "AC";
        $emp2 = "AutoNet";
        $db2= "AN";
        $emp3 = "CarGroupFusion";
        $db3= "CG";
    

        //$db = "CG";
        $codEmpresa = 0;
        if ($marca == 2){

            switch($concesionario){
                case 4:
                    $db = "AC";
                    $codEmpresa = 6;
                break;
                case 5:
                    $db = "AN";
                    $codEmpresa = 3;
                break;
                case 6:
                    $db = "CG";
                    $codEmpresa = 8;
                break;
                case 8:
                    $db = "RB";
                    $codEmpresa = 10;
                break;
            }
            

            if ($db == 'RB' && $rbConsolidado){
                $haberesNetos_RB = array();
                $haberesNetos_GF = array();
    
                $haberesNetos_RB = DB::connection('RB')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> ''");
    
                $haberesNetos_GF = DB::connection('GF')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> '' AND ComproGiama = 1");
    
                $haberesNetos = array_merge($haberesNetos_RB, $haberesNetos_GF);
            }else{
                //$haberesNetos =  DB::connection($db)->table('haberesnetosok')->whereNotNull('FechaCobroReal')->get(); 
                $haberesNetos = HaberNeto::on($db)->whereNotNull('FechaCobroReal')->get();
            }
            $usaHNConcesionario = false;

        }else{

            if ($marca == 99){ //GIAMA
                $haberesNetos_RB = array();
                $haberesNetos_GF = array();
                $haberesNetos_AN = array();
                $haberesNetos_CG = array();

                $haberesNetos_RB = DB::connection('RB')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> ''");
                $haberesNetos_GF = DB::connection('GF')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> '' AND ComproGiama = 1");

                $haberesNetos_AN = DB::connection('AN')->select("SELECT *  FROM haberesnetosok WHERE OcultarHN <> 1 IFNULL(FechaCobroReal, '') <> ''");
                $haberesNetos_CG = DB::connection('CG')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> ''");

                $haberesNetos = array_merge($haberesNetos_RB, $haberesNetos_GF, $haberesNetos_AN, $haberesNetos_CG);

                $usaHNConcesionario = false;
            }else{

                $usaHNConcesionario = true;
                $db = "GF";
                $codEmpresa = 1;
                $haberesNetos = HaberNeto::on($db)->where('Concesionario', $concesionario)->whereNotNull('FechaCobroReal')->get();
            }
        }

        if ($codEmpresa == 0){
            $esInterempresa = true;
        }else{
            $esInterempresa = false;
        }

        $lstModelos = $this->getValoresAutoAFecha(now(), $codEmpresa);
        
        //dd($haberesNetos);

        $errObtenerOp = array();

        foreach ($haberesNetos as $hn) {
            $oHN = new \stdClass();
            $oOp = new \stdClass();

            $oHN->MontoCobroReal = $hn->MontoCobroReal;

            /*
            if ($oHN->MontoCobroReal > 0){
                continue;
            }
            */

            $oOp->Marca = $hn->Marca;
            $oOp->Concesionario = $hn->Concesionario;
            $oOp->Grupo = $hn->Grupo;
            $oOp->Orden = $hn->Orden;
            $oOp->Empresa = $hn->EmpresaOrigenGyO;
            
            if ($hn->Marca != 2){
                $oOp->Plan = $hn->Plan;
            }
            

            if($usaHNConcesionario){
                $oOp->TipoPlan = $hn->TipoPlan;
                $oOp->Cliente = $hn->Cliente;
                $oOp->Modelo = $hn->CodModelo;
                $oOp->NomModelo = $hn->NomModelo;
                $oOp->Avance = $hn->Avance;
                $oOp->CPG = $hn->CuotasPagas;
                $oOp->Empresa = $hn->Concesionario;

                $oOp->PrecioAutoActual = $hn->ValorAutoHoy;

                $oOp->CuotaTerminal = $hn->Precio;
              
                
                $oOp->Plazo = $hn->Plazo;
                $oOp->FechaVtoCuota = $hn->FechaVtoCuota;
                $oHN->ComproGiama = $hn->ComproGiama; 
                $oHN->TIRSpot = $hn->TIRSpot; 
                $oHN->Empresa = $hn->Empresa;
              
          
            }else{
                /*
                $oHN->Empresa = $oOp->Empresa;
                $oEmpresaOrigenOp = $oOp->Empresa;
*/
                $oHN->Empresa = 8;
                $oEmpresaOrigenOp = 8;    

                $itemBuscado = $this->searchGyOyMyE($list, $oOp->Grupo, $oOp->Orden, $oOp->Marca, $oOp->Empresa);
                
                $oHN->Duration = $hn->DurationCobro;
                $oHN->TIR = $hn->TIRCobro;
           //dd($itemBuscado);
           

            }
            $oOp->PrecioAutoActual = $hn->ValorAutoHoy;

            $oHN->Operacion = $oOp;
            $oHN->Id = $hn->Id;
            $oHN->ID_Dato = $hn->ID_Dato;
            $oHN->Rescindido = $hn->Rescindido;
            $oHN->Titular = $hn->Titular;
            $oHN->TipoCompra = $hn->TipoCompra;
            $oHN->GrupoTomaPlan = $hn->Grupo_TomaPlan;
            $oHN->OrdenTomaPlan = $hn->Orden_TomaPlan;
            $oHN->MontoCobroEstimado = $hn->MontoCobro;
            $oHN->FechaCobroReal = $hn->FechaCobroReal;
            $oHN->MontoCompra = $hn->MontoCompra;
            $oHN->FechaCompra = $hn->FechaAltaRegistro;
 
            $oHN->EnvioMailReclamoTerminal = $hn->EnvioMailReclamoTerminal;
            $oHN->HaberNetoSubite = $hn->HaberNetoSubite;
            $oHN->SeDesrescindio = $hn->SeDesrescindio;
            $oHN->Transferencia = $hn->Transferencia;
            $oHN->ConcesionarioPropio = $hn->ConcesionarioPropio;
           
           // $oHN->Empresa = $hn->Empresa;
            //$oHN->IdConcesinario = $hn->IdConcesinario;
            
            $oHN->TIRSpot = $hn->TIRSpot; 
            //$oHN->Empresa = $hn->Empresa;
            //$oHN->IdConcesinario = $hn->IdConcesinario;
            $oHN->Duration = $hn->DurationCobro;
            $oHN->TIR = $hn->TIRCobro;

            $oHN->MontoCompraDolares = $hn->MontoCompraDolares;
            $oHN->MontoCobroDolares = $hn->MontoCobroDolares;
            $oHN->HaberNetoSubiteUSD = $hn->HaberNetoSubiteUSD;
            $oHN->Utilidad =  $hn->UtilidadCobro;
            $oHN->UtilidadUSD =  $hn->UtilidadCobroDolares;

            $oHN->HaberNetoSubite = $hn->HaberNetoSubite;

            $oHN->UtilidadActual = $hn->UtilidadActual;
            $oHN->DurationActual = $hn->DurationActual;
            $oHN->DurationCompra = $hn->DurationCompra;
            $oHN->TIRActual = $hn->TIRActual;
            $oHN->FechaCuota84 = $hn->FechaCuota84;
           
            if($hn->ComproGiama == 1){
                array_push($listHN_C_ComproGiama, $oHN);
            }else{
                array_push($listHN_C_CE, $oHN);
            }

            array_push($listHNCobrados, $oHN);
        }

        $lista['NoEncontrados'] = $errObtenerOp;
        $lista['ListHNCobrados'] = $listHNCobrados;

        $lista['ListHN_C_ComproGiama'] = $listHN_C_ComproGiama;
        $lista['ListHN_C_CE'] = $listHN_C_CE;
        
       // dd($listHNCobrados);
        return $lista;
        //dd($listHN);
    }

    public function getValoresUSD($fcomp, $fcob){
            //
    }

    public function searchGyOyMyE($list, $gru, $ord, $mar, $emp){

        // dd($list);
 
         $filtered_array = array_filter($list, function($val) use($gru, $ord, $mar, $emp){
             return ($val->Grupo==$gru and $val->Orden==$ord and $val->Marca==$mar and $val->empresa==$emp);
         });
 
         if ($filtered_array){
             return array_values($filtered_array);
         }else{
            return false;
         }
     }

    public function getValoresAutoAFecha($fecha, $codEmp){
        $lstM = array();
        //dd($fecha);
        $fecha = "20200702";
        $resModelos = DB::select("CALL hnweb_getvaloresautofecha(".$fecha.");");

        foreach ($resModelos as $r) {
            $mod =new \stdClass();

            $mod->Marca = $r->Marca;
            $mod->Codigo = $r->Modelo;
            $mod->CuotaTerminal = $r->Precio;
            $mod->Empresa = $codEmp;

            array_push($lstM, $mod  );
        } 

        return $lstM;
    }

    public function getHN($codEmp, $esVigentes, $lstOp, $lstModelos, $idConc){

    }

    public function buscarOp(Request $request){

        $busqueda = array();
        switch($request->Marca){
            case 2:
                switch($request->Concesionario){
                    case 4:
                        $db = 'AC';
                    break;
                    case 5:
                        $db = 'AN';
                    break;
                    case 6:
                        $db = 'CG';
                    break;
                    case 8:
                        $db = 'RB';
                    break;
                    default:
                        $db = 'GF';
                    break;
                }

                if (isset($request->Grupo)){
                    $request->Solicitud = 'NULL';   
                }else{
                    $request->Grupo = 'NULL';
                    $request->Orden = 'NULL';
                }

                if ($db == 'GF'){
                    $busqueda = DB::select("CALL hnweb_buscaop_v2(".$request->Marca.", ".$request->Concesionario.", ".$request->Solicitud.", ".$request->Grupo.", ".$request->Orden.");");
                }else{
                    $busqueda = DB::connection($db)->select("CALL hnweb_buscaop_v2(".$request->Marca.", ".$request->Solicitud.", ".$request->Grupo.", ".$request->Orden.");");
                }
              
            break;
            default:
                if (isset($request->Grupo)){
                    $request->Solicitud = 'NULL';   
                }else{
                    $request->Grupo = 'NULL';
                    $request->Orden = 'NULL';
                }
                $busqueda = DB::select("CALL hnweb_buscaop_v2(".$request->Marca.", ".$request->Concesionario.", ".$request->Solicitud.", ".$request->Grupo.", ".$request->Orden.");");
            break;
        }

        return $busqueda;
      
    }


    public function grabarHN(Request $request){

        $hn = array();

        if (!isset($request->MontoCobroEstimado)){
            $request->MontoCobroEstimado = "NULL";
        }

        switch($request->Marca){
            case 2: //FIAT
                $porcentajeComision = 0.06;

                $empGyO = $request->EmpresaGyO;
                
                switch($request->Concesionario){
                    case 4: //AutoCervo
                        $db = 'AC';
                        $porcentajeComision = 0.06;
                      //  $empGyO = 6;
                    break;
                    case 5: //AutoNet
                        $db = 'AN';
                        $porcentajeComision = 0.06;
                       // $empGyO = 3;
                    break;
                    case 6: //Car Group
                        $db = 'CG';
                        $porcentajeComision = 0.06;
                       // $empGyO = 8;
                    break;
                    case 8: //RB
                        $db = 'RB';
                        $porcentajeComision = 0.06;
                        //$empGyO = 10;
                    break;
                    default:
                        $db = 'GF';
                        $porcentajeComision = 0.06;

                        $empGyO = 'NULL';
                    break;
                }
                
                

            break;
            case 3: //PEU
                $porcentajeComision = 0.06;

                $db = 'GF';
                $empGyO = 'NULL';
                $request->NroTransferencia = 'NULL';
            break;
            case 5: //VW
                $porcentajeComision = 0.08;

                switch($request->Concesionario){
                    case 1: //Sauma
                    case 2: //Iruña
                    case 3: //Amendola
                        $porcentajeComision = 0.08;
                    break;
                    case 7: //Luxcar es el 8 + 12 = 20%
                        //$porcentajeComision = 0.2;
                        // LuxCar pasa a tener solo el 8% de comision, ya no es 8% + 12%
                        $porcentajeComision = 0.08;
                    break;

                }
                $db = 'GF';
                $empGyO = 'NULL';
                $request->NroTransferencia = 'NULL';
            break;
            case 9: //FORD
                $porcentajeComision = 0.08;

                switch($request->Concesionario){
                    case 9: //SAPAC
                        $porcentajeComision = 0.08;
                    break;

                }
                $db = 'GF';
                $empGyO = 'NULL';
                $request->NroTransferencia = 'NULL';
            break;

        }

        $comisionHN = $request->HaberNetoSubite * $porcentajeComision;
        $montoCompraConComision = $request->MontoCompra + $comisionHN;

        $hn = DB::connection($db)->select("CALL hnweb_haberesnetos('C', NULL, ".$request->ID_Dato.", ".$request->Marca.", ".$request->Concesionario.", ".$request->Grupo.", "
        .$request->Orden.", ".$empGyO.", ".$request->Titular.", ".$request->TipoCompra.", NULL, NULL, 0, ".$montoCompraConComision.", "
        .$request->MontoCobroEstimado.", NULL, NULL, ".$request->HaberNetoSubite.", ".$request->HaberNetoOriginal.", NULL, '".$request->Login."', ".$request->NroTransferencia.", 0, ".$request->ComproGiama.",".$request->TitularCompraHN.", ".$request->TIRSpot.");");
        
        if ($hn){
            if ($request->Concesionario == 8){
                $hn_calc = DB::connection($db)->select("CALL hnweb_set_variables_hn(".$request->Marca.", ".$request->Concesionario.", ".$empGyO.", ".$request->ID_Dato.", ".$montoCompraConComision.", ".$request->HaberNetoSubite.");");
            }else{
                $hn_calc = DB::connection($db)->select("CALL hnweb_set_variables_hn(".$request->Marca.", ".$request->Concesionario.", ".$request->ID_Dato.", ".$montoCompraConComision.", ".$request->HaberNetoSubite.");");
            }

            
            $result = DB::select("CALL hnweb_set_historial_datos(4, ".$request->ID_Dato.
            ", ".$request->Concesionario.
            ", ".$request->Grupo.', '.$request->Orden.
            ", NULL, NULL, 
            NULL, NULL, 
            NULL, NULL, NULL, 
            NULL, NULL, NULL, 1, ".$empGyO.");");
            
            /*
            if ($request->Concesionario == 8){
                $hist_id = HistoricoCompra::where('ID_Dato', $request->ID_Dato)->where('Grupo', $request->Grupo)->where('Orden', $request->Orden)->orderBy('ID', 'desc')->take(1)->get();
            }else{
                $hist_id = HistoricoCompra::where('ID_Dato', $request->ID_Dato)->where('Concesionario', $request->Concesionario)->orderBy('ID', 'desc')->take(1)->get();
            }
           
        
            if ($hist_id){
                
                $id = $hist_id[0]->ID;
                
                $hist = HistoricoCompra::where('ID', '=', $id)->firstOrFail();
                $hist->Vendido = 1;

                $hist->save();
            }
            */

            $hn = $hn_calc;
        }
       
        return $hn;
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