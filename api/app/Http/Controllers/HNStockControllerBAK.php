<?php

namespace App\Http\Controllers;

use App\HaberNeto;
use Illuminate\Http\Request;
use DB;
use DateTime;
use App\Http\Controllers\UtilsController;
use ArrayObject;

class HNStockController extends Controller
{
 


    
    public function getListHNStock(Request $request){

        $lstStock = array();

        $anio = $request->Anio;

        $marca = $request->Marca;
        $concesionario = $request->Concesionario;
        $filtros = $request->Filtros;
        $rbConsolidado = $request->ConsolidadoRB;

        $lstItemsHN_Stock = $this->getItemsStock($anio, $marca, $concesionario, $filtros, $rbConsolidado);
        
        $lstMeses = $this->getStockMeses($anio, $lstItemsHN_Stock['ListHN']);
        $lstMeses_CE = $this->getStockMeses($anio, $lstItemsHN_Stock['ListHN_CE']);

        $lstMesesStock = $this->getStockMesesStock($anio, $lstItemsHN_Stock['ListHN'], $lstMeses['lstStock'], $lstMeses['SaldoCaja']); 
        $lstMesesStock_CE = $this->getStockMesesStock($anio, $lstItemsHN_Stock['ListHN_CE'], $lstMeses_CE['lstStock'], $lstMeses_CE['SaldoCaja']);    

        $lstMesesRent = $this->getStockMesesRent($anio, $lstItemsHN_Stock['ListHN'], $lstMesesStock['lstStockFinal'], $lstMesesStock['CostoStock'], $lstMesesStock['DurationStock'], $lstMesesStock['DurationStock_TIR']);
        $lstMesesRent_CE = $this->getStockMesesRent($anio, $lstItemsHN_Stock['ListHN_CE'], $lstMesesStock_CE['lstStockFinal'], $lstMesesStock_CE['CostoStock'], $lstMesesStock_CE['DurationStock'], $lstMesesStock_CE['DurationStock_TIR']);


        $lstStock['Grid1'] = $lstMeses['lstMeses'];
        $lstStock['Grid2'] = $lstMesesStock['lstMeseStock'];
        $lstStock['Grid3'] = $lstMesesRent;

        $lstStock['Grid1_CE'] = $lstMeses_CE['lstMeses'];
        $lstStock['Grid2_CE'] = $lstMesesStock_CE['lstMeseStock'];
        $lstStock['Grid3_CE'] = $lstMesesRent_CE;
 
        //dd($lstStock);
        return $lstStock;

    }

    public function getItemsStock($anio, $marca, $concesionario, $filtros, $rbConsolidado){
    
        $respuesta = array(); 
        $list = array();
        $lstHN_CE = array();
        $lstHN = array();

        if ($marca == 2){
            switch($concesionario){
                case 4:
                    $db = "AC";
                break;
                case 5:
                    $db = "AN";
                break;
                case 6:
                    $db = "CG";
                break;
                case 8:
                    $db = "RB";
                break;
            }
            
        }else{
            $db = "GF";
        }

        if ($db == 'RB' && $rbConsolidado){
            $lstHN_RB = array();
            $lstHN_GF = array();

            $lstHN_RB = DB::connection('RB')->select("SELECT *  FROM haberesnetosok WHERE Concesionario = ".$concesionario);

            $lstHN_GF = DB::connection('GF')->select("SELECT *  FROM haberesnetosok WHERE ComproGiama = 1");

            $lstHN = array_merge($lstHN_RB, $lstHN_GF);
        }else{
            $lstHN = HaberNeto::on($db)->where('Concesionario', $concesionario)->get();
            $lstHN_CE = HaberNeto::on($db)->where('Concesionario', $concesionario)->where('ComproGiama', 0)->get();
        }
        //->whereYear('FechaAltaRegistro', '=', $anio)->orWhereYear('FechaCobroReal', '=', $anio)->get();

        //return $lstHN;
        $list['ListHN'] = $lstHN;
        $list['ListHN_CE'] = $lstHN_CE;

        return $list;
    }


