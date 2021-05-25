<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use DB;
Use Session;
Use Auth;
Use Redirect;
use App\Estado;
use App\Concesionario;
use App\MotivoCaida;
use App\CotizacionDolar;
use App\CotizacionDolarCCL;
use App\EscalaCalculo;
use App\ParametroCalculo;
use App\CodigoPlan;
use App\ListaPrecio;
use App\Precio;

class UtilsController extends Controller
{

    public function getNameConcesionario($concesionario){

        return Concesionario::select('Nombre')->where('ID',$concesionario)->get();

    }

    public function getCodeNameConcesionariosFacturacion(){

        //return Concesionario::select('ID','Nombre')->whereNotIn('ID', [8,11,12])->get();
        return Concesionario::select('ID','Nombre')->whereNotNull('OrdenamientoFacturacion')->orderBy('OrdenamientoFacturacion','asc')->get();

    }

    public function getDabaseName($marca, $concesionario){

        switch($marca){
            case 2:
                switch($concesionario){
                    case 4:
                        return 'AC';
                    break;
                    case 5:
                        return 'AN';
                    break;
                    case 6:
                        return 'CG';
                    break;
                    case 8:
                        return 'RB';
                    break;
                }
            break;
            default:
                return 'GF';
            break;
        }
    }

    public function getDabaseNameByCE($concesionario){

        switch($concesionario){
            case 4:
                return 'AC';
            case 5:
                return 'AN';
            case 6:
                return 'CG';
            case 8:
                return 'RB';
            default:
                return 'GF';
        }
    }

    public function checkEsPropioOtraSoc($apellido, $nombre){
        
        $apeLow = strtolower($apellido);
        $nomLow = strtolower($nombre);

        if (
            (strpos($apeLow,"volkswagen argentina sa") !== false) ||
            (strpos($apeLow,"volkswagen argentina s.a.") !== false) ||
            (strpos($apeLow,"autokar") !== false) ||
            (strpos($apeLow,"autotag") !== false) ||
            (strpos($apeLow,"autofinancia") !== false) ||
            (strpos($apeLow,"auto financia") !== false) ||    
            (strpos($nomLow ,"autokar") !== false )||
            (strpos($nomLow,"autotag") !== false) ||
            (strpos($nomLow ,"autofinancia") !== false) ||
            (strpos($nomLow ,"auto financia") !== false) ||
            (strpos($apeLow,"iruña") !== false) ||
            (strpos($apeLow,"iruna") !== false) ||
            (strpos($apeLow,"sapac") !== false) ||
            (strpos($apeLow,"luxcar") !== false) ||
            (strpos($apeLow,"car group") !== false) ||
            (strpos($apeLow,"car gruop") !== false) ||
            (strpos($apeLow,"autonet") !== false) ||
            (strpos($apeLow,"mdplanes") !== false) ||
            (strpos($apeLow, "gestion financiera") !== false) ||
            (strpos($apeLow,"margian") !== false) ||
            (strpos($apeLow,"ricardo bevacqua") !== false) ||
            (strpos($apeLow,"bevacqua ricardo") !== false) ||
            (strpos($nomLow,"luxcar") !== false) ||
            (strpos($nomLow ,"car group") !== false) ||
            (strpos($nomLow ,"car gruop") !== false )||
            (strpos($nomLow ,"autonet") !== false) ||
            (strpos($nomLow ,"mdplanes") !== false) ||
            (strpos($nomLow ,"gestion financiera") !== false) ||
            (strpos($nomLow ,"margian") !== false) ||
            (strpos($nomLow ,"ricardo bevacqua") !== false) ||
            (strpos($nomLow ,"bevacqua ricardo") !== false) ||
            ((strpos($nomLow ,"ricardo") !== false) && (strpos($apeLow,"bevacqua") !== false))
        ){
            return true;
        }else{
            return false;
        }

    }

