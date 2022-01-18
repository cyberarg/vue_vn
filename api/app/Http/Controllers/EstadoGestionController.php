<?php

namespace App\Http\Controllers;

use App\DatoSubite;
use Illuminate\Http\Request;
use DB;

class EstadoGestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDatos(Request $request)
    {

        $marca = $request->Marca;
        $concesionario =  $request->Concesionario;
        $esVinculo = $request->EsVinculo;

        $db1= "AC"; //AutoCervo
        $db2= "AN"; //AutoNet
        $db3= "CG"; //CarGroupFusion

        $result = array();

        $estados = array();

        //TODAS LAS MARCAS - TODOS LOS CONCESIONARIOS
        if ($marca == 0){

            $results1 = array();
            $results2 = array();
            $results3 = array(); 
            $results4 = array(); 
            $total1 = array();
            $total2 = array();
            $total3 = array();    
            $total4 = array(); 

            if (!$esVinculo){
                $results1 = DB::connection($db1)->select("CALL hnweb_subitereporte();");
                $results2 = DB::connection($db2)->select("CALL hnweb_subitereporte();");
                $results3 =  DB::connection($db3)->select("CALL hnweb_subitereporte();");

                $total1 = DB::connection($db1)->select("CALL hnweb_subitereporte_totales_marca_conc(2, NULL);");
                $total2 = DB::connection($db2)->select("CALL hnweb_subitereporte_totales_marca_conc(2, NULL);");
                $total3 =  DB::connection($db3)->select("CALL hnweb_subitereporte_totales_marca_conc(2, NULL);");
            }
            $results6 = DB::select("CALL hnweb_subitereporte_marca_conc(6, NULL);");
            $total6 = DB::select("CALL hnweb_subitereporte_totales_marca_conc(6, NULL);");

            $results0 = DB::select("CALL hnweb_subitereporte_marca_conc(5, NULL);");
            $total0 = DB::select("CALL hnweb_subitereporte_totales_marca_conc(5, NULL);");

            $estados = array_merge($results0, $results6, $results1, $results2, $results3);
            $totaldatos =  array_merge($total0, $total6, $total1, $total2, $total3);
        }else {
            switch ($marca) {
                case 2:
                    switch ($concesionario) {
                        case 4:
                            $estados = DB::connection($db1)->select("CALL hnweb_subitereporte();");
                            $totaldatos = DB::connection($db1)->select("CALL hnweb_subitereporte_totales_marca_conc(".$marca.", NULL);");
                        break;
                        case 5:
                            $estados = DB::connection($db2)->select("CALL hnweb_subitereporte();");
                            $totaldatos = DB::connection($db2)->select("CALL hnweb_subitereporte_totales_marca_conc(".$marca.", NULL);");
                        break;
                        case 6:
                            $estados = DB::connection($db3)->select("CALL hnweb_subitereporte();");
                            $totaldatos = DB::connection($db3)->select("CALL hnweb_subitereporte_totales_marca_conc(".$marca.", NULL);");
                        break;
                        case 13: // Fiat - Datos Web
                            $estados = DB::select("CALL hnweb_subitereporte_marca_conc(".$marca.", ".$concesionario.");");
                            $totaldatos = DB::select("CALL hnweb_subitereporte_totales_marca_conc(".$marca.", ".$concesionario.");");
                        break;
                        default:
                            $results1 = DB::connection($db1)->select("CALL hnweb_subitereporte();");
                            $results2 = DB::connection($db2)->select("CALL hnweb_subitereporte();");
                            $results3 =  DB::connection($db3)->select("CALL hnweb_subitereporte();");
                            $results4 = DB::select("CALL hnweb_subitereporte_marca_conc(".$marca.", NULL);");

                            $total1 = DB::connection($db1)->select("CALL hnweb_subitereporte_totales_marca_conc(".$marca.", NULL);");
                            $total2 = DB::connection($db2)->select("CALL hnweb_subitereporte_totales_marca_conc(".$marca.", NULL);");
                            $total3 =  DB::connection($db3)->select("CALL hnweb_subitereporte_totales_marca_conc(".$marca.", NULL);");
                            $total4 =  DB::connection($db4)->select("CALL hnweb_subitereporte_totales_marca_conc(".$marca.", NULL);");

                            $estados = array_merge($results1, $results2, $results3, $results4);
                            $totaldatos =  array_merge($total1, $total2, $total3, $total4);
                        break;
                    }
                   
                break;
                
                default:
                    switch ($concesionario) {
                        case 0:
                            $estados = DB::select("CALL hnweb_subitereporte_marca_conc(".$marca.", NULL);");
                            $totaldatos = DB::select("CALL hnweb_subitereporte_totales_marca_conc(".$marca.", NULL);");
                        break;
                        default:
                            $estados = DB::select("CALL hnweb_subitereporte_marca_conc(".$marca.", ".$concesionario.");");
                            $totaldatos = DB::select("CALL hnweb_subitereporte_totales_marca_conc(".$marca.", ".$concesionario.");");
                        break;
                    }
                break;
            }
        }
        
        $result['Estados'] = $estados;
        $result['TotalDatos'] = $totaldatos;

        return $result;

    }

}