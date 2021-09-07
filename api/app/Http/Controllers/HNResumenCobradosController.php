<?php

namespace App\Http\Controllers;

use App\HaberNeto;
use Illuminate\Http\Request;
use DB;
use DateTime;
use App\Http\Controllers\UtilsController;
use ArrayObject;

class HNResumenCobradosController extends Controller
{

    
    public function getListHNResumenCobrados(Request $request){
        $lstResumenCobro = array();

        $anio = $request->Anio;

        $marca = $request->Marca;
        $concesionario = $request->Concesionario;
        $filtros = $request->Filtros;
        $rbConsolidado = $request->ConsolidadoRB;

        $lstItemsHN_Resumen = $this->getItemsResumen($anio, $marca, $concesionario, $filtros, $rbConsolidado);
        
        $lstCobrados = $this->getResumenCobradosMeses($anio, $lstItemsHN_Resumen['ListHN'], $marca);
        $lstCobrados_CE = $this->getResumenCobradosMeses($anio, $lstItemsHN_Resumen['ListHN_CE'], $marca);

        $lstResumenCobro['Grid1_Cobrados'] = $lstCobrados['lstMeses'];
        $lstResumenCobro['Grid2_Cobrados'] = $lstCobrados['listAnios'];
        $lstResumenCobro['Grid1_Cobrados_CE'] = $lstCobrados_CE['lstMeses'];
        $lstResumenCobro['Grid2_Cobrados_CE'] = $lstCobrados_CE['listAnios'];
        
        return $lstResumenCobro;
    
    }