    public function getPrimerDiaPeriodo($periodo){
        $periodoMes = substr($periodo, 4, strlen($periodo));
        $periodoAnio = substr($periodo, 0, 4);

        $periodoDia = '01';

        if (strlen($periodoMes) == 1){
            $periodoMes = "0".$periodoMes;
        }

        return $periodoAnio.$periodoMes.$periodoDia;
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

        return $periodoAnio.$periodoMes.$periodoDia;
    }


	public function reversarFecha($fecha, $style)
    {
        switch ($style) {
            case 'DB':
                $date = implode("", array_reverse(explode("/", $fecha)));
            break;
            case 'FE':
                $date = implode("/", array_reverse(explode("/", $fecha)));
            break;
        }
        return $date;
    }

    public function getNombreEstado($codEstado){
        $nombre = Estado::where('Codigo', $codEstado)->pluck('Nombre');
        return $nombre;
    }

    public function getNombreMotivo($codMotivo){
        switch($codMotivo){
            case 0:
                $nombre = 'Espera el cobro';
            break;
            case 1:
                $nombre = 'Conflicto';
            break;
            case 2:
                $nombre = 'Llamar mas adelante';
            break;
            default:
                $nombre = '';
            break;
        }
        return $nombre;
    }

    public function getNombreMotivoCaida($codMotivoC){
        $nombre = MotivoCaida::where('Codigo', $codMotivoC)->pluck('Nombre');
        return $nombre;
    }

    public function getComisionALiquidar($precioCompra, $precioMaximoCompra){

        $comision = ($precioMaximoCompra - $precioCompra) * 0.1;

        if ($comision < 250){
            $comision = 250;
        }
        return $comision;
    }

    public function getPrecioMaximoCompra($avance, $haberNeto){
        switch($avance){

            case  ($avance <= 44):
                return $haberNeto * 0.2;
            break;
            case  (45 <= $avance) && ($avance <= 61):
                return $haberNeto * 0.2;
            break;
            case (62 <= $avance) && ($avance <= 66):
                return $haberNeto * 0.3;
            break;
            case (67 <= $avance) && ($avance <= 69):
                return $haberNeto * 0.35;
            break;
            case (70 <= $avance) && ($avance <= 79):
                return $haberNeto * 0.4;
            break;
            case (80 <= $avance) && ($avance <= 83):
                return $haberNeto * 0.5;
            break;
            default:
            return 0;
        break;
        }

    }

