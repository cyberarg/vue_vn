<?php

namespace App\Http\Controllers;

use App\DatoSubite;
use Illuminate\Http\Request;
use DB;
use App\User;
use Session;

class ImportarHNController extends Controller
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

    public function importarDatosHN(Request $request){

       // $arr2 = json_decode($request->data);
        $arrObj = $request->data;
    
        $listDatos = DB::select("CALL hnweb_subitegetdatos(NULL, NULL, 0);");
        
        $lstImp = array();

        //foreach ($arr2 as $dato) {
        foreach ($arrObj as $dato) {

            $oImp = new \stdClass();
            
           // $oImp->Dato = array();
            $oImp->ID = 0;
            $oImp->Marca = 0;
            $oImp->Grupo = "";
            $oImp->Orden = 0;
            $oImp->ImporteHN = 0;
            $oImp->Avance = 0;
            $oImp->PrecioMaximoCompra = 0;
            $oImp->Accion = "";
            $oImp->Procesar = false;

            $js = json_decode(json_encode($dato));

            $grupo = $js->Grupo;
            $orden = $js->Orden;

            $buscado = $this->searchGyO($listDatos, $grupo, $orden);
        
            if($buscado){
                
                //$oImp->Dato = $buscado;
                $oImp->ID = $buscado[0]->ID;
                $oImp->Grupo = $js->Grupo;
                $oImp->Orden = $js->Orden;
                $impo = str_replace(".", "" ,$js->ImporteHN);
                $impo = round($impo);
                $oImp->ImporteHN = $impo; //str_replace(".", "," ,$js->ImporteHN);
                $oImp->Marca = $buscado[0]->Marca;
                $oImp->Avance = $buscado[0]->Avance;
                $oImp->PrecioMaximoCompra = $buscado[0]->PrecioMaximoCompra;
                $oImp->Accion = "Grabar";
                $oImp->Procesar = true;

                array_push($lstImp, $oImp);

            }

        }

        return $lstImp;

    }

    public function procesarRegistrosHN(Request $request){

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
