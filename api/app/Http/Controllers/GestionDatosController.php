<?php

namespace App\Http\Controllers;

use App\SubiteDatos;
use App\User;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\UtilsController;
use Auth;

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
    public function index(Request $request)
    {

        $db3= "CG";
        //$result = DB::select("CALL hnweb_subitegetdatos(NULL, NULL, 0);");
        $marca = 5;
        $concesionario = 'NULL';
        $result = DB::select("CALL hnweb_subitegetdatos_vw(NULL, NULL, 0, ".$marca.", ".$concesionario.");"); 
       // $result = DB::connection($db3)->select("CALL hnweb_subitegetdatos(NULL, NULL, 0);");
        $list = array();
  
        foreach ($result as $r) {

            $oDet = json_decode(json_encode($r), FALSE);
            $oEstado = new \stdClass();

            $agregar = false;
            if(!($this->enOtraSociedadOPropio($oDet->Nombres, $oDet->Apellido))){
                $agregar = true;
            }
            if ($agregar){
                $oDet->ApeNom = $oDet->Apellido;
                if ($oDet->Nombres != ""){
                    $oDet->ApeNom = $oDet->ApeNom.", ".$oDet->Nombres;
                }
                

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

                $oEstado->Codigo = $oDet->CodEstado;
                $oEstado->Nombre = $oDet->NomEstado;

                $oDet->Estado = $oEstado;

                $oDet->PrecioMaximoCompra = $this->getPrecioMaximoCompra($oDet->Avance, $oDet->HaberNeto);

                array_push($list, $oDet);
            }
           

        } //end foreach

        return $list;
                
    }

    public function getPrecioMaximoCompra($avance, $haberNeto){
        switch($avance){
            case  (45 <= $avance) && ($avance <= 61):
                return $haberNeto * 0.2;
            break;
            case (62 <= $avance) && ($avance <= 66):
                return $haberNeto * 0.3;
            break;
            case (67 <= $avance) && ($avance <= 69):
                return $haberNeto * 0.35;
            break;
            case (70 <= $avance) && ($avance <= 79):
                return $haberNeto * 0.4;
            break;
            case (80 <= $avance) && ($avance <= 83):
                return $haberNeto * 0.5;
            break;
            default:
            return 0;
        break;
        }

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

    Public Function enOtraSociedadOPropio($nombre, $apellido){
        $apeLow = strtolower($apellido);
        $nomLow = strtolower($nombre);

        if (

            (strpos($apeLow,"volkswagen argentina sa") !== false) ||
            (strpos($apeLow,"volkswagen argentina s.a.") !== false) ||
            (strpos($apeLow,"autokar") !== false) ||
            (strpos($apeLow,"autofinancia") !== false) ||
            (strpos($apeLow,"auto financia") !== false) ||    
            (strpos($nomLow ,"autokar") !== false )||
            (strpos($nomLow ,"autofinancia") !== false) ||
            (strpos($nomLow ,"auto financia") !== false) ||
            (strpos($apeLow,"car group") !== false) ||
            (strpos($apeLow,"car gruop") !== false) ||
            (strpos($apeLow,"autonet") !== false) ||
            (strpos($apeLow,"mdplanes") !== false) ||
            (strpos($apeLow, "gestion financiera") !== false) ||
            (strpos($apeLow,"margian") !== false) ||
            (strpos($apeLow,"ricardo bevacqua") !== false) ||
            (strpos($nomLow ,"car group") !== false) ||
            (strpos($nomLow ,"car gruop") !== false )||
            (strpos($nomLow ,"autonet") !== false) ||
            (strpos($nomLow ,"mdplanes") !== false) ||
            (strpos($nomLow ,"gestion financiera") !== false) ||
            (strpos($nomLow ,"margian") !== false) ||
            (strpos($nomLow ,"ricardo bevacqua") !== false) ||
            ((strpos($nomLow ,"ricardo") !== false) && (strpos($apeLow,"bevacqua") !== false))
        ){
            return true;
        }else{
            return false;
        }

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
        $marca = 5;
        $concesionario = 'NULL';

        
        //return DB::select("CALL hnweb_subitegetdatos(".$id.", NULL, 0);");
        //return DB::connection($db3)->select("CALL hnweb_subitegetdatos(".$id.", NULL, 0);");
        return DB::select("CALL hnweb_subitegetdatos_vw(".$id.", NULL, 0, ".$marca.", ".$concesionario.");");
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
        //$dato->FechaCompra =  $util->reversarFecha($request->FechaCompra, 'DB');
        $dato->FechaCompra =  str_replace("/", "", $request->FechaCompra);
        $dato->PrecioCompra =  $request->PrecioCompra;

        if ($request->CodEstado){
            $dato->CodEstado = $request->CodEstado;
            if ($request->CodEstado == 4){
                $dato->Motivo =  $request->Motivo;
            }else{
                $dato->Motivo = null;
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
