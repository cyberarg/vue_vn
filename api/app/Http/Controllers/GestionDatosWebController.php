<?php

namespace App\Http\Controllers;

use App\DatoWeb;
use App\ObservacionWeb;
use Illuminate\Http\Request;
use App\Http\Controllers\UtilsController;
use DateTime;

class GestionDatosWebController extends Controller
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
        return DatoWeb::whereNull('PasarDato')->get();
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

        $dato->FullName = $request->FullName;

        if ($dato->NroDoc != $request->NroDoc){
            $dato->NroDoc =  $request->NroDoc; 
        }
        
        if ($dato->Telefono != $request->Telefono){
            $dato->Telefono =  $request->Telefono; 
        }
   
        if ($dato->Email != $request->Email){
            $dato->Email =  $request->Email; 
        }

        if ($dato->Telefono2 != $request->Telefono2){
            $dato->Telefono2 =  $request->Telefono2; 
        }
   
        if ($dato->Email2 != $request->Email2){
            $dato->Email2 =  $request->Email2; 
        }

        if ($dato->Domicilio != $request->Domicilio){
            $dato->Domicilio = $request->Domicilio; 
        }

        if ($dato->PasarDato != $request->PasarDato){
            $dato->PasarDato = $request->PasarDato; 
        }

        if ($dato->Marca != $request->Marca){
            $dato->Marca = $request->Marca; 
        }

        if ($dato->Grupo != $request->Grupo){
            $dato->Grupo = $request->Grupo; 
        }

        if ($dato->Orden != $request->Orden){
            $dato->Orden = $request->Orden; 
        }

        if ($dato->HaberNeto != $request->HaberNeto){
            $dato->HaberNeto = $request->HaberNeto; 
        }

        if ($dato->CPG != $request->CPG){
            $dato->CPG = $request->CPG; 
        }

        if ($dato->CAD != $request->CAD){
            $dato->CAD = $request->CAD; 
        }

        if ($dato->Plan != $request->Plan){
            $dato->Plan = $request->Plan; 
        }

        if ($dato->Avance != $request->Avance){
            $dato->Avance = $request->Avance; 
        }

        if ($dato->FechaVtoCuota2 != $request->FechaVtoCuota2){
            $dato->FechaVtoCuota2 = $request->FechaVtoCuota2; 
        }

        if ($dato->PorcentajeValorHN != $request->PorcentajeValorHN){
            $dato->PorcentajeValorHN = $request->PorcentajeValorHN; 
        }

        $dato->EsDatoNuevo =  0;

        if ($request->CodEstado){
             $hoy = new DateTime('NOW');
             $ffHoy =  $hoy->format('Y-m-d H:i:s');

            if ($dato->CodEstado != $request->CodEstado){
                $dato->CodEstado = $request->CodEstado;

                if ($dato->CodEstado == 5){ //Pasar A Asignación
                    //Generar Registro en SubiteDatos
                }
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
