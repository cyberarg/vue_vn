<?php

namespace App\Http\Controllers;
use App\ObservacionWeb;
use Illuminate\Http\Request;
use DB;
use App\User;
use App\DatoWeb;
use Session;

class ObservacionWebController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        return ObservacionWeb::all(); 
       
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

        $newObs = new ObservacionWeb();
        $newObs->setConnection('GF');
        $newObs->ID_DatoWeb = $request->id;
        $newObs->Obs =$request->Obs;
        $newObs->login = $request->login;
        $newObs->Fecha = now();

        $newObs->save();

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
        return ObservacionWeb::where('ID_DatoWeb',$id)->get();

    }

    public function getObservacion(Request $request)
    {
        $observaciones = ObservacionWeb::where('ID_DatoWeb',$request->id)->get(); // static method

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
