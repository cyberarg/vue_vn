<?php

namespace App\Http\Controllers;

use App\DatoSubite;
use Illuminate\Http\Request;
use DB;
use App\User;
use Session;

class ImportarDatosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return view('importardatos');
        
    }

    public function importarDatos(Request $request){

       // $arr2 = json_decode($request->data);
        $arrObj = $request->data;

        $marca = 2;
        $soloCarOne = 0;
    
        $listDatos = DB::select("CALL hnweb_gettodaslasoperaciones(".$marca.", ".$soloCarOne.");");
        //hnweb_gettodaslasoperaciones`(
        //    p_MARCA INT,
        //    p_SOLOCARONE TINYINT(1)
        
        $lstImp = array();
        $listOculta = array();

        //foreach ($arr2 as $dato) {
        foreach ($arrObj as $dato) {

            $oImp = new \stdClass();
            $oCli = new \stdClass();
            $oOp = new \stdClass();
            $_Accion = new \stdClass();

            $_Accion->Codigo = '';
            $_Accion->Texto = '';
            
            //Objeto Cliente
            $oCli->Telefonos = '';
            $oCli->Telefono2 = '';
            $oCli->DomicilioOcupacion = '';
            $oCli->Nombres = '';
            $oCli->Apellido = '';
              
            //Objeto Operacion
            // $oImp->Dato = array();
            $oOp->Codigo = 0;
            $oOp->Marca = 0;
            $oOp->Grupo = "";
            $oOp->Orden = 0;
            $oOp->NroSolicitud = 0;
           
            $oOp->Cliente = $oCli;  
           
            
            //Objeto Importacion
            $oImp->Accion = $_Accion;

            $oImp->Accion = "";
            $oImp->Codigo = 0;
            $oImp->NroSolicitud = 0;
            $oImp->Marca = 0;
            $oImp->Grupo = "";
            $oImp->Orden = 0;
            $oImp->FechaVtoCuota = "";
            $oImp->TelefonoFijo = '';
            $oImp->Celular = '';
            $oImp->Domicilio = '';
            $oImp->ApellidoyNombres = '';
            $oImp->HaberNeto = 0;
           

            $oOp->Procesar = false;

            $js = json_decode(json_encode($dato));

            $grupo = $js->Grupo;
            $orden = $js->Orden;
            //$marca = $js->Marca;

            $buscado = $this->searchGyOyM($listDatos, $grupo, $orden, $marca);
        
            if($buscado){
                
                $oOp->Codigo = $buscado[0]->Codigo;
                $oOp->FechaVtoCuota = $buscado[0]->FechaVtoCuota;
                $oOp->NroSolicitud = $buscado[0]->Solicitud;

                $oImp->Codigo = $buscado[0]->Codigo;
                $oImp->FechaVtoCuota =  $buscado[0]->FechaVtoCuota;
                $oImp->NroSolicitud = $buscado[0]->Solicitud;
                
            }else{
                $_Accion->Codigo = 'A';
                $_Accion->Texto = 'Grabar - Inexistente en Operaciones';
                $oImp->Accion = "A: Grabar - Inexistente en Operaciones";
            }

            $ApeNom = $js->ApellidoyNombre;
            $arrApeNom = explode(",", $ApeNom);
            $apellido = '';
            $nombre = '';

            if (count($arrApeNom) > 1) {
                $apellido = trim($arrApeNom[0]);
                $nombre = trim($arrApeNom[1]);

                $oImp->ApellidoyNombre = $arrApeNom;
            }else{
                $apellido = $ApeNom;

                $oImp->ApellidoyNombre = $apellido;
            }
                

            $oCli->Telefonos = isset($js->TelefonoFijo) ? $js->TelefonoFijo : '';
            $oCli->Telefono2 = isset($js->Celular) ? $js->Celular : '';
            $oCli->DomicilioOcupacion = isset($js->Domicilio) ? $js->Domicilio : '';
            $oCli->Nombres = $nombre;
            $oCli->Apellido = $apellido;
            $oOp->Cliente = $oCli;

            $oImp->TelefonoFijo = $oCli->Telefonos;
            $oImp->Celular =  $oCli->Telefono2;
            $oImp->Domicilio = $oCli->DomicilioOcupacion;
            

            $oImp->Marca = $marca;
            $oImp->Grupo = $js->Grupo;
            $oImp->Orden = $js->Orden;


            $oOp->Grupo = $js->Grupo;
            $oOp->Orden = $js->Orden;
            $oOp->Marca = $marca;
            //$oImp->Operacion = $oOp;

            $oImp->ImporteHN = $js->ImporteHN;

            array_push($lstImp, $oImp);

        } // Termina el foreach que recorre los registros del excel

        return $lstImp;

        //CONCILIACION

        $lstSubite = DB::select("CALL hnweb_subitegetdatos(NULL, NULL, 1);");

        return $lstSubite;
         //hnweb_subitegetdatos`(p_ID INT,  p_SUP INT, p_ESOFICIALYSUP TINYINT)
        
        foreach ($lstImp as $imp) {
            $grupo = $imp->Grupo;
            $oreden = $imp->Orden;

            $itemBuscado = $this->searchGyOyM($lstSubite, $grupo, $orden, $marca);

            if ($itemBuscado){
                $grabaFechaVtoCuota = false;

                if (isset($imp->FechaVtoCuota) && $imp->FechaVtoCuota != ""){
                    $grabaFechaVtoCuota = true;
                }

                if ($grabaFechaVtoCuota){
                    $_Accion = new \stdClass();

                    $_Accion->Codigo = 'E';
                    $_Accion->Texto = 'Ya existe. Se grabará la Fecha Vto. Cuota (Op), Nombre y Apellido (Excel).';
                    
                    $imp->Accion = "E: Ya existe. Se grabará la Fecha Vto. Cuota (Op), Nombre y Apellido (Excel).";
                    $imp->Procesar = true;
                }else{
                    $_Accion = new \stdClass();

                    $_Accion->Codigo = 'N';
                    $_Accion->Texto = 'Ya existe. Por inexistencia de op no hay FechaVtoCuota para grabar. Solo se grabará Nombre y Apellido (Excel).';
                    
                    $imp->Accion = "N: Ya existe. Por inexistencia de op no hay FechaVtoCuota para grabar. Solo se grabará Nombre y Apellido (Excel).";
                    $imp->Procesar = true;
                } 
            }else{
                if (!isset($imp->Accion) || $imp->Accion == ""){
                    $_Accion = new \stdClass();

                    $_Accion->Codigo = 'A';
                    $_Accion->Texto = 'Grabar.';
                    
                    $imp->Accion = "A: Grabar.";
                    $imp->Procesar = true;
                } 
            }
        }

        /*
        //ARMO LIST CON LOS QUE SE VAN A OCULTAR
        foreach ($lstSubite as $s) {
            $encontro = false;

            foreach ($lstImp as $l) {
                if ($s->Grupo == $l->Grupo && $s->Orden == $l->Orden){
                    $encontro = true;
                break;
                }
            }

            if(!$encontro){
                array_push($listOculta, $s);
            }
        }
        */
        return $lstImp;

    }

    public function procesarRegistros(Request $request){

        $login = $request->login;
        $arrObj = $request->data;
        
        $str = array();
       
        foreach ($arrObj as $dato) {
            
            $js = json_decode(json_encode($dato));

           // $str[] = $js;

            $str[] = "CALL hnweb_subitemodifhn(".$js->Marca.", '".$js->Grupo."', ".$js->Orden.", ".$js->ImporteHN.", '".$login."', ".$js->PrecioMaximoCompra.");";
            $res[] = DB::select("CALL hnweb_subitemodifhn(".$js->Marca.", '".$js->Grupo."', ".$js->Orden.", ".$js->ImporteHN.", '".$login."', ".$js->PrecioMaximoCompra.");");
        
            //
            //hnweb_subitemodifhn(p_MARCA INT,
            //    p_GRUPO VARCHAR(5),
            //    p_ORDEN INT,   
            //    p_HABERNETO DECIMAL(18, 2),
            //   p_LOGIN VARCHAR(50),
            //    p_PRECIOCOMPRAMAXANT INT
            

        }
    
        return $str;

    }


    public function searchGyOyM($list, $gru, $ord, $mar){

       // dd($listDatos);

        $filtered_array = array_filter($list, function($val) use($gru, $ord, $mar){
            return ($val->Grupo==$gru and $val->Orden==$ord and $val->Marca==$mar);
        });

        if ($filtered_array){
            return array_values($filtered_array);
        }else{
           return false;
        }
    }

    public function searchGyO($listDatos, $gru, $ord){

        // dd($listDatos);
 
         $filtered_array = array_filter($listDatos, function($val) use($gru, $ord){
             return ($val->Grupo==$gru and $val->Orden==$ord);
         });
 
         if ($filtered_array){
             return array_values($filtered_array);
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
        dd($request);
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
