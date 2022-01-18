<?php

namespace App\Http\Controllers;

use App\Motivo;
use App\MotivoCaida;
use App\Estado;
use App\EstadoWeb;
use Illuminate\Http\Request;
use DB;

class ComboboxController extends Controller
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
        //return Supervisor::all();
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
        switch($id){
            case 'motivos':
                return Motivo::all();
            break;
            case 'motivos_caida':
                return MotivoCaida::all();
            break;
            case 'estados':
                return Estado::all();
            break;
            case 'estadosweb':
                return EstadoWeb::all();
            break;
            default:
            return "";
        break;
        }
  
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
