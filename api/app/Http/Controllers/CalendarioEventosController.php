<?php

namespace App\Http\Controllers;
use App\EventoCalendario;
use App\PersonalCalendario;
use App\SectoresCalendario;
use App\Calendario;
use Illuminate\Http\Request;
use DB;

class CalendarioEventosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getCombosCalendario()
    {
        $datos = array();

        $datos['Sectores'] = SectoresCalendario::all();
        $datos['Personal'] = PersonalCalendario::all();
        $datos['TipoEventos'] = EventoCalendario::all();

        return $datos;
    }

    public function getEventosCalendario(Request $request)
    {
       // return Oficial::all();
        return DB::select("CALL hnweb_subitegetoficiales(NULL)");
    }

}
