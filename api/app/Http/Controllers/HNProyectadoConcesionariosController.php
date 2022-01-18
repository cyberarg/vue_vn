<?php

namespace App\Http\Controllers;

use App\HaberNeto;
use Illuminate\Http\Request;
use DB;
use DateTime;
use App\Http\Controllers\UtilsController;

class HNProyectadoConcesionariosController extends Controller
{
 

    public function getListHNProyectados(Request $request){

        $lstProyectado = array();

        $anio = $request->Anio;

        $marca = $request->Marca;
        $concesionario = $request->Concesionario;
        $filtros = $request->Filtros;


        $lstItemsHN_Proy = $this->getItemProyectado($anio, $marca, $concesionario, $filtros);
    
        //dd($lstItemsHN_Proy);
        $lstMeses = $this->getProyectadosMeses($anio, $lstItemsHN_Proy['listProyectados']);
        $lstMeses_CE = $this->getProyectadosMeses($anio, $lstItemsHN_Proy['listProyectados_CE']);

        $lstRentabilidadMeses = $this->getProyectadosMesesRentabilidad($anio, $lstItemsHN_Proy['listProyectados']);
        $lstRentabilidadMeses_CE = $this->getProyectadosMesesRentabilidad($anio, $lstItemsHN_Proy['listProyectados_CE']);

        $lstAnios = $this->getProyectadoAnios($lstItemsHN_Proy['listProyectados']);
        $lstAnios_CE = $this->getProyectadoAnios($lstItemsHN_Proy['listProyectados_CE']);

        $lstRentabilidadAnios = $this->getProyectadosAniosRentabilidad($lstItemsHN_Proy['listProyectados']);
        $lstRentabilidadAnios_CE = $this->getProyectadosAniosRentabilidad($lstItemsHN_Proy['listProyectados_CE']);
        
        $lstProyectado['Meses'] = $lstMeses;
        $lstProyectado['Meses_CE'] = $lstMeses_CE;

        $lstProyectado['RentabilidadMeses'] = $lstRentabilidadMeses;
        $lstProyectado['RentabilidadMeses_CE'] = $lstRentabilidadMeses_CE;

        $lstProyectado['Anios'] = $lstAnios;
        $lstProyectado['Anios_CE'] = $lstAnios_CE;

        $lstProyectado['RentabilidadAnios'] = $lstRentabilidadAnios;
        $lstProyectado['RentabilidadAnios_CE'] = $lstRentabilidadAnios_CE;

        return $lstProyectado;

    }

    public function getItemProyectado_Selecteds($anio, $marca, $concesionario, $soloCE_Giama_Todos, $filtros){
    
        $respuesta = array(); 
        $lstProy = array();

        $uC = new UtilsController;
        $db = $uC->getDabaseName($marca, $concesionario);

        if ($marca == 99){


            $lstHN_RB = array();
            $lstHN_GF = array();

            $lstHN_AN = array();
            $lstHN_CG = array();

            $lstHN_RB = DB::connection('RB')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = '' ");

            $lstHN_GF = DB::connection('GF')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = '' AND (ComproGiama = 1 OR ContabilizarParaRB = 1)");

            $lstHN_AN = DB::connection('AN')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = '' ");
            $lstHN_CG = DB::connection('CG')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = '' ");

            $lstHN = array_merge($lstHN_RB, $lstHN_GF, $lstHN_AN, $lstHN_CG);

            $lstProy = array_merge($lstHN_RB, $lstHN_GF, $lstHN_AN, $lstHN_CG);

            $lstProy_Solo_CE = array();

            $respuesta['listProyectados'] = $lstProy;
            $respuesta['listProyectados_CE'] = $lstProy_Solo_CE;

  
        }else{

            switch ($soloCE_Giama_Todos){
                case 0: // TODOS
                    $lstProy = DB::connection($db)->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = '' AND Concesionario = ".$concesionario);
                break;
                case 1: // GIAMA (RB)
                    //$lstProy = DB::connection($db)->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = '' AND (ComproGiama = 1 OR ContabilizarParaRB = 1) AND Concesionario = ".$concesionario);
                    $lstProy = DB::connection($db)->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = '' AND (ComproGiama = 1) AND Concesionario = ".$concesionario);
                break;
                case 3: // Total GIAMA 
                    $lstProy = DB::connection($db)->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = '' AND (ComproGiama = 1 OR ContabilizarParaRB = 1) AND Concesionario = ".$concesionario);
                    
                break;
                case 2: //SOLO CE
                    //$lstProy = DB::connection($db)->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = '' AND (ComproGiama = 0 AND IFNULL(ContabilizarParaRB, 2) = 2) AND Concesionario = ".$concesionario);
                    $lstProy = DB::connection($db)->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = '' AND (ComproGiama = 0) AND Concesionario = ".$concesionario);
                break;
            }
       }

        return $lstProy;
    }

