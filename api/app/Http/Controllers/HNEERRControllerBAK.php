<?php

namespace App\Http\Controllers;

use App\HaberNeto;
use App\HN_Acumulados;
use Illuminate\Http\Request;
use DB;
use DateTime;
use App\Http\Controllers\UtilsController;

class HNEERRController extends Controller
{
   
    public function getListHN_Op($empresa){

        $list = array();
        $itemsDB = DB::connection('CG')->select("CALL hnweb_get_op_hn_inter_empresas(".$empresa.");");
        
        foreach ($itemsDB as $r) {
            $i = new \stdClass();
            $i->FechaVtoCuota = $r->FechaVtoCuota;
            $i->FechaCuota84 = null;
            $i->FechaCuota84 = date('Y-m-d', strtotime("+82 months", strtotime($r->FechaVtoCuota)));
            $i->CPG = $r->CPG;
            $i->Marca = $r->Marca;
            $i->Grupo = $r->Grupo;
            $i->Orden = $r->Orden;
            $i->Empresa = $r->empresa;

            array_push($list, $i);
        }
        //dd($list);
        return $list;
    }

    public function getListHNEERR(Request $request){

        $lstEERR = array();

        $anio = $request->Anio;
        $concesionario = $request->Concesionario;
        $filtros = $request->Filtros;

        switch($concesionario){
            case 6:
            $empresa = 8;
        break;
        }

        
        //$empresa = 8;
        //$oEmpresa = 'Car Group';
        //$marca = 2;
       
    
        $lstHN_Op = $this->getListHN_Op($empresa);
        $lstItemsHN_Proy = $this->getItemsEEERR('M', $anio, $empresa, $filtros, $lstHN_Op);
        
        $lstGrid1 = $this->getDetalleGrid1($anio, $lstItemsHN_Proy);

        $lstHN_Resumen = array();
        $lstAcum_Meses = array();

        foreach ($lstGrid1 as $r) {
            if ($r->Codigo != 2 && $r->Codigo != 3){
                array_push($lstHN_Resumen, $r);

                if ($r->Codigo == 5 || $r->Codigo == 7){
                    array_push($lstAcum_Meses, $r);
                }
            }    

        } //END FOREACH

        $useDurationHoy = true;

        $lstAcumulados = $this->getAcumulados($anio, $lstHN_Op, $lstAcum_Meses, $filtros, $useDurationHoy, $empresa);


        
        $lstEERR['Meses'] = $lstMeses;
        $lstEERR['RentabilidadMeses'] = $lstRentabilidadMeses;
        $lstEERR['Anios'] = $lstAnios;
        $lstEERR['RentabilidadAnios'] = $lstRentabilidadAnios;

        return $lstEERR;

    }

