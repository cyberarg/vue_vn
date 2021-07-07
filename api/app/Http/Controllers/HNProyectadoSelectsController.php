<?php

namespace App\Http\Controllers;

use App\HaberNeto;
use Illuminate\Http\Request;
use DB;
use DateTime;
use App\Http\Controllers\UtilsController;
use App\Http\Controllers\HNProyectadoConcesionariosController;

class HNProyectadoSelectsController extends Controller
{

    public function getListHNProyectados_Selected(Request $request){ 

        $lstProyectado = array();

        $anio = $request->Anio;
        $concesionario = $request->Concesionario;
        $seleccionados = $request->Seleccionados;
        $marca = $request->Marca;
        $filtros = $request->Filtros;
        $rbConsolidado = $request->ConsolidadoRB;

        $empresa = 0;
        if ($marca == 2){

            switch($concesionario){
                case 4:
                    $db = "AC";
                    $empresa = 6;
                break;
                case 5:
                    $db = "AN";
                    $empresa = 3;
                break;
                case 6:
                    $db = "CG";
                    $empresa = 8;
                break;
                case 8:
                    $db = "RB";
                    $empresa = 10;
                break;
            }

        }else{
            $usaHNConcesionario = true;
            $db = "GF";
            $empresa = 1;
        }

        $lstItemsHN_Proy = array();
        $lstItemsHN_Proy_Giama = array();
        $lstItemsHN_Proy_Giama_Total = array();
        $lstItemsHN_Proy_CE = array();

        foreach ($seleccionados as $seleccionado) {
            
            $marca = $seleccionado['Marca'];
            $concesionario = $seleccionado['Codigo'];

            $resProy = new HNProyectadoConcesionariosController();

            $lstItemsHN_Proy = array_merge($lstItemsHN_Proy, $resProy->getItemProyectado_Selecteds($anio, $marca, $concesionario, 0, $filtros));
            $lstItemsHN_Proy_Giama = array_merge($lstItemsHN_Proy_Giama, $resProy->getItemProyectado_Selecteds($anio, $marca, $concesionario, 1, $filtros));
            $lstItemsHN_Proy_Giama_Total = array_merge($lstItemsHN_Proy_Giama_Total, $resProy->getItemProyectado_Selecteds($anio, $marca, $concesionario, 3, $filtros));
            $lstItemsHN_Proy_CE = array_merge($lstItemsHN_Proy_CE, $resProy->getItemProyectado_Selecteds($anio, $marca, $concesionario, 2, $filtros));
        
        }
        

        $lstMeses = $resProy->getProyectadosMeses($anio, $lstItemsHN_Proy);
        $lstMeses_Giama = $resProy->getProyectadosMeses($anio, $lstItemsHN_Proy_Giama);
        $lstMeses_Giama_Total = $resProy->getProyectadosMeses($anio, $lstItemsHN_Proy_Giama_Total);
        $lstMeses_CE = $resProy->getProyectadosMeses($anio, $lstItemsHN_Proy_CE);

        $lstRentabilidadMeses = $resProy->getProyectadosMesesRentabilidad($anio, $lstItemsHN_Proy);
        $lstRentabilidadMeses_Giama = $resProy->getProyectadosMesesRentabilidad($anio, $lstItemsHN_Proy_Giama);
        $lstRentabilidadMeses_Giama_Total = $resProy->getProyectadosMesesRentabilidad($anio, $lstItemsHN_Proy_Giama_Total);
        $lstRentabilidadMeses_CE = $resProy->getProyectadosMesesRentabilidad($anio, $lstItemsHN_Proy_CE);

        $lstAnios = $resProy->getProyectadoAnios($lstItemsHN_Proy);
        $lstAnios_Giama = $resProy->getProyectadoAnios($lstItemsHN_Proy_Giama);
        $lstAnios_Giama_Total = $resProy->getProyectadoAnios($lstItemsHN_Proy_Giama_Total);
        $lstAnios_CE = $resProy->getProyectadoAnios($lstItemsHN_Proy_CE);

        $lstRentabilidadAnios = $resProy->getProyectadosAniosRentabilidad($lstItemsHN_Proy);
        $lstRentabilidadAnios_Giama = $resProy->getProyectadosAniosRentabilidad($lstItemsHN_Proy_Giama);
        $lstRentabilidadAnios_Giama_Total = $resProy->getProyectadosAniosRentabilidad($lstItemsHN_Proy_Giama_Total);
        $lstRentabilidadAnios_CE = $resProy->getProyectadosAniosRentabilidad($lstItemsHN_Proy_CE);
        
        $lstProyectado['Meses'] = $lstMeses;
        $lstProyectado['Meses_Giama'] = $lstMeses_Giama;
        $lstProyectado['Meses_Giama_Total'] = $lstMeses_Giama_Total;
        $lstProyectado['Meses_CE'] = $lstMeses_CE;

        $lstProyectado['RentabilidadMeses'] = $lstRentabilidadMeses;
        $lstProyectado['RentabilidadMeses_Giama'] = $lstRentabilidadMeses_Giama;
        $lstProyectado['RentabilidadMeses_Giama_Total'] = $lstRentabilidadMeses_Giama_Total;
        $lstProyectado['RentabilidadMeses_CE'] = $lstRentabilidadMeses_CE;

        $lstProyectado['Anios'] = $lstAnios;
        $lstProyectado['Anios_Giama'] = $lstAnios_Giama;
        $lstProyectado['Anios_Giama_Total'] = $lstAnios_Giama_Total;
        $lstProyectado['Anios_CE'] = $lstAnios_CE;

        $lstProyectado['RentabilidadAnios'] = $lstRentabilidadAnios;
        $lstProyectado['RentabilidadAnios_Giama'] = $lstRentabilidadAnios_Giama;
        $lstProyectado['RentabilidadAnios_Giama_Total'] = $lstRentabilidadAnios_Giama_Total;
        $lstProyectado['RentabilidadAnios_CE'] = $lstRentabilidadAnios_CE;

        return $lstProyectado;

    }

