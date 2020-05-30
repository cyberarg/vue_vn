<?php

namespace App\Http\Controllers;

use App\HaberNeto;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\UtilsController;

class HaberesNetosController extends Controller
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

    public function getDatosHaberesNetos(Request $request){

        return $this->getHaberesNetosVigentes(8);


    }


    public function getHaberesNetosVigentes($codEmpresa){
       
        $listHN = array();
        $list = array();
        $lstModelos = array();
        $util = new UtilsController;

        $lista = array();

        if ($codEmpresa == 0){
            $esInterempresa = true;
        }else{
            $esInterempresa = false;
        }

        $idConc = 0;
        $usaHNConcesionario = false;

        $emp1 = "AutoCervo";
        $db1= "AC";
        $emp2 = "AutoNet";
        $db2= "AN";
        $emp3 = "CarGroupFusion";
        $db3= "CG";
    
        $result = DB::select("CALL hnweb_get_op_hn_inter_empresas(".$codEmpresa.");");

        foreach ($result as $r) {
            if (($idConc > 0 && $r->Consecionario == $idConc) || ($idConc == 0)){

                array_push($list, $r);
            }
        } //END FOREACH

        //SE HACE UN FOREACH DE LA LISTA DE EMPRESAS
        $lstModelos = $this->getValoresAutoAFecha(now(), $codEmpresa);

        $haberesNetos = HaberNeto::all();

        $errObtenerOp = array();

        foreach ($haberesNetos as $hn) {
            $oHN = new \stdClass();
            $oOp = new \stdClass();

            $oHN->MontoCobroReal = $hn->MontoCobroReal;

            if ($oHN->MontoCobroReal > 0){
                continue;
            }

            $oOp->Marca = $hn->Marca;
            $oOp->Grupo = $hn->Grupo;
            $oOp->Orden = $hn->Orden;
            $oOp->Empresa = $hn->EmpresaOrigenGyO;

            if($usaHNConcesionario){
                $oOp->TipoPlan = $hn->TipoPlan;
                $oOp->Cliente = $hn->Cliente;
                $oOp->Modelo = $hn->Modelo;
                $oOp->Avance = $hn->Avance;
                $oOp->CuotasPagas = $hn->CuotasPagas;
                $oOp->Plazo = $hn->Plazo;
                $oOp->FechaVtoCuota = $hn->FechaVtoCuota;
            }else{

                $oEmpresaOrigenOp = $oOp->Empresa;
                $itemBuscado = $this->searchGyOyMyE($list, $oOp->Grupo, $oOp->Orden, $oOp->Marca, $oOp->Empresa);
           //dd($itemBuscado);
                if ($itemBuscado){
                    $oOp->NroSolicitud = $itemBuscado[0]->Solicitud;
                    $oOp->FechaVtoCuota = $itemBuscado[0]->FechaVtoCuota;
                    $oOp->Avance = $util->getAvanceAutomatico($itemBuscado[0]->FechaVtoCuota);
                    $oOp->Plazo = $itemBuscado[0]->Plazo;
                    $oOp->TipoPlan = $itemBuscado[0]->TipoPlan;
                    $oOp->CPG = $itemBuscado[0]->CPG;
                    $oOp->Modelo = $itemBuscado[0]->CodModelo;
                    $oOp->NomModelo = $itemBuscado[0]->NomModelo;

                    
                    
                    foreach ($lstModelos as $mod) {
                        if ($oEmpresaOrigenOp == $mod->Empresa){
                            if ($oOp->Marca == $mod->Marca){
                                if ($oOp->Modelo == $mod->Codigo){
                                    $oOp->CuotaTerminal = $mod->CuotaTerminal;
                                break;
                                }
                            }
                        }
                    }    
                }else{
                    $errObtenerOp[] = "Error al obtener los datos de la op: Grupo/Orden ".$oOp->Grupo."/".$oOp->Orden." - Marca: ".$oOp->Marca." - Empresa: ".$oOp->Empresa.".";
                    continue;
                }   
            }

            $oHN->Operacion = $oOp;
            $oHN->Id = $hn->Id;
            $oHN->Rescindido = $hn->Rescindido;
            $oHN->Titular = $hn->Titular;
            $oHN->TipoCompra = $hn->TipoCompra;
            $oHN->GrupoTomaPlan = $hn->Grupo_TomaPlan;
            $oHN->OrdenTomaPlan = $hn->Orden_TomaPlan;
            $oHN->MontoCobroEstimado = $hn->MontoCobro;
            $oHN->FechaCobroReal = $hn->FechaCobroReal;
            $oHN->MontoCompra = $hn->MontoCompra;
            $oHN->FechaCompra = $hn->FechaAltaRegistro;
            $oHN->EnvioMailReclamoTerminal = $hn->EnvioMailReclamoTerminal;
            $oHN->HaberNetoSubite = $hn->HaberNetoSubite;
            $oHN->SeDesrescindio = $hn->SeDesrescindio;
            $oHN->Transferencia = $hn->Transferencia;
            $oHN->ConcesionarioPropio = $hn->ConcesionarioPropio;
            $oHN->Empresa = $hn->Empresa;
            $oHN->IdConcesinario = $hn->IdConcesinario;
       

            //ACA PIDE LOS VALORES DE DOLARES
            //cls_HabernesNetosOk Linea 465
/*
            Dim i As New cls_HN_Item
            i.FechaCompra = oHN.FechaCompra
            i.FechaCobro = oHN.FechaCobroReal
            i.MontoCompra = oHN.MontoCompra
            i.MontoCobroReal = CInt(oHN.MontoCobroReal)
            i.HaberNetoSubite = oHN.HaberNetoSubite_ok

            i.GetValoresUSD(i, oError)

            If oError.Descripcion <> "" Then
                Throw New Exception(oError.Descripcion)
            Else
                oHN.MontoCompraDolares = i.MontoCompraDolares
                oHN.MontoCobroRealDolares = i.MontoCobroDolares
                oHN.HaberNetoSubiteUSD = i.HaberNetoSubiteUSD
            End If
*/
            $valoresUSD = new \stdClass();
            $valoresUSD->MontoCompraDolares = 0;
            $valoresUSD->MontoCobroRealDolares = 0;
            $valoresUSD->HaberNetoSubiteUSD = 0;

            $oHN->MontoCompraDolares = $valoresUSD->MontoCompraDolares;
            $oHN->MontoCobroRealDolares = $valoresUSD->MontoCobroRealDolares;
            $oHN->HaberNetoSubiteUSD = $valoresUSD->HaberNetoSubiteUSD;

           
            array_push($listHN, $oHN);
        }

        $lista['NoEncontrados'] = $errObtenerOp;
        $lista['ListHN'] = $listHN;
        return $lista;
        //dd($listHN);
    }

    public function getValoresUSD($fcomp, $fcob){
            //
    }

    public function searchGyOyMyE($list, $gru, $ord, $mar, $emp){

        // dd($list);
 
         $filtered_array = array_filter($list, function($val) use($gru, $ord, $mar, $emp){
             return ($val->Grupo==$gru and $val->Orden==$ord and $val->Marca==$mar and $val->empresa==$emp);
         });
 
         if ($filtered_array){
             return array_values($filtered_array);
         }else{
            return false;
         }
     }

    public function getValoresAutoAFecha($fecha, $codEmp){
        $lstM = array();
        //dd($fecha);
        $fecha = "20200528";
        $resModelos = DB::select("CALL hnweb_getvaloresautofecha(".$fecha.");");

        foreach ($resModelos as $r) {
            $mod =new \stdClass();

            $mod->Marca = $r->Marca;
            $mod->Codigo = $r->Modelo;
            $mod->CuotaTerminal = $r->Precio;
            $mod->Empresa = $codEmp;

            array_push($lstM, $r);
        } 

        return $lstM;
    }

    public function getHN($codEmp, $esVigentes, $lstOp, $lstModelos, $idConc){

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