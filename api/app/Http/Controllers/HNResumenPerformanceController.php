<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\UtilsController;

class HNResumenPerformanceController extends Controller
{
 
    public function getResumen(Request $request){

        $lstResumen = array();

        $marca = $request->Marca;
        $concesionario = $request->Concesionario;
        $anio = $request->Anio;

        $lst = array();
        $lst_CE = array();

        $lstFlujo = array();
        $lstFlujo_CE = array();
        $lstFlujo_RB = array();

        $arrRB = array();
        $arrGF = array();
        $lstRes = array();
        $lstRes_CE = array();
        $lstGridCotiz = array();

        $marca = $request->Marca;
        $concesionario = $request->Concesionario;
        $filtros = $request->Filtros;
        $rbConsolidado = $request->ConsolidadoRB;

        $uC = new UtilsController;
        $db = $uC->getDabaseName($marca, $concesionario);

        switch($marca){

            case 99: //GIAMA
                $lstRes = DB::connection('GF')->select("CALL hnweb_get_resumen_anual_2(".$anio.")");
                $lstFlujo = DB::connection('GF')->select("CALL hnweb_get_saldo_caja(".$anio.")");
            break;
    
            default:
                $lstRes_CE = DB::connection('GF')->select("CALL hnweb_get_resumen_anual_ce(".$anio.", ".$concesionario.")");

                $lstFlujo = DB::connection($db)->select("CALL hnweb_get_saldo_caja_ce(".$anio.", ".$concesionario.", NULL)");
                $lstFlujo_RB = DB::connection($db)->select("CALL hnweb_get_saldo_caja_ce(".$anio.", ".$concesionario.", 1)");
                $lstFlujo_CE = DB::connection($db)->select("CALL hnweb_get_saldo_caja_ce(".$anio.", ".$concesionario.", 0)");
            break;
            
        }

        $lstResumen['Grid1'] = $lstRes;
        $lstResumen['Grid1_CE'] = $lstRes_CE;

        $lstResumen['Grid2'] = $lstFlujo;
        $lstResumen['Grid2_CE'] = $lstFlujo_CE;
        $lstResumen['Grid2_RB'] = $lstFlujo_RB;
      
        return $lstResumen;

    }


}