    public function getStockMesesRent($anio, $listItems, $listStockFinal, $listCostoStock, $listDurationStock, $listDurationStockTIR){
        $respuesta = array();
        $listStockRent = array();

        $keys = array('M1', 'M2', 'M3', 'M4', 'M5', 'M6', 'M7', 'M8', 'M9', 'M10', 'M11', 'M12', 'Total');

        $lstRentTotal = array();
        $lstRentTotal = array_fill_keys($keys, 0);

        $lstDuration = array();
        $lstDuration = array_fill_keys($keys, 0);

        $lstTIR = array();
        $lstTIR = array_fill_keys($keys, 0);

        $ffHoy = new DateTime('NOW');

        if ($anio < $ffHoy->format("Y")){
            $mesBorde = 12;
        }else{
            $mesBorde = $ffHoy->format("n");
        }
      
        $listStockFinal_Acotado = array();
        $listCostoStock_Acotado = array();

        //for ($i=1; $i < 13; $i++) {
        for ($i=1; $i <= $mesBorde; $i++) { 
            if ($listCostoStock['M'.$i] > 0){
                $lstRentTotal['M'.$i] = ($listStockFinal['M'.$i] / $listCostoStock['M'.$i]) - 1;
                
            }else{
                $lstRentTotal['M'.$i] = 0;
            }

            if($listDurationStockTIR['M'.$i] > 0){
                $lstTIR['M'.$i] = $lstRentTotal['M'.$i] / $listDurationStockTIR['M'.$i];
            }else{
                $lstTIR['M'.$i] = 0;
            }
           
            //$lstRentTotal['Total'] += $lstRentTotal['M'.$i];

            $listStockFinal_Acotado['M'.$i] = $listStockFinal['M'.$i];
            $listCostoStock_Acotado['M'.$i] = $listCostoStock['M'.$i];
           
        }

        $lstRentTotal['Total'] += $lstRentTotal['M'.$mesBorde];

        if ($listDurationStockTIR['Total'] > 0){
            $lstTIR['Total'] = $lstRentTotal['Total'] / $listDurationStockTIR['Total'];
        }
        

        $listStockFinal_Acotado['Total'] = $listStockFinal['M'.$mesBorde];
        $listCostoStock_Acotado['Total'] = $listCostoStock['M'.$mesBorde];
        

        $arrStockFinal['Tipo'] = 'Tipo de Cambio Período';
        //$arrStockFinal['Valores'] = $listStockFinal;
        $arrStockFinal['Valores'] = $listStockFinal_Acotado;
        array_push($listStockRent, $arrStockFinal);

        $arrCostoStock['Tipo'] = 'Aumento Precios';
        //$arrCostoStock['Valores'] = $listCostoStock;
        $arrCostoStock['Valores'] = $listCostoStock_Acotado;
        array_push($listStockRent, $arrCostoStock);

        /*
        $arrRentTotal['Tipo'] = 'Rent. Total';
        $arrRentTotal['Valores'] = $lstRentTotal;
        array_push($listStockRent, $arrRentTotal);

        $arrDuration['Tipo'] = 'Duration';
        $arrDuration['Valores'] = $listDurationStock;
        array_push($listStockRent, $arrDuration);
    
        $arrTIR['Tipo'] = 'TIR';
        $arrTIR['Valores'] = $lstTIR;
        array_push($listStockRent, $arrTIR);
        */

        return $listStockRent;

    }

    public function getStockMesesStock($anio, $listItems, $listStock, $listSaldoCaja){


        $respuesta = array();
        $listStock = array();

        $keys = array('M1', 'M2', 'M3', 'M4', 'M5', 'M6', 'M7', 'M8', 'M9', 'M10', 'M11', 'M12', 'Total');
        $keysNum = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);

        $lstStockInicial = array();
        $lstStockInicial = array_fill_keys($keys, 0);

        $lstStockFinal = array();
        $lstStockFinal = array_fill_keys($keys, 0);

        $lstVerificacion = array();
        $lstVerificacion = array_fill_keys($keys, 0);
  
        $lstCostoStock = array();
        $lstCostoStock = array_fill_keys($keys, 0);

        $lstCantCasos = array();
        $lstCantCasos = array_fill_keys($keys, 0);

        $pasada = 0;

        $ffHoy = new DateTime('NOW');

        $Ene = array();
        $Feb = array();
        $Mar = array();
        $Abr = array();
        $May = array();
        $Jun = array();
        $Jul = array();
        $Ago = array();
        $Sep = array();
        $Oct = array();
        $Nov = array();
        $Dic = array();

        $lstArr = array();

        $lstResumen = new ArrayObject($lstArr);
        
        $lstDurationPonderadas = array();
        $lstDurationPonderadas = array_fill_keys($keys, 0);

        

