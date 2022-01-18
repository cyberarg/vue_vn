<?php

namespace App\Http\Controllers;
 
use App\SubiteDatos;
use Illuminate\Http\Request;
use DB;
use ArrayObject;
use App\Http\Controllers\UtilsController;

class ReporteObservacionesOficialController extends Controller
{
   
    public function getReporteObservaciones(Request $request){
        $arr = array();

        $fechaDesde = $request->FechaDesde;
        $fechaHasta = $request->FechaHasta;


        $queryStrObs = "CALL hnweb_observaciones_oficiales('".$fechaDesde."', '".$fechaHasta."');";

        $queryStrDatos = "SELECT ID, Marca, Concesionario, Grupo, subite_datos.Orden, Solicitud, Apellido, Nombres, NroDoc, Telefono1, Telefono2, Telefono3, Telefono4, Email1, Email2, 
                        FechaVtoCuota2, Plan, Avance, CPG, CAD, HaberNeto, CodOficial, CodSup, 
                        subite_datos.CodOficial, O.Nombre AS NomOficial, O.Login, subite_datos.CodEstado, 
                        E.Nombre AS NomEstado,
                        FechaCompra, PrecioCompra, FechaAltaRegistro, Domicilio, Origen, 
                        PrecioMaximoCompra, FechaUltimaAsignacion, Vendido, Motivo, EsDatoNuevo,
                        (SELECT DATE_FORMAT(Fecha,'%Y/%m/%d')
                        FROM subite_obs 
                        WHERE ID_Datos = subite_datos.ID AND Automatica = 0 ORDER BY Fecha DESC LIMIT 1) AS FechaUltObs
                        FROM subite_datos 
                        LEFT JOIN subite_oficiales O ON subite_datos.CodOficial = O.Codigo 
                        LEFT JOIN subite_estados E ON subite_datos.CodEstado = E.Codigo 
                        WHERE id IN (SELECT DISTINCT(ID_Datos)
                                FROM subite_obs
                                WHERE automatica = 0 AND fecha BETWEEN '".$fechaDesde."' AND '".$fechaHasta."');";

        $report = array();
        $datos = array();

        $arrGF = DB::connection('GF')->select($queryStrDatos);
        $arrAN = DB::connection('AN')->select($queryStrDatos);
        $arrCG = DB::connection('CG')->select($queryStrDatos);

        $report = DB::connection('GF')->select($queryStrObs);//->toArray(); 
        $datos = array_merge($arrGF, $arrAN, $arrCG); 
        
        $respuesta['Reporte'] = $report;
        $respuesta['Datos'] = $datos;

        return $respuesta;
    }
    

}