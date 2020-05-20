<?php

namespace App\Http\Controllers;

use App\DatoSubite;
use Illuminate\Http\Request;
use DB;

class ReporteComprasMesActualController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        
        $lstMesActual = array();
        $lstMesActual = json_decode($request->list,true);

        //return $lstMesActual;
        /*
        return $request->data;
        $lstMesActual = $request->list;

        
*/


        $listMes = array();

        $listMes[0]['Tipo'] = 'Avance 0';
        $listMes[0]['Casos'] = 0;
        $listMes[0]['MontoHN'] = 0;
       
        $listMes[1]['Tipo'] = '1 a 60';
        $listMes[1]['Casos'] = 0; 
        $listMes[1]['MontoHN'] = 0;
       
        $listMes[2]['Tipo'] = '61 a 70';
        $listMes[2]['Casos'] = 0; 
        $listMes[2]['MontoHN'] = 0;
       
        $listMes[3]['Tipo'] = '71 a 80';
        $listMes[3]['Casos'] = 0; 
        $listMes[3]['MontoHN'] = 0;
       
        $listMes[4]['Tipo'] = '81 a 83';
        $listMes[4]['Casos'] = 0; 
        $listMes[4]['MontoHN'] = 0;


        foreach ($lstMesActual as $r) {
            
            switch ($r->AvanceAutomatico){
                case 0:
                    $listMes[0]['Casos'] += 1;
                    $listMes[0]['MontoHN'] += $r->HaberNeto;
                break;
                case (1 <= $r->AvanceAutomatico) && ( $r->AvanceAutomatico <= 60):
                    $listMes[1]['Casos'] += 1; 
                    $listMes[1]['MontoHN'] += $r->HaberNeto;
                break;
                case (61 <= $r->AvanceAutomatico) && ( $r->AvanceAutomatico <= 70):
                    $listMes[2]['Casos'] += 1; 
                    $listMes[2]['MontoHN'] += $r->HaberNeto;
                break;
                case (71 <= $r->AvanceAutomatico) && ( $r->AvanceAutomatico <=  80):
                    $listMes[3]['Casos'] += 1; 
                    $listMes[3]['MontoHN'] += $r->HaberNeto;
                break;
                case (81 <= $r->AvanceAutomatico) && ( $r->AvanceAutomatico <=  83):
                    $listMes[4]['Casos'] += 1; 
                    $listMes[4]['MontoHN'] += $r->HaberNeto;
                break;

            }

        }


        return $listMes;

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