        $lstDuration = array();
        $lstDuration = array_fill_keys($keys, 0);
        $lstPonderaciones = array();
        $lstPonderaciones = array_fill_keys($keys, 0);

        $lstPonderacionesCompra= array();
        $lstPonderacionesCompra = array_fill_keys($keys, 0);

        $lstPonderaciones_Acotadas = array();
        $lstPonderaciones_Acotadas = array_fill_keys($keys, 0);

        $lstPonderacionesCompra_Acotadas = array();
        $lstPonderacionesCompra_Acotadas = array_fill_keys($keys, 0);

        foreach ($listItems as $it) {


            if (!is_null($it->FechaCobroReal)){
                $fechaCobro = date('Y-m-d', strtotime($it->FechaCobroReal));
                $ffCobro = DateTime::createFromFormat("Y-m-d", $fechaCobro);
            }else{
                $fechaCobro = null;
                $ffCobro = null;
            }
           
            $fechaCompra = date('Y-m-d', strtotime($it->FechaAltaRegistro));
            $ffCompra = DateTime::createFromFormat("Y-m-d", $fechaCompra);

           

            $fechaEne = date("Y-m-d", strtotime($anio.'0131'));
            $fechaEne = DateTime::createFromFormat("Y-m-d", $fechaEne);

            $fechaFeb = date("Y-m-d", strtotime($anio.'0229'));
            $fechaFeb = DateTime::createFromFormat("Y-m-d", $fechaFeb);
            
            $fechaMar = date("Y-m-d", strtotime($anio.'0331'));
            $fechaMar = DateTime::createFromFormat("Y-m-d", $fechaMar);

            $fechaAbr = date("Y-m-d", strtotime($anio.'0430'));
            $fechaAbr = DateTime::createFromFormat("Y-m-d", $fechaAbr);

            $fechaMay = date("Y-m-d", strtotime($anio.'0531'));
            $fechaMay = DateTime::createFromFormat("Y-m-d", $fechaMay);

            $fechaJun = date("Y-m-d", strtotime($anio.'0630'));
            $fechaJun = DateTime::createFromFormat("Y-m-d", $fechaJun);

            $fechaJul = date("Y-m-d", strtotime($anio.'0731'));
            $fechaJul = DateTime::createFromFormat("Y-m-d", $fechaJul);

            $fechaAgo = date("Y-m-d", strtotime($anio.'0831'));
            $fechaAgo = DateTime::createFromFormat("Y-m-d", $fechaAgo);

            $fechaSep = date("Y-m-d", strtotime($anio.'0930'));
            $fechaSep = DateTime::createFromFormat("Y-m-d", $fechaSep);

            $fechaOct = date("Y-m-d", strtotime($anio.'1031'));
            $fechaOct = DateTime::createFromFormat("Y-m-d", $fechaOct);

            $fechaNov = date("Y-m-d", strtotime($anio.'1130'));
            $fechaNov = DateTime::createFromFormat("Y-m-d", $fechaNov);

            $fechaDic = date("Y-m-d", strtotime($anio.'1231'));
            $fechaDic = DateTime::createFromFormat("Y-m-d", $fechaDic);

            $anioAnt = $anio - 1;
            $fechaDicAnt = date("Y-m-d", strtotime($anioAnt.'1231'));
            $fechaDicAnt = DateTime::createFromFormat("Y-m-d", $fechaDicAnt);

            

            if ((is_null($ffCobro) || ($ffCobro->format("Y")) >= $anio) && $ffCompra->format("Y") <= $anio ){
                
                if((is_null($ffCobro) || $ffCobro > $fechaEne) && $ffCompra <= $fechaEne){
                    $lstStockFinal['M1'] += $it->HaberNetoSubiteUSD;
                    $lstStockInicial['M2'] = $lstStockFinal['M1'];

                    $lstCostoStock['M1'] += $it->MontoCompraDolares;  

                    $itemHN = new \stdClass();
                    $itemHN->Duration = $it->DurationActual;
                    $itemHN->DurationCompra = $it->DurationCompra;
                    $itemHN->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;
                    $itemHN->DurationPonderada = 0;

                    //$lstResumen->offsetSet(1, $itemHN);
                    array_push($Ene, $itemHN);

                }

                if((is_null($ffCobro) || $ffCobro > $fechaFeb ) && $ffCompra <= $fechaFeb){
                    $lstStockFinal['M2'] += $it->HaberNetoSubiteUSD;
                    $lstStockInicial['M3'] = $lstStockFinal['M2'];

                    $lstCostoStock['M2'] += $it->MontoCompraDolares;  

                    $itemHN = new \stdClass();
                    $itemHN->Duration = $it->DurationActual;
                    $itemHN->DurationCompra = $it->DurationCompra;
                    $itemHN->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;
                    $itemHN->DurationPonderada = 0;

                    array_push($Feb, $itemHN);
                    //$lstResumen->offsetSet(2, $itemHN);
                }

                if((is_null($ffCobro) || $ffCobro > $fechaMar) && $ffCompra <= $fechaMar){
                    $lstStockFinal['M3'] += $it->HaberNetoSubiteUSD;
                    $lstStockInicial['M4'] = $lstStockFinal['M3'];

                    $lstCostoStock['M3'] += $it->MontoCompraDolares;  

                    $itemHN = new \stdClass();
                    $itemHN->Duration = $it->DurationActual;
                    $itemHN->DurationCompra = $it->DurationCompra;
                    $itemHN->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;
                    $itemHN->DurationPonderada = 0;

                    array_push($Mar, $itemHN);
                    //$lstResumen->offsetSet(3, $itemHN);
                }

                if((is_null($ffCobro) || $ffCobro > $fechaAbr) && $ffCompra <= $fechaAbr){
                    $lstStockFinal['M4'] += $it->HaberNetoSubiteUSD;
                    $lstStockInicial['M5'] = $lstStockFinal['M4'];

                    $lstCostoStock['M4'] += $it->MontoCompraDolares;  

                    $itemHN = new \stdClass();
                    $itemHN->Duration = $it->DurationActual;
                    $itemHN->DurationCompra = $it->DurationCompra;
                    $itemHN->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;
                    $itemHN->DurationPonderada = 0;

                    array_push($Abr, $itemHN);
                    //$lstResumen->offsetSet(4, $itemHN);
                }

                if((is_null($ffCobro) || $ffCobro > $fechaMay) && $ffCompra <= $fechaMay){
                    $lstStockFinal['M5'] += $it->HaberNetoSubiteUSD;
                    $lstStockInicial['M6'] = $lstStockFinal['M5'];

                    $lstCostoStock['M5'] += $it->MontoCompraDolares;  

                    $itemHN = new \stdClass();
                    $itemHN->Duration = $it->DurationActual;
                    $itemHN->DurationCompra = $it->DurationCompra;
                    $itemHN->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;
                    $itemHN->DurationPonderada = 0;

                    array_push($May, $itemHN);
                    //$lstResumen->offsetSet(5, $itemHN);
                }

                if((is_null($ffCobro) || $ffCobro > $fechaJun) && $ffCompra <= $fechaJun){
                    $lstStockFinal['M6'] += $it->HaberNetoSubiteUSD;
                    $lstStockInicial['M7'] = $lstStockFinal['M6'];

                    $lstCostoStock['M6'] += $it->MontoCompraDolares;  

                    $itemHN = new \stdClass();
                    $itemHN->Duration = $it->DurationActual;
                    $itemHN->DurationCompra = $it->DurationCompra;
                    $itemHN->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;
                    $itemHN->DurationPonderada = 0;

                    array_push($Jun, $itemHN);
                    //$lstResumen->offsetSet(6, $itemHN);
                }

                
                if((is_null($ffCobro) || $ffCobro > $fechaJul) && $ffCompra <= $fechaJul){
                    $lstStockFinal['M7'] += $it->HaberNetoSubiteUSD;
                    $lstStockInicial['M8'] = $lstStockFinal['M7'];

                    $lstCostoStock['M7'] += $it->MontoCompraDolares;  

                    $itemHN = new \stdClass();
                    $itemHN->Duration = $it->DurationActual;
                    $itemHN->DurationCompra = $it->DurationCompra;
                    $itemHN->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;
                    $itemHN->DurationPonderada = 0;

                    array_push($Jul, $itemHN);
                    //$lstResumen->offsetSet(7, $itemHN);
                }

                if((is_null($ffCobro) || $ffCobro > $fechaAgo) && $ffCompra <= $fechaAgo){
                    $lstStockFinal['M8'] += $it->HaberNetoSubiteUSD;
                    $lstStockInicial['M9'] = $lstStockFinal['M8'];

                    $lstCostoStock['M8'] += $it->MontoCompraDolares;  

                    $itemHN = new \stdClass();
                    $itemHN->Duration = $it->DurationActual;
                    $itemHN->DurationCompra = $it->DurationCompra;
                    $itemHN->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;
                    $itemHN->DurationPonderada = 0;

                    array_push($Ago, $itemHN);
                    //$lstResumen->offsetSet(8, $itemHN);
                }

                if((is_null($ffCobro) || $ffCobro > $fechaSep) && $ffCompra <= $fechaSep){
                    $lstStockFinal['M9'] += $it->HaberNetoSubiteUSD;
                    $lstStockInicial['M10'] = $lstStockFinal['M9'];

                    $lstCostoStock['M9'] += $it->MontoCompraDolares;  

                    $itemHN = new \stdClass();
                    $itemHN->Duration = $it->DurationActual;
                    $itemHN->DurationCompra = $it->DurationCompra;
                    $itemHN->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;
                    $itemHN->DurationPonderada = 0;

                    array_push($Sep, $itemHN);
                    //$lstResumen->offsetSet(9, $itemHN);
                }

                if((is_null($ffCobro) || $ffCobro > $fechaOct) && $ffCompra <= $fechaOct){
                    $lstStockFinal['M10'] += $it->HaberNetoSubiteUSD;
                    $lstStockInicial['M11'] = $lstStockFinal['M10'];

                    $lstCostoStock['M10'] += $it->MontoCompraDolares;  

                    $itemHN = new \stdClass();
                    $itemHN->Duration = $it->DurationActual;
                    $itemHN->DurationCompra = $it->DurationCompra;
                    $itemHN->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;
                    $itemHN->DurationPonderada = 0;

                    array_push($Oct, $itemHN);
                    //$lstResumen->offsetSet(10, $itemHN);
                }

                if((is_null($ffCobro) || $ffCobro > $fechaNov) && $ffCompra <= $fechaNov){
                    $lstStockFinal['M11'] += $it->HaberNetoSubiteUSD;
                    $lstStockInicial['M12'] = $lstStockFinal['M11'];

                    $lstCostoStock['M11'] += $it->MontoCompraDolares;  

                    $itemHN = new \stdClass();
                    $itemHN->Duration = $it->DurationActual;
                    $itemHN->DurationCompra = $it->DurationCompra;
                    $itemHN->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;
                    $itemHN->DurationPonderada = 0;

                    array_push($Nov, $itemHN);
                    //$lstResumen->offsetSet(11, $itemHN);
                }

                if((is_null($ffCobro) || $ffCobro > $fechaDic) && $ffCompra <= $fechaDic){
                    $lstStockFinal['M12'] += $it->HaberNetoSubiteUSD;
                    //$lstStockInicial['M4'] = $lstStockFinal['M1'];

                    $lstCostoStock['M12'] += $it->MontoCompraDolares;  

                    $itemHN = new \stdClass();
                    $itemHN->Duration = $it->DurationActual;
                    $itemHN->DurationCompra = $it->DurationCompra;
                    $itemHN->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;
                    $itemHN->DurationPonderada = 0;

                    array_push($Dic, $itemHN);
                    //$lstResumen->offsetSet(12, $itemHN);
                }
                

            }

            if((is_null($ffCobro) || $ffCobro > $fechaDicAnt) && $ffCompra <= $fechaDicAnt){
                $lstStockInicial['M1'] += $it->HaberNetoSubiteUSD;
                //$lstStockInicial['M4'] = $lstStockFinal['M1'];

                $lstCostoStock['M1'] += $it->MontoCompraDolares;  
            }

             

        } //END FOREACH