    public function getAcumulados($anio, $listOP, $listAcum, $filtros, $useDurHoy, $empresa){

        $list = new HN_Acumulados;

        $lstHN_Items = $this->getItemsEEERR('A', $anio, $empresa, $filtros, $lstHN_Op);


        $lstMes = array();
        $anioInicial = 2018;
        $lstAnio_Stock = array();
        $lstAnio_Comp = array();
        $lstAnio_Cob = array();
        $lstEERR_Saldo = array();

        while ($anioInicial <= $anio) {
            
            $list->Anio = $anioInicial;

            $lstAnio_Stock[] = $anioInicial;
            $lstAnio_Comp[] = $anioInicial;
            $lstAnio_Cob[] = $anioInicial;

            $anioInicial += 1;
        }

        $arrRes0['Tipo'] = "Stock Final Período";
        $arrRes0['Codigo'] = 10; // 0

        $arrRes1['Tipo'] = "Stock Inicio Período";
        $arrRes1['Codigo'] = 11; // 1

        $arrRes2['Tipo'] = "Costo Stock";
        $arrRes2['Codigo'] = 12; // 2

        $arrRes3['Tipo'] = "Duration";
        $arrRes3['Codigo'] = 13; // 3

        $arrRes4['Tipo'] = "TIR";
        $arrRes4['Codigo'] = 14; // 4

        $arrRes5['Tipo'] = "Rent. Total";
        $arrRes5['Codigo'] = 15; // 5

        $arrRes6['Tipo'] = "Verificación";
        $arrRes6['Codigo'] = 16; // 6

        $arrRes7['Tipo'] = "Duration Para TIR";
        $arrRes7['Codigo'] = 17; // 7

        if (!is_null($listAcum) && isset($listAcum)){
            $lstEERR_Saldo = $listAcum;

            $listAcum = array();
            array_push($listAcum, $arrRes0);
            array_push($listAcum, $arrRes1);
            array_push($listAcum, $arrRes2);
            array_push($listAcum, $arrRes3);
            array_push($listAcum, $arrRes4);
            array_push($listAcum, $arrRes5);
            array_push($listAcum, $arrRes6);
            array_push($listAcum, $arrRes7);

            for ($i=1; $i < 13; $i++) { 
                $lstMes[$i] = $i; 
            }

        }

        $util = new UtilsController;
            //

        $Ene_UltDia = $util->getUltimoDiaPeriodo($anio."1");
        $Fen_UltDia = $util->getUltimoDiaPeriodo($anio."2");
        $Mar_UltDia = $util->getUltimoDiaPeriodo($anio."3");
        $Abr_UltDia = $util->getUltimoDiaPeriodo($anio."4");
        $May_UltDia = $util->getUltimoDiaPeriodo($anio."5");
        $Jun_UltDia = $util->getUltimoDiaPeriodo($anio."6");
        $Jul_UltDia = $util->getUltimoDiaPeriodo($anio."7");
        $Ago_UltDia = $util->getUltimoDiaPeriodo($anio."8");
        $Sep_UltDia = $util->getUltimoDiaPeriodo($anio."9");
        $Oct_UltDia = $util->getUltimoDiaPeriodo($anio."10");
        $Nov_UltDia = $util->getUltimoDiaPeriodo($anio."11");
        $Dic_UltDia = $util->getUltimoDiaPeriodo($anio."12");

        $anioAnt = $anio - 1;
        $ultDiaAnioAnt = $anioAnt."1231";


        foreach ($lstHN_Items as $it) {

            if (isset($listAcum)){ 
                
                if (isset($it->FechaCobro)){
                    $fecha = date('Y-m-d', strtotime($it->FechaCobro));
                    $fc = DateTime::createFromFormat("Y-m-d", $fecha);
                }else{
                    $fc = null;
                }
                

                $fechaComp = date('Y-m-d', strtotime($it->FechaCompra));
                $fcomp = DateTime::createFromFormat("Y-m-d", $fechaComp);

                if((!isset($it->FechaCobro) || $fc->format("Y") >= $anio) && ($fcomp->format("Y") <= $anio)){

                    if ((!isset($it->FechaCobro) || $fc > $Ene_UltDia) && $fcomp <= $Ene_UltDia)){
                        
                            //LINEa 257 cls_HN_Acumulados
                        /*
                        $listAcum
                        $lstMes[$i]

                    lstAcu_Meses(0).Enero += it.HaberNetoSubiteUSD
                    lstAcu_Meses(2).Enero += it.MontoCompraDolares
                    lstMes(0).lstItems.Add(New cls_HN_Anio_Item(CInt(it.HaberNetoSubiteUSD), it.FechaCompra, it.FechaCuota84))
                    */
                    }
                }



            }
        } //END FOREACH
        

    }


    public function getDetalleGrid1($anio, $listItems){

        $listEERR = array();

        $keys = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 'Total');

        $lstHNCompUSDCobro = array(); // 0
        $lstHNCompUSDCobro = array_fill_keys($keys, 0);
 
        $lstHNCompUSDCosto = array(); // 1
        $lstHNCompUSDCosto = array_fill_keys($keys, 0);

        $lstHNCobradosUSD = array(); // 2
        $lstHNCobradosUSD = array_fill_keys($keys, 0);

        $lstHNSubiteCobradoUSD = array(); // 3
        $lstHNSubiteCobradoUSD = array_fill_keys($keys, 0);

        $lstDifCobro = array(); // 4
        $lstDifCobro = array_fill_keys($keys, 0);

        $lstEERRPeriodo = array(); // 5
        $lstEERRPeriodo = array_fill_keys($keys, 0);

        $lstSaldoCaja = array(); // 6
        $lstSaldoCaja = array_fill_keys($keys, 0);

        foreach ($listItems as $it) {
            if (!is_null($it->FechaCompra)){

                $fecha = date('Y-m-d', strtotime($it->FechaCompra));
                $ff = DateTime::createFromFormat("Y-m-d", $fecha);

                if ($ff->format("Y") == $anio){
            
                    $lstHNCompUSDCobro[$ff->format("n")] += $it->HaberNetoSubiteUSD; // 0
                    $lstHNCompUSDCosto[$ff->format("n")] += $it->MontoCompraDolares; // 1

                }

                if(!is_null($it->FechaCobro)){
                    $fechaC = date('Y-m-d', strtotime($it->FechaCobro));
                    $fc = DateTime::createFromFormat("Y-m-d", $fechaC);

                    if ($fc->format("Y") == $anio) {
                        $lstHNCobradosUSD[$fc->format("n")] += $it->MontoCobroDolares; // 2 
                        $lstHNSubiteCobradoUSD[$fc->format("n")] += $it->HaberNetoSubiteUSD; // 3
                    }
                }
            }
        } // END FOREACH

        for ($i=1; $i < 13; $i++) { 
            $lstHNCompUSDCobro['Total'] += $lstHNCompUSDCobro[$i];
            $lstHNCompUSDCosto['Total'] += $lstHNCompUSDCosto[$i];
            $lstHNCobradosUSD['Total'] += $lstHNCobradosUSD[$i];
            $lstHNSubiteCobradoUSD['Total'] += $lstHNSubiteCobradoUSD[$i];
        }

        //Dif. de Cobro: MontoCobroDolares - HaberNetoSubiteUSD
        for ($i=1; $i < 13; $i++) { 
            $lstDifCobro[$i] += ($lstHNCobradosUSD[$i] - $lstHNSubiteCobradoUSD[$i]);
            $lstDifCobro['Total'] += $lstDifCobro[$i]; // 4
        }

        //EERR del Periodo: HN Comprados USD (Cobro) - HN Comprados USD (Costo) + Dif. de Cobro
        for ($i=1; $i < 13; $i++) { 
            $lstEERRPeriodo[$i] += ($lstHNCompUSDCobro[$i] - $lstHNCompUSDCosto[$i] + $lstDifCobro[$i]);
            $lstEERRPeriodo['Total'] += $lstEERRPeriodo[$i];
        }

        //'Saldo de Caja: MontoCobroDolares - MontoCompraDolares
        for ($i=1; $i < 13; $i++) { 
            $lstSaldoCaja[$i] += ($lstHNCobradosUSD[$i] - $lstHNCompUSDCosto[$i]);
            $lstSaldoCaja['Total'] += $lstSaldoCaja[$i];
        }

        $arrR_0['Tipo'] = 'HN Comprados en USD (Cobro)';
        $arrR_0['Valores'] = $lstHNCompUSDCobro;
        $arrR_0['Codigo'] = 0;
        array_push($listEERR, $arrR_0);

        $arrR_1['Tipo'] = 'HN Comprados en USD (Costo)';
        $arrR_1['Valores'] = $lstHNCompUSDCosto;
        $arrR_0['Codigo'] = 1;
        array_push($listEERR, $arrR_1);
        
        $arrR_2['Tipo'] = 'HN Cobrados en USD';
        $arrR_2['Valores'] = $lstHNCobradosUSD;
        $arrR_0['Codigo'] = 2;
        array_push($listEERR, $arrR_2);

        $arrR_3['Tipo'] = 'HN Subite Cobrados USD';
        $arrR_3['Valores'] = $lstHNSubiteCobradoUSD;
        $arrR_0['Codigo'] = 3;
        array_push($listEERR, $arrR_3);

        $arrR_4['Tipo'] = 'Dif. de Cobro';
        $arrR_4['Valores'] = $lstDifCobro;
        $arrR_0['Codigo'] = 4;
        array_push($listEERR, $arrR_4);

        $arrR_5['Tipo'] = 'EERR del Período';
        $arrR_5['Valores'] = $lstEERRPeriodo;
        $arrR_0['Codigo'] = 5;
        array_push($listEERR, $arrR_5);
        
        $arrR_6['Tipo'] = 'Saldo de Caja';
        $arrR_6['Valores'] = $lstSaldoCaja;
        $arrR_0['Codigo'] = 7;
        array_push($listEERR, $arrR_6);
        
        return $listEERR;

    }

    public function getItemsEERR($tipo, $anio, $empresa, $filtros, $lstHN_Op){
    
        $respuesta = array(); 
        $lstItemsEERR = array();

        $lstItemsEERR = DB::connection('CG')->select("CALL net_haberesnetos_resumen_2(".$tipo.",NULL,NULL,NULL,NULL,NULL,NULL,".$anio.");");
        $oEmpresa = 'Car Group';
        $marca = 2;

        $arrCotiz = DB::select('SELECT DATE_FORMAT(Fecha,"%Y-%m-%d") AS Fecha, CotizacionCompra, CotizacionVenta FROM cotizaciondolarhistorico;');

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

            $itemBuscado = $this->searchGyOyMyE($lstHN_Op, $i->Grupo, $i->Orden, $marca, $empresa);

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
            
  
            array_push($lstItemsEERR, $i);
        }
  
        return $lstItemsEERR;
    }

    

}