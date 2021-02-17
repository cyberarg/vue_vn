<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\UtilsController;

class HNStockController extends Controller
{
 
    public function getListHNStock(Request $request){

        $lstStock = array();
        $anio = date('Y');

        $lst = array();
        $lst_CE = array();
        $lst_Anual = array();
        $lst_Anual_CE = array();
        $lstCotiz = array();
        $lstGridCotiz = array();

        $marca = $request->Marca;
        $concesionario = $request->Concesionario;
        $filtros = $request->Filtros;
        $rbConsolidado = $request->ConsolidadoRB;

        $uC = new UtilsController;
        $db = $uC->getDabaseName($marca, $concesionario);

       
        $lstCotiz = DB::connection($db)->select("CALL hnweb_get_cotizacion_dolar_periodo('CCL', ".$anio.")");
        //$lstCotiz = DB::connection($db)->select("CALL hnweb_get_cotizacion_dolar_periodo('CCL', 2020)");

        switch($marca){
            case 2:
            case 99: 
                $lst = DB::connection($db)->select("CALL hnweb_get_Stock_New(".$marca.", ".$concesionario.")");
                //$lst_CE = DB::select("CALL hnweb_get_Stock_Solo_CE(".$marca.", ".$concesionario.")");
            break;
            case 5:
                $lst = DB::connection($db)->select("CALL hnweb_get_Stock_Soporte(".$marca.", ".$concesionario.")");
                $lst_CE = DB::connection($db)->select("CALL hnweb_get_Stock_Soporte_Solo_CE(".$marca.", ".$concesionario.")");
                $lst_Anual_CE = DB::connection($db)->select("CALL hnweb_get_Stock_Anual_Solo_CE(".$marca.", ".$concesionario.")");
  
            break;

        }
        $lst_Anual = DB::connection($db)->select("CALL hnweb_get_Stock_Anual_New(".$marca.", ".$concesionario.")");
  
        $lstStock['Grid1'] = $lstCotiz;
        $lstStock['Grid2'] = $lst;
        $lstStock['Grid2_CE'] = $lst_CE;

        $lstStock['Grid3'] = $lst_Anual;
        $lstStock['Grid3_CE'] = $lst_Anual_CE;

        return $lstStock;

    }


}