       // dd($pasada);

       $lstResumen->offsetSet(1, $Ene);
       $lstResumen->offsetSet(2, $Feb);
       $lstResumen->offsetSet(3, $Mar);
       $lstResumen->offsetSet(4, $Abr);
       $lstResumen->offsetSet(5, $May);
       $lstResumen->offsetSet(6, $Jun);
       $lstResumen->offsetSet(7, $Jul);
       $lstResumen->offsetSet(8, $Ago);
       $lstResumen->offsetSet(9, $Sep);
       $lstResumen->offsetSet(10, $Oct);
       $lstResumen->offsetSet(11, $Nov);
       $lstResumen->offsetSet(12, $Dic);



        
        $lstStockFinal_Acotado = array();
        $lstStockInicial_Acotado = array();

        if ($anio < $ffHoy->format("Y")){
            $mesBorde = 12;
        }else{
            $mesBorde = $ffHoy->format("n");
        }
       

        for ($i=1; $i <= $mesBorde; $i++) { 
            $lstStockFinal_Acotado['M'.$i] = $lstStockFinal['M'.$i];
            $lstStockInicial_Acotado['M'.$i] = $lstStockInicial['M'.$i];
        }

        //CON LOS TOTALES CALCULADOS YA PUEDO VER LA DURATION PONDERADA
        //dd($lstResumen);
        $i = 1;

