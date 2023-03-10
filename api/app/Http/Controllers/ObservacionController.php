<?php

namespace App\Http\Controllers;
use App\Observacion;
use Illuminate\Http\Request;
use DB;
use App\User;
use App\SubiteDatos;
use Session;

class ObservacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        return Observacion::all(); 
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd("create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //switch($request->marca){
        //    case 2:
                switch($request->concesionario){
                    case 4:
                        $db = 'AC';
                    break;
                    case 5:
                        $db = 'AN';
                    break;
                    case 6:
                        $db = 'CG';
                    break;
                    default:
                        $db = 'GF';
                    break;
                }

        //    break;
         //    default:
         //        $db = 'GF';
         //    break;
        //}
       
        $newObs = new Observacion();
        $newObs->setConnection($db);
        $newObs->ID_Datos = $request->id;
        $newObs->Obs =$request->Obs;
        $newObs->login = $request->login;
        $newObs->Fecha = now();
        //$newObs->login = $user['login'];
        $newObs->Automatica =  $request->Automatica;

        $newObs->save();

        $dato = SubiteDatos::on($db)->findOrFail($request->id);
        $dato->EsDatoNuevo =  0;
        $dato->save();

        return $newObs;
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Observacion::where('ID_Datos',$id)->get();

    }

    public function getObservacion(Request $request)
    {
        //switch($request->marca){
        //    case 2:
                switch($request->concesionario){
                    case 4:
                        $db = 'AC';
                    break;
                    case 5:
                        $db = 'AN';
                    break;
                    case 6:
                        $db = 'CG';
                    break;
                    default:
                        $db = 'GF';
                    break;
                }
           // break;
           // default:
           //     $db = 'GF';
           // break;
        //}
        $observaciones = Observacion::on($db)->where('ID_Datos',$request->id)->get(); // static method

        return $observaciones;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd("edit");
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
        /*
        $oficial = Oficial::findOrFail($id);
        $oficial->Nombre = $request->Nombre;
        $oficial->login = $request->login;
        $oficial->Supervisor = $request->NomSup['Codigo'];

        $oficial->save();

        return $oficial;
        */
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
