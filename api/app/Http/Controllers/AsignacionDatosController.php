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
        $db3= "CG";
        $marca = 5;
        $concesionario = "NULL";
        $arr = array();

        $resultvw = DB::select("CALL hnweb_subitegetdatos_vw(NULL, NULL, 0, ".$marca.", ".$concesionario.", NULL);"); 
        
        $resultcg = DB::connection($db3)->select("CALL hnweb_subitegetdatos(NULL, NULL, 0, NULL);");
        //$resultcg = array();

        $arr = array_merge($resultvw, $resultcg);

        return $arr;
        //return DB::select("CALL hnweb_subitegetdatos_vw(NULL, NULL, 0, ".$marca.", ".$concesionario.", NULL);"); 
        //return DB::select("CALL net_subitegetdatos(NULL, NULL, 0);");
        
    }

    public function asginarDatos(Request $request){

        $arrObj = $request->data;
        $oficial = json_decode(json_encode($request->oficial));
        $supervisor = json_decode(json_encode($request->supervisor));
        $user = json_decode(json_encode($request->login));

        //$marca = 5;

        if (isset($supervisor) && (is_object($supervisor))){
            $asignaOficial = 0;
            $codOficial = 0;
            $codSupervisor = $supervisor->Codigo;
            $obsAut = "La operación fue asignada al Supervisor ".$supervisor->Nombre."  por el usuario ".$user.".";
                    
        }else{
            $asignaOficial = 1;
            $codSupervisor = 0;
            $codOficial = $oficial->Codigo;
            $obsAut =  "La operación fue asignada al Oficial ".$oficial->Nombre." por el usuario ".$user.".";
        }
        

        foreach ($arrObj as $dato) {
            
            $js = json_decode(json_encode($dato));


            $str[] = "CALL hnweb_subiteasignacion(".$asignaOficial.", '".$js->Grupo."', ".$js->Orden.", ".$codOficial.", ".$codSupervisor.");";
            $res[] = DB::select("CALL hnweb_subiteasignacion(".$asignaOficial.", '".$js->Grupo."', ".$js->Orden.", ".$js->Marca.", ".$codOficial.", ".$codSupervisor.");");
        // DB::select("CALL hnweb_subiteasignacion(p_ASIGNAOFICIAL, P_GRUPO, P_ORDEN, p_MARCA, P_OFICIAL, P_SUPERVISOR);");

            $obs = new ObservacionController;
            $reqObs = new Request;

            $reqObs->id = $js->ID;
            $reqObs->Obs = $obsAut;
            $reqObs->login = 'hnweb';
            $reqObs->Automatica = 1;
            
            $obs->store($reqObs);
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