    public function getItemProyectado($anio, $marca, $concesionario, $filtros){
    
        $respuesta = array(); 
        $lstProy = array();

        if ($marca == 99){


            $lstHN_RB = array();
            $lstHN_GF = array();

            $lstHN_AN = array();
            $lstHN_CG = array();

            $lstHN_RB = DB::connection('RB')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = '' ");

            $lstHN_GF = DB::connection('GF')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = '' AND (ComproGiama = 1 OR ContabilizarParaRB = 1)");

            $lstHN_AN = DB::connection('AN')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = '' ");
            $lstHN_CG = DB::connection('CG')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = '' ");

            $lstHN = array_merge($lstHN_RB, $lstHN_GF, $lstHN_AN, $lstHN_CG);

            $lstProy = array_merge($lstHN_RB, $lstHN_GF, $lstHN_AN, $lstHN_CG);

            $lstProy_Solo_CE = array();

            $respuesta['listProyectados'] = $lstProy;
            $respuesta['listProyectados_CE'] = $lstProy_Solo_CE;

        }else{
            $db = "GF";

            $lstProy = HaberNeto::on($db)->where('Concesionario', $concesionario)->whereNull('FechaCobroReal')->get();
    
            $lstProy_Solo_CE = HaberNeto::on($db)->where('Concesionario', $concesionario)->where('ComproGiama', 0)->whereNull('FechaCobroReal')->get();
    
            $respuesta['listProyectados'] = $lstProy;
            $respuesta['listProyectados_CE'] = $lstProy_Solo_CE;

        }

        

        return $respuesta;
    }