    public function getAvanceAutomatico($FechaVtoCuota2){

        $avance = 0;

        $fvto2 = strtotime($FechaVtoCuota2);    

        $fecha = strtotime(now());

        if ($FechaVtoCuota2 === NULL){
            return 0;
        }else{
            $fvtoc2 = date_create(date('Y-m-d', $fvto2));
            $ff = date_create(date('Y-m-d', $fecha));       
    
            if (checkdate(date('m', $fvto2), date('d', $fvto2), date('Y', $fvto2))){
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

    public function getCotizacionDolarPeriodos($tipo, $anio){

        $keys = array('M1', 'M2', 'M3', 'M4', 'M5', 'M6', 'M7', 'M8', 'M9', 'M10', 'M11', 'M12', 'Total');

        $cotizaciones = array();
        $cotizaciones = array_fill_keys($keys, 0);

        $anioActual = date("Y");

        if ($anio < date("Y")){
            for ($m=1; $m < 13; $m++) { 
                $periodo = $anio.$m;
                if ($tipo == 'CCL'){
                     $cot = CotizacionDolarCCL::select('CotizacionVenta','CotizacionCompra')->where('Fecha', '=', $this->getUltimoDiaPeriodo($periodo))->get();  
                }else{
                    $cot = CotizacionDolar::select('CotizacionVenta','CotizacionCompra')->where('Fecha', '=', $this->getUltimoDiaPeriodo($periodo))->get();
                }
                if (isset($cot[0]->CotizacionVenta) && isset($cot[0]->CotizacionCompra)){
                    $cotizaciones['M'.$m] = round(($cot[0]->CotizacionVenta + $cot[0]->CotizacionCompra) / 2, 2);
                }
                
            }
            return $cotizaciones;
        }
        
        for ($m=1; $m < date('m'); $m++) { 
            $periodo = $anio.$m;
            if ($tipo == 'CCL'){
                $cot = CotizacionDolarCCL::select('CotizacionVenta','CotizacionCompra')->where('Fecha', '=', $this->getUltimoDiaPeriodo($periodo))->get();
            }else{
                $cot = CotizacionDolar::select('CotizacionVenta','CotizacionCompra')->where('Fecha', '=', $this->getUltimoDiaPeriodo($periodo))->get();
            }
            if (isset($cot[0]->CotizacionVenta) && isset($cot[0]->CotizacionCompra)){
                $cotizaciones['M'.$m] = round(($cot[0]->CotizacionVenta + $cot[0]->CotizacionCompra) / 2, 2);
            }
        }
        //Agrego la ultima fehca de contizacion del mes actual
        if ($tipo == 'CCL'){
            $cot = CotizacionDolarCCL::select('CotizacionVenta','CotizacionCompra')->whereYear('Fecha', '=', date("Y"))->whereMonth('Fecha', '=', date("m"))->orderBy('Fecha', 'desc')->take(1)->get();
        }else{
            $cot = CotizacionDolar::select('CotizacionVenta','CotizacionCompra')->whereYear('Fecha', '=', date("Y"))->whereMonth('Fecha', '=', date("m"))->orderBy('Fecha', 'desc')->take(1)->get();
        }
        
        if (isset($cot[0]->CotizacionVenta) && isset($cot[0]->CotizacionCompra)){
            $cotizaciones['M'.date("m")] = round(($cot[0]->CotizacionVenta + $cot[0]->CotizacionCompra)/ 2, 2);
        }
        
        return $cotizaciones;
    }

    public function getCotizacionesDolar($tipo, $anio){

        if ($tipo == 'CCL'){
            return CotizacionDolarCCL::whereYear('Fecha', '=', $anio)->get();
        }else{
            return CotizacionDolar::whereYear('Fecha', '=', $anio)->get();
        }

    }

    public function getListasPrecios($marca, $anio){
        return ListaPrecio::where('Marca', '=', $marca)->whereYear('VigenciaDesde', '>=', $anio)->whereYear('VigenciaHasta', '<=', $anio)->get();
    }

    //public function getPrecioLista(){
    public function getPrecioLista($marca, $idLista, $idModelo){
        //$marca = 5; 
        //$idLista = 157;
        //$idModelo = 199;
        return Precio::where('Marca', '=', $marca)->where('Codigo', '=', $idLista)->where('CodigoModelo', '=', $idModelo)->get();
    }

    public function searchCotizacionDolar($fecha, $cotiz){
        return $cotiz->where('Fecha', '=', $fecha);
    }

    public function getEscalas($marca){
       return EscalaCalculo::where('Marca', '=', $marca)->get();
    }

    public function getParametrosCalculo($marca){
        return ParametroCalculo::where('Marca', '=', $marca)->get();
    }

    public function getParametrosPlanes($marca){
        return CodigoPlan::where('Marca', '=', $marca)->get();
    }

    public function getValorHaberNeto($marca, $plan, $cpg, $cad, $valorMovil){

        /*
        $marca = 5;
        $plan = '1A';
        $cpg = 25;
        $cad = 0;
        //$avance = 75;
        $valorMovil = 1230150;
        */

        //dd($plan);
        //dd($cpg);
        //dd($cad);
        //dd($valorMovil);

        $hnCalculado = 0;
        $ecalasMarcas = $this->getEscalas($marca);
        $parametrosMarcas = $this->getParametrosCalculo($marca)->first();
        $parametrosPlanes = $this->getParametrosPlanes($marca);


        $escalasPlan = $ecalasMarcas->where('Plan', '=', $plan);
        $parametrosPlan = $parametrosPlanes->where('Plan', $plan)->first();

        

        $diferencia = 0;
        $cantTot = 0;
        $cuotasReales = 0;
        $resto = $cpg;

        
        if ($cpg > 0){
            foreach ($escalasPlan as $escalaP) {
            
                if ($diferencia < 0){
                break;
                }

                $diferencia = $resto - $escalaP->CantidadCuotasEscala;

                if ($diferencia > 0 || $diferencia == 0){
                    $cuotasReales = $cuotasReales + ($escalaP->CantidadCuotasEscala * (1 + $escalaP->DifRecup));
                    $resto = $diferencia;
                }else{
                    if ($diferencia < 0){
                        $cuotasReales = $cuotasReales + ($resto * (1 + $escalaP->DifRecup));
                    }
                }
            
                if ($escalaP->DescuentoEscala != null){  
                    $cuotasReales = $cuotasReales * $escalaP->DescuentoEscala;
                }

            }
        }

    
        $resto = $cad;
        $diferencia = 0;
        
        if ($cad > 0){
            foreach ($escalasPlan as $escalaP) {
                if ($diferencia < 0){
                break;
                }
                $diferencia = $resto - $escalaP->CantidadCuotasEscala;
    
                if ($diferencia > 0){
                    $cuotasReales = $cuotasReales + ($escalaP->CantidadCuotasEscala * (1 + $escalaP->DifRecup));
                    $resto = $diferencia;
                }else{
                    if ($diferencia < 0){
                        $cuotasReales = $cuotasReales + ($resto * (1 + $escalaP->DifRecup));
                    }
                }
         
                if ($escalaP->DescuentoEscala != null){  
                    $cuotasReales = $cuotasReales * $escalaP->DescuentoEscala;
                }
            }
        }
        

        $cuotasTotales = $cpg + $cad;
        $porcPlan = 0;

        $tipoPlan = $parametrosPlan->TipoPlan;
        $plazo = $parametrosPlan->Plazo;
        $cuotaProrr = $parametrosPlan->CuotaProrrDerechoAdm;

        $derechoAdm = $parametrosMarcas->DerechoAdmision;
        $penalidad = $parametrosMarcas->Penalidad;
        $valorMovilPago = $parametrosMarcas->ValorMovilPago;
        $cargosAdm = $parametrosMarcas->CargosAdministrativos;

        switch($tipoPlan){
            case 1:
                $porcPlan = 1;
            break;
            case 2:
                $porcPlan = 0.7;
            break;
            case 3:
                $porcPlan = 0.6;
            break;
            case 4:
                $porcPlan = 0.8;
            break;
            case 6:
                $porcPlan = 0.5;
            break;
        }


        if ($cuotasTotales > $cuotaProrr){
            $terminoDerAdm = 0;
        }else{
            $terminoDerAdm = ((($valorMovil * $derechoAdm) / $cuotaProrr) * ($cuotaProrr - $cuotasTotales));
        }
        
        $hnMovil = ((($valorMovil / $plazo) * $cuotasTotales) * $porcPlan) * 0.95; //Esto es el 5% de Descuento, hay que parametrizarlo

        $hnMovil = round($hnMovil,2);

        $cuotasReales = round($cuotasReales, 2);

        $hnProrrateo = ((($valorMovil / $plazo) * ($cuotasTotales - $cuotasReales) * $porcPlan));

        $hnProrrateo = round($hnProrrateo, 2);

        $hnFormula = $hnMovil - $hnProrrateo;

        $hnProrrateo = round($hnProrrateo, 2);

        $terminoDerAdm = round($terminoDerAdm, 2);

        $hnReal = $hnFormula - ($terminoDerAdm + ($penalidad * $hnFormula) + ($cargosAdm * $hnFormula));
        
        $hnReal = round($hnReal,0);


        return $hnReal;
    
    }

    //Public function getValoresUSD(ByRef i As cls_HN_Item, ByRef oError As cls_Error)
    public function getValoresUSD(&$i){

             
        $ayer = date(strtotime("yesterday"));
        $hoy = date(strtotime("today")); 

        $fechaCompra = $i->FechaCompra;   
        $fechaCobro = $i->FechaCobro;

        if ($fechaCompra == $hoy){
            $fechaCompra = $ayer;
        } 
        $fechaCompra = strtotime($fechaCompra);

        if ($fechaCobro == $hoy){
            $fechaCobro = $ayer;
        } 
        
        if (!is_null($i->FechaCobro)){
            $fechaCobro = strtotime($fechaCobro);
        }
     
       $arrCotiz = DB::select('SELECT DATE_FORMAT(Fecha,"%Y-%m-%d") AS Fecha, CotizacionCompra, CotizacionVenta FROM cotizaciondolarhistorico;');
        
       if (isset($fechaCompra)){
            for ($p = 0; $p < 5; $p++) {

                if ($p > 0){
                    $fechaCompra = strtotime("yesterday");
                }
                
                if ($this->getCompraUSD($fechaCompra, $i, $arrCotiz)){
                break;
                }

            }
            
        }else{
            $i->MontoCompraDolares = 0;
            
        }

        if (isset($fechaCobro)){
            $f = $fechaCobro;
            for ($p = 0; $p < 5; $p++) {
                if ($p > 0){
                    $f = strtotime("yesterday");
                }
                
                if ($this->getCobroUSD($f, $i, $arrCotiz)){
                break;
                }
            }
        }else{
            $i->MontoCobroDolares = 0;
        }

        if (!is_null($i->FechaCobro)){
            $f = $fechaCobro;
        }else{
            $f = strtotime("yesterday");
        }

        for ($p = 0; $p < 5; $p++) {
            if ($p > 0){
                $f = strtotime("yesterday");
            }
            
            if ($this->getHNUSD($f, $i, $arrCotiz)){
            break;
            }
        }

    }


    public function getCompraUSD($_FechaCompra, &$i, $listCotiz){

        if (strtotime($_FechaCompra)){
            $Fecha = $_FechaCompra;

            $cotizBuscada = $this->searchCotizacionUSD($listCotiz, $Fecha);

            if ($cotizBuscada){
                $promCotiz = ($cotizBuscada[0]->CotizacionCompra + $cotizBuscada[0]->CotizacionVenta) / 2;
                if ($promCotiz > 0){
                    $i->MontoCompraDolares = $i->MontoCompra / $promCotiz; 
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function getCobroUSD($_FechaCobro, &$i, $listCotiz){

        if (strtotime($_FechaCobro)){
            $Fecha = $_FechaCobro;

            $cotizBuscada = $this->searchCotizacionUSD($listCotiz, $Fecha);
        
            if ($cotizBuscada){
                $promCotiz = ($cotizBuscada[0]->CotizacionCompra + $cotizBuscada[0]->CotizacionVenta) / 2;
                if ($promCotiz > 0){
                    $i->MontoCobroDolares = $i->MontoCobroReal / $promCotiz; 
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }    
    }

    public function getHNUSD($_Fecha, &$i, $listCotiz){

        if (strtotime($_Fecha)){
            $Fecha = $_Fecha;
    
            $cotizBuscada = $this->searchCotizacionUSD($listCotiz, $Fecha);
    
            if ($cotizBuscada){
                $promCotiz = ($cotizBuscada[0]->CotizacionCompra + $cotizBuscada[0]->CotizacionVenta) / 2;
                if ($promCotiz > 0){
                    $i->HaberNetoSubiteUSD = $i->HaberNetoSubite / $promCotiz;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    

    public function searchCotizacionUSD($listCotiz, $fecha){

        if (strtotime($fecha)){
            $fecha = date('Y-m-d',$fecha);
            //dd($fecha);
            $filtered_array = array_filter($listCotiz, function($val) use($fecha){
                return ($val->Fecha==$fecha);
            });
    
            if ($filtered_array){
                return array_values($filtered_array);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
        
}