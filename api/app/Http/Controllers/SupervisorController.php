<?php

namespace App\Http\Controllers;

use App\Supervisor;
use Illuminate\Http\Request;
use DB;

class SupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     /*
    public function __invoke(Request $request){
        return response()->json(DB::select("CALL net_subitegetoficiales ('C')"));
    }
    */

    public function index(Request $request)
    {
       // return Oficial::all();
        return Supervisor::all();
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
        $newOficial->login = $request->Login;
        $newOficial->Supervisor = $request->NomSup;
        
        $newOficial->save();

        return Oficial::findOrFail($newOficial->Codigo);
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
        //dd($request);
        //dd($request->query());
        //$oficialObj = $request->item;

        $oficial = Oficial::findOrFail($id);
        $oficial->Nombre = $request->Nombre;
        $oficial->login = $request->Login;
        $oficial->Supervisor = $request->NomSup;
        //$oficial->Supervisor = $request->CodigoSupervisor;
        $oficial->save();

        return $oficial;
        //$oficial->update($request->query());
        

        
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