        foreach ($lstResumen as $itemMes) {
        
            foreach ($itemMes as $item) {
                //dd($item);
                
                $ponderacion = 0;
                if($lstStockFinal['M'.$i]> 0){
                    $ponderacion = $item->HaberNetoSubiteUSD / $lstStockFinal['M'.$i];
                }
                $durationPonderada = $item->Duration * $ponderacion;
                $durationPonderadaCompra = $item->DurationCompra * $ponderacion;
    
                $lstPonderaciones['M'.$i] += $durationPonderada;
                $lstPonderacionesCompra['M'.$i] += $durationPonderadaCompra;

            }
            $i++;
   
        }

        $i = 1;
        $total_Stock_Mes = 0;
        foreach ($lstResumen as $itemMes) {
            foreach ($itemMes as $item) {
                $total_Stock_Mes += $item->HaberNetoSubiteUSD;
            }
            $i++;   
        }

        $i = 1;
        foreach ($lstResumen as $itemMes) {
            foreach ($itemMes as $item) {

                $ponderacion = 0;
                if($total_Stock_Mes > 0){
                    $ponderacion = $item->HaberNetoSubiteUSD / $total_Stock_Mes;
                }
                $durationPonderada = $item->Duration * $ponderacion;
                $durationPonderadaCompra = $item->DurationCompra * $ponderacion;
    
                $lstPonderaciones['Total'] += $durationPonderada;
                $lstPonderacionesCompra['Total'] += $durationPonderadaCompra;
            }
            $i++;   
        }
      

