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
    public function index(Request $request)
    {
        $emp1 = "AutoCervo";
        $db1= "AC";
        $emp2 = "AutoNet";
        $db2= "AN";
        $emp3 = "CarGroupFusion";
        $db3= "CG";

        $estadoGestion = [];

        $estados = array();
        $empresa1= array();
        $empresa2= array();
        $empresa3= array();
        $est1 = array();
       $est2 = array();

       /*
       $respuesta = array();
       //$empre1 = new \stdClass;
       $empre1 = array();
        
       $empresa = array();
   results1 = DB::connection($db1)->select("CALL net_subitereporte();");

        $empresa['Nombre'] = $emp1;
        $empresa['Items'] = $results1;

        $respuesta['Empresa'] = $empresa;
*/
        $results1 = DB::connection($db1)->select("CALL hnweb_subitereporte();");
        $results2 = DB::connection($db2)->select("CALL hnweb_subitereporte();");
        $results3 =  DB::connection($db3)->select("CALL hnweb_subitereporte();");


       //$estados = array_merge($empresa1, $empresa2);
        $estados = array_merge($results1, $results2, $results3);

       // dd($estados);
return $estados;
        //return response()->json(compact('respuesta'));

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