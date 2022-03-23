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

        $utils = new UtilsController;
        
        $result = array();
        //switch($marca){
        //    case 2:
                switch($concesionario){
                    case 4:
                        $db = 'AC';
                        $result = DB::connection($db)->select("CALL hnweb_subitegetdatos(NULL, NULL, 1, NULL);");   
                    break;
                    case 5:
                        $db = 'AN';
                        $result = DB::connection($db)->select("CALL hnweb_subitegetdatos(NULL, NULL, 1, NULL);");

                    break;
                    case 6:
                        $db = 'CG';
                        $result = DB::connection($db)->select("CALL hnweb_subitegetdatos(NULL, NULL, 1, NULL);");

                    break;
                    default:
                        $db = 'GF';
                        $result = DB::select("CALL hnweb_subitegetdatos_vw(NULL, NULL, 0, ".$marca.", ".$concesionario.", NULL);"); 
                    break;
                }
           // break;
           // default:
           //     $db = 'GF';
           //     $result = DB::select("CALL hnweb_subitegetdatos_vw(NULL, NULL, 0, ".$marca.", ".$concesionario.", NULL);"); 
           // break;
       // }

        $list = array();
        if (isset($result)){

            foreach ($result as $r) {

                $oDet = json_decode(json_encode($r), FALSE);

                //$fvc2 = strtotime($oDet->FechaVtoCuota2);
                $fvc2 = $oDet->FechaVtoCuota2;

                if ($oDet->Marca == 2 || $oDet->Marca == 7){
                    if ($oDet->FechaVtoCuota2 === NULL){
                        if (isset($oDet->AvanceCalculado) && $oDet->AvanceCalculado === NULL){
                            $oDet->AvanceAutomatico = 0;
                            $oDet->AvanceCalculado = $oDet->AvanceAutomatico;
                        }
                        
                    }else{
                        $oDet->AvanceAutomatico = $utils->getAvanceAutomatico($fvc2);
                        $oDet->Avance = $oDet->AvanceAutomatico;
                        $oDet->AvanceCalculado = $oDet->AvanceAutomatico;
                    }

                }else{
                    $oDet->AvanceAutomatico = $oDet->Avance;

                    if ($oDet->AvanceCalculado !== NULL){
                        $oDet->Avance = $oDet->AvanceCalculado;
                    }
                }

                if ($oDet->Marca == 3){
                    $totPagas = 80; //Para Peugeot que NO tiene CPG ni CAD informada, le pongo un valor alto para que pase el filtro
                }else{
                    $totPagas = $oDet->CPG + $oDet->CAD;
                }
                
                $oDet->PMaxCompra = $utils->getPrecioMaximoCompra($oDet->Avance, $oDet->HaberNeto);

                //Pedido Dani Fernandez 01/10/2020 SACAR los casos cuota 84
                //Pedido Guido 12/11/2020 SACAR los casos Cuotas Pagas < 10
                //if(!($this->enOtraSociedadOPropio($oDet->Nombres, $oDet->Apellido)) && $oDet->Avance < 84 && $totPagas > 9 && $oDet->HaberNeto > 29999 && $oDet->CodEstado <> 5){
                //if((!($utils->enOtraSociedadOPropio($oDet->Nombres, $oDet->Apellido)) && $oDet->Avance < 84 && $totPagas > 9 && $oDet->CodEstado <> 5) || ($oDet->Marca == 3 && $oDet->CodEstado <> 5)){
                    // Los casos cuotas pagas < 10 NO aplican para Fiat. Audio WA Dani 1/6/21 


                   if ( (!($utils->enOtraSociedadOPropioMerge($oDet->Nombres, $oDet->Apellido)) && $oDet->Avance < 84 && $oDet->CodEstado <> 5)){


                    //CHEQUEOS BASE B 18,19,20,21,22
                    if ($oDet->Concesionario == 18 || $oDet->Concesionario == 19 || $oDet->Concesionario == 20 || $oDet->Concesionario == 21 || $oDet->Concesionario == 22 || $oDet->Concesionario == 24 ){

                        if (($oDet->Avance < 84 ) && ($oDet->Avance >= 70) && ($oDet->HaberNeto >= 160000)){
                            array_push($list, $oDet);
                        }
                    }else{
                        //Pedido Dani Fernandez 04/11/2021 SACAR los casos de Fiat que tiene pmax de compra menor a 9000
                        if (($oDet->Avance == 84 && $oDet->CodOficial == null) || (($oDet->Marca == 2 || $oDet->Marca == 7 ) && $oDet->PMaxCompra < 9000)){
                            continue;
                        }

                        //El minimo HN a Mostrar $30000 es SOLO para los casos que NO sean Fiat Mail Dani 6/1/21
                        
                        if ($oDet->Marca == 2 || $oDet->Marca == 7 || ($oDet->Marca == 3 && $oDet->Avance > 45 && $oDet->HaberNeto > 29999) || ($oDet->Marca == 5 && $totPagas > 9 && $oDet->HaberNeto > 29999)){
                            array_push($list, $oDet);
                        }
                    }

                }

                
            }
        }

        return  $list;
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

           // switch($marca){
           //     case 2:
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
                        default:
                            $db = 'GF';

                            $codOficial = $oficial->Codigo;
                            $str[] = "CALL hnweb_subiteasignacion(".$asignaOficial.", '".$js->Grupo."', ".$js->Orden.", ".$js->Marca.", ".$js->Concesionario.", ".$codOficial.", ".$codSupervisor.");";
                            $res[] = DB::connection($db)->select("CALL hnweb_subiteasignacion(".$asignaOficial.", '".$js->Grupo."', ".$js->Orden.", ".$marca.", ".$concesionario.", ".$codOficial.", ".$codSupervisor.");");

                        break;
                    }
            //    break;
            //    default:
            //        $db = 'GF';

           //         $codOficial = $oficial->Codigo;
           //         $str[] = "CALL hnweb_subiteasignacion(".$asignaOficial.", '".$js->Grupo."', ".$js->Orden.", ".$js->Marca.", ".$js->Concesionario.", ".$codOficial.", ".$codSupervisor.");";
           //         $res[] = DB::connection($db)->select("CALL hnweb_subiteasignacion(".$asignaOficial.", '".$js->Grupo."', ".$js->Orden.", ".$marca.", ".$concesionario.", ".$codOficial.", ".$codSupervisor.");");

           //     break;
          //  }

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

          //  switch($js->Marca){
           //     case 2:
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
                        default:
                            $db = 'GF';
                        break;
                    }
            //    break;
            //    default:
            //        $db = 'GF';
            //    break;
           // }

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

          //  switch($js->Marca){
          //      case 2:
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
                        default:
                            $db = 'GF';
                        break;
                    }

          //      break;
           //     default:
           //         $db = 'GF';
           //     break;
         //   }

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