        $lstStockFinal_Acotado['Total'] = $lstStockFinal['M'.$mesBorde];
        $lstStockInicial_Acotado['Total'] = $lstStockInicial['M'.$mesBorde];
        /*
        $lstStockFinal['Total'] += $lstStockFinal['M12'];
        $lstStockInicial['Total'] += $lstStockInicial['M12'];
        */

        //for ($i=1; $i < 13; $i++) { 
        for ($i=1; $i <= $mesBorde; $i++) { 
            $lstVerificacion['M'.$i] = ($lstStockFinal['M'.$i] - $lstStockInicial['M'.$i]) - ($listStock['M'.$i] - $listSaldoCaja['M'.$i]);
            
            $lstVerificacion['Total'] += $lstVerificacion['M'.$i];

            $lstPonderaciones_Acotadas['M'.$i] = $lstPonderaciones['M'.$i] / 365;
            $lstPonderacionesCompra_Acotadas['M'.$i] = $lstPonderacionesCompra['M'.$i] / 365;
        }

        $lstPonderaciones_Acotadas['Total'] = $lstPonderaciones['Total'] / 365;
        $lstPonderacionesCompra_Acotadas['Total'] = $lstPonderacionesCompra['Total'] / 365;

        //dd($lstPonderacionesCompra_Acotadas);

        $arrStockFinal['Tipo'] = 'Stock Período';
        //$arrStockFinal['Valores'] = $lstStockFinal;
        $arrStockFinal['Valores'] = $lstStockFinal_Acotado;
        array_push($listStock, $arrStockFinal);

        $arrStockInicial['Tipo'] = 'Compra Período';
        //$arrStockInicial['Valores'] = $lstStockInicial;
        $arrStockInicial['Valores'] = $lstStockInicial_Acotado;
        array_push($listStock, $arrStockInicial);

        $arrStockInicial['Tipo'] = 'Costo Período';
        //$arrStockInicial['Valores'] = $lstStockInicial;
        $arrStockInicial['Valores'] = $lstCostoStock;
        array_push($listStock, $arrStockInicial);

        /*
        $arrStockInicial['Tipo'] = 'Stock Inicial Período';
        //$arrStockInicial['Valores'] = $lstStockInicial;
        $arrStockInicial['Valores'] = $lstStockInicial_Acotado;
        array_push($listStock, $arrStockInicial);
        */

