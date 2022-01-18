<?php

namespace App\Http\Controllers;

use App\HaberNeto;
use App\HN_Acumulados;
use Illuminate\Http\Request;
use DB;
use DateTime;
use App\Http\Controllers\UtilsController;

class HNResumenController extends Controller
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

    public function getMetricas($lstHN_Op, $filtros, $empresa){

        $lstHN_Items = $this->getItems('M', $empresa, $filtros, $lstHN_Op);

    }

    public function getHNResumen(Request $request){

        $lstEERR = array();

        $anio = $request->Anio;
        $concesionario = $request->Concesionario;
        $filtros = $request->Filtros;

        switch($concesionario){
            case 6:
            $empresa = 8;
        break;
        }

        $lstHN_Op = $this->getListHN_Op($empresa);
        
        $mlstHN_Metricas = $this->getMetricas($lstHN_Op, $filtros, $empresa);
        

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

    


   
    public function getItems($tipo, $empresa, $filtros, $lstHN_Op){
    
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