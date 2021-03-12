<?php

namespace App\Http\Controllers;

use App\DatoWeb;
use App\ObservacionWeb;
use Illuminate\Http\Request;
use App\Http\Controllers\UtilsController;
use DateTime;

class GestionDatosController extends Controller
{

    public function __construct(){
        //$this->middleware(['auth:api']);
     }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getDatos(Request $request)
    {
        return DatoWeb::on($db)->whereNull('PasarDato')->get();
    }


    public function index(Request $request)
    {
        //
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
        return DatoWeb::findOrFail($id);
    }

    public function showDato(Request $request)
    {
        return DatoWeb::findOrFail($request->ID);

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

    public function update(Request $request){
        //
    }

    public function updateDato(Request $request)
    {

        $dato = DatoWeb::findOrFail($request->ID);
        $textObsAutom = "";
        $util = new UtilsController;

        $dato->Nombres = $request->Nombres;
        $dato->Apellido = $request->Apellido; 

        if ($dato->NroDoc != $request->NroDoc){
            $dato->NroDoc =  $request->NroDoc; 
        }
        
        if ($dato->Telefono1 != $request->Telefono1){
            $dato->Telefono1 =  $request->Telefono1; 
        }
   
        if ($dato->Email1 != $request->Email1){
            $dato->Email1 =  $request->Email1; 
        }

        if ($dato->Domicilio != $request->Domicilio){
            $dato->Domicilio = $request->Domicilio; 
        }

        $dato->EsDatoNuevo =  0;

        if ($request->CodEstado){
             $hoy = new DateTime('NOW');
             $ffHoy =  $hoy->format('Y-m-d H:i:s');

            if ($dato->CodEstado == 5 && $request->CodEstado == 9){    
                $dato->FechaVentaCaida = $ffHoy;
            }

            if ($dato->CodEstado != $request->CodEstado){
                $dato->CodEstado = $request->CodEstado;
            }
        
        }
        
        $dato->save();

        return $dato;
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