        /*
        //Stock del Periodo
        $arrStockPer['Tipo'] = 'Stock del Período';
        $arrStockPer['Valores'] = $listStock;
        array_push($listStock, $arrStockPer);

    
        $arrSaldoCaja['Tipo'] = 'Saldo de Caja';
        $arrSaldoCaja['Valores'] = $listSaldoCaja;
        array_push($listStock, $arrSaldoCaja);
        
        $arrVerificacion['Tipo'] = 'Verificación';
        $arrVerificacion['Valores'] = $lstVerificacion;
        array_push($listStock, $arrVerificacion);
        */
        
        /*
        $arrCC['Tipo'] = 'Casos a Cobrar';
        $arrCC['Valores'] = $lstCantCasos;
        array_push($listMeses, $arrCC);
        */

        $respuesta['lstMeseStock'] = $listStock;
        $respuesta['lstStockFinal'] = $lstStockFinal_Acotado;
        $respuesta['CostoStock'] = $lstCostoStock;
        //dd($lstCostoStock);
        $respuesta['DurationStock'] = $lstPonderaciones_Acotadas;
        $respuesta['DurationStock_TIR'] = $lstPonderacionesCompra_Acotadas;

       // dd($respuesta);

        return $respuesta;
    }



    public function getStockMeses($anio, $listItems){

        $respuesta = array();
        $listMeses = array();

        $ffHoy = new DateTime('NOW');

        $keys = array('M1', 'M2', 'M3', 'M4', 'M5', 'M6', 'M7', 'M8', 'M9', 'M10', 'M11', 'M12', 'Total');

        $lstCompradosCobro = array();
        $lstCompradosCobro = array_fill_keys($keys, 0);

        $lstCompradosCosto = array();
        $lstCompradosCosto = array_fill_keys($keys, 0);

        $lstCobradosReal = array();
        $lstCobradosReal = array_fill_keys($keys, 0);

        $lstCobradosEstimados = array();
        $lstCobradosEstimados = array_fill_keys($keys, 0);

        $lstDifCobro= array();
        $lstDifCobro = array_fill_keys($keys, 0);

        $lstStock = array();
        $lstStock = array_fill_keys($keys, 0);

        $lstSaldoCaja = array();
        $lstSaldoCaja = array_fill_keys($keys, 0);

        $lstCantCasos = array();
        $lstCantCasos = array_fill_keys($keys, 0);

        $lstResumen = array();
        $lstDurationPonderadas = array();
        $lstDurationPonderadas = array_fill_keys($keys, 0);

        $lstDuration = array();
        $lstDuration = array_fill_keys($keys, 0);
        $lstPonderaciones = array();
        $lstPonderaciones = array_fill_keys($keys, 0);

        foreach ($listItems as $it) {

            //$itemHN = new \stdClass();

            if (!is_null($it->FechaAltaRegistro)){
             
                $fecha = date('Y-m-d', strtotime($it->FechaAltaRegistro));
                $ff = DateTime::createFromFormat("Y-m-d", $fecha);

                //if($ff->format("Y") == $anio){
                if($ff->format("Y") == $anio && ($anio < $ffHoy->format("Y") || $ff->format("n") <= $ffHoy->format("n"))){
                    $lstCompradosCobro['M'.$ff->format("n")] += round($it->HaberNetoSubiteUSD); //Cobro Estimado
                    $lstCompradosCosto['M'.$ff->format("n")] += round($it->MontoCompraDolares);
                
                    $lstCantCasos['M'.$ff->format("n")] += 1;

                    /*
                    $itemHN->Duration = $it->DurationActual;
                    $itemHN->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;
                    $itemHN->Mes = $ff->format("n");
                    $itemHN->DurationPonderada = 0;

                    array_push($lstResumen, $itemHN);
                    */
                }

            }

            if (!is_null($it->FechaCobroReal)){
                $fechaC = date('Y-m-d', strtotime($it->FechaCobroReal));
                $ffC = DateTime::createFromFormat("Y-m-d", $fechaC);
                
                //if($ffC->format("Y") == $anio){
                if($ffC->format("Y") == $anio && ($anio < $ffHoy->format("Y") || $ffC->format("n") <= $ffHoy->format("n"))){
                    $lstCobradosReal['M'.$ffC->format("n")] += round($it->MontoCobroDolares);
                    $lstCobradosEstimados['M'.$ffC->format("n")] += round($it->HaberNetoSubiteUSD);
                }     
            }

        } //END FOREACH 1

      
        
        for ($i=1; $i < 13; $i++) { 

           $lstDifCobro['M'.$i] = $lstCobradosReal['M'.$i] - $lstCobradosEstimados['M'.$i];
          
           $lstSaldoCaja['M'.$i] = $lstCobradosReal['M'.$i] - $lstCompradosCosto['M'.$i];

            $lstCompradosCobro['Total'] += $lstCompradosCobro['M'.$i];
            $lstCompradosCosto['Total'] += $lstCompradosCosto['M'.$i];
            $lstCantCasos['Total'] += $lstCantCasos['M'.$i];

            $lstStock['M'.$i] = $lstCompradosCobro['M'.$i] - $lstCompradosCosto['M'.$i] + $lstDifCobro['M'.$i];

            $lstStock['Total'] +=  $lstStock['M'.$i];   
            $lstSaldoCaja['Total'] +=  $lstSaldoCaja['M'.$i];    
            $lstDifCobro['Total'] += $lstDifCobro['M'.$i];
        }

        /*
        //CON LOS TOTALES CALCULADOS YA PUEDO VER LA DURATION PONDERADA
        foreach ($lstResumen as $item) {

            $poderacion = 0;
            if($lstCompradosCobro['M'.$item->Mes] > 0){
                $poderacion = $item->HaberNetoSubiteUSD / $lstCompradosCobro['M'.$item->Mes];
            }
            $item->DurationPonderada = $item->Duration * $poderacion;

            $lstPonderaciones['M'.$item->Mes] += $item->DurationPonderada;
        }
        */

        $arrCobro['Tipo'] = 'Resultado por Tenencia';
        $arrCobro['Codigo'] = 'HNCOMCOBR';
        $arrCobro['Valores'] = $lstCompradosCobro;
        array_push($listMeses, $arrCobro);

        $arrCosto['Tipo'] = 'Resultado por Compra del Mes';
        $arrCosto['Codigo'] = 'HNCOMCOST';
        $arrCosto['Valores'] = $lstCompradosCosto;
        array_push($listMeses, $arrCosto);

        $arrDifCobro['Tipo'] = 'Resultado Neto';
        $arrDifCobro['Codigo'] = 'DIFCOB';
        $arrDifCobro['Valores'] = $lstDifCobro;  
        array_push($listMeses, $arrDifCobro);

        /*
        $arrCobro['Tipo'] = 'HN Comprados en USD (Cobro)';
        $arrCobro['Codigo'] = 'HNCOMCOBR';
        $arrCobro['Valores'] = $lstCompradosCobro;
        array_push($listMeses, $arrCobro);

        $arrCosto['Tipo'] = 'HN Comprados en USD (Costo)';
        $arrCosto['Codigo'] = 'HNCOMCOST';
        $arrCosto['Valores'] = $lstCompradosCosto;
        array_push($listMeses, $arrCosto);

        $arrDifCobro['Tipo'] = 'Dif. de Cobro';
        $arrDifCobro['Codigo'] = 'DIFCOB';
        $arrDifCobro['Valores'] = $lstDifCobro;  
        array_push($listMeses, $arrDifCobro);
        
        $arrStockPer['Tipo'] = 'Stock del Período';
        $arrStockPer['Codigo'] = 'StockPER';
        $arrStockPer['Valores'] = $lstStock;
        array_push($listMeses, $arrStockPer);


        $arrCC['Tipo'] = 'Casos a Cobrar';
        $arrCC['Codigo'] = 'CANTCAS';
        $arrCC['Valores'] = $lstCantCasos;
        //array_push($listMeses, $arrCC);
        */

        $respuesta['lstMeses'] = $listMeses;
        $respuesta['lstStock'] = $lstStock;
        $respuesta['SaldoCaja'] = $lstSaldoCaja;
  

        return $respuesta;
    }


    public function searchGyOyMyE($list, $gru, $ord, $mar, $emp){

        // dd($list);
 
         $filtered_array = array_filter($list, function($val) use($gru, $ord, $mar, $emp){
             return ($val->Grupo==$gru and $val->Orden==$ord and $val->Marca==$mar and $val->Empresa==$emp);
         });
 
         if ($filtered_array){
             return array_values($filtered_array);
         }else{
            return false;
         }
     }


}