<?php

namespace App\Http\Controllers;

use App\DatoSubite;
use Illuminate\Http\Request;
use DB;

class AsignacionDatosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//
    }

    public function getDatosAsignar(Request $request){
        $marca = $request->Marca;
        $concesionario = $request->Concesionario;
        
        $result = array();
        switch($marca){
            case 2:
                switch($concesionario){
                    case 4:
                        $db = 'AC';
                        $result = DB::connection($db)->select("CALL hnweb_subitegetdatos(NULL, NULL, 0, NULL);");   
                    break;
                    case 5:
                        $db = 'AN';
                        $result = DB::connection($db)->select("CALL hnweb_subitegetdatos(NULL, NULL, 0, NULL);");

                    break;
                    case 6:
                        $db = 'CG';
                        $result = DB::connection($db)->select("CALL hnweb_subitegetdatos(NULL, NULL, 0, NULL);");

                    break;
                }
            break;
            default:
                $db = 'GF';
                $result = DB::select("CALL hnweb_subitegetdatos_vw(NULL, NULL, 0, ".$marca.", ".$concesionario.", NULL);"); 
            break;
        }

        $list = array();
        if (isset($result)){

            foreach ($result as $r) {

                $oDet = json_decode(json_encode($r), FALSE);

                $fvc2 = strtotime($oDet->FechaVtoCuota2);

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

                if ($oDet->Marca == 3){
                    $totPagas = 80; //Para Peugeot que NO tiene CPG ni CAD informada, le pongo un valor alto para que pase el filtro
                }else{
                    $totPagas = $oDet->CPG + $oDet->CAD;
                }
                

                //Pedido Dani Fernandez 01/10/2020 SACAR los casos cuota 84
                //Pedido Guido 12/11/2020 SACAR los casos Cuotas Pagas < 10
                //if(!($this->enOtraSociedadOPropio($oDet->Nombres, $oDet->Apellido)) && $oDet->Avance < 84 && $totPagas > 9 && $oDet->HaberNeto > 29999 && $oDet->CodEstado <> 5){
                if((!($this->enOtraSociedadOPropio($oDet->Nombres, $oDet->Apellido)) && $oDet->Avance < 84 && $totPagas > 9 && $oDet->CodEstado <> 5) || ($oDet->Marca == 3 && $oDet->CodEstado <> 5)){

                    if ($oDet->Avance == 84 && $oDet->CodOficial == null){
                        continue;
                    }

                    //El minimo HN a Mostrar $30000 es SOLO para los casos que NO sean Fiat Mail Dani 6/1/21
                    
                    if ($oDet->Marca == 2 || $oDet->Marca == 3 || ($oDet->Marca == 5 && $totPagas > 9 && $oDet->HaberNeto > 29999)){
                        array_push($list, $oDet);
                    }

                  
                }

                
            }
        }

        return  $list;
    }

    Public Function enOtraSociedadOPropio($nombre, $apellido){
        $apeLow = strtolower($apellido);
        $nomLow = strtolower($nombre);

        if (

            (strpos($apeLow,"volkswagen") !== false) ||
            (strpos($apeLow,"autokar") !== false) ||
            (strpos($apeLow,"autofinancia") !== false) ||
            (strpos($apeLow,"auto financia") !== false) ||   

            (strpos($apeLow,"bevacqua ricardo nicolas") !== false) ||   
            (strpos($apeLow,"iruña") !== false) || 
            (strpos($apeLow,"iruna") !== false) || 
            (strpos($apeLow,"mirage") !== false) || 
            
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

    public function asginarDatos(Request $request){

        $arrObj = $request->data;
        $oficial = json_decode(json_encode($request->oficial));
        $supervisor = json_decode(json_encode($request->supervisor));
        $marca = $request->marca;
        $concesionario = $request->concesionario;
        $user = json_decode(json_encode($request->login));

        //$marca = 5;

        if (isset($supervisor) && (is_object($supervisor))){
            $asignaOficial = 0;
            $codOficial = 0;
            $codSupervisor = $supervisor->Codigo;
            $obsAut = "La operación fue asignada al Supervisor ".$supervisor->Nombre."  por el usuario ".$user.".";
                    
        }else{
            $asignaOficial = 1;
            $codSupervisor = 0;

            $obsAut =  "La operación fue asignada al Oficial ".$oficial->Nombre." por el usuario ".$user.".";
        }

        
        foreach ($arrObj as $dato) {
            
            $js = json_decode(json_encode($dato));

            switch($marca){
                case 2:
                    switch($concesionario){ 
                        case 4:      
                            $db = 'AC';

                            $codOficial = $oficial->CodigoAutoCervo;
                            $str[] = "CALL hnweb_subiteasignacion(".$asignaOficial.", '".$js->Grupo."', ".$js->Orden.", ".$marca.", ".$codOficial.", ".$codSupervisor.");";
                            $res[] = DB::connection($db)->select("CALL hnweb_subiteasignacion(".$asignaOficial.", '".$js->Grupo."', ".$js->Orden.", ".$marca.", ".$codOficial.", ".$codSupervisor.");");

                        break;
                        case 5:
                            $db = 'AN';

                            $codOficial = $oficial->CodigoAutoNet;
                            $str[] = "CALL hnweb_subiteasignacion(".$asignaOficial.", '".$js->Grupo."', ".$js->Orden.", ".$marca.", ".$codOficial.", ".$codSupervisor.");";
                            $res[] = DB::connection($db)->select("CALL hnweb_subiteasignacion(".$asignaOficial.", '".$js->Grupo."', ".$js->Orden.", ".$marca.", ".$codOficial.", ".$codSupervisor.");");

                        break;
                        case 6:
                            $db = 'CG'; 

                            $codOficial = $oficial->CodigoCarGroup;
                            $str[] = "CALL hnweb_subiteasignacion(".$asignaOficial.", '".$js->Grupo."', ".$js->Orden.", ".$marca.", ".$codOficial.", ".$codSupervisor.");";
                            $res[] = DB::connection($db)->select("CALL hnweb_subiteasignacion(".$asignaOficial.", '".$js->Grupo."', ".$js->Orden.", ".$marca.", ".$codOficial.", ".$codSupervisor.");");

                        break;
                    }
                break;
                default:
                    $db = 'GF';

                    $codOficial = $oficial->Codigo;
                    $str[] = "CALL hnweb_subiteasignacion(".$asignaOficial.", '".$js->Grupo."', ".$js->Orden.", ".$js->Marca.", ".$js->Concesionario.", ".$codOficial.", ".$codSupervisor.");";
                    $res[] = DB::connection($db)->select("CALL hnweb_subiteasignacion(".$asignaOficial.", '".$js->Grupo."', ".$js->Orden.", ".$marca.", ".$concesionario.", ".$codOficial.", ".$codSupervisor.");");

                break;
            }

            //$str[] = "CALL hnweb_subiteasignacion(".$asignaOficial.", '".$js->Grupo."', ".$js->Orden.", ".$codOficial.", ".$codSupervisor.");";
        // DB::select("CALL hnweb_subiteasignacion(p_ASIGNAOFICIAL, P_GRUPO, P_ORDEN, p_MARCA, P_OFICIAL, P_SUPERVISOR);");

            $obs = new ObservacionController;
            $reqObs = new Request;

            $reqObs->marca = $js->Marca;
            $reqObs->concesionario = $js->Concesionario;
            $reqObs->id = $js->ID;
            $reqObs->Obs = $obsAut;
            $reqObs->login = 'hnweb';
            $reqObs->Automatica = 1;
            
            $obs->store($reqObs);
        }
        return $str;

    }

    public function reciclarDato(Request $request){
        $arrObj = $request->data;
        //$marca = 5;

        foreach ($arrObj as $dato) {
            
            $js = json_decode(json_encode($dato));

            switch($js->Marca){
                case 2:
                    switch($js->Concesionario){ 
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

            $str[] = "CALL hnweb_reciclarDato('".$js->Grupo."', ".$js->Orden.", ".$js->Marca.", ".$js->Concesionario.");";
            $res[] = DB::connection($db)->select("CALL hnweb_reciclarDato('".$js->Grupo."', ".$js->Orden.", ".$js->Marca.", ".$js->Concesionario.");");
        // DB::select("CALL hnweb_subitepasarasingestionar(P_GRUPO, P_ORDEN);");
        }
        return $str;

    } 

    public function pasarASinGestionar(Request $request){

        $arrObj = $request->data;
        //$marca = 5;

        foreach ($arrObj as $dato) {
            
            $js = json_decode(json_encode($dato));

            switch($js->Marca){
                case 2:
                    switch($js->Concesionario){ 
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

            $str[] = "CALL hnweb_subitepasarasingestionar('".$js->Grupo."', ".$js->Orden.", ".$js->Marca.");";
            $res[] = DB::connection($db)->select("CALL hnweb_subitepasarasingestionar('".$js->Grupo."', ".$js->Orden.", ".$js->Marca.");");
        // DB::select("CALL hnweb_subitepasarasingestionar(P_GRUPO, P_ORDEN);");
        }
        return $str;

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
