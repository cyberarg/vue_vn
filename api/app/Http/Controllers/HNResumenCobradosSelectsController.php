<?php

namespace App\Http\Controllers;

use App\HaberNeto;
use Illuminate\Http\Request;
use DB;
use DateTime;
use App\Http\Controllers\UtilsController;
use ArrayObject;
use App\Http\Controllers\HNResumenCobradosController;

class HNResumenCobradosSelectsController extends Controller
{

    public function getListHNResumenCobrados_Selected(Request $request){

        $lstResumenCobro = array();

        $anio = $request->Anio;

        $marca = $request->Marca;
        $concesionario = $request->Concesionario;

        $seleccionados = $request->Seleccionados;
        $filtros = $request->Filtros;
        $rbConsolidado = $request->ConsolidadoRB;

        $array_result = array();
        $array_result_CE = array();
        $lstItemsHN_Resumen = array();  
        $lstItemsHN_Resumen_Giama = array();
        $lstItemsHN_Resumen_TotalGiama = array();
        $lstItemsHN_Resumen_CE = array();        

        $p = 0;
        
        foreach ($seleccionados as $seleccionado) {
            
            $marca = $seleccionado['Marca'];
            $concesionario = $seleccionado['Codigo'];

            $resCobros = new HNResumenCobradosController();
            
            // soloCE_Giama_Todos: 0-Todos, 1-Giama, 2-Solo CE
            $lstItemsHN_Resumen = array_merge($lstItemsHN_Resumen, $resCobros->getItemsResumen_Selecteds($anio, $marca, $concesionario, 0, $filtros, 1));
            $lstItemsHN_Resumen_Giama = array_merge($lstItemsHN_Resumen_Giama, $resCobros->getItemsResumen_Selecteds($anio, $marca, $concesionario, 1, $filtros, 1));
            $lstItemsHN_Resumen_CE = array_merge($lstItemsHN_Resumen_CE, $resCobros->getItemsResumen_Selecteds($anio, $marca, $concesionario, 2, $filtros, 0));
            $lstItemsHN_Resumen_TotalGiama = array_merge($lstItemsHN_Resumen_TotalGiama, $resCobros->getItemsResumen_Selecteds($anio, $marca, $concesionario, 3, $filtros, 1));

            $p++;
        }

        $lstCobrados = $resCobros->getResumenCobradosMeses($anio, $lstItemsHN_Resumen, $marca);
        $lstCobrados_Giama = $resCobros->getResumenCobradosMeses($anio, $lstItemsHN_Resumen_Giama, $marca);
        $lstCobrados_CE = $resCobros->getResumenCobradosMeses($anio, $lstItemsHN_Resumen_CE, $marca);
        $lstCobrados_TotalGiama = $resCobros->getResumenCobradosMeses($anio, $lstItemsHN_Resumen_TotalGiama, $marca);


        $lstResumenCobro['Grid1_Cobrados'] = $lstCobrados['lstMeses'];
        $lstResumenCobro['Grid2_Cobrados'] = $lstCobrados['listAnios'];

        $lstResumenCobro['Grid1_Cobrados_Giama'] = $lstCobrados_Giama['lstMeses'];
        $lstResumenCobro['Grid2_Cobrados_Giama'] = $lstCobrados_Giama['listAnios'];

        $lstResumenCobro['Grid1_Cobrados_CE'] = $lstCobrados_CE['lstMeses'];
        $lstResumenCobro['Grid2_Cobrados_CE'] = $lstCobrados_CE['listAnios'];

        $lstResumenCobro['Grid1_Cobrados_TotalGiama'] = $lstCobrados_TotalGiama['lstMeses'];
        $lstResumenCobro['Grid2_Cobrados_TotalGiama'] = $lstCobrados_TotalGiama['listAnios'];
        
        return $lstResumenCobro;

    }

}