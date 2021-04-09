<?php

namespace App\Http\Controllers;

use App\SubiteDatos;
use App\User;
use App\Oficial;
use App\Observacion;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\UtilsController;
use Auth;
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

        //$db3= "CG";
        //$result = DB::select("CALL hnweb_subitegetdatos(NULL, NULL, 0);");
        $marca = $request->Marca;
        $concesionario = $request->Concesionario;
        $oficial = $request->oficial;
        $objOficial = Oficial::find($oficial);
        $supervisor = $objOficial->Supervisor;

        switch($marca){
            case 2:
                switch($concesionario){
                    case 4:
                        $db = 'AC';
                        if (isset($objOficial->CodigoAutoCervo)){
                            $result = DB::connection($db)->select("CALL hnweb_subitegetdatos(NULL, NULL, 0, ".$objOficial->CodigoAutoCervo.");");   
                        }
                                
                    break;
                    case 5:
                        $db = 'AN';
                        if (isset($objOficial->CodigoAutoNet)){
                            $result = DB::connection($db)->select("CALL hnweb_subitegetdatos(NULL, ".$objOficial->SupervisorAutoNet.", 0, ".$objOficial->CodigoAutoNet.");");
                        }
                    break;
                    case 6:
                        $db = 'CG';
                        if (isset($objOficial->CodigoCarGroup)){
                            $result = DB::connection($db)->select("CALL hnweb_subitegetdatos(NULL, ".$objOficial->SupervisorCarGroup.", 0, ".$objOficial->CodigoCarGroup.");");
                        } 
                    break;
                }
            break;
            default:
                $db = 'GF';
                $result = DB::select("CALL hnweb_subitegetdatos_vw(NULL, ".$supervisor.", 0, ".$marca.", ".$concesionario.", ".$oficial.");"); 
            break;
        }

        

        
        //$resultcg = array();

        //$result = array_merge($resultvw, $resultcg);
       // $result = DB::connection($db3)->select("CALL hnweb_subitegetdatos(NULL, NULL, 0);");
        $list = array();
        if (isset($result)){

            foreach ($result as $r) {

                $oDet = json_decode(json_encode($r), FALSE);
                $oEstado = new \stdClass();
                $oCaida = new \stdClass();

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

                    /*
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
                    */

                    if ($oDet->Marca == 2){
                        if ($oDet->FechaVtoCuota2 === NULL){
                            $oDet->AvanceAutomatico = 0;
                            $oDet->AvanceCalculado = $oDet->AvanceAutomatico;
                        }else{
                            $oDet->AvanceAutomatico = $this->getAvanceAutomatico($fvc2);
                            $oDet->Avance = $oDet->AvanceAutomatico;
                            $oDet->AvanceCalculado = $oDet->AvanceAutomatico;
                        }
                    }else{
                        $oDet->AvanceAutomatico = $oDet->Avance;
                        
                        if ($oDet->AvanceCalculado !== NULL){
                            $oDet->Avance = $oDet->AvanceCalculado;
                        }
                    }

                    $oEstado->Codigo = $oDet->CodEstado;
                    $oEstado->Nombre = $oDet->NomEstado;

                    $oCaida->Codigo = $oDet->CodMotivoCaida;
                    $oCaida->Nombre = $oDet->NomMotivoCaida;

                    $totPagas = $oDet->CPG + $oDet->CAD;

                    $oDet->Estado = $oEstado;
                    $oDet->Caida = $oCaida;

                    $util = new UtilsController;
                    $oDet->FechaCompra = $util->reversarFecha($oDet->FechaCompra, 'FE');

                    $oDet->PrecioMaximoCompra = $util->getPrecioMaximoCompra($oDet->Avance, $oDet->HaberNeto);
                    
                    //Cambio minimo HN a Mostrar $30000 WA Dani 3/12/20 para Peugeot dejo pasar avances menores a 45 WA Dani 12/3/21
                    if ($oDet->Avance < 84 && ($oDet->Marca == 3 || $oDet->Avance > 44)){

                        //El minimo HN a Mostrar $30000 es SOLO para los casos que NO sean Fiat Mail Dani 6/1/21
                        if ($oDet->Marca == 2 || $oDet->Marca == 3 || ($oDet->Marca != 2 && $totPagas > 9 && $oDet->HaberNeto > 29999)){
                            array_push($list, $oDet); 
                        }
                    }
                   
                }

            } //end foreach
        }
        return $list;
            
    }


    public function index(Request $request)
    {
        /*
        $db3= "CG";
        //$result = DB::select("CALL hnweb_subitegetdatos(NULL, NULL, 0);");
        $marca = 5;
        $concesionario = 'NULL';

        $oficial = $request->oficial;
        $supervisor = 60;
  

        $resultvw = DB::select("CALL hnweb_subitegetdatos_vw(NULL, ".$supervisor.", 0, ".$marca.", ".$concesionario.", ".$oficial.");"); 

        $resultcg = DB::connection($db3)->select("CALL hnweb_subitegetdatos(NULL, 323, 0, 222);");
        //$resultcg = array();

        $result = array_merge($resultvw, $resultcg);
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

                $util = new UtilsController;
                $oDet->FechaCompra = $util->reversarFecha($oDet->FechaCompra, 'FE');

                $oDet->PrecioMaximoCompra = $util->getPrecioMaximoCompra($oDet->Avance, $oDet->HaberNeto);

                array_push($list, $oDet);
            }
           

        } //end foreach

        return $list;
        */
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
                //$avance = ($diff->format('%y') * 12 + $diff->format('%m')) + 2;
                $avance = (($diff->format('%a') / 365) * 12) + 2;
                $avance = round($avance, 0);
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

            (strpos($apeLow,"volkswagen") !== false) ||
            (strpos($apeLow,"autokar") !== false) ||
            (strpos($apeLow,"autofinancia") !== false) ||
            (strpos($apeLow,"auto financia") !== false) ||   
            
            (strpos($apeLow,"auto haus") !== false) ||   
            (strpos($apeLow,"automotores russoniello") !== false) ||   
            (strpos($apeLow,"autora") !== false) ||   
            (strpos($apeLow,"autostad") !== false) ||   
            (strpos($apeLow,"biara srl") !== false) ||   
            (strpos($apeLow,"gras automotores") !== false) ||   
            (strpos($apeLow,"guido guidi") !== false) ||   
            (strpos($apeLow,"guillermo dietrich") !== false) ||   
            (strpos($apeLow,"sauma") !== false) ||   
            (strpos($apeLow,"plan oportunidad") !== false) ||   
            (strpos($apeLow,"plan reenganche") !== false) ||   
            (strpos($apeLow,"sauma") !== false) ||   
            (strpos($apeLow,"torino autos") !== false) ||   

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
            (strpos($apeLow,"bevacqua ricardo") !== false) ||
            (strpos($apeLow,"bevacqua ricardo nicolas") !== false) ||
            (strpos($apeLow,"mirage") !== false) ||
            (strpos($apeLow,"iruna") !== false) ||
            (strpos($apeLow,"iruña") !== false) ||
            (strpos($apellido,"IRUÑA SA") !== false) ||
            (strpos($apeLow,"iruña sa") !== false) ||
            (strpos($apeLow,"iruña s.a.") !== false) ||
            (strpos($apeLow,"iru#a") !== false) ||
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

    public function showDato(Request $request)
    {

        $id = $request->id;
        $marca = $request->marca;
        $concesionario = $request->concesionario;
        
        $result = array();
        switch($marca){
            case 2:
                switch($concesionario){
                    case 4:
                        $db = 'AC';
                        $result = DB::connection($db)->select("CALL hnweb_subitegetdatos(".$id.", NULL, 0, NULL);");   
                    break;
                    case 5:
                        $db = 'AN';
                        $result = DB::connection($db)->select("CALL hnweb_subitegetdatos(".$id.", NULL, 0, NULL);");

                    break;
                    case 6:
                        $db = 'CG';
                        $result = DB::connection($db)->select("CALL hnweb_subitegetdatos(".$id.", NULL, 0, NULL);");

                    break;
                }
            break;
            default:
                $db = 'GF';
                $result = DB::select("CALL hnweb_subitegetdatos_vw(".$id.", NULL, 0, ".$marca.", ".$concesionario.", NULL);");
            break;
        }

        $list = array();
        if (isset($result)){

            foreach ($result as $r) {

                $oDet = json_decode(json_encode($r), FALSE);

                $fvc2 = strtotime($oDet->FechaVtoCuota2);
                /*
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
                */
                if ($oDet->Marca == 2){
                    if ($oDet->FechaVtoCuota2 === NULL){
                        $oDet->AvanceAutomatico = 0;
                    }else{
                        $oDet->AvanceAutomatico = $this->getAvanceAutomatico($fvc2);
                        $oDet->Avance = $oDet->AvanceAutomatico;
                    }
                }else{
                    $oDet->AvanceAutomatico = $oDet->Avance;
                }

                array_push($list, $oDet);
            }
        }

        return $list;
        //return $list;
        
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
        return "4021";
    }

    public function updateDato(Request $request)
    {

        switch($request->Marca){
            case 2:
                switch($request->Concesionario){ 
                    case 4:
                        $db = 'AC';
                    break;
                    case 5:
                        $db = 'AN';
                    break;
                    case 6:
                        $db = 'CG';
                    break;
                }
            break;
            default:
                $db = 'GF';
            break;
        }

        $dato = SubiteDatos::on($db)->findOrFail($request->ID);
        $textObsAutom = "";
        $util = new UtilsController;

        $dato->Nombres = $request->Nombres;
        $dato->Apellido = $request->Apellido; 

        //La base de pa7_gf que tiene los datos de VW y nuevas marcas tiene otra estructura.
        if ($db == 'GF'){
            $dato->NroDoc = $request->NroDoc;
        }
        
        //$dato->Telefono1 =  $request->Telefono1; 
        if ($dato->Telefono1 != $request->Telefono1){
            $textObsAutom .= ", Teléfono 1 a ".$request->Telefono1.".";
            $dato->Telefono1 =  $request->Telefono1; 
        }
        
        if ($dato->Telefono2 != $request->Telefono2){
            $textObsAutom .= ", Teléfono 2 a ".$request->Telefono2.".";
            $dato->Telefono2 =  $request->Telefono2; 
        }

        if ($dato->Telefono3 != $request->Telefono3){
            $textObsAutom .= ", Teléfono 3 a ".$request->Telefono3.".";
            $dato->Telefono3 =  $request->Telefono3; 
        }

        if ($dato->Telefono4 != $request->Telefono4){
            $textObsAutom .= ", Teléfono 4 a ".$request->Telefono4.".";
            $dato->Telefono4 =  $request->Telefono4; 
        }

        if ($dato->Email1 != $request->Email1){
            $textObsAutom .= ", Email 1 a ".$request->Email1.".";
            $dato->Email1 =  $request->Email1; 
        }

        if ($dato->Domicilio != $request->Domicilio){
            $textObsAutom .= ", Domicilio a ".$request->Domicilio.".";
            $dato->Domicilio = $request->Domicilio; 
        }

        if (isset($request->FechaCompra)){
            $dato->FechaCompra =  $util->reversarFecha($request->FechaCompra, 'DB');
        }
        
        //$dato->FechaCompra =  str_replace("/", "", $request->FechaCompra);
    
        $dato->PrecioCompra =  $request->PrecioCompra;
        $dato->EsDatoNuevo =  0;


        if ($request->CodEstado){

             //Si ERA VendePlan y ahora se le cambio el estado a otro, hay que marcadolo como Caida
             $hoy = new DateTime('NOW');
             $ffHoy =  $hoy->format('Y-m-d H:i:s');
             
             /*
             if ($dato->CodEstado == 5 && $request->CodEstado != 5){    
                 $dato->FechaVentaCaida = $ffHoy;
             }
             */

            if ($dato->CodEstado == 5 && $request->CodEstado == 9){    
                $dato->FechaVentaCaida = $ffHoy;
            }

            if ($dato->CodEstado != $request->CodEstado){
                $textObsAutom .= ", Estado a ".$util->getNombreEstado($request->CodEstado).".";
                $dato->CodEstado = $request->CodEstado;
            }


            switch ($request->CodEstado){
                case 4:
                    if ($dato->Motivo != $request->Motivo){
                        $textObsAutom .= ", Motivo a ".$util->getNombreMotivo($request->Motivo).".";
                        $dato->Motivo =  $request->Motivo;
                    }
                break;
                case 9:
                    if ($dato->MotivoCaida != $request->MotivoCaida){
                        $textObsAutom .= ", Motivo Caida a ".$util->getNombreMotivoCaida($request->MotivoCaida).".";
                        $dato->MotivoCaida =  $request->MotivoCaida;
                    }
                break;
                case 5:
                    //La FechaVenta se pone solo una única vez
                    if ($dato->FechaVenta == null){
                        $dato->FechaVenta = $ffHoy;

                        $porcentajeComision = 0;
                        switch($request->Concesionario){
                            case 1:
                            case 2:
                            case 3:
                                $porcentajeComision = 0.08;
                            break;
                            case 7: //Luxcar es el 8 + 12 = 20%
                                $porcentajeComision = 0.2;
                            break;
                            case 4:
                            case 5:
                            case 6:
                            case 8:
                                $porcentajeComision = 0.06;
                            break;
                        }
                        $dato->MontoHNCompra = $request->HaberNeto;
                        $dato->MontoCompraDato = $request->PrecioCompra + ($request->HaberNeto * $porcentajeComision);

                        $dato->PrecioMaximoCompra = $request->PrecioMaximoCompra;
                        $dato->ComisionALiquidar = $util->getComisionALiquidar($dato->PrecioCompra, $dato->PrecioMaximoCompra);
                    }
                break;
            }

            if ($request->CodEstado == 4){
                if ($dato->Motivo != $request->Motivo){
                    $textObsAutom .= ", Motivo a ".$util->getNombreMotivo($request->Motivo).".";
                    $dato->Motivo =  $request->Motivo;
                }

            }else{
                $dato->Motivo = null;
            }
            //$dato->Motivo = $request->Motivo;
        }
        
        
        $dato->save();

        if ($textObsAutom != ""){

            $textObsAutom = ltrim($textObsAutom, ", ");
            $textobs = "Se modificaron los siguientes campos: ".$textObsAutom; 

            $obs = new ObservacionController;
            $reqObs = new Request;
           
            $reqObs->marca = $request->Marca;
            $reqObs->concesionario = $request->Concesionario;
            $reqObs->id = $request->ID;
            $reqObs->Obs = $textobs;
            $reqObs->login = 'hnweb';
            $reqObs->Automatica = 1;
            
            $obs->store($reqObs);
        }

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
