<?php

namespace App\Http\Controllers;

use App\DatoSubite;
use Illuminate\Http\Request;
use DB;

class AsignacionDatosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $marca = 5;
        $concesionario = 1;
        return DB::select("CALL hnweb_subitegetdatos_vw(NULL, NULL, 0, ".$marca.", ".$concesionario.");"); 
        //return DB::select("CALL net_subitegetdatos(NULL, NULL, 0);");
        
    }

    public function asginarDatos(Request $request){

        $arrObj = $request->data;
        $oficial = json_decode(json_encode($request->oficial));
        $supervisor = json_decode(json_encode($request->supervisor));

        $marca = 5;

        if (isset($supervisor) && (is_object($supervisor))){
            $asignaOficial = 0;
            $codOficial = 0;
            $codSupervisor = $supervisor->Codigo;
        }else{
            $asignaOficial = 1;
            $codSupervisor = 0;
            $codOficial = $oficial->Codigo;
        }
        

        foreach ($arrObj as $dato) {
            
            $js = json_decode(json_encode($dato));


            $str[] = "CALL hnweb_subiteasignacion(".$asignaOficial.", '".$js->Grupo."', ".$js->Orden.", ".$codOficial.", ".$codSupervisor.");";
            $res[] = DB::select("CALL hnweb_subiteasignacion(".$asignaOficial.", '".$js->Grupo."', ".$js->Orden.", ".$marca.", ".$codOficial.", ".$codSupervisor.");");
        // DB::select("CALL hnweb_subiteasignacion(p_ASIGNAOFICIAL, P_GRUPO, P_ORDEN, p_MARCA, P_OFICIAL, P_SUPERVISOR);");
        }
        return $str;

    }

    public function pasarASinGestionar(Request $request){

        $arrObj = $request->data;
        $marca = 5;

        foreach ($arrObj as $dato) {
            
            $js = json_decode(json_encode($dato));

            $str[] = "CALL hnweb_subitepasarasingestionar('".$js->Grupo."', ".$js->Orden.", ".$marca.");";
            $res[] = DB::select("CALL hnweb_subitepasarasingestionar('".$js->Grupo."', ".$js->Orden.", ".$marca.");");
        // DB::select("CALL hnweb_subitepasarasingestionar(P_GRUPO, P_ORDEN);");
        }
        return $str;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
