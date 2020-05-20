<?php

namespace App\Http\Controllers;
use App\Supervisor;
use App\Oficial;
use Illuminate\Http\Request;
use DB;

class OficialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
       // return Oficial::all();
        return DB::select("CALL hnweb_subitegetoficiales(NULL)");
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

        $newOficial = new Oficial();
        $newOficial->Nombre =$request->Nombre;
        $newOficial->login = $request->login;
        $newOficial->Supervisor =  $request->NomSup['Codigo'];

        $newOficial->save();
        $newOficial->CodSup =  $request->NomSup['Codigo'];
        $newOficial->NomSup =  $request->NomSup['Nombre'];

        return $newOficial;
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd("show");
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

        $oficial = Oficial::findOrFail($id);
        $oficial->Nombre = $request->Nombre;
        $oficial->login = $request->login;
        $oficial->Supervisor = $request->NomSup['Codigo'];

        $oficial->save();

        return $oficial;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $oficial = Oficial::findOrFail($id);
        $oficial->delete(); 
    }
}
