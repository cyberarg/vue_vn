<?php

namespace App\Http\Controllers;

use App\SubiteDatos;
use App\EstadoGestion;
use Illuminate\Http\Request;
use DB;
use DateTime;
use \stdClass;

class TableroControlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    //public function index(Request $request)
    public function getDatos(Request $request)
    {
        //dd($request->periodo);
       $periodo = $request->periodo;
       
        $periodoMes = substr($periodo, 4, strlen($periodo));
        $periodoAnio = substr($periodo, 0, 4);

        $fechaHoy = new DateTime('NOW');
        $fechaHoyDB = $fechaHoy->format("Ymd");

        $yearHoy = $fechaHoy->format("Y");
        $monthHoy = $fechaHoy->format("n");
        $dayHoy = $fechaHoy->format("d");



        $queryStrReport = "CALL hnweb_tablerocontrol('".$fechaHoyDB."');";

        $db = "GF";
        
        $reporte = DB::connection($db)->select($queryStrReport);
        $datos =  SubiteDatos::on($db)->where('CodEstado', 5)->whereYear('fechacompra', '<', $fechaHoyDB)->get();

        $lst = array();
        
        $lst['Reporte'] = $reporte;
        $lst['Datos'] = $datos;


        return $lst;
    }


    public function getDetalleEvolucionCompras(Request $request){

        $lst = array();
        
        $query = "SELECT hcc.Concesionario AS CodCE, ces.Nombre AS Concesionario, hcc.grupo AS Grupo, hcc.orden AS Orden, date_format(hcc.fechacompra, '%d/%m/%Y') AS FechaCompra, hcc.PrecioCompra,
        sof.Nombre AS Oficial, CASE hcc.Vendido WHEN 1 THEN 'SI' ELSE '' END AS Transferido
        FROM pa7_gf.hnweb_historial_compras_caidas hcc
        LEFT JOIN pa7_gf.hnweb_concesionarios ces ON ces.ID = hcc.Concesionario
        LEFT JOIN pa7_gf.subite_oficiales sof ON sof.Codigo = hcc.CodOficialUnificado
        WHERE hcc.CodEstado = 5 
        AND YEAR(hcc.fechacompra) = YEAR(NOW()) 
        AND MONTH(hcc.fechacompra) = MONTH(NOW())
        ORDER BY CodCe, FechaCompra, Oficial ASC ";

        $lst['Datos'] = DB::select($query);

        return $lst;
    }
    

}