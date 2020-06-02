<?php

namespace App\Http\Controllers;

use App\SubiteDatos;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\UtilsController;

class GestionDatosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $db3= "CG";
        //$result = DB::select("CALL hnweb_subitegetdatos(NULL, NULL, 0);");
        $result = DB::select("CALL hnweb_subitegetdatos_vw(NULL, NULL, 0);"); 
       // $result = DB::connection($db3)->select("CALL hnweb_subitegetdatos(NULL, NULL, 0);");
        $list = array();
  
        foreach ($result as $r) {

            $oDet = json_decode(json_encode($r), FALSE);

            $oDet->ApeNom = $oDet->Apellido.", ".$oDet->Nombres;

            $fcav = null;
            $fvc2 = strtotime($oDet->FechaVtoCuota2);

            if ($oDet->FechaVtoCuota2 === NULL){
                if ($oDet->Marca == 5){
                    $oDet->AvanceAutomatico = $oDet->Avance;
                }else{
                    $oDet->AvanceAutomatico = 0;
                }
   
           }else{
                $oDet->AvanceAutomatico = $this->getAvanceAutomatico($fvc2);
                $oDet->Avance = $oDet->AvanceAutomatico;
           }
//dd($oDet);
           array_push($list, $oDet);

        } //end foreach

        return $list;
                
    }


    public function getAvanceAutomatico($FechaVtoCuota2){

        $avance = 0;

        $fecha = strtotime(now());

        if ($FechaVtoCuota2 === NULL){
            return 0;
        }else{
            $fvtoc2 = date_create(date('Y-m-d', $FechaVtoCuota2));
            $ff = date_create(date('Y-m-d', $fecha));       
    
            if (checkdate(date('m', $FechaVtoCuota2), date('d', $FechaVtoCuota2), date('Y', $FechaVtoCuota2))){
                $diff = date_diff($fvtoc2 , $ff);
                $avance = ($diff->format('%y') * 12 + $diff->format('%m')) + 2;
            }
        }

        if ($avance > 84){
            $avance = 84;
        }

        return $avance;
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
        //return DB::select("CALL hnweb_subitegetdatos(".$id.", NULL, 0);");
        //return DB::connection($db3)->select("CALL hnweb_subitegetdatos(".$id.", NULL, 0);");
        return DB::select("CALL hnweb_subitegetdatos_vw(".$id.", NULL, 0);");
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

        $dato = SubiteDatos::findOrFail($id);

        $util = new UtilsController;

        $dato->Nombres = $request->Nombres;
        $dato->Apellido = $request->Apellido; 
        $dato->Telefono1 =  $request->Telefono1; 
        $dato->Telefono2 =  $request->Telefono2; 
        $dato->Telefono3 =  $request->Telefono3; 
        $dato->Telefono4 =  $request->Telefono4; 
        $dato->Email1 =  $request->Email1;
        $dato->Domicilio = $request->Domicilio;
        $dato->FechaCompra =  $util->reversarFecha($request->FechaCompra, 'DB');
        $dato->PrecioCompra =  $request->PrecioCompra;

        if ($request->CodEstado){
            $dato->CodEstado = $request->CodEstado;
            if ($request->CodEstado == 4){
                $dato->Motivo =  $request->Motivo + 1;
            }else{
                $dato->Motivo = 0;
            }
            //$dato->Motivo = $request->Motivo;
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