    public function getItemsResumen_Selecteds($anio, $marca, $concesionario, $soloCE_Giama_Todos, $filtros, $rbConsolidado){
    
        $respuesta = array(); 
        $list = array();
        $lstHN = array();
        $lstHN_CE = array();

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
                default:
                    $db = "GF";
                break;
            }
            
        }else{
            $db = "GF";
        }

        if ($db == 'RB' && $rbConsolidado){
            $lstHN_RB = array();
            $lstHN_GF = array();

            $lstHN_RB = DB::connection('RB')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> '' AND Concesionario = ".$concesionario);

            //$lstHN_GF = DB::connection('GF')->select("SELECT *  FROM haberesnetosok WHERE ComproGiama = 1");

            //$lstHN = array_merge($lstHN_RB, $lstHN_GF);
            $lstHN = $lstHN_RB;
        }else{

            if ($marca == 99){ //GIAMA
                $lstHN_RB = array();
                $lstHN_GF = array();

                $lstHN_AN = array();
                $lstHN_CG = array();

                $lstHN_RB = DB::connection('RB')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> '' AND Concesionario = 8");

                $lstHN_GF = DB::connection('GF')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> '' AND (ComproGiama = 1 OR ContabilizarParaRB = 1)");

                $lstHN_AN = DB::connection('AN')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> '' AND Concesionario = 5");
                $lstHN_CG = DB::connection('CG')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> '' AND Concesionario = 6");
    
                $lstHN = array_merge($lstHN_RB, $lstHN_GF, $lstHN_AN, $lstHN_CG);
            }else{

                switch ($soloCE_Giama_Todos){
                    case 0: // TODOS
                        $lstHN = DB::connection($db)->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> '' AND Concesionario = ".$concesionario);
                    break;
                    case 1: // GIAMA
                        $lstHN = DB::connection($db)->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> '' AND (ComproGiama = 1 OR ContabilizarParaRB = 1) AND Concesionario = ".$concesionario);
                    break;
                    case 2: //SOLO CE
                        $lstHN = DB::connection($db)->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> '' AND (ComproGiama = 0 AND IFNULL(ContabilizarParaRB, 2) = 2) AND Concesionario = ".$concesionario);
                    break;
                }

            }        
        }

        return $lstHN;
    }

    public function getItemsResumen($anio, $marca, $concesionario, $filtros, $rbConsolidado){
    
        $respuesta = array(); 
        $list = array();
        $lstHN = array();
        $lstHN_CE = array();

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

            $lstHN_RB = DB::connection('RB')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> '' AND Concesionario = ".$concesionario);

            $lstHN_GF = DB::connection('GF')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> '' AND (ComproGiama = 1 OR ContabilizarParaRB = 1)");

            $lstHN = array_merge($lstHN_RB, $lstHN_GF);
        }else{

            if ($marca == 99){ //GIAMA
                $lstHN_RB = array();
                $lstHN_GF = array();

                $lstHN_AN = array();
                $lstHN_CG = array();

                $lstHN_RB = DB::connection('RB')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> '' AND Concesionario = 8");

                $lstHN_GF = DB::connection('GF')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> '' AND (ComproGiama = 1 OR ContabilizarParaRB = 1)");

                $lstHN_AN = DB::connection('AN')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> '' AND Concesionario = 5");
                $lstHN_CG = DB::connection('CG')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') <> '' AND Concesionario = 6");
    
                $lstHN = array_merge($lstHN_RB, $lstHN_GF, $lstHN_AN, $lstHN_CG);
            }else{

                $lstHN = HaberNeto::on($db)->where('Concesionario', $concesionario)->get();
                //$lstHN = HaberNeto::on($db)->where('TipoCompra', '=', 1)->whereYear('FechaAltaRegistro', '>=', 2018)->where('Concesionario', $concesionario)->get();
                $lstHN_CE = HaberNeto::on($db)->where('Concesionario', $concesionario)->where('ComproGiama', 0)->get();
            }        
        }
        //->whereYear('FechaAltaRegistro', '=', $anio)->orWhereYear('FechaCobroReal', '=', $anio)->get();

       //return $lstHN;
       $list['ListHN'] = $lstHN;
       $list['ListHN_CE'] = $lstHN_CE;

       return $list;
    }

    public function getResumenCobradosMeses($anio, $listItems, $marca){
        $respuesta = array();
        $listMeses = array();

        $listAnios = array();

        $anio0 = $anio - 2;
        $anio1 = $anio - 1;
   

        $keys = array('M1', 'M2', 'M3', 'M4', 'M5', 'M6', 'M7', 'M8', 'M9', 'M10', 'M11', 'M12', 'Total');
        $keysAnios = array('HN', 'RentARS', 'RentARS_Porc', 'RentUSD', 'RentUSD_Porc', 'Duration', 'TIR');

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

        $lstCobradosCantCasos_Anio = array();
        $lstCobradosCantCasos_Anio = array_fill_keys($keys, 0);
        $lstCobradosCantCasos_Anio1 = array();
        $lstCobradosCantCasos_Anio1 = array_fill_keys($keys, 0);
        $lstCobradosCantCasos_Anio0 = array();
        $lstCobradosCantCasos_Anio0 = array_fill_keys($keys, 0);

        $lstCobradosDuration_Anio = array();
        $lstCobradosDuration_Anio = array_fill_keys($keys, 0);
        $lstCobradosDuration_Anio1 = array();
        $lstCobradosDuration_Anio1 = array_fill_keys($keys, 0);
        $lstCobradosDuration_Anio0 = array();
        $lstCobradosDuration_Anio0 = array_fill_keys($keys, 0);

        $lstCobradosRentARS_Anio = array();
        $lstCobradosRentARS_Anio = array_fill_keys($keys, 0);
        $lstCobradosRentARS_Anio1 = array();
        $lstCobradosRentARS_Anio1 = array_fill_keys($keys, 0);
        $lstCobradosRentARS_Anio0 = array();
        $lstCobradosRentARS_Anio0 = array_fill_keys($keys, 0);

        $lstCobradosRentARSPorc_Anio = array();
        $lstCobradosRentARSPorc_Anio = array_fill_keys($keys, 0);
        $lstCobradosRentARSPorc_Anio1 = array();
        $lstCobradosRentARSPorc_Anio1 = array_fill_keys($keys, 0);
        $lstCobradosRentARSPorc_Anio0 = array();
        $lstCobradosRentARSPorc_Anio0 = array_fill_keys($keys, 0);

        $lstCobradosRentUSD_Anio = array();
        $lstCobradosRentUSD_Anio = array_fill_keys($keys, 0);
        $lstCobradosRentUSD_Anio1 = array();
        $lstCobradosRentUSD_Anio1 = array_fill_keys($keys, 0);
        $lstCobradosRentUSD_Anio0 = array();
        $lstCobradosRentUSD_Anio0 = array_fill_keys($keys, 0);

        $lstCobradosRentUSDPorc_Anio = array();
        $lstCobradosRentUSDPorc_Anio = array_fill_keys($keys, 0);
        $lstCobradosRentUSDPorc_Anio1 = array();
        $lstCobradosRentUSDPorc_Anio1 = array_fill_keys($keys, 0);
        $lstCobradosRentUSDPorc_Anio0 = array();
        $lstCobradosRentUSDPorc_Anio0 = array_fill_keys($keys, 0);

        $lstTotales_Anio = array();
        $lstTotales_Anio = array_fill_keys($keysAnios, 0);
        $lstTotales_Anio1 = array();
        $lstTotales_Anio1 = array_fill_keys($keysAnios, 0);
        $lstTotales_Anio0 = array();
        $lstTotales_Anio0 = array_fill_keys($keysAnios, 0);

        $lstHNCompradosARS_Anio = array();
        $lstHNCompradosARS_Anio = array_fill_keys($keys, 0);
        
        $itemsAnio = array();
        $itemsAnio1 = array();
        $itemsAnio0 = array();

        $lstArr = array();

        $lstArrMeses = array();
        $lstArrMeses = array_fill_keys($keys, 0);

        $lstMesesAnio = array();
        $lstMesesAnio = array_fill_keys($keys, 0);

        $lstPonderacionesMesesAnio = array();
        $lstPonderacionesMesesAnio = array_fill_keys($keys, 0);


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

            if (!is_null($it->FechaCobroReal)){
             
                $fecha = date('Y-m-d', strtotime($it->FechaCobroReal));
                $ff = DateTime::createFromFormat("Y-m-d", $fecha);

                //if($ff->format("Y") == $anio){
                switch ($ff->format("Y")){

                    case $anio:
                        $lstCobradosARS_Anio['M'.$ff->format("n")] += $it->MontoCobroReal;
                        $lstCobradosUSD_Anio['M'.$ff->format("n")] += $it->MontoCobroDolares; 
                        
                        $lstCompradosARS_Anio['M'.$ff->format("n")] += $it->MontoCompra;
                        $lstCompradosUSD_Anio['M'.$ff->format("n")] += $it->MontoCompraDolares;

                        $lstHNCompradosARS_Anio['M'.$ff->format("n")] += $it->HaberNetoSubite;
                        
                        $SumHNARS_Anio += $it->HaberNetoSubite;

                       
                        $lstCobradosCantCasos_Anio['M'.$ff->format("n")] += 1;

                        $itemHN_Mes = new \stdClass();
                        $itemHN_Mes->FechaCobroReal = $ff;
                        $itemHN_Mes->Duration = $it->DurationActual;
                        $itemHN_Mes->DurationCobro = $it->DurationCobro;
                        $itemHN_Mes->DurationCompra = $it->DurationCompra;
                        $itemHN_Mes->HaberNetoSubite = $it->HaberNetoSubite;
                        $itemHN_Mes->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;
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

                    case $anio1:
                        $lstCobradosARS_Anio1['M'.$ff->format("n")] += $it->MontoCobroReal;
                        $lstCobradosUSD_Anio1['M'.$ff->format("n")] += $it->MontoCobroDolares; 
                        
                        $lstCompradosARS_Anio1['M'.$ff->format("n")] += $it->MontoCompra;
                        $lstCompradosUSD_Anio1['M'.$ff->format("n")] += $it->MontoCompraDolares; 
                       
                        $lstCobradosCantCasos_Anio1['M'.$ff->format("n")] += 1;

                        $SumHNARS_Anio1 += $it->HaberNetoSubite;

                        $itemHN = new \stdClass();
                        $itemHN->Duration = $it->DurationActual;
                        $itemHN->DurationCobro = $it->DurationCobro;
                        $itemHN->DurationCompra = $it->DurationCompra;
                        $itemHN->HaberNetoSubite = $it->HaberNetoSubite;
                        $itemHN->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;
                        $itemHN->DurationPonderada = 0;
                       
                        array_push($itemsAnio1, $itemHN);
                    break;

                    case $anio0:
                        $lstCobradosARS_Anio0['M'.$ff->format("n")] += $it->MontoCobroReal;
                        $lstCobradosUSD_Anio0['M'.$ff->format("n")] += $it->MontoCobroDolares; 
                        
                        $lstCompradosARS_Anio0['M'.$ff->format("n")] += $it->MontoCompra;
                        $lstCompradosUSD_Anio0['M'.$ff->format("n")] += $it->MontoCompraDolares; 
                       
                        $lstCobradosCantCasos_Anio0['M'.$ff->format("n")] += 1;
                        
                        $SumHNARS_Anio0 += $it->HaberNetoSubite;

                        $itemHN = new \stdClass();
                        $itemHN->Duration = $it->DurationActual;
                        $itemHN->DurationCobro = $it->DurationCobro;
                        $itemHN->DurationCompra = $it->DurationCompra;
                        $itemHN->HaberNetoSubite = $it->HaberNetoSubite;
                        $itemHN->HaberNetoSubiteUSD = $it->HaberNetoSubiteUSD;
                        $itemHN->DurationPonderada = 0;
                       
                        array_push($itemsAnio0, $itemHN);
                    break;
                    /*
                    default:
                        continue;
                     break;
                   */
                } 

            }

        } //END FOREACH

       
        
        for ($i=1; $i < 13; $i++) { 

            $lstCobradosRentARS_Anio['M'.$i] =  $lstCobradosARS_Anio['M'.$i] - $lstCompradosARS_Anio['M'.$i];
            $lstCobradosRentUSD_Anio['M'.$i] =  $lstCobradosUSD_Anio['M'.$i] - $lstCompradosUSD_Anio['M'.$i];

            $lstCobradosARS_Anio['Total'] += $lstCobradosARS_Anio['M'.$i];
            $lstCobradosUSD_Anio['Total'] += $lstCobradosUSD_Anio['M'.$i];
            $lstCobradosCantCasos_Anio['Total'] += $lstCobradosCantCasos_Anio['M'.$i];

            $lstCobradosRentARS_Anio['Total'] += $lstCobradosRentARS_Anio['M'.$i];
            $lstCobradosRentUSD_Anio['Total'] += $lstCobradosRentUSD_Anio['M'.$i];

            $lstCompradosARS_Anio['Total'] += $lstCompradosARS_Anio['M'.$i]; 
            $lstCompradosUSD_Anio['Total'] += $lstCompradosUSD_Anio['M'.$i];

            if ($lstCompradosARS_Anio['M'.$i] > 0){
                $lstCobradosRentARSPorc_Anio['M'.$i] = round((($lstCobradosARS_Anio['M'.$i] /  $lstCompradosARS_Anio['M'.$i]) - 1) * 100);
            }else{
                $lstCobradosRentARSPorc_Anio['M'.$i] = 0;
            }
            if ($lstCompradosUSD_Anio['M'.$i] > 0){
                $lstCobradosRentUSDPorc_Anio['M'.$i] = round((($lstCobradosUSD_Anio['M'.$i] /  $lstCompradosUSD_Anio['M'.$i]) - 1) * 100);
            }else{
                $lstCobradosRentUSDPorc_Anio['M'.$i] = 0;
            }

            // Anio1
            $lstCobradosRentARS_Anio1['M'.$i] =  $lstCobradosARS_Anio1['M'.$i] - $lstCompradosARS_Anio1['M'.$i];
            $lstCobradosRentUSD_Anio1['M'.$i] =  $lstCobradosUSD_Anio1['M'.$i] - $lstCompradosUSD_Anio1['M'.$i];

            $lstCobradosARS_Anio1['Total'] += $lstCobradosARS_Anio1['M'.$i];
            $lstCobradosUSD_Anio1['Total'] += $lstCobradosUSD_Anio1['M'.$i];
            $lstCobradosCantCasos_Anio1['Total'] += $lstCobradosCantCasos_Anio1['M'.$i];

            $lstCobradosRentARS_Anio1['Total'] += $lstCobradosRentARS_Anio1['M'.$i];
            $lstCobradosRentUSD_Anio1['Total'] += $lstCobradosRentUSD_Anio1['M'.$i];

            $lstCompradosARS_Anio1['Total'] += $lstCompradosARS_Anio1['M'.$i]; 
            $lstCompradosUSD_Anio1['Total'] += $lstCompradosUSD_Anio1['M'.$i];

            if ($lstCompradosARS_Anio1['M'.$i] > 0){
                $lstCobradosRentARSPorc_Anio1['M'.$i] = round((($lstCobradosARS_Anio1['M'.$i] /  $lstCompradosARS_Anio1['M'.$i]) - 1) * 100);
            }else{
                $lstCobradosRentARSPorc_Anio1['M'.$i] = 0;
            }
            if ($lstCompradosUSD_Anio1['M'.$i] > 0){
                $lstCobradosRentUSDPorc_Anio1['M'.$i] = round((($lstCobradosUSD_Anio1['M'.$i] /  $lstCompradosUSD_Anio1['M'.$i]) - 1) * 100);
            }else{
                $lstCobradosRentUSDPorc_Anio1['M'.$i] = 0;
            }

            // Anio0
            $lstCobradosRentARS_Anio0['M'.$i] =  $lstCobradosARS_Anio0['M'.$i] - $lstCompradosARS_Anio0['M'.$i];
            $lstCobradosRentUSD_Anio0['M'.$i] =  $lstCobradosUSD_Anio0['M'.$i] - $lstCompradosUSD_Anio0['M'.$i];

            $lstCobradosARS_Anio0['Total'] += $lstCobradosARS_Anio0['M'.$i];
            $lstCobradosUSD_Anio0['Total'] += $lstCobradosUSD_Anio0['M'.$i];
            $lstCobradosCantCasos_Anio0['Total'] += $lstCobradosCantCasos_Anio0['M'.$i];

            $lstCobradosRentARS_Anio0['Total'] += $lstCobradosRentARS_Anio0['M'.$i];
            $lstCobradosRentUSD_Anio0['Total'] += $lstCobradosRentUSD_Anio0['M'.$i];

            $lstCompradosARS_Anio0['Total'] += $lstCompradosARS_Anio0['M'.$i]; 
            $lstCompradosUSD_Anio0['Total'] += $lstCompradosUSD_Anio0['M'.$i];

            if ($lstCompradosARS_Anio0['M'.$i] > 0){
                $lstCobradosRentARSPorc_Anio0['M'.$i] = round((($lstCobradosARS_Anio0['M'.$i] /  $lstCompradosARS_Anio0['M'.$i]) - 1) * 100);
            }else{
                $lstCobradosRentARSPorc_Anio0['M'.$i] = 0;
            }
            if ($lstCompradosUSD_Anio0['M'.$i] > 0){
                $lstCobradosRentUSDPorc_Anio0['M'.$i] = round((($lstCobradosUSD_Anio0['M'.$i] /  $lstCompradosUSD_Anio0['M'.$i]) - 1) * 100);
            }else{
                $lstCobradosRentUSDPorc_Anio0['M'.$i] = 0;
            }

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
        $lstPonderacionesCobro = array();

        $lstPonderaciones['A0'] = 0;
        $lstPonderaciones['A1'] = 0;
        $lstPonderaciones['A2'] = 0;

        $lstPonderacionesCobro['A0'] = 0;
        $lstPonderacionesCobro['A1'] = 0;
        $lstPonderacionesCobro['A2'] = 0;

        //dd($arrTotHNAnio[1]);

        //$lstMesesAnio = array();

        foreach ($itemsAnio as $item) {
            $ponderacion = 0;
            if($SumHNARS_Anio > 0){
                $ponderacion = $item->HaberNetoSubite / $SumHNARS_Anio;
            }

       
            $fc = $item->FechaCobroReal;
            $lstMesesAnio['M'.$fc->format("n")] += $item->HaberNetoSubite;
            $durationPonderadaCobro = $item->DurationCobro * $ponderacion;
            $lstPonderacionesCobro['A2'] += $durationPonderadaCobro;


        }

        $i = 1;
        //dd($lstHNCompradosARS_Anio['M1']);
        foreach ($lstResumenMeses as $itemsMeses) {
           
            foreach ($itemsMeses as $item) {

                $ponderacion = 0;
                if($lstHNCompradosARS_Anio['M'.$i] > 0){
                    $ponderacion = $item->HaberNetoSubite / $lstHNCompradosARS_Anio['M'.$i];
                }
          
                $durationPonderadaCobro = $item->DurationCobro * $ponderacion;
                $lstPonderacionesMesesAnio['M'.$i] += $durationPonderadaCobro;
            }
            $i++;
        }
  
        for ($i=1; $i < 13; $i++) { 
            /*
            if ($marca == 2){
                $lstPonderacionesMesesAnio['M'.$i] = round($lstPonderacionesMesesAnio['M'.$i] / 365, 1);
            }else{
                $lstPonderacionesMesesAnio['M'.$i] = round($lstPonderacionesMesesAnio['M'.$i], 1);
            }
            */
            $lstPonderacionesMesesAnio['M'.$i] = round($lstPonderacionesMesesAnio['M'.$i], 1);
            
        }

       

        foreach ($itemsAnio1 as $item) {
            $ponderacion = 0;
            if($SumHNARS_Anio1 > 0){
                $ponderacion = $item->HaberNetoSubite / $SumHNARS_Anio1;
            }

            $durationPonderadaCobro = $item->DurationCobro * $ponderacion;
            $lstPonderacionesCobro['A1'] += $durationPonderadaCobro;
        }
        
        foreach ($itemsAnio0 as $item) {
            $ponderacion = 0;
            if($SumHNARS_Anio0 > 0){
                $ponderacion = $item->HaberNetoSubite / $SumHNARS_Anio0;
            }

            $durationPonderadaCobro = $item->DurationCobro * $ponderacion;
            $lstPonderacionesCobro['A0'] += $durationPonderadaCobro;
        }

        
//dd($lstPonderacionesCobro);

     
        for ($i=0; $i < 3; $i++) { 
            /*
            if ($marca == 2){
                $lstPonderaciones['A'.$i] = round($lstPonderaciones['A'.$i] / 365, 1);
                $lstPonderacionesCobro['A'.$i] = round($lstPonderacionesCobro['A'.$i] / 365, 1);
            }else{
                $lstPonderaciones['A'.$i] = round($lstPonderaciones['A'.$i], 1);
                $lstPonderacionesCobro['A'.$i] = round($lstPonderacionesCobro['A'.$i], 1);
            }
            */

            $lstPonderaciones['A'.$i] = round($lstPonderaciones['A'.$i], 1);
            $lstPonderacionesCobro['A'.$i] = round($lstPonderacionesCobro['A'.$i], 1);
            
        }
      

        $lstPonderacionesMesesAnio['Total'] =  $lstPonderacionesCobro['A2'];

        if ($lstCompradosARS_Anio['Total'] > 0){
            $lstCobradosRentARSPorc_Anio['Total'] = round((($lstCobradosRentARS_Anio['Total'] /  $lstCompradosARS_Anio['Total']) - 1) * 100);
        }else{
            $lstCobradosRentARSPorc_Anio['Total'] = 0;
        }
        if ($lstCompradosUSD_Anio['Total'] > 0){
            $lstCobradosRentUSDPorc_Anio['Total'] = round((($lstCobradosRentUSD_Anio['Total'] /  $lstCompradosUSD_Anio['Total']) - 1) * 100);
        }else{
            $lstCobradosRentUSDPorc_Anio['Total'] = 0;
        }

        //Anio 1
        if ($lstCompradosARS_Anio1['Total'] > 0){
            $lstCobradosRentARSPorc_Anio1['Total'] = round((($lstCobradosRentARS_Anio1['Total'] /  $lstCompradosARS_Anio1['Total']) - 1) * 100);
        }else{
            $lstCobradosRentARSPorc_Anio1['Total'] = 0;
        }
        if ($lstCompradosUSD_Anio1['Total'] > 0){
            $lstCobradosRentUSDPorc_Anio1['Total'] = round((($lstCobradosRentUSD_Anio1['Total'] /  $lstCompradosUSD_Anio1['Total']) - 1) * 100);
        }else{
            $lstCobradosRentUSDPorc_Anio1['Total'] = 0;
        }

        //Anio0
        if ($lstCompradosARS_Anio0['Total'] > 0){
            $lstCobradosRentARSPorc_Anio0['Total'] = round((($lstCobradosRentARS_Anio0['Total'] /  $lstCompradosARS_Anio0['Total']) - 1) * 100);
        }else{
            $lstCobradosRentARSPorc_Anio0['Total'] = 0;
        }
        if ($lstCompradosUSD_Anio0['Total'] > 0){
            $lstCobradosRentUSDPorc_Anio0['Total'] = round((($lstCobradosRentUSD_Anio0['Total'] /  $lstCompradosUSD_Anio0['Total']) - 1) * 100);
        }else{
            $lstCobradosRentUSDPorc_Anio0['Total'] = 0;
        }



        $lstTotales_Anio['HN'] = $lstCobradosARS_Anio['Total'];
        $lstTotales_Anio['RentARS'] = $lstCobradosRentARS_Anio['Total'];
        $lstTotales_Anio['RentARS_Porc'] = $lstCobradosRentARSPorc_Anio['Total'];
        $lstTotales_Anio['RentUSD'] = $lstCobradosRentUSD_Anio['Total'];
        $lstTotales_Anio['RentUSD_Porc'] = $lstCobradosRentUSDPorc_Anio['Total'];
        
       // $lstTotales_Anio['Duration'] = 0;
        //$lstTotales_Anio['TIR'] = 0;

        $lstTotales_Anio['Duration'] = $lstPonderacionesCobro['A2'];
        if ($lstPonderacionesCobro['A2'] > 0){
            $lstTotales_Anio['TIR'] = $lstCobradosRentUSDPorc_Anio['Total'] / $lstPonderacionesCobro['A2'];
        }else{
            $lstTotales_Anio['TIR'] = 0;
        }
       // dd( $lstTotales_Anio['Duration'] );


        $lstTotales_Anio1['HN'] = $lstCobradosARS_Anio1['Total'];
        $lstTotales_Anio1['RentARS'] = $lstCobradosRentARS_Anio1['Total'];
        $lstTotales_Anio1['RentARS_Porc'] = $lstCobradosRentARSPorc_Anio1['Total'];
        $lstTotales_Anio1['RentUSD'] = $lstCobradosRentUSD_Anio1['Total'];
        $lstTotales_Anio1['RentUSD_Porc'] = $lstCobradosRentUSDPorc_Anio1['Total'];
       
        //$lstTotales_Anio1['Duration'] = 0;
        //$lstTotales_Anio1['TIR'] = 0;

        $lstTotales_Anio1['Duration'] = $lstPonderacionesCobro['A1'];
        if($lstPonderacionesCobro['A1'] > 0){
            $lstTotales_Anio1['TIR'] = $lstCobradosRentUSDPorc_Anio1['Total'] / $lstPonderacionesCobro['A1'];
        }else{
            $lstTotales_Anio1['TIR'] = 0;
        }
       

        $lstTotales_Anio0['HN'] = $lstCobradosARS_Anio0['Total'];
        $lstTotales_Anio0['RentARS'] = $lstCobradosRentARS_Anio0['Total'];
        $lstTotales_Anio0['RentARS_Porc'] = $lstCobradosRentARSPorc_Anio0['Total'];
        $lstTotales_Anio0['RentUSD'] = $lstCobradosRentUSD_Anio0['Total'];
        $lstTotales_Anio0['RentUSD_Porc'] = $lstCobradosRentUSDPorc_Anio0['Total'];
       
       // $lstTotales_Anio0['Duration'] = 0;
        //$lstTotales_Anio0['TIR'] = 0;
        $lstTotales_Anio0['Duration'] = $lstPonderacionesCobro['A0'];
        if($lstPonderacionesCobro['A0'] > 0){
            $lstTotales_Anio0['TIR'] = $lstCobradosRentUSDPorc_Anio0['Total'] / $lstPonderacionesCobro['A0'];
        }else{
            $lstTotales_Anio0['TIR'] = 0;
        }
      

        //dd($lstTotales_Anio0);
        
        $arrAnios0['Anio'] = $anio0;
        $arrAnios0['Valores'] = $lstTotales_Anio0;
        array_push($listAnios, $arrAnios0);

        $arrAnios1['Anio'] = $anio1;
        $arrAnios1['Valores'] = $lstTotales_Anio1;
        array_push($listAnios, $arrAnios1);

        $arrAnios['Anio'] = $anio;
        $arrAnios['Valores'] = $lstTotales_Anio;
        array_push($listAnios, $arrAnios);


        $arrCobroARS['Tipo'] = 'HN Cobrados $';
        $arrCobroARS['Fila'] = 1;
        $arrCobroARS['Valores'] = $lstCobradosARS_Anio;
        array_push($listMeses, $arrCobroARS);

        $arrCobroUSD['Tipo'] = 'HN Cobrados USD';
        $arrCobroUSD['Fila'] = 2;
        $arrCobroUSD['Valores'] = $lstCobradosUSD_Anio;
        array_push($listMeses, $arrCobroUSD);

        $arrCC['Tipo'] = 'HN Cobrados (Cant.)';
        $arrCC['Fila'] = 3;
        $arrCC['Valores'] = $lstCobradosCantCasos_Anio;
        array_push($listMeses, $arrCC);

        $arrDur['Tipo'] = 'Duration HN Cobrados';
        $arrDur['Fila'] = 4;
        $arrDur['Valores'] = $lstPonderacionesMesesAnio;
        array_push($listMeses, $arrDur);

        //dd($lstPonderacionesMesesAnio);

        $arrRentARS['Tipo'] = 'Rent. $';
        $arrRentARS['Fila'] = 5;
        $arrRentARS['Valores'] = $lstCobradosRentARS_Anio;
        array_push($listMeses, $arrRentARS);

        $arrRentARS_Porc['Tipo'] = 'Rent. $ (%)';
        $arrRentARS_Porc['Fila'] = 6;
        $arrRentARS_Porc['Valores'] = $lstCobradosRentARSPorc_Anio;
        array_push($listMeses, $arrRentARS_Porc);

        $arrRentUSD['Tipo'] = 'Rent. USD';
        $arrRentUSD['Fila'] = 7;
        $arrRentUSD['Valores'] = $lstCobradosRentUSD_Anio;
        array_push($listMeses, $arrRentUSD);

        $arrRentUSD_Porc['Tipo'] = 'Rent. USD (%)';
        $arrRentUSD_Porc['Fila'] = 8;
        $arrRentUSD_Porc['Valores'] = $lstCobradosRentUSDPorc_Anio;
        array_push($listMeses, $arrRentUSD_Porc);


        $respuesta['lstMeses'] = $listMeses;
        $respuesta['listAnios'] = $listAnios;

        return $respuesta;

    }


    


}