    public function getItemProyectado($anio, $empresa, $db, $rbCons, $marca, $filtros, $lstHN_Op){
    
        $respuesta = array(); 
        $lstProy = array();
        

        if ($db == 'RB' && $rbCons == 1){
            $lstProyRB = array();
            $lstProyGF = array();

            $lstProyRB = DB::connection('RB')->select("SELECT *  FROM haberesnetosok WHERE IFNULL(FechaCobroReal, '') = ''");

            $lstProyGF = DB::connection('GF')->select("SELECT *  FROM haberesnetosok WHERE ComproGiama = 1 AND IFNULL(FechaCobroReal, '') = ''");

            $lstProy = array_merge($lstProyRB, $lstProyGF);

        }else{
            $lstProy = HaberNeto::on($db)->whereNull('FechaCobroReal')->get();
        }

        

        /*
        $itemsProy = DB::connection($db)->select("CALL net_haberesnetos_resumen_2('P',NULL,NULL,NULL,NULL,NULL,NULL,".$anio.");");
        $oEmpresa = $empresa;

        //$arrCotiz = DB::select('SELECT DATE_FORMAT(Fecha,"%Y-%m-%d") AS Fecha, CotizacionCompra, CotizacionVenta FROM cotizaciondolarhistorico;');
        $arrCotiz = array();
        
        foreach ($itemsProy as $r) {
            $i = new \stdClass();
   
            $i->Empresa = $oEmpresa;
            $i->FechaCompra = $r->FechaCompra;
            $i->FechaCobro = $r->FechaCobro;
            $i->MontoCompra = $r->MontoCompra;
            $i->MontoCobroReal = $r->MontoCobro;
            $i->MontoCobroEstimado = $r->MontoCobroEstimado;
            $i->HaberNetoSubite = $r->HaberNetoSubite;
            $i->HaberNetoOriginal = $r->HaberNetoOriginal;
            $i->Grupo = $r->Grupo;
            $i->Orden = $r->Orden;
            $i->Marca = $r->Marca;
            $i->EmpresaOrigen = $r->EmpresaOrigenGyO;
            $i->TipoCompra = $r->TipoCompra;
            
            $i->CPG = null;
            $i->FechaCuota84 = null;

            $itemBuscado = $this->searchGyOyMyE($lstHN_Op, $i->Grupo, $i->Orden, $marca, $i->EmpresaOrigen);

            if($itemBuscado){
                $i->FechaCuota84 = $itemBuscado[0]->FechaCuota84;
                $i->CPG = $itemBuscado[0]->CPG;
            }else{
                $lstNoEncontrados[] = "No se encontró el Grupo/Orden ".$i->Grupo."/".$i->Orden." en la empresa ".$oEmpresa.".";
            }
            
            if ($i->CPG >= 8){
                if ($i->HaberNetoSubite == 0){
                    if ($i->MontoCobroReal == 0){
                        $i->HaberNetoSubite = $i->MontoCobroEstimado;
                    }else{
                        $i->HaberNetoSubite = $i->MontoCobroReal;
                    }
                }
            }

          

            $i->MontoCompraDolares = $i->MontoCompra / 75;
            $i->MontoCobroDolares = $i->MontoCobroReal / 75;
            $i->HaberNetoSubiteUSD = $i->HaberNetoSubite / 75;
            

            
            array_push($lstProy, $i);
        }
        */

        $respuesta['listProyectados'] = $lstProy;
 
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
                        $lstCompraARS['A0'] += round($it->MontoCompra);
                        $lstHNARS['A0'] += round($it->HaberNetoSubite);

                        $lstCompraUSD['A0'] += round($it->MontoCompraDolares);
                        $lstHNUSD['A0'] += round($it->HaberNetoSubiteUSD);
                    break;
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 7:
                        $lstCompraARS['A'.$difAnios] += round($it->MontoCompra);
                        $lstHNARS['A'.$difAnios] += round($it->HaberNetoSubite);

                        $lstCompraUSD['A'.$difAnios] += round($it->MontoCompraDolares);
                        $lstHNUSD['A'.$difAnios] += round($it->HaberNetoSubiteUSD);
                    break;
                }
            }
        }//END FOREACH

        for ($i=0; $i < 8; $i++) { 
            $lstRentaARS['A'.$i] = round($lstHNARS['A'.$i] - $lstCompraARS['A'.$i]);
            $lstRentaARS['Total'] += $lstRentaARS['A'.$i];

            if($lstCompraARS['A'.$i] > 0){
                $lstRentaARS_Porc['A'.$i] = round((($lstHNARS['A'.$i] / $lstCompraARS['A'.$i]) - 1) * 100);  
                //$lstRentaARS_Porc['Total'] += $lstRentaARS_Porc['A'.$i]; 
            }

            $lstHNARS['Total'] += $lstHNARS['A'.$i];
            $lstCompraARS['Total'] += $lstCompraARS['A'.$i];

            $lstRentaUSD['A'.$i] = round($lstHNUSD['A'.$i] - $lstCompraUSD['A'.$i]);
  
            if($lstCompraUSD['A'.$i] > 0){
                $lstRentaUSD_Porc['A'.$i] = round((($lstHNUSD['A'.$i] / $lstCompraUSD['A'.$i]) - 1) * 100);  
                //$lstRentaUSD_Porc['Total'] += $lstRentaUSD_Porc['A'.$i]; 
            }

            $lstHNUSD['Total'] += $lstHNUSD['A'.$i];
            $lstCompraUSD['Total'] += $lstCompraUSD['A'.$i];

            $lstRentaUSD['Total'] += $lstRentaUSD['A'.$i];
        }

        $lstRentaARS_Porc['Total'] = round((($lstHNARS['Total'] / $lstCompraARS['Total']) - 1) * 100);  

        $lstRentaUSD_Porc['Total'] = round((($lstHNUSD['Total'] / $lstCompraUSD['Total']) - 1) * 100); 

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
                        $lstHNARS['A0'] += round($it->HaberNetoSubite);
                        $lstHNUSD['A0'] += round($it->HaberNetoSubiteUSD);
                        $lstCantCasos['A0'] += 1;
                    break;
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 7:
                        $lstHNARS['A'.$difAnios] += round($it->HaberNetoSubite);
                        $lstHNUSD['A'.$difAnios] += round($it->HaberNetoSubiteUSD);
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
            
            $lstPorcPart['A'.$i] = round($lstHNARS['A'.$i] / $totalCobrar * 100);

            $lstPorcPart['Total'] += $lstPorcPart['A'.$i];
        }

        //$lstPorcPart['Total'] = 0;

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
               
                $hoy = new DateTime('NOW');
                $yearHoy = $hoy->format("Y");
                $monthHoy = $hoy->format("m");

               
                if ($ff->format("Y") != $anio ||  ($anio == $yearHoy && $ff->format("m") < $monthHoy)){
                    continue;
                }

                $lstCompraARS['M'.$ff->format("n")] += $it->MontoCompra;
                $lstCompraUSD['M'.$ff->format("n")] += $it->MontoCompraDolares;
                
                $lstHNARS['M'.$ff->format("n")] += $it->HaberNetoSubite;
                $lstHNUSD['M'.$ff->format("n")] += $it->HaberNetoSubiteUSD;
            }
        } // END FOREACH

        for ($i=1; $i < 13; $i++) { 
            $lstRentaARS['M'.$i] = round($lstHNARS['M'.$i] - $lstCompraARS['M'.$i]);
            $lstRentaARS['Total'] += $lstRentaARS['M'.$i];
            if($lstCompraARS['M'.$i] > 0){
                $lstRentaARS_Porc['M'.$i] = round((($lstHNARS['M'.$i] / $lstCompraARS['M'.$i]) - 1) * 100);  
                
            }
            $lstHNARS['Total'] += $lstHNARS['M'.$i];
            $lstCompraARS['Total'] += $lstCompraARS['M'.$i];

            $lstRentaUSD['M'.$i] = round($lstHNUSD['M'.$i] - $lstCompraUSD['M'.$i]);
            $lstRentaUSD['Total'] += $lstRentaUSD['M'.$i];
            if($lstCompraUSD['M'.$i] > 0){
                $lstRentaUSD_Porc['M'.$i] = round((($lstHNUSD['M'.$i] / $lstCompraUSD['M'.$i]) - 1) * 100);     
            }
            $lstHNUSD['Total'] += $lstHNUSD['M'.$i];
            $lstCompraUSD['Total'] += $lstCompraUSD['M'.$i];
        }

        if ($lstCompraARS['Total'] > 0){
            $lstRentaARS_Porc['Total'] = round((($lstHNARS['Total'] / $lstCompraARS['Total']) - 1) * 100); 
        }else{
            $lstRentaARS_Porc['Total'] = 0;
        }
         
        if ($lstCompraUSD['Total'] > 0){
            $lstRentaUSD_Porc['Total'] = round((($lstHNUSD['Total'] / $lstCompraUSD['Total']) - 1) * 100);  
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

                 if (($ffAnio < $anio) || ($ffAnio == $yearHoy && $ffMes < $monthHoy)){
                    $ff = $hoy;
                }
                

                $lstCobrarARS['M'.$ff->format("n")] += round($it->HaberNetoSubite);
                $lstCobrarUSD['M'.$ff->format("n")] += round($it->HaberNetoSubiteUSD);
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