    public function getProyectadosAniosRentabilidad($listItems){

        $listRentabilidadAnios = array();

        $keys = array('A0', 'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'Total');

        $lstCompraARS = array();
        $lstCompraARS = array_fill_keys($keys, 0);

        $lstCompraUSD = array();
        $lstCompraUSD = array_fill_keys($keys, 0);

        $lstHNARS = array();
        $lstHNARS = array_fill_keys($keys, 0);

        $lstHNUSD = array();
        $lstHNUSD = array_fill_keys($keys, 0);

        $lstRentaARS = array();
        $lstRentaARS = array_fill_keys($keys, 0);

        $lstRentaARS_Porc = array();
        $lstRentaARS_Porc = array_fill_keys($keys, 0);

        $lstRentaUSD = array();
        $lstRentaUSD = array_fill_keys($keys, 0);

        $lstRentaUSD_Porc = array();
        $lstRentaUSD_Porc = array_fill_keys($keys, 0);


        foreach ($listItems as $it) {
            if (!is_null($it->FechaCuota84)){

                $fecha = date('Y-m-d', strtotime("+1 months", strtotime($it->FechaCuota84)));
                $ff = DateTime::createFromFormat("Y-m-d", $fecha);

                $hoy = new DateTime('NOW');
                $yearHoy = $hoy->format("Y");

                $difAnios = $ff->format("Y") - $yearHoy;

                switch ($difAnios){
                    case ($difAnios < 0):
                    case 0:
                        /*
                        $lstCompraARS['A0'] += round($it->MontoCompra);
                        $lstHNARS['A0'] += round($it->HaberNetoSubite);

                        $lstCompraUSD['A0'] += round($it->MontoCompraDolares);
                        $lstHNUSD['A0'] += round($it->HaberNetoSubiteUSD);
                        */
                        $lstCompraARS['A0'] += $it->MontoCompra;
                        $lstHNARS['A0'] += $it->HaberNetoSubite;

                        $lstCompraUSD['A0'] += $it->MontoCompraDolares;
                        $lstHNUSD['A0'] += $it->HaberNetoSubiteUSD;  
                    break;
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 7:
                        /*
                        $lstCompraARS['A'.$difAnios] += round($it->MontoCompra);
                        $lstHNARS['A'.$difAnios] += round($it->HaberNetoSubite);

                        $lstCompraUSD['A'.$difAnios] += round($it->MontoCompraDolares);
                        $lstHNUSD['A'.$difAnios] += round($it->HaberNetoSubiteUSD);
                        */

                        $lstCompraARS['A'.$difAnios] += $it->MontoCompra;
                        $lstHNARS['A'.$difAnios] += $it->HaberNetoSubite;

                        $lstCompraUSD['A'.$difAnios] += $it->MontoCompraDolares;
                        $lstHNUSD['A'.$difAnios] += $it->HaberNetoSubiteUSD;
                    break;
                }
            }
        }//END FOREACH

        for ($i=0; $i < 8; $i++) { 
            //$lstRentaARS['A'.$i] = round($lstHNARS['A'.$i] - $lstCompraARS['A'.$i]);
            $lstRentaARS['A'.$i] = $lstHNARS['A'.$i] - $lstCompraARS['A'.$i];
            $lstRentaARS['Total'] += $lstRentaARS['A'.$i];

            if($lstCompraARS['A'.$i] > 0){
                //$lstRentaARS_Porc['A'.$i] = round((($lstHNARS['A'.$i] / $lstCompraARS['A'.$i]) - 1) * 100);  
                $lstRentaARS_Porc['A'.$i] = (($lstHNARS['A'.$i] / $lstCompraARS['A'.$i]) - 1) * 100;  
                //$lstRentaARS_Porc['Total'] += $lstRentaARS_Porc['A'.$i]; 
            }

            $lstHNARS['Total'] += $lstHNARS['A'.$i];
            $lstCompraARS['Total'] += $lstCompraARS['A'.$i];

           // $lstRentaUSD['A'.$i] = round($lstHNUSD['A'.$i] - $lstCompraUSD['A'.$i]);
           $lstRentaUSD['A'.$i] = $lstHNUSD['A'.$i] - $lstCompraUSD['A'.$i];
            
            if($lstCompraUSD['A'.$i] > 0){
               // $lstRentaUSD_Porc['A'.$i] = round((($lstHNUSD['A'.$i] / $lstCompraUSD['A'.$i]) - 1) * 100);  
               $lstRentaUSD_Porc['A'.$i] = (($lstHNUSD['A'.$i] / $lstCompraUSD['A'.$i]) - 1) * 100;  
               // $lstRentaUSD_Porc['Total'] += $lstRentaUSD_Porc['A'.$i]; 
            }

            $lstHNUSD['Total'] += $lstHNUSD['A'.$i];
            $lstCompraUSD['Total'] += $lstCompraUSD['A'.$i];

            $lstRentaUSD['Total'] += $lstRentaUSD['A'.$i];
        }

        if ($lstCompraARS['Total'] > 0){
           // $lstRentaARS_Porc['Total'] = round((($lstHNARS['Total'] / $lstCompraARS['Total']) - 1) * 100); 
            $lstRentaARS_Porc['Total'] = (($lstHNARS['Total'] / $lstCompraARS['Total']) - 1) * 100; 
        }else{
            $lstRentaARS_Porc['Total'] = 0;
        }
         
        if ($lstCompraUSD['Total'] > 0){
            //$lstRentaUSD_Porc['Total'] = round((($lstHNUSD['Total'] / $lstCompraUSD['Total']) - 1) * 100); 
            $lstRentaUSD_Porc['Total'] = (($lstHNUSD['Total'] / $lstCompraUSD['Total']) - 1) * 100; 
        }else{
            $lstRentaUSD_Porc['Total'] = 0; 
        }
       
        $arrR_ARS['Tipo'] = 'Rent. $';
        $arrR_ARS['Valores'] = $lstRentaARS;
        array_push($listRentabilidadAnios, $arrR_ARS);

        $arrRP_ARS['Tipo'] = 'Rent. $ (%)';
        $arrRP_ARS['Valores'] = $lstRentaARS_Porc;
        array_push($listRentabilidadAnios, $arrRP_ARS);

        $arrR_USD['Tipo'] = 'Rent. USD';
        $arrR_USD['Valores'] = $lstRentaUSD;
        array_push($listRentabilidadAnios, $arrR_USD);

        $arrRP_USD['Tipo'] = 'Rent. USD (%)';
        $arrRP_USD['Valores'] = $lstRentaUSD_Porc;
        array_push($listRentabilidadAnios, $arrRP_USD);

        return $listRentabilidadAnios;

    }

    public function getProyectadoAnios($listItems){

        $listAnios = array();

        $keys = array('A0', 'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'Total');

        $lstHNARS = array();
        $lstHNARS = array_fill_keys($keys, 0);

        $lstHNUSD = array();
        $lstHNUSD = array_fill_keys($keys, 0);

        $lstCantCasos = array();
        $lstCantCasos = array_fill_keys($keys, 0);

        $lstPorcPart = array();
        $lstPorcPart = array_fill_keys($keys, 0);

        foreach ($listItems as $it) {
            if (!is_null($it->FechaCuota84)){

                $fecha = date('Y-m-d', strtotime("+1 months", strtotime($it->FechaCuota84)));
                $ff = DateTime::createFromFormat("Y-m-d", $fecha);

                $hoy = new DateTime('NOW');
                $yearHoy = $hoy->format("Y");

                $difAnios = $ff->format("Y") - $yearHoy;

                switch ($difAnios){
                    case ($difAnios < 0):
                    case 0:
                        //$lstHNARS['A0'] += round($it->HaberNetoSubite);
                        //$lstHNUSD['A0'] += round($it->HaberNetoSubiteUSD);
                        $lstHNARS['A0'] += $it->HaberNetoSubite;
                        $lstHNUSD['A0'] += $it->HaberNetoSubiteUSD;
                        $lstCantCasos['A0'] += 1;
                    break;
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 7:
                        //$lstHNARS['A'.$difAnios] += round($it->HaberNetoSubite);
                        //$lstHNUSD['A'.$difAnios] += round($it->HaberNetoSubiteUSD);
                        $lstHNARS['A'.$difAnios] += $it->HaberNetoSubite;
                        $lstHNUSD['A'.$difAnios] += $it->HaberNetoSubiteUSD;
                        $lstCantCasos['A'.$difAnios] += 1;
                    break;
                }
            }
        } //END FOREACH


        $totalCobrar = $lstHNARS['A0'] + $lstHNARS['A1'] + $lstHNARS['A2'] + $lstHNARS['A3'] + $lstHNARS['A4'] + $lstHNARS['A5'] + $lstHNARS['A6'] + $lstHNARS['A7'];
        if ($totalCobrar == 0){
            $totalCobrar = 1;
        }

        for ($i=0; $i < 8; $i++) { 
            $lstHNARS['Total'] += $lstHNARS['A'.$i];
            $lstHNUSD['Total'] += $lstHNUSD['A'.$i];
            $lstCantCasos['Total'] += $lstCantCasos['A'.$i];
            
            //$lstPorcPart['A'.$i] = round($lstHNARS['A'.$i] / $totalCobrar * 100);
            $lstPorcPart['A'.$i] = $lstHNARS['A'.$i] / $totalCobrar * 100;

            $lstPorcPart['Total'] += $lstPorcPart['A'.$i];

            $lstHNARS['A'.$i] = round($lstHNARS['A'.$i]);
            $lstHNUSD['A'.$i] = round($lstHNUSD['A'.$i]);
        }

        //$lstPorcPart['Total'] = "-";

        $arrHN_ARS['Tipo'] = 'HN a Cobrar $';
        $arrHN_ARS['Valores'] = $lstHNARS;
        array_push($listAnios, $arrHN_ARS);

        $arrHN_USD['Tipo'] = 'HN a Cobrar USD';
        $arrHN_USD['Valores'] = $lstHNUSD;
        array_push($listAnios, $arrHN_USD);

        $arrCC['Tipo'] = 'Casos a Cobrar';
        $arrCC['Valores'] = $lstCantCasos;
        array_push($listAnios, $arrCC);

        $arrPorcP['Tipo'] = '% Part.';
        $arrPorcP['Valores'] = $lstPorcPart;
        array_push($listAnios, $arrPorcP);

        return $listAnios;
    }


    public function getProyectadosMesesRentabilidad($anio, $listItems){
        $listRentabilidadMeses = array();

        $keys = array('M1', 'M2', 'M3', 'M4', 'M5', 'M6', 'M7', 'M8', 'M9', 'M10', 'M11', 'M12', 'Total');

        $lstCompraARS = array();
        $lstCompraARS = array_fill_keys($keys, 0);

        $lstCompraUSD = array();
        $lstCompraUSD = array_fill_keys($keys, 0);

        $lstHNARS = array();
        $lstHNARS = array_fill_keys($keys, 0);

        $lstHNUSD = array();
        $lstHNUSD = array_fill_keys($keys, 0);

        $lstRentaARS = array();
        $lstRentaARS = array_fill_keys($keys, 0);

        $lstRentaARS_Porc = array();
        $lstRentaARS_Porc = array_fill_keys($keys, 0);

        $lstRentaUSD = array();
        $lstRentaUSD = array_fill_keys($keys, 0);

        $lstRentaUSD_Porc = array();
        $lstRentaUSD_Porc = array_fill_keys($keys, 0);

        foreach ($listItems as $it) {
            if (!is_null($it->FechaCuota84)){

                $fecha = date('Y-m-d', strtotime("+1 months", strtotime($it->FechaCuota84)));
          
                $ff = DateTime::createFromFormat("Y-m-d", $fecha);
                $ffAnio = $ff->format("Y");
                $ffMes =  $ff->format("m");
                
                $hoy = new DateTime('NOW');
                $yearHoy = $hoy->format("Y");
                $monthHoy = $hoy->format("m");


                if ($ffAnio > $anio){
                    continue;
                }
                
                if (($ffAnio < $anio) || ($anio == $yearHoy && $ffMes < $monthHoy)){
                    $ff = $hoy;
                }

                $lstCompraARS['M'.$ff->format("n")] += $it->MontoCompra;
                $lstCompraUSD['M'.$ff->format("n")] += $it->MontoCompraDolares;
                
                $lstHNARS['M'.$ff->format("n")] += $it->HaberNetoSubite;
                $lstHNUSD['M'.$ff->format("n")] += $it->HaberNetoSubiteUSD;
            }
        } // END FOREACH

        for ($i=1; $i < 13; $i++) { 
            //$lstRentaARS['M'.$i] = round($lstHNARS['M'.$i] - $lstCompraARS['M'.$i]);
            $lstRentaARS['M'.$i] = $lstHNARS['M'.$i] - $lstCompraARS['M'.$i];
            $lstRentaARS['Total'] += $lstRentaARS['M'.$i];
            if($lstCompraARS['M'.$i] > 0){
                //$lstRentaARS_Porc['M'.$i] = round((($lstHNARS['M'.$i] / $lstCompraARS['M'.$i]) - 1) * 100);  
                $lstRentaARS_Porc['M'.$i] = (($lstHNARS['M'.$i] / $lstCompraARS['M'.$i]) - 1) * 100;  
                
            }
            $lstHNARS['Total'] += $lstHNARS['M'.$i];
            $lstCompraARS['Total'] += $lstCompraARS['M'.$i];

           // $lstRentaUSD['M'.$i] = round($lstHNUSD['M'.$i] - $lstCompraUSD['M'.$i]);
            $lstRentaUSD['M'.$i] = $lstHNUSD['M'.$i] - $lstCompraUSD['M'.$i];
            $lstRentaUSD['Total'] += $lstRentaUSD['M'.$i];
            if($lstCompraUSD['M'.$i] > 0){
               // $lstRentaUSD_Porc['M'.$i] = round((($lstHNUSD['M'.$i] / $lstCompraUSD['M'.$i]) - 1) * 100);
               $lstRentaUSD_Porc['M'.$i] = (($lstHNUSD['M'.$i] / $lstCompraUSD['M'.$i]) - 1) * 100;
            }
            $lstHNUSD['Total'] += $lstHNUSD['M'.$i];
            $lstCompraUSD['Total'] += $lstCompraUSD['M'.$i];
        }

        if ($lstCompraARS['Total'] > 0){
            //$lstRentaARS_Porc['Total'] = round((($lstHNARS['Total'] / $lstCompraARS['Total']) - 1) * 100);  
            $lstRentaARS_Porc['Total'] = (($lstHNARS['Total'] / $lstCompraARS['Total']) - 1) * 100;  
        }else{
            $lstRentaARS_Porc['Total'] = 0;
        }
      
        if ($lstCompraUSD['Total'] > 0){
           // $lstRentaUSD_Porc['Total'] = round((($lstHNUSD['Total'] / $lstCompraUSD['Total']) - 1) * 100);
           $lstRentaUSD_Porc['Total'] = (($lstHNUSD['Total'] / $lstCompraUSD['Total']) - 1) * 100;
        }else{
            $lstRentaUSD_Porc['Total'] = 0;
        }
        

        $arrR_ARS['Tipo'] = 'Rent. $';
        $arrR_ARS['Valores'] = $lstRentaARS;
        array_push($listRentabilidadMeses, $arrR_ARS);

        $arrRP_ARS['Tipo'] = 'Rent. $ (%)';
        $arrRP_ARS['Valores'] = $lstRentaARS_Porc;
        array_push($listRentabilidadMeses, $arrRP_ARS);

        $arrR_USD['Tipo'] = 'Rent. USD';
        $arrR_USD['Valores'] = $lstRentaUSD;
        array_push($listRentabilidadMeses, $arrR_USD);

        $arrRP_USD['Tipo'] = 'Rent. USD (%)';
        $arrRP_USD['Valores'] = $lstRentaUSD_Porc;
        array_push($listRentabilidadMeses, $arrRP_USD);

        return $listRentabilidadMeses;

    }


    public function getProyectadosMeses($anio, $listItems){

        //dd($listItems);

        $listMeses = array();

        $keys = array('M1', 'M2', 'M3', 'M4', 'M5', 'M6', 'M7', 'M8', 'M9', 'M10', 'M11', 'M12', 'Total');

        $lstCobrarARS = array();
        $lstCobrarARS = array_fill_keys($keys, 0);

        $lstCobrarUSD = array();
        $lstCobrarUSD = array_fill_keys($keys, 0);

        $lstCantCasos = array();
        $lstCantCasos = array_fill_keys($keys, 0);

        foreach ($listItems as $it) {

            if (!is_null($it->FechaCuota84)){
                //dd($it->FechaCuota84);
                $fecha = date('Y-m-d', strtotime("+1 months", strtotime($it->FechaCuota84)));
          
                $ff = DateTime::createFromFormat("Y-m-d", $fecha);
                $ffAnio = $ff->format("Y");
                $ffMes =  $ff->format("m");
                
                $hoy = new DateTime('NOW');
                $yearHoy = $hoy->format("Y");
                $monthHoy = $hoy->format("m");

                
                if ($ffAnio > $anio){
                    continue;
                }
                
    
                if (($ffAnio < $anio) || ($anio == $yearHoy && $ffMes < $monthHoy)){
                    $ff = $hoy;
                }
    
                //$lstCobrarARS['M'.$ff->format("n")] += round($it->HaberNetoSubite);
                //$lstCobrarUSD['M'.$ff->format("n")] += round($it->HaberNetoSubiteUSD);
                $lstCobrarARS['M'.$ff->format("n")] += $it->HaberNetoSubite;
                $lstCobrarUSD['M'.$ff->format("n")] += $it->HaberNetoSubiteUSD;
                $lstCantCasos['M'.$ff->format("n")] += 1;
           

            }
            

        } //END FOREACH

        for ($i=1; $i < 13; $i++) { 
            $lstCobrarARS['Total'] += $lstCobrarARS['M'.$i];
            $lstCobrarUSD['Total'] += $lstCobrarUSD['M'.$i];
            $lstCantCasos['Total'] += $lstCantCasos['M'.$i];
        }

        $arrCA['Tipo'] = 'HN a Cobrar $';
        $arrCA['Valores'] = $lstCobrarARS;
        array_push($listMeses, $arrCA);

        $arrCD['Tipo'] = 'HN a Cobrar USD';
        $arrCD['Valores'] = $lstCobrarUSD;
        array_push($listMeses, $arrCD);

        $arrCC['Tipo'] = 'Casos a Cobrar';
        $arrCC['Valores'] = $lstCantCasos;
        array_push($listMeses, $arrCC);


        return $listMeses;
    }

    public function getValoresUSD($fecha, $arrDolar){
        
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