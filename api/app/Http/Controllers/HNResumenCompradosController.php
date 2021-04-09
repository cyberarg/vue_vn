<?php

namespace App\Http\Controllers;

use App\HaberNeto;
use Illuminate\Http\Request;
use DB;
use DateTime;
use App\Http\Controllers\UtilsController;
use ArrayObject;

class HNResumenCompradosController extends Controller
{

    public function getListHNResumenComprados_Selecteds(Request $request){

        $lstResumenCompra = array();

        $anio = $request->Anio;

        $marca = $request->Marca;
        $concesionario = $request->Concesionario;

        $seleccionados = $request->Seleccionados;
        $filtros = $request->Filtros;
        $rbConsolidado = $request->ConsolidadoRB;

        $p = 0;
        foreach ($seleccionados as $seleccionado) {

            $array_result = array();
            $marca = $seleccionado['Marca'];
            $concesionario = $seleccionado['Codigo'];
            
            $lstItemsHN_Resumen = $this->getItemsResumen($anio, $marca, $concesionario, $filtros, $rbConsolidado);
        
        
            $lstComprados = $this->getResumenCompradosMeses($anio, $lstItemsHN_Resumen['ListHN'], $marca);
            $lstComprados_CE = $this->getResumenCompradosMeses($anio, $lstItemsHN_Resumen['ListHN_CE'], $marca);


            if ($p == 0){
                $lstResumenCompra['Grid1_Comprados'] = $lstComprados['lstMeses'];
                $lstResumenCompra['Grid2_Comprados'] = $lstComprados['listAnios'];
    
                $lstResumenCompra['Grid1_Comprados_CE'] = $lstComprados_CE['lstMeses'];
                $lstResumenCompra['Grid2_Comprados_CE'] = $lstComprados_CE['listAnios'];
                
            }else{

                $lstResumenCompra['Grid1_Comprados'] = array_merge($lstResumenCompra['Grid1_Comprados'], $lstComprados['lstMeses']);
                $lstResumenCompra['Grid2_Comprados'] = array_merge($lstResumenCompra['Grid2_Comprados'], $lstComprados['listAnios']);

                $lstResumenCompra['Grid1_Comprados_CE'] = array_merge($lstResumenCompra['Grid1_Comprados_CE'], $lstComprados_CE['lstMeses']);
                $lstResumenCompra['Grid2_Comprados_CE'] = array_merge($lstResumenCompra['Grid2_Comprados_CE'], $lstComprados_CE['listAnios']);
   
            }

            $p++;
        }

  
        return $lstResumenCompra;

    }


    public function getItemsResumen_Selecteds($anio, $marca, $concesionario, $soloCE_Giama_Todos, $filtros, $rbConsolidado){
    
        $respuesta = array(); 
        $lstHN = array();
        $lstHN_CE = array();
        $list = array();

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

            if ($soloCE_Giama_Todos != 2){
                $lstHN_RB = DB::connection('RB')->select("SELECT *  FROM haberesnetosok WHERE Concesionario = ".$concesionario);
            }
            

            //$lstHN_GF = DB::connection('GF')->select("SELECT *  FROM haberesnetosok WHERE ComproGiama = 1");

            //$lstHN = array_merge($lstHN_RB, $lstHN_GF);
            $lstHN = $lstHN_RB;
        }else{

            if ($marca == 99){ //GIAMA
                $lstHN_RB = array();
                $lstHN_GF = array();

                $lstHN_AN = array();
                $lstHN_CG = array();

                $lstHN_RB = DB::connection('RB')->select("SELECT *  FROM haberesnetosok WHERE Concesionario = 8");

                $lstHN_GF = DB::connection('GF')->select("SELECT *  FROM haberesnetosok WHERE ComproGiama = 1");

                $lstHN_AN = DB::connection('AN')->select("SELECT *  FROM haberesnetosok WHERE Concesionario = 5");
                $lstHN_CG = DB::connection('CG')->select("SELECT *  FROM haberesnetosok WHERE Concesionario = 6");
    
                $lstHN = array_merge($lstHN_RB, $lstHN_GF, $lstHN_AN, $lstHN_CG);
            }else{
                
                switch ($soloCE_Giama_Todos){
                    case 0: // TODOS
                        $lstHN = DB::connection($db)->select("SELECT *  FROM haberesnetosok WHERE Concesionario = ".$concesionario);
                    break;
                    case 1: // GIAMA
                        $lstHN = DB::connection($db)->select("SELECT *  FROM haberesnetosok WHERE ComproGiama = 1 AND Concesionario = ".$concesionario);
                    break;
                    case 2: //SOLO CE
                        $lstHN = DB::connection($db)->select("SELECT *  FROM haberesnetosok WHERE ComproGiama = 0 AND Concesionario = ".$concesionario);
                    break;
                }
              
                
            }
        }

        //$list[] = $lstHN;

        return $lstHN;
    }
    

    public function getListHNResumenComprados(Request $request){

        $lstResumenCompra = array();

        $anio = $request->Anio;

        $marca = $request->Marca;
        $concesionario = $request->Concesionario;
        $filtros = $request->Filtros;
        $rbConsolidado = $request->ConsolidadoRB;

        $lstItemsHN_Resumen = $this->getItemsResumen($anio, $marca, $concesionario, $filtros, $rbConsolidado);
        
        $lstComprados = $this->getResumenCompradosMeses($anio, $lstItemsHN_Resumen['ListHN'], $marca);
        $lstComprados_CE = $this->getResumenCompradosMeses($anio, $lstItemsHN_Resumen['ListHN_CE'], $marca);

        $lstResumenCompra['Grid1_Comprados'] = $lstComprados['lstMeses'];
        $lstResumenCompra['Grid2_Comprados'] = $lstComprados['listAnios'];

        $lstResumenCompra['Grid1_Comprados_CE'] = $lstComprados_CE['lstMeses'];
        $lstResumenCompra['Grid2_Comprados_CE'] = $lstComprados_CE['listAnios'];
        
        return $lstResumenCompra;

    }

    public function getItemsResumen($anio, $marca, $concesionario, $filtros, $rbConsolidado){
    
        $respuesta = array(); 
        $lstHN = array();
        $lstHN_CE = array();
        $list = array();

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

            if ($marca == 99){ //GIAMA
                $lstHN_RB = array();
                $lstHN_GF = array();

                $lstHN_AN = array();
                $lstHN_CG = array();

                $lstHN_RB = DB::connection('RB')->select("SELECT *  FROM haberesnetosok WHERE Concesionario = 8");

                $lstHN_GF = DB::connection('GF')->select("SELECT *  FROM haberesnetosok WHERE ComproGiama = 1");

                $lstHN_AN = DB::connection('AN')->select("SELECT *  FROM haberesnetosok WHERE Concesionario = 5");
                $lstHN_CG = DB::connection('CG')->select("SELECT *  FROM haberesnetosok WHERE Concesionario = 6");
    
                $lstHN = array_merge($lstHN_RB, $lstHN_GF, $lstHN_AN, $lstHN_CG);
            }else{

                $lstHN = HaberNeto::on($db)->where('Concesionario', $concesionario)->get();
               
                //$lstHN = HaberNeto::on($db)->where('TipoCompra', '=', 1)->whereYear('FechaAltaRegistro', '>=', 2018)->where('Concesionario', $concesionario)->get();
                $lstHN_CE = HaberNeto::on($db)->where('Concesionario', $concesionario)->where('ComproGiama', 0)->get();
           
            }
        }

        $list['ListHN'] = $lstHN;
        $list['ListHN_CE'] = $lstHN_CE;
        return $list;
    }



    public function getResumenCompradosMeses($anio, $listItems, $marca){

        $respuesta = array();
        $listMeses = array();
        $listAnios = array();

        $anio0 = $anio - 2;
        $anio1 = $anio - 1;


        $keys = array('M1', 'M2', 'M3', 'M4', 'M5', 'M6', 'M7', 'M8', 'M9', 'M10', 'M11', 'M12', 'Total');
        $keysAnios = array('HN', 'RentARS', 'RentARS_Porc', 'RentUSD', 'RentUSD_Porc', 'Duration', 'TIR');

        $lstCompradosDurationCompra_Anio = array();
        $lstCompradosDurationCompra_Anio = array_fill_keys($keys, 0);

        $lstCompradosDurationActual_Anio = array();
        $lstCompradosDurationActual_Anio = array_fill_keys($keys, 0);


        $lstCompradosARS_Anio = array();
        $lstCompradosARS_Anio = array_fill_keys($keys, 0);
        $lstCompradosARS_Anio1 = array();
        $lstCompradosARS_Anio1 = array_fill_keys($keys, 0);
        $lstCompradosARS_Anio0 = array();
        $lstCompradosARS_Anio0 = array_fill_keys($keys, 0);

        $lstCompradosUSD_Anio = array();
        $lstCompradosUSD_Anio = array_fill_keys($keys, 0);
        $lstCompradosUSD_Anio1 = array();
        $lstCompradosUSD_Anio1 = array_fill_keys($keys, 0);
        $lstCompradosUSD_Anio0 = array();
        $lstCompradosUSD_Anio0 = array_fill_keys($keys, 0);

        $lstCobradosARS_Anio = array();
        $lstCobradosARS_Anio = array_fill_keys($keys, 0);
        $lstCobradosARS_Anio1 = array();
        $lstCobradosARS_Anio1 = array_fill_keys($keys, 0);
        $lstCobradosARS_Anio0 = array();
        $lstCobradosARS_Anio0 = array_fill_keys($keys, 0);
        
        $lstCobradosUSD_Anio = array();
        $lstCobradosUSD_Anio = array_fill_keys($keys, 0);
        $lstCobradosUSD_Anio1 = array();
        $lstCobradosUSD_Anio1 = array_fill_keys($keys, 0);
        $lstCobradosUSD_Anio0 = array();
        $lstCobradosUSD_Anio0 = array_fill_keys($keys, 0);

        $lstCobradosUSD_Spot_Anio = array();
        $lstCobradosUSD_Spot_Anio = array_fill_keys($keys, 0);
        $lstCobradosUSD_Spot_Anio1 = array();
        $lstCobradosUSD_Spot_Anio1 = array_fill_keys($keys, 0);
        $lstCobradosUSD_Spot_Anio0 = array();
        $lstCobradosUSD_Spot_Anio0 = array_fill_keys($keys, 0);

        
        $lstCompradosCantCasos_Anio = array();
        $lstCompradosCantCasos_Anio = array_fill_keys($keys, 0);
        
        $lstCompradosCantCasos_Anio1 = array();
        $lstCompradosCantCasos_Anio1 = array_fill_keys($keys, 0);
        $lstCompradosCantCasos_Anio0 = array();
        $lstCompradosCantCasos_Anio0 = array_fill_keys($keys, 0);

        $lstCompradosDuration_Anio = array();
        $lstCompradosDuration_Anio = array_fill_keys($keys, 0);
        $lstCompradosDuration_Anio1 = array();
        $lstCompradosDuration_Anio1 = array_fill_keys($keys, 0);
        $lstCompradosDuration_Anio0 = array();
        $lstCompradosDuration_Anio0 = array_fill_keys($keys, 0);

        $lstCompradosRentARS_Anio = array();
        $lstCompradosRentARS_Anio = array_fill_keys($keys, 0);
        $lstCompradosRentARS_Anio1 = array();
        $lstCompradosRentARS_Anio1 = array_fill_keys($keys, 0);
        $lstCompradosRentARS_Anio0 = array();
        $lstCompradosRentARS_Anio0 = array_fill_keys($keys, 0);

        $lstCompradosRentARSPorc_Anio = array();
        $lstCompradosRentARSPorc_Anio = array_fill_keys($keys, 0);
        $lstCompradosRentARSPorc_Anio1 = array();
        $lstCompradosRentARSPorc_Anio1 = array_fill_keys($keys, 0);
        $lstCompradosRentARSPorc_Anio0 = array();
        $lstCompradosRentARSPorc_Anio0 = array_fill_keys($keys, 0);

        $lstCompradosRentUSD_Anio = array();
        $lstCompradosRentUSD_Anio = array_fill_keys($keys, 0);

        $lstCompradosRentUSD_Spot_Anio = array();
        $lstCompradosRentUSD_Spot_Anio = array_fill_keys($keys, 0);

        $lstCompradosRentUSD_Spot_Anio0 = array();
        $lstCompradosRentUSD_Spot_Anio0 = array_fill_keys($keys, 0);
        $lstCompradosRentUSD_Spot_Anio1 = array();
        $lstCompradosRentUSD_Spot_Anio1 = array_fill_keys($keys, 0);

        $lstCompradosRentUSD_Anio1 = array();
        $lstCompradosRentUSD_Anio1 = array_fill_keys($keys, 0);
        $lstCompradosRentUSD_Anio0 = array();
        $lstCompradosRentUSD_Anio0 = array_fill_keys($keys, 0);

        $lstRentUnitariaUSD = array();
        $lstRentUnitariaUSD = array_fill_keys($keys, 0);

        $lstCompradosRentUSDPorc_Anio = array();
        $lstCompradosRentUSDPorc_Anio = array_fill_keys($keys, 0);

        $lstCompradosRentUSDPorc_Spot_Anio = array();
        $lstCompradosRentUSDPorc_Spot_Anio = array_fill_keys($keys, 0);
        $lstCompradosRentUSDPorc_Spot_Anio1 = array();
        $lstCompradosRentUSDPorc_Spot_Anio1 = array_fill_keys($keys, 0);
        $lstCompradosRentUSDPorc_Spot_Anio0 = array();
        $lstCompradosRentUSDPorc_Spot_Anio0 = array_fill_keys($keys, 0);
        

        $lstCompradosRentUSDPorc_Anio1 = array();
        $lstCompradosRentUSDPorc_Anio1 = array_fill_keys($keys, 0);
        $lstCompradosRentUSDPorc_Anio0 = array();
        $lstCompradosRentUSDPorc_Anio0 = array_fill_keys($keys, 0);
        
        $lstHNCompradosARS_Anio = array();
        $lstHNCompradosARS_Anio = array_fill_keys($keys, 0);

        $itemsAnio = array();
        $itemsAnio1 = array();
        $itemsAnio0 = array();

        $lstArr = array();

        $lstPonderacionesMesesAnio = array();
        $lstPonderacionesMesesAnio = array_fill_keys($keys, 0);

        $lstPonderacionesActualMesesAnio = array();
        $lstPonderacionesActualMesesAnio = array_fill_keys($keys, 0);

        $lstPonderaciones_TIR_MesesAnio = array();
        $lstPonderaciones_TIR_MesesAnio = array_fill_keys($keys, 0);

        $lstPonderaciones_TIRSpot_MesesAnio = array();
        $lstPonderaciones_TIRSpot_MesesAnio = array_fill_keys($keys, 0);

        $lstMesesAnio = array();
        $lstMesesAnio = array_fill_keys($keys, 0);

        $SumHNARS_Anio = 0;
        $SumHNARS_Anio1 = 0;
        $SumHNARS_Anio0 = 0;

        $arrEne = array();
        $arrFeb = array();
        $arrMar = array();
        $arrAbr = array();
        $arrMay = array();
        $arrJun = array();
        $arrJul = array();
        $arrAgo = array();
        $arrSep = array();
        $arrOct = array();
        $arrNov = array();
        $arrDic = array();

        $lstResumen = new ArrayObject($lstArr);

        $lstResumenMeses = new ArrayObject();

        foreach ($listItems as $it) {

            if (!is_null($it->FechaCuota84)){
             
                $fecha = date('Y-m-d', strtotime($it->FechaAltaRegistro));
                $ff = DateTime::createFromFormat("Y-m-d", $fecha);

                switch($ff->format("Y")){
                    case $anio: //Anio
                        $lstCompradosARS_Anio['M'.$ff->format("n")] += round($it->MontoCompra);
                        $lstCompradosUSD_Anio['M'.$ff->format("n")] += round($it->MontoCompraDolares); 

                        $lstCobradosARS_Anio['M'.$ff->format("n")] += round($it->HaberNetoSubite);
                        $lstCobradosUSD_Anio['M'.$ff->format("n")] += round($it->HaberNetoSubiteUSD); 

                        $lstCobradosUSD_Spot_Anio['M'.$ff->format("n")] += round($it->HaberNetoOriginalUSD);     
                        
                        $lstCobradosARS_Anio['M'.$ff->format("n")] += round($it->MontoCobroReal);
                        $lstCobradosUSD_Anio['M'.$ff->format("n")] += round($it->MontoCobroDolares); 
                        
                        
                        $SumHNARS_Anio += $it->HaberNetoSubite;
                        $lstHNCompradosARS_Anio['M'.$ff->format("n")] += $it->HaberNetoSubite;
                        $lstCompradosCantCasos_Anio['M'.$ff->format("n")] += 1;


                        $itemHN_Mes = new \stdClass();
                        $itemHN_Mes->Duration = $it->DurationActual;
                        $itemHN_Mes->FechaCompra = $ff;
                        $itemHN_Mes->DurationCompra = $it->DurationCompra;

                        $itemHN_Mes->HaberNetoSubite = $it->HaberNetoSubite;
                        $itemHN_Mes->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;

                        $itemHN_Mes->HaberNetoOriginalUSD = $it->HaberNetoOriginalUSD;
                        $itemHN_Mes->TIR = $it->TIRActual;
                        $itemHN_Mes->TIRSpot = $it->TIRSpot;

                        $itemHN_Mes->DurationPonderada = 0;
                       
                        switch ($ff->format("n")){

                            case 1:
                                array_push($arrEne, $itemHN_Mes);
                            break;
                            case 2:
                                array_push($arrFeb, $itemHN_Mes);
                            break;
                            case 3:
                                array_push($arrMar, $itemHN_Mes);
                            break;
                            case 4:
                                array_push($arrAbr, $itemHN_Mes);
                            break;
                            case 5:
                                array_push($arrMay, $itemHN_Mes);
                            break;
                            case 6:
                                array_push($arrJun, $itemHN_Mes);
                            break;
                            case 7:
                                array_push($arrJul, $itemHN_Mes);
                            break;
                            case 8:
                                array_push($arrAgo, $itemHN_Mes);
                            break;
                            case 9:
                                array_push($arrSep, $itemHN_Mes);
                            break;
                            case 10:
                                array_push($arrOct, $itemHN_Mes);
                            break;
                            case 11:
                                array_push($arrNov, $itemHN_Mes);
                            break;
                            case 12:
                                array_push($arrDic, $itemHN_Mes);
                            break;
            
                        }


                        array_push($itemsAnio, $itemHN_Mes);

                    break;
                    case $anio1: //Anio - 1
                        $lstCompradosARS_Anio1['M'.$ff->format("n")] += round($it->MontoCompra);
                        $lstCompradosUSD_Anio1['M'.$ff->format("n")] += round($it->MontoCompraDolares); 
    
                        $lstCobradosARS_Anio1['M'.$ff->format("n")] += round($it->HaberNetoSubite);
                        $lstCobradosUSD_Anio1['M'.$ff->format("n")] += round($it->HaberNetoSubiteUSD); 

                        $lstCobradosUSD_Spot_Anio1['M'.$ff->format("n")] += round($it->HaberNetoOriginalUSD); 
                       
                        $lstCompradosCantCasos_Anio1['M'.$ff->format("n")] += 1;
                        $SumHNARS_Anio1 += $it->HaberNetoSubite;

                        $itemHN = new \stdClass();
                        $itemHN->Duration = $it->DurationActual;
                        $itemHN->DurationCompra = $it->DurationCompra;

                        $itemHN->HaberNetoSubite = $it->HaberNetoSubite;
                        $itemHN->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;
                        $itemHN->DurationPonderada = 0;
                
                        array_push($itemsAnio1, $itemHN);
                    break;
                    case $anio0: //Anio - 2
                        $lstCompradosARS_Anio0['M'.$ff->format("n")] += round($it->MontoCompra);
                        $lstCompradosUSD_Anio0['M'.$ff->format("n")] += round($it->MontoCompraDolares); 
    
                        $lstCobradosARS_Anio0['M'.$ff->format("n")] += round($it->HaberNetoSubite);
                        $lstCobradosUSD_Anio0['M'.$ff->format("n")] += round($it->HaberNetoSubiteUSD); 

                        $lstCobradosUSD_Spot_Anio0['M'.$ff->format("n")] += round($it->HaberNetoOriginalUSD); 
                       
                        $lstCompradosCantCasos_Anio0['M'.$ff->format("n")] += 1;
                        $SumHNARS_Anio0 += $it->HaberNetoSubite;

                        $itemHN = new \stdClass();
                        $itemHN->Duration = $it->DurationActual;
                        $itemHN->DurationCompra = $it->DurationCompra;

                        $itemHN->HaberNetoSubite = $it->HaberNetoSubite;
                        $itemHN->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;
                        $itemHN->DurationPonderada = 0;
                
                        array_push($itemsAnio0, $itemHN);
                    break;
                    default:
                        continue;
                    break;

                }


            }

        } //END FOREACH

       

        for ($i=1; $i < 13; $i++) { 

            $lstCompradosRentARS_Anio['M'.$i] =  $lstCobradosARS_Anio['M'.$i] - $lstCompradosARS_Anio['M'.$i];
            $lstCompradosRentUSD_Anio['M'.$i] =  $lstCobradosUSD_Anio['M'.$i] - $lstCompradosUSD_Anio['M'.$i];

            $lstCompradosRentUSD_Spot_Anio['M'.$i] =  $lstCobradosUSD_Spot_Anio['M'.$i] - $lstCompradosUSD_Anio['M'.$i];


            if ($lstCompradosCantCasos_Anio['M'.$i]){
                $lstRentUnitariaUSD['M'.$i] = $lstCompradosRentUSD_Anio['M'.$i] / $lstCompradosCantCasos_Anio['M'.$i];
            }else{
                $lstRentUnitariaUSD['M'.$i] = 0;
            }
           

            $lstCompradosRentARS_Anio1['M'.$i] =  $lstCobradosARS_Anio1['M'.$i] - $lstCompradosARS_Anio1['M'.$i];
            $lstCompradosRentUSD_Anio1['M'.$i] =  $lstCobradosUSD_Anio1['M'.$i] - $lstCompradosUSD_Anio1['M'.$i];

            $lstCompradosRentUSD_Spot_Anio1['M'.$i] =  $lstCobradosUSD_Spot_Anio1['M'.$i] - $lstCompradosUSD_Anio1['M'.$i];

            $lstCompradosRentARS_Anio0['M'.$i] =  $lstCobradosARS_Anio0['M'.$i] - $lstCompradosARS_Anio0['M'.$i];
            $lstCompradosRentUSD_Anio0['M'.$i] =  $lstCobradosUSD_Anio0['M'.$i] - $lstCompradosUSD_Anio0['M'.$i];

            $lstCompradosRentUSD_Spot_Anio0['M'.$i] =  $lstCobradosUSD_Spot_Anio0['M'.$i] - $lstCompradosUSD_Anio0['M'.$i];

            $lstCompradosARS_Anio['Total'] += $lstCompradosARS_Anio['M'.$i];
            $lstCompradosUSD_Anio['Total'] += $lstCompradosUSD_Anio['M'.$i];
            $lstCompradosCantCasos_Anio['Total'] += $lstCompradosCantCasos_Anio['M'.$i];

            $lstRentUnitariaUSD['Total'] += $lstRentUnitariaUSD['M'.$i];

            if ($lstCompradosCantCasos_Anio['M'.$i] > 0){
                $lstCompradosDurationCompra_Anio['M'.$ff->format("n")] = $lstCompradosDurationCompra_Anio['M'.$ff->format("n")] / $lstCompradosCantCasos_Anio['M'.$i];
                $lstCompradosDurationActual_Anio['M'.$ff->format("n")] = $lstCompradosDurationActual_Anio['M'.$ff->format("n")] / $lstCompradosCantCasos_Anio['M'.$i];
            }
            

            $lstCompradosARS_Anio1['Total'] += $lstCompradosARS_Anio1['M'.$i];
            $lstCompradosUSD_Anio1['Total'] += $lstCompradosUSD_Anio1['M'.$i];
            $lstCompradosCantCasos_Anio1['Total'] += $lstCompradosCantCasos_Anio1['M'.$i];

            $lstCompradosARS_Anio0['Total'] += $lstCompradosARS_Anio0['M'.$i];
            $lstCompradosUSD_Anio0['Total'] += $lstCompradosUSD_Anio0['M'.$i];
            $lstCompradosCantCasos_Anio0['Total'] += $lstCompradosCantCasos_Anio0['M'.$i];

            $lstCompradosRentARS_Anio['Total'] += $lstCompradosRentARS_Anio['M'.$i];
            $lstCompradosRentUSD_Anio['Total'] += $lstCompradosRentUSD_Anio['M'.$i];



            //$lstCompradosRentUSD_Anio['Total'] += $lstCompradosRentUSD_Anio['M'.$i];
            //$lstCompradosRentUSD_Anio['Total'] += $lstCompradosRentUSD_Anio['M'.$i];

            $lstCompradosRentUSD_Spot_Anio['Total'] += $lstCompradosRentUSD_Spot_Anio['M'.$i];
            $lstCobradosUSD_Spot_Anio['Total'] += $lstCobradosUSD_Spot_Anio['M'.$i];

            $lstCobradosUSD_Spot_Anio1['Total'] += $lstCobradosUSD_Spot_Anio1['M'.$i];
            $lstCobradosUSD_Spot_Anio0['Total'] += $lstCobradosUSD_Spot_Anio0['M'.$i];


            $lstCobradosUSD_Anio['Total'] += $lstCobradosUSD_Anio['M'.$i];
            
/*
            $lstCompradosRentUSDPorc_Spot_Anio
            $lstPonderaciones_TIR_MesesAnio
            $lstPonderaciones_TIRSpot_MesesAnio 
*/
            $lstCompradosRentARS_Anio1['Total'] += $lstCompradosRentARS_Anio1['M'.$i];
            $lstCompradosRentUSD_Anio1['Total'] += $lstCompradosRentUSD_Anio1['M'.$i];
            $lstCompradosRentUSD_Spot_Anio1['Total'] += $lstCompradosRentUSD_Spot_Anio1['M'.$i];

            $lstCompradosRentARS_Anio0['Total'] += $lstCompradosRentARS_Anio0['M'.$i];
            $lstCompradosRentUSD_Anio0['Total'] += $lstCompradosRentUSD_Anio0['M'.$i];
            $lstCompradosRentUSD_Spot_Anio0['Total'] += $lstCompradosRentUSD_Spot_Anio0['M'.$i];
            




            if ($lstCompradosARS_Anio['M'.$i] > 0){
                $lstCompradosRentARSPorc_Anio['M'.$i] = round((($lstCobradosARS_Anio['M'.$i] /  $lstCompradosARS_Anio['M'.$i]) - 1) * 100);
            }else{
                $lstCompradosRentARSPorc_Anio['M'.$i] = 0;
            }
            if ($lstCompradosUSD_Anio['M'.$i] > 0){
                $lstCompradosRentUSDPorc_Anio['M'.$i] = round((($lstCobradosUSD_Anio['M'.$i] /  $lstCompradosUSD_Anio['M'.$i]) - 1) * 100);

                $lstCompradosRentUSDPorc_Spot_Anio['M'.$i] = round((($lstCobradosUSD_Spot_Anio['M'.$i] /  $lstCompradosUSD_Anio['M'.$i]) - 1) * 100);
                
            }else{
                $lstCompradosRentUSDPorc_Anio['M'.$i] = 0;
                $lstCompradosRentUSDPorc_Spot_Anio['M'.$i] = 0;
            }

            if ($lstCompradosARS_Anio1['M'.$i] > 0){
                $lstCompradosRentARSPorc_Anio1['M'.$i] = round((($lstCobradosARS_Anio1['M'.$i] /  $lstCompradosARS_Anio1['M'.$i]) - 1) * 100);
            }else{
                $lstCompradosRentARSPorc_Anio1['M'.$i] = 0;
            }
            if ($lstCompradosUSD_Anio1['M'.$i] > 0){
                $lstCompradosRentUSDPorc_Anio1['M'.$i] = round((($lstCobradosUSD_Anio1['M'.$i] /  $lstCompradosUSD_Anio1['M'.$i]) - 1) * 100);
                $lstCompradosRentUSDPorc_Spot_Anio1['M'.$i] = round((($lstCobradosUSD_Spot_Anio1['M'.$i] /  $lstCompradosUSD_Anio1['M'.$i]) - 1) * 100);
            }else{
                $lstCompradosRentUSDPorc_Anio1['M'.$i] = 0;
                $lstCompradosRentUSDPorc_Spot_Anio1['M'.$i] = 0;
            }

            if ($lstCompradosARS_Anio0['M'.$i] > 0){
                $lstCompradosRentARSPorc_Anio0['M'.$i] = round((($lstCobradosARS_Anio0['M'.$i] /  $lstCompradosARS_Anio0['M'.$i]) - 1) * 100);
            }else{
                $lstCompradosRentARSPorc_Anio0['M'.$i] = 0;
            }
            if ($lstCompradosUSD_Anio0['M'.$i] > 0){
                $lstCompradosRentUSDPorc_Anio0['M'.$i] = round((($lstCobradosUSD_Anio0['M'.$i] /  $lstCompradosUSD_Anio0['M'.$i]) - 1) * 100);
                $lstCompradosRentUSDPorc_Spot_Anio0['M'.$i] = round((($lstCobradosUSD_Spot_Anio0['M'.$i] /  $lstCompradosUSD_Anio0['M'.$i]) - 1) * 100);
            }else{
                $lstCompradosRentUSDPorc_Anio0['M'.$i] = 0;
                $lstCompradosRentUSDPorc_Spot_Anio0['M'.$i] = 0;
            }
    

            $lstCobradosARS_Anio['Total'] += $lstCobradosARS_Anio['M'.$i];
            $lstCobradosUSD_Anio['Total'] += $lstCobradosUSD_Anio['M'.$i];

            $lstCobradosARS_Anio1['Total'] += $lstCobradosARS_Anio1['M'.$i];
            $lstCobradosUSD_Anio1['Total'] += $lstCobradosUSD_Anio1['M'.$i];

            $lstCobradosARS_Anio0['Total'] += $lstCobradosARS_Anio0['M'.$i];
            $lstCobradosUSD_Anio0['Total'] += $lstCobradosUSD_Anio0['M'.$i];
           
        }

        if ($lstCompradosCantCasos_Anio['Total'] > 0){
            $lstRentUnitariaUSD['Total'] =  $lstCompradosRentUSD_Anio['Total'] / $lstCompradosCantCasos_Anio['Total'];
        }else{
            $lstRentUnitariaUSD['Total'] =  0;
        }

 

        $lstResumen->offsetSet(1, $itemsAnio);
        $lstResumen->offsetSet(2, $itemsAnio1);
        $lstResumen->offsetSet(3, $itemsAnio0);

        $lstResumenMeses->offsetSet(1, $arrEne);
        $lstResumenMeses->offsetSet(2, $arrFeb);
        $lstResumenMeses->offsetSet(3, $arrMar);
        $lstResumenMeses->offsetSet(4, $arrAbr);
        $lstResumenMeses->offsetSet(5, $arrMay);
        $lstResumenMeses->offsetSet(6, $arrJun);
        $lstResumenMeses->offsetSet(7, $arrJul);
        $lstResumenMeses->offsetSet(8, $arrAgo);
        $lstResumenMeses->offsetSet(9, $arrSep);
        $lstResumenMeses->offsetSet(10, $arrOct);
        $lstResumenMeses->offsetSet(11, $arrNov);
        $lstResumenMeses->offsetSet(11, $arrDic);

        $arrTotHNAnio = array();
        $arrTotHNAnio[0] = $SumHNARS_Anio0;
        $arrTotHNAnio[1] = $SumHNARS_Anio1;
        $arrTotHNAnio[2] = $SumHNARS_Anio;

        $lstPonderaciones = array();
        $lstPonderacionesCompra = array();

        $lstPonderacionesActual = array();
        

        $lstPonderaciones['A0'] = 0;
        $lstPonderaciones['A1'] = 0;
        $lstPonderaciones['A2'] = 0;

        $lstPonderacionesCompra['A0'] = 0;
        $lstPonderacionesCompra['A1'] = 0;
        $lstPonderacionesCompra['A2'] = 0;

        $lstPonderacionesActual['A0'] = 0;
        $lstPonderacionesActual['A1'] = 0;
        $lstPonderacionesActual['A2'] = 0;

        foreach ($itemsAnio as $item) {
            $ponderacion = 0;
            if($SumHNARS_Anio > 0){
                $ponderacion = $item->HaberNetoSubite / $SumHNARS_Anio;
            }

            $fc = $item->FechaCompra;
            $lstMesesAnio['M'.$fc->format("n")] += $item->HaberNetoSubite;
            /*
            if ($marca == 2){
                $item->Duration = $item->Duration / 365;
                $item->DurationCompra = $item->DurationCompra / 365;
            }
            */
            $durationPonderada = $item->Duration * $ponderacion;
            $durationPonderadaCompra = $item->DurationCompra * $ponderacion;
            $durationPonderadaActual = $item->Duration * $ponderacion;

            $lstPonderacionesCompra['A2'] += $durationPonderadaCompra;
            $lstPonderacionesActual['A2'] += $durationPonderadaActual;
        }


        $i = 1;
        $cantMeses = 1;
        //dd($lstHNCompradosARS_Anio['M1']);
        foreach ($lstResumenMeses as $itemsMeses) {
           
            foreach ($itemsMeses as $item) {

                $ponderacion = 0;
                if($lstHNCompradosARS_Anio['M'.$i] > 0){
                    $ponderacion = $item->HaberNetoSubite / $lstHNCompradosARS_Anio['M'.$i];
                }
          
                $durationPonderadaCompra = $item->DurationCompra * $ponderacion;
                $durationPonderadaActual = $item->Duration * $ponderacion;
                $lstPonderacionesMesesAnio['M'.$i] += $durationPonderadaCompra;
                $lstPonderacionesActualMesesAnio['M'.$i] += $durationPonderadaActual;



               
                /*
                $tir_PonderadaCompra = $item->TIR * $ponderacion;
                $lstPonderaciones_TIR_MesesAnio['M'.$i] += $tir_PonderadaCompra;

                $tirSpot_PonderadaCompra = $item->TIRSpot * $ponderacion;
                $lstPonderaciones_TIRSpot_MesesAnio['M'.$i] += $tirSpot_PonderadaCompra;
                */
                //$lstPonderaciones_TIRSpot_MesesAnio['Total'] += $lstPonderaciones_TIRSpot_MesesAnio['M'.$i];
                //$lstPonderaciones_TIR_MesesAnio['Total'] += $lstPonderaciones_TIR_MesesAnio['M'.$i];
                
                
            }
            $i++;
            $cantMeses++;
        }
  
        for ($i=1; $i < 13; $i++) { 
            /*
            if ($marca == 2){
                $lstPonderacionesMesesAnio['M'.$i] = round($lstPonderacionesMesesAnio['M'.$i] / 365, 1);
                $lstPonderacionesActualMesesAnio['M'.$i] = round($lstPonderacionesActualMesesAnio['M'.$i] / 365, 1);
            }else{
                $lstPonderacionesMesesAnio['M'.$i] = round($lstPonderacionesMesesAnio['M'.$i], 1);
                $lstPonderacionesActualMesesAnio['M'.$i] = round($lstPonderacionesActualMesesAnio['M'.$i], 1);
            }
            */

            $lstPonderacionesMesesAnio['M'.$i] = round($lstPonderacionesMesesAnio['M'.$i], 1);
            $lstPonderacionesActualMesesAnio['M'.$i] = round($lstPonderacionesActualMesesAnio['M'.$i], 1);

            if ($lstPonderacionesMesesAnio['M'.$i] > 0){
                /*
                $lstPonderaciones_TIR_MesesAnio['M'.$i] = $lstCompradosRentUSDPorc_Anio['M'.$i] / $lstPonderacionesMesesAnio['M'.$i];
                $lstPonderaciones_TIRSpot_MesesAnio['M'.$i] = $lstCompradosRentUSDPorc_Spot_Anio['M'.$i] / $lstPonderacionesMesesAnio['M'.$i];    
                */
                $lstPonderaciones_TIR_MesesAnio['M'.$i] = $lstCompradosRentUSDPorc_Anio['M'.$i] / $lstPonderacionesActualMesesAnio['M'.$i];
                $lstPonderaciones_TIRSpot_MesesAnio['M'.$i] = $lstCompradosRentUSDPorc_Spot_Anio['M'.$i] / $lstPonderacionesMesesAnio['M'.$i]; 
            }
            
        }

       
        foreach ($itemsAnio1 as $item) {
            $ponderacion = 0;
            if($SumHNARS_Anio1 > 0){
                $ponderacion = $item->HaberNetoSubite / $SumHNARS_Anio1;
            }

            $durationPonderadaCompra = $item->DurationCompra * $ponderacion;
            $durationPonderadaActual = $item->Duration * $ponderacion;

            $lstPonderacionesCompra['A1'] += $durationPonderadaCompra;
            $lstPonderacionesActual['A1'] += $durationPonderadaActual;
        }
        
        foreach ($itemsAnio0 as $item) {
            $ponderacion = 0;
            if($SumHNARS_Anio0 > 0){
                $ponderacion = $item->HaberNetoSubite / $SumHNARS_Anio0;
            }

            $durationPonderadaCompra = $item->DurationCompra * $ponderacion;
            $durationPonderadaActual = $item->Duration * $ponderacion;

            $lstPonderacionesCompra['A0'] += $durationPonderadaCompra;
            $lstPonderacionesActual['A0'] += $durationPonderadaActual;
        } //dd($arrTotHNAnio[1]);

  
        
        for ($i=0; $i < 3; $i++) { 

            /*
            if ($marca == 2){
                $lstPonderaciones['A'.$i] = round($lstPonderaciones['A'.$i] / 365, 1);
                $lstPonderacionesCompra['A'.$i] = round($lstPonderacionesCompra['A'.$i] / 365, 1);
                $lstPonderacionesActual['A'.$i] = round($lstPonderacionesActual['A'.$i] / 365, 1);
            }else{
                $lstPonderaciones['A'.$i] = round($lstPonderaciones['A'.$i], 1);
                $lstPonderacionesCompra['A'.$i] = round($lstPonderacionesCompra['A'.$i], 1);
                $lstPonderacionesActual['A'.$i] = round($lstPonderacionesActual['A'.$i], 1);
            }
            */
            $lstPonderaciones['A'.$i] = round($lstPonderaciones['A'.$i], 1);
            $lstPonderacionesCompra['A'.$i] = round($lstPonderacionesCompra['A'.$i], 1);
            $lstPonderacionesActual['A'.$i] = round($lstPonderacionesActual['A'.$i], 1);
            
        }
     
        $lstPonderacionesMesesAnio['Total'] =  $lstPonderacionesCompra['A2'];

        if ($lstCompradosARS_Anio['Total'] > 0){
            $lstCompradosRentARSPorc_Anio['Total'] = round((($lstCobradosARS_Anio['Total'] /  $lstCompradosARS_Anio['Total']) - 1) * 100);
        }else{
            $lstCompradosRentARSPorc_Anio['Total'] = 0;
        }
        if ($lstCompradosUSD_Anio['Total'] > 0){
            $lstCompradosRentUSDPorc_Anio['Total'] = round((($lstCobradosUSD_Anio['Total'] /  $lstCompradosUSD_Anio['Total']) - 1) * 100);

            $lstCompradosRentUSDPorc_Spot_Anio['Total'] = round((($lstCobradosUSD_Spot_Anio['Total'] /  $lstCompradosUSD_Anio['Total']) - 1) * 100);

            if ($lstPonderacionesMesesAnio['Total'] > 0){
                $lstPonderaciones_TIR_MesesAnio['Total'] = $lstCompradosRentUSDPorc_Anio['Total'] / $lstPonderacionesMesesAnio['Total'];
                $lstPonderaciones_TIRSpot_MesesAnio['Total'] = $lstCompradosRentUSDPorc_Spot_Anio['Total'] / $lstPonderacionesMesesAnio['Total'];
            }else{
                $lstPonderaciones_TIR_MesesAnio['Total'] = 0;
                $lstPonderaciones_TIRSpot_MesesAnio['Total'] = 0;
            }
            

        }else{
            $lstCompradosRentUSDPorc_Anio['Total'] = 0;
            $lstCompradosRentUSDPorc_Spot_Anio['Total'] = 0;
        }


        
       

        if ($lstCompradosARS_Anio1['Total'] > 0){
            $lstCompradosRentARSPorc_Anio1['Total'] = round((($lstCobradosARS_Anio1['Total'] /  $lstCompradosARS_Anio1['Total']) - 1) * 100);
        }else{
            $lstCompradosRentARSPorc_Anio1['Total'] = 0;
        }
        if ($lstCompradosUSD_Anio1['Total'] > 0){
            $lstCompradosRentUSDPorc_Anio1['Total'] = round((($lstCobradosUSD_Anio1['Total'] /  $lstCompradosUSD_Anio1['Total']) - 1) * 100);
            $lstCompradosRentUSDPorc_Spot_Anio1['Total'] = round((($lstCobradosUSD_Spot_Anio1['Total'] /  $lstCompradosUSD_Anio1['Total']) - 1) * 100);
        }else{
            $lstCompradosRentUSDPorc_Anio1['Total'] = 0;
            $lstCompradosRentUSDPorc_Spot_Anio1['Total'] = 0;
        }

        if ($lstCompradosARS_Anio0['Total'] > 0){
            $lstCompradosRentARSPorc_Anio0['Total'] = round((($lstCobradosARS_Anio0['Total'] /  $lstCompradosARS_Anio0['Total']) - 1) * 100);
        }else{
            $lstCompradosRentARSPorc_Anio0['Total'] = 0;
        }
        if ($lstCompradosUSD_Anio0['Total'] > 0){
            $lstCompradosRentUSDPorc_Anio0['Total'] = round((($lstCobradosUSD_Anio0['Total'] /  $lstCompradosUSD_Anio0['Total']) - 1) * 100);
            $lstCompradosRentUSDPorc_Spot_Anio0['Total'] = round((($lstCobradosUSD_Spot_Anio0['Total'] /  $lstCompradosUSD_Anio0['Total']) - 1) * 100);
        }else{
            $lstCompradosRentUSDPorc_Anio0['Total'] = 0;
            $lstCompradosRentUSDPorc_Spot_Anio0['Total'] = 0;
        }


        $lstTotales_Anio['HN'] = $lstCompradosARS_Anio['Total'];
        $lstTotales_Anio['RentARS'] = $lstCompradosRentARS_Anio['Total'];
        $lstTotales_Anio['RentARS_Porc'] = $lstCompradosRentARSPorc_Anio['Total'];
        $lstTotales_Anio['RentUSD'] = $lstCompradosRentUSD_Anio['Total'];
        $lstTotales_Anio['RentUSD_Porc'] = $lstCompradosRentUSDPorc_Anio['Total'];

        //$lstCompradosDurationCompra_Anio
        //$lstCompradosDurationActual_Anio
        $lstTotales_Anio['Duration'] = $lstPonderacionesCompra['A2'];
        if($lstPonderacionesCompra['A2'] > 0){
            $lstTotales_Anio['TIR'] = $lstCompradosRentUSDPorc_Anio['Total'] / $lstPonderacionesCompra['A2'];

        }else{
            $lstTotales_Anio['TIR'] = 0;
        }

        $lstTotales_Anio['RentUSD_Spot'] = $lstCompradosRentUSD_Spot_Anio['Total'];
        $lstTotales_Anio['RentUSD_Spot_Porc'] = $lstCompradosRentUSDPorc_Spot_Anio['Total'];
        $lstTotales_Anio['TIR_Spot'] = $lstPonderaciones_TIRSpot_MesesAnio['Total'];



        $lstTotales_Anio1['HN'] = $lstCompradosARS_Anio1['Total'];
        $lstTotales_Anio1['RentARS'] = $lstCompradosRentARS_Anio1['Total'];
        $lstTotales_Anio1['RentARS_Porc'] = $lstCompradosRentARSPorc_Anio1['Total'];
        $lstTotales_Anio1['RentUSD'] = $lstCompradosRentUSD_Anio1['Total'];
        $lstTotales_Anio1['RentUSD_Porc'] = $lstCompradosRentUSDPorc_Anio1['Total'];

        $lstTotales_Anio1['Duration'] = $lstPonderacionesCompra['A1'];
        if($lstPonderacionesCompra['A1'] > 0){
            $lstTotales_Anio1['TIR'] = $lstCompradosRentUSDPorc_Anio1['Total'] / $lstPonderacionesCompra['A1'];
        }else{
            $lstTotales_Anio1['TIR'] = 0;
        }

        $lstTotales_Anio1['RentUSD_Spot'] = $lstCompradosRentUSD_Spot_Anio1['Total'];
        $lstTotales_Anio1['RentUSD_Spot_Porc'] = $lstCompradosRentUSDPorc_Spot_Anio1['Total'];
        if($lstPonderacionesCompra['A1'] > 0){
            $lstTotales_Anio1['TIR_Spot'] = $lstCompradosRentUSDPorc_Spot_Anio1['Total'] / $lstPonderacionesCompra['A1'];
        }else{
            $lstTotales_Anio1['TIR_Spot'] = 0;
        }

        $lstTotales_Anio0['HN'] = $lstCompradosARS_Anio0['Total'];
        $lstTotales_Anio0['RentARS'] = $lstCompradosRentARS_Anio0['Total'];
        $lstTotales_Anio0['RentARS_Porc'] = $lstCompradosRentARSPorc_Anio0['Total'];
        $lstTotales_Anio0['RentUSD'] = $lstCompradosRentUSD_Anio0['Total'];
        $lstTotales_Anio0['RentUSD_Porc'] = $lstCompradosRentUSDPorc_Anio0['Total'];

        $lstTotales_Anio0['Duration'] = $lstPonderacionesCompra['A0'];
        if($lstPonderacionesCompra['A0'] > 0){
            $lstTotales_Anio0['TIR'] = $lstCompradosRentUSDPorc_Anio0['Total'] / $lstPonderacionesCompra['A0'];
        }else{
            $lstTotales_Anio0['TIR'] = 0;
        }
        
        $lstTotales_Anio0['RentUSD_Spot'] = $lstCompradosRentUSD_Spot_Anio0['Total'];
        $lstTotales_Anio0['RentUSD_Spot_Porc'] = $lstCompradosRentUSDPorc_Spot_Anio0['Total'];
        if($lstPonderacionesCompra['A0'] > 0){
            $lstTotales_Anio0['TIR_Spot'] = $lstCompradosRentUSDPorc_Spot_Anio0['Total'] / $lstPonderacionesCompra['A0'];
        }else{
            $lstTotales_Anio0['TIR_Spot'] = 0;
        }
        

       
        $arrAnios0['Anio'] = $anio0;
        $arrAnios0['Valores'] = $lstTotales_Anio0;
        array_push($listAnios, $arrAnios0);

        $arrAnios1['Anio'] = $anio1;
        $arrAnios1['Valores'] = $lstTotales_Anio1;
        array_push($listAnios, $arrAnios1);

        $arrAnios['Anio'] = $anio;
        $arrAnios['Valores'] = $lstTotales_Anio;
        array_push($listAnios, $arrAnios);


        $arrCompraARS['Tipo'] = 'HN Comprados $';
        $arrCompraARS['Fila'] = 1;
        $arrCompraARS['Valores'] = $lstCompradosARS_Anio;
        array_push($listMeses, $arrCompraARS);

        $arrCompraUSD['Tipo'] = 'HN Comprados USD';
        $arrCompraUSD['Fila'] = 2;
        $arrCompraUSD['Valores'] = $lstCompradosUSD_Anio;
        array_push($listMeses, $arrCompraUSD);

        $arrCC['Tipo'] = 'HN Comprados (Cant.)';
        $arrCC['Fila'] = 3;
        $arrCC['Valores'] = $lstCompradosCantCasos_Anio;
        array_push($listMeses, $arrCC);

        $arrDur['Tipo'] = 'Duration HN Comprados';
        $arrDur['Fila'] = 4;
        $arrDur['Valores'] = $lstPonderacionesMesesAnio;
        array_push($listMeses, $arrDur);

        $arrRentARS['Tipo'] = 'Rent. $';
        $arrRentARS['Fila'] = 5;
        $arrRentARS['Valores'] = $lstCompradosRentARS_Anio;
        array_push($listMeses, $arrRentARS);

        $arrRentARS_Porc['Tipo'] = 'Rent. $ (%)';
        $arrRentARS_Porc['Fila'] = 6;
        $arrRentARS_Porc['Valores'] = $lstCompradosRentARSPorc_Anio;
        array_push($listMeses, $arrRentARS_Porc);

        $arrRentUSD['Tipo'] = 'Rent. USD';
        $arrRentUSD['Fila'] = 7;
        $arrRentUSD['Valores'] = $lstCompradosRentUSD_Anio;
        array_push($listMeses, $arrRentUSD);

        $arrRentUnitUSD['Tipo'] = 'Rent. Unitaria USD';
        $arrRentUnitUSD['Fila'] = 8;
        $arrRentUnitUSD['Valores'] = $lstRentUnitariaUSD;
        array_push($listMeses, $arrRentUnitUSD);

        $arrRentUSD_Porc['Tipo'] = 'Rent. USD (%)';
        $arrRentUSD_Porc['Fila'] = 9;
        $arrRentUSD_Porc['Valores'] = $lstCompradosRentUSDPorc_Anio;
        array_push($listMeses, $arrRentUSD_Porc);

        $arrRentUSD_Porc['Tipo'] = 'TIR';
        $arrRentUSD_Porc['Fila'] = 10;
        $arrRentUSD_Porc['Valores'] = $lstPonderaciones_TIR_MesesAnio;
        array_push($listMeses, $arrRentUSD_Porc);


        $arrRentUSD['Tipo'] = 'Rent. USD Spot';
        $arrRentUSD['Fila'] = 11;
        $arrRentUSD['Valores'] = $lstCompradosRentUSD_Spot_Anio;
        array_push($listMeses, $arrRentUSD);

        $arrRentUSD_Porc['Tipo'] = 'Rent. USD Spot (%)';
        $arrRentUSD_Porc['Fila'] = 12;
        $arrRentUSD_Porc['Valores'] = $lstCompradosRentUSDPorc_Spot_Anio;
        array_push($listMeses, $arrRentUSD_Porc);

        $arrRentUSD_Porc['Tipo'] = 'TIR Spot';
        $arrRentUSD_Porc['Fila'] = 13;
        $arrRentUSD_Porc['Valores'] = $lstPonderaciones_TIRSpot_MesesAnio; 
        array_push($listMeses, $arrRentUSD_Porc);


        $respuesta['lstMeses'] = $listMeses;
        $respuesta['listAnios'] = $listAnios;

        return $respuesta;
    }


}