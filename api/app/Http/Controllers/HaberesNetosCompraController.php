<?php

namespace App\Http\Controllers;

use App\ControlTransferencias;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\UtilsController;

class HaberesNetosCompraController extends Controller
{
   
  

    public function checkNroTransfer(Request $request){

        $marca = $request->Marca;
        $concesionario = $request->Concesionario;
        $nroTransferencia = $request->Transferencia;

        $result_transfer = array();
        $lstTransfers = array();

        $response = array();

        switch($marca){
            case 2:
                switch($concesionario){
                    case 4: //AutoCervo
                        $db = 'CV';
                        $spName = 'hnweb_gettransferencias';
                    break;
                    case 5: //AutoNet
                        $db = 'AN';
                        $spName = 'hnweb_gettransferencias_incluyerb';
                    break;
                    case 6: //Car Group
                        $db = 'CG';
                        $spName = 'hnweb_gettransferencias';
                    break;
                    case 8: //RB
                        $db = 'RB';
                        $spName = 'hnweb_gettransferencias_incluyerb';
                    break;
                    case 13: // Fiat Dato Web pasa por arriba los chequeos
                        $response['Msg'] = "";
                        $response['EstaUtilizada'] = false;
                        return $response;
                    break;
                }
            break;
            default:
                $db = 'GF';
                $spName = '';
            break;

        }

        $result_transfer = DB::connection($db)->select("CALL ".$spName."(".$nroTransferencia.", ".$nroTransferencia.", 0, 0, NULL);");
        
        /*
        p_NRODESDE INT,
        p_NROHASTA INT,
        p_SUPERVISOR INT,
        p_ESTADO INT, //--0=TODAS - 1=SIN ASIGNAR - 2=ASIGNADA - 3=ANULADA - 4=UTILIZADA - 5=ASIGNADAS SIN UTILIZAR 60 dias
        p_FECHAASIGNACIONSUP VARCHAR(8)
        */

        foreach ($result_transfer as $r) {
            $ct = new ControlTransferencias();

            $ct->Transferencia = $r->Transferencia;
            $ct->TransferenciaPS = $r->TransferenciaPS;
            $ct->TransferenciaOP = $r->TransferenciaOP;
            $ct->TransferenciaHN = $r->TransferenciaHN;
            $ct->TransferenciaCompra = $r->TransferenciaCompraMP;
            $ct->TransferenciaVenta = $r->TransferenciaVentaMP;
    
            $ct->CodSupervisor = $r->CodSup;
            $ct->NomSupervisor = $r->NomSup;
            $ct->Obs = $r->Obs;
            $ct->CodEstado = $r->CodEstado;
            $ct->NomEstado =  $r->NomEstado;
            $ct->FechaAsignacionSup =$r->FechaAsignacionSup;

            $ok = false;

            if (isset($FDesde)) {
                if ($ct->FechaAsignacionSup >= $FDesde){
                    $ok = true;
                }
            }else{
                $ok = true;
            }

            if($ok){
                if (isset($FHasta)) {
                    if ($ct->FechaAsignacionSup <= $FHasta){
                        $ok = true;
                    }else{
                        $ok = false;
                    }
                }else{
                    $ok = true;
                }
            }


            if($ok){
                array_push($lstTransfers, $ct);
            }

            
        } //END FOREACH

        
        if (count($lstTransfers) > 0){
            
            $ctActual = array_values($lstTransfers)[0];

            $msj = "";
            $estaUtilizada = false;

            switch ($ctActual->NomEstado){
                case "SIN ASIGNAR":
                     $msj = "La transferencia no está asignada.";
                     $response['Msg'] = $msj;
                    $response['EstaUtilizada'] = false;
                break;
                case "ASIGNADA":
                    $msj =  "";
                    $response['Msg'] = $msj;
                    $response['EstaUtilizada'] = false;
                break;
                case "ANULADA":
                    $msj = "La transferencia está anulada.";
                    $response['Msg'] = $msj;
                    $response['EstaUtilizada'] = false;
                break;
                case "UTILIZADA":
                    $estaUtilizada = true;
                    $msj = "La transferencia ya fue utilizada en ";

                    if($ctActual->TransferenciaPS > 0){
                        $estaUtilizada = false;
                        $msj .= "Pre Solicitudes, ";
                    }
                    if($ctActual->TransferenciaOP > 0){
                        $msj .= "Operaciones, ";
                    }
                    if($ctActual->TransferenciaHN > 0){
                        $msj .= "Haberes Netos, ";
                    }
                    if($ctActual->TransferenciaCompra > 0){
                        $msj .= "Compra de Mesa de Planes, ";
                    }
                    if($ctActual->TransferenciaVenta > 0){
                        $msj .= "Venta de Mesa de Planes, ";
                    }
                    $response['Msg'] = substr_replace($msj ,".",-2);
                    $response['EstaUtilizada'] = $estaUtilizada;
      
                break;
            }


        }else{
            $response['Msg'] = "La transferencia no existe.";
            $response['EstaUtilizada'] = false;
        }

        return $response;
    }


}