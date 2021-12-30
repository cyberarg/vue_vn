<?php

namespace App\Http\Controllers;

use App\HaberNeto;
use Illuminate\Http\Request;
use DB;
use DateTime;
use App\Http\Controllers\UtilsController;
use ArrayObject;
use App\Http\Controllers\HNResumenCompradosController;

class HNResumenCompradosSelectsController extends Controller
{

    public function getListHNResumenComprados_Selected(Request $request){

        $lstResumenCompra = array();

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

            $resCompas = new HNResumenCompradosController();
            
            // soloCE_Giama_Todos: 0-Todos, 1-Giama, 2-Solo CE
            $lstItemsHN_Resumen = array_merge($lstItemsHN_Resumen, $resCompas->getItemsResumen_Selecteds($anio, $marca, $concesionario, 0, $filtros, 1));
            $lstItemsHN_Resumen_Giama = array_merge($lstItemsHN_Resumen_Giama, $resCompas->getItemsResumen_Selecteds($anio, $marca, $concesionario, 1, $filtros, 1));
            $lstItemsHN_Resumen_CE = array_merge($lstItemsHN_Resumen_CE, $resCompas->getItemsResumen_Selecteds($anio, $marca, $concesionario, 2, $filtros, 1));
            $lstItemsHN_Resumen_TotalGiama = array_merge($lstItemsHN_Resumen_TotalGiama, $resCompas->getItemsResumen_Selecteds($anio, $marca, $concesionario, 3, $filtros, 1));

            $p++;
        }

        $lstComprados = $resCompas->getResumenCompradosMeses($anio, $lstItemsHN_Resumen, $marca);
        $lstComprados_Giama = $resCompas->getResumenCompradosMeses($anio, $lstItemsHN_Resumen_Giama, $marca);
        $lstComprados_CE = $resCompas->getResumenCompradosMeses($anio, $lstItemsHN_Resumen_CE, $marca);
        $lstComprados_TotalGiama = $resCompas->getResumenCompradosMeses($anio, $lstItemsHN_Resumen_TotalGiama, $marca);

        $lstResumenCompra['Grid1_Comprados'] = $lstComprados['lstMeses'];
        $lstResumenCompra['Grid2_Comprados'] = $lstComprados['listAnios'];

        $lstResumenCompra['Grid1_Comprados_Giama'] = $lstComprados_Giama['lstMeses'];
        $lstResumenCompra['Grid2_Comprados_Giama'] = $lstComprados_Giama['listAnios'];

        $lstResumenCompra['Grid1_Comprados_CE'] = $lstComprados_CE['lstMeses'];
        $lstResumenCompra['Grid2_Comprados_CE'] = $lstComprados_CE['listAnios'];

        $lstResumenCompra['Grid1_Comprados_TotalGiama'] = $lstComprados_TotalGiama['lstMeses'];
        $lstResumenCompra['Grid2_Comprados_TotalGiama'] = $lstComprados_TotalGiama['listAnios'];

        return $lstResumenCompra;

    }

}