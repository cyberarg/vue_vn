<?php

namespace App\Http\Controllers;

use App\SubiteDatos;
use App\User;
use App\Oficial;
use App\Observacion;
use App\HistoricoCompra;
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

    public function getDatosLeads(Request $request){

        $utils = new UtilsController;

        $result = DB::select("CALL hnweb_subitegetdatos_leads();"); 

        $list = array();
        if (isset($result)){

            foreach ($result as $r) {

                $oDet = json_decode(json_encode($r), FALSE);
                $oEstado = new \stdClass();
                $oCaida = new \stdClass();


                $oDet->ApeNom = $oDet->Apellido;
                if ($oDet->Nombres != ""){
                    $oDet->ApeNom = $oDet->ApeNom.", ".$oDet->Nombres;
                }
                

                $fcav = null;
                $fvc2 = strtotime($oDet->FechaVtoCuota2);

                if ($oDet->Marca == 2 || $oDet->Marca == 7 ){
                    if ($oDet->FechaVtoCuota2 === NULL){
                        $oDet->AvanceAutomatico = 0;

                        if (isset($oDet->AvanceCalculado) && $oDet->AvanceCalculado === NULL){
                            $oDet->AvanceCalculado = $oDet->AvanceAutomatico;
                        }
                        
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
                
                array_push($list, $oDet); 

            } //end foreach
        }
        return $list;

    }

    public function getDatos(Request $request)
    {

        $marca = $request->Marca;
        $concesionario = $request->Concesionario;
        $oficial = $request->oficial;
        $objOficial = Oficial::find($oficial);
        $supervisor = $objOficial->Supervisor;

        $utils = new UtilsController;

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

            default:
                $db = 'GF';
                $result = DB::select("CALL hnweb_subitegetdatos_vw(NULL, ".$supervisor.", 0, ".$marca.", ".$concesionario.", ".$oficial.");"); 
            break;
        }

        $list = array();
        if (isset($result)){

            foreach ($result as $r) {

                $oDet = json_decode(json_encode($r), FALSE);
                $oEstado = new \stdClass();
                $oCaida = new \stdClass();

                $agregar = false;
                if(!($utils->enOtraSociedadOPropioMerge($oDet->Nombres, $oDet->Apellido))){
                    $agregar = true;
                }
                if ($agregar){
                    $oDet->ApeNom = $oDet->Apellido;
                    if ($oDet->Nombres != ""){
                        $oDet->ApeNom = $oDet->ApeNom.", ".$oDet->Nombres;
                    }
                    

                    $fcav = null;
                    $fvc2 = strtotime($oDet->FechaVtoCuota2);

                    if ($oDet->Marca == 2 || $oDet->Marca == 7 ){
                        if ($oDet->FechaVtoCuota2 === NULL){
                            $oDet->AvanceAutomatico = 0;

                            if (isset($oDet->AvanceCalculado) && $oDet->AvanceCalculado === NULL){
                                $oDet->AvanceCalculado = $oDet->AvanceAutomatico;
                            }
                           
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

                    if ($oDet->HaberNeto_Fiat !== NULL){
                        //REEMPLAZO VARIABLES
                        $oDet->HaberNeto = $oDet->HaberNeto_Fiat;
                        $oDet->PMaxCompra = $oDet->PMaxCompra_Fiat;
                        $oDet->EsCircularFiat = 1;
                       
                        array_push($list, $oDet); 
    
                    }else{

                        $oDet->PrecioMaximoCompra = $util->getPrecioMaximoCompra($oDet->Avance, $oDet->HaberNeto);
                        
                        //Cambio minimo HN a Mostrar $30000 WA Dani 3/12/20 para Peugeot dejo pasar avances menores a 45 WA Dani 12/3/21
                        if ($oDet->Avance < 84 && ($oDet->Marca == 3 || $oDet->Avance > 44)){

                            //El minimo HN a Mostrar $30000 es SOLO para los casos que NO sean Fiat Mail Dani 6/1/21
                            if ($oDet->Marca == 2 || $oDet->Marca == 7 || $oDet->Marca == 3 || ($oDet->Marca != 2 && $totPagas > 9 && $oDet->HaberNeto > 29999)){
                                array_push($list, $oDet); 
                            }
                        }
                    }
                   
                }

            } //end foreach
        }
        return $list;
            
    }


    public function index(Request $request)
    {

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
            (strpos($apeLow,"iru??a") !== false) ||
            (strpos($apellido,"IRU??A SA") !== false) ||
            (strpos($apeLow,"iru??a sa") !== false) ||
            (strpos($apeLow,"iru??a s.a.") !== false) ||
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
       // switch($marca){
           // case 2:
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
                    default:
                        $db = 'GF';
                        $result = DB::select("CALL hnweb_subitegetdatos_vw(".$id.", NULL, 0, ".$marca.", ".$concesionario.", NULL);");
                    break;
                }
           //  break;
           // default:
           //     $db = 'GF';
           //     $result = DB::select("CALL hnweb_subitegetdatos_vw(".$id.", NULL, 0, ".$marca.", ".$concesionario.", NULL);");
          //  break;
       // }

        $list = array();
        if (isset($result)){

            foreach ($result as $r) {

                $oDet = json_decode(json_encode($r), FALSE);

                $fvc2 = strtotime($oDet->FechaVtoCuota2);

                if ($oDet->Marca == 2 || $oDet->Marca == 7 ){
                    if ($oDet->FechaVtoCuota2 === NULL){
                        if (isset($oDet->AvanceCalculado) && $oDet->AvanceCalculado === NULL){
                            $oDet->AvanceAutomatico = 0;
                        }else{
                            $oDet->AvanceAutomatico = $oDet->AvanceCalculado;
                        }
                        
                    }else{
                        $oDet->AvanceAutomatico = $this->getAvanceAutomatico($fvc2);
                        $oDet->Avance = $oDet->AvanceAutomatico;
                    }

                }else{
                    $oDet->AvanceAutomatico = $oDet->Avance;
                }

                if ($oDet->HaberNeto_Fiat !== NULL){

                    //REEMPLAZO VARIABLES
                    $oDet->HaberNeto = $oDet->HaberNeto_Fiat;
                    $oDet->PMaxCompra = $oDet->PMaxCompra_Fiat;
                    $oDet->EsCircularFiat = 1;

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

        $esVendePlan = false;
        $esVentaCaida = false;

       // switch($request->Marca){
         //   case 2:
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
                    default:
                        $db = 'GF';
                    break;
                }
          //  break;
           
        //}

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
            $textObsAutom .= ", Tel??fono 1 a ".$request->Telefono1.".";
            $dato->Telefono1 =  $request->Telefono1; 
        }
        
        if ($dato->Telefono2 != $request->Telefono2){
            $textObsAutom .= ", Tel??fono 2 a ".$request->Telefono2.".";
            $dato->Telefono2 =  $request->Telefono2; 
        }

        if ($dato->Telefono3 != $request->Telefono3){
            $textObsAutom .= ", Tel??fono 3 a ".$request->Telefono3.".";
            $dato->Telefono3 =  $request->Telefono3; 
        }

        if ($dato->Telefono4 != $request->Telefono4){
            $textObsAutom .= ", Tel??fono 4 a ".$request->Telefono4.".";
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

                $esVentaCaida = true;
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
                    // Limpio todos los campos claves para las comisiones
                    $dato->FechaVenta = null;
                    $dato->MontoHNCompra = null;
                    $dato->MontoCompraDato = null;

                    $dato->PrecioMaximoCompra = null;
                    $dato->ComisionALiquidar = null;
                break;
                case 9:
                    if ($dato->MotivoCaida != $request->MotivoCaida){
                        $textObsAutom .= ", Motivo Caida a ".$util->getNombreMotivoCaida($request->MotivoCaida).".";
                        $dato->MotivoCaida =  $request->MotivoCaida;
                    }
                    // Limpio todos los campos claves para las comisiones
                    $dato->FechaVenta = null;
                    $dato->MontoHNCompra = null;
                    $dato->MontoCompraDato = null;

                    $dato->PrecioMaximoCompra = null;
                    $dato->ComisionALiquidar = null;

                break;
                case 5:
                    //La FechaVenta se pone solo una ??nica vez 
                    if ($dato->FechaVenta == null){
                        $dato->FechaVenta = $ffHoy;
                
                        $esVendePlan = true;

                        $porcentajeComision = 0;
                        switch($request->Concesionario){
                            case 1:
                            case 2:
                            case 3:
                            case 7: 
                                $porcentajeComision = 0.08;
                            break;
                            /*
                            case 7:
                            //Luxcar es el 8 + 12 = 20%
                                $porcentajeComision = 0.2;
                            break;
                            */
                            /*
                            case 4:
                            case 5:
                            case 6:
                            case 8:
                            case 10:
                                $porcentajeComision = 0.06;
                            break;
                            */
                            default: // Por ahora todos los CE de DatosWeb van a tener el mismo porcentaje que CG, AN, AC y Alizze
                                $porcentajeComision = 0.06;
                            break;
                        }
                        $dato->MontoHNCompra = $request->HaberNeto;
                        $dato->MontoCompraDato = $request->PrecioCompra + ($request->HaberNeto * $porcentajeComision);

                        $dato->PrecioMaximoCompra = $request->PrecioMaximoCompra;
                        $dato->ComisionALiquidar = $util->getComisionALiquidar($request->PrecioCompra, $request->PrecioMaximoCompra);
                    }
                break;
                default:
                    // Limpio todos los campos claves para las comisiones
                    $dato->FechaVenta = null;
                    $dato->MontoHNCompra = null;
                    $dato->MontoCompraDato = null;

                    $dato->PrecioMaximoCompra = null;
                    $dato->ComisionALiquidar = null;
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

        $codOficialUnificado = $util->getCodigoOficialUnificado($request->CodOficial, $request->Concesionario);

        
        //Evaluo si fue una compra o una caida para actualizar el historico de compras
        //de donde sale el Reporte de Caidas
        $resultHist = array();
        if ($esVendePlan){

            $resultHist = DB::select("CALL hnweb_set_historial_datos(1, ".$request->ID.
            ", ".$request->Concesionario.
            ", ".$request->Grupo.
            ", ".$request->Orden.
            ", ".$request->CodOficial.
            ", ".$codOficialUnificado.
            ", ".$request->Avance.", ".$request->HaberNeto.
            ", ".$request->CodEstado.", '".$util->formatFechaDB($request->FechaCompra).
            "', ".$request->PrecioCompra.
            ", NULL, NULL, NULL, NULL, NULL);");

            /*
            $hist = new HistoricoCompra;
            
            $hist->ID_Dato = $dato->ID;
            $hist->Concesionario = $dato->Concesionario;
            $hist->Grupo = $dato->Grupo;
            $hist->Orden = $dato->Orden;
            
            $hist->CodOficial = $dato->CodOficial;
            

            $hist->Avance = $dato->Avance;
            $hist->HaberNeto = $dato->HaberNeto;
            $hist->CodEstado = $dato->CodEstado;
            $hist->FechaCompra = $dato->FechaCompra;
            $hist->PrecioCompra = $dato->PrecioCompra;

            $hist->save();
            */

        }

        if ($esVentaCaida){

           $resultHist = DB::select("CALL hnweb_set_historial_datos(2, ".$request->ID.", ".
           $request->Concesionario.
           ", NULL, NULL, ".
           $request->CodOficial.", ".$codOficialUnificado.
           ", NULL, NULL, NULL, NULL, NULL, ".
           $request->MotivoCaida.", '".$request->FechaCaida.
           "', NULL, NULL, NULL);");
            /*
            $hist_id = HistoricoCompra::where('ID_Dato', $dato->ID)->where('Concesionario', $dato->Concesionario)->orderBy('ID', 'desc')->take(1)->get();
        
            if ($hist_id){
                
                $id = $hist_id[0]->ID;
                $hist = HistoricoCompra::where('ID', '=', $id)->firstOrFail();

                $hist->MotivoCaida = $dato->MotivoCaida;
                $hist->FechaCaida = $dato->FechaVentaCaida;

                $hist->save();
            }
            */
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
