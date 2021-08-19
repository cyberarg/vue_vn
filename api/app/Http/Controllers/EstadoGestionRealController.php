<?php

namespace App\Http\Controllers;

use App\DatoSubite;
use Illuminate\Http\Request;
use DB;
use App\SubiteDatos;
use App\Oficial;

class EstadoGestionRealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDatos(Request $request)
    {

        $marca = $request->Marca;
        $concesionario =  $request->Concesionario;
        $esVinculo = $request->EsVinculo;

        $utils = new UtilsController;

        $db1= "AC"; //AutoCervo
        $db2= "AN"; //AutoNet
        $db3= "CG"; //CarGroupFusion

        $db = $utils->getDabaseName($marca, $concesionario);

        $result = array();

        $estados = array();

        $estados = SubiteDatos::on($db)->select('ID', 'Marca', 'Concesionario', 'CodOficial', 'CodEstado', 'Avance', 'AvanceCalculado', 'FechaVtoCuota2', 'HaberNeto', 'Apellido', 'Nombres')->where('Concesionario', $concesionario)
        ->orderBy('Concesionario')
        ->orderBy('CodOficial')
        ->orderBy('CodEstado')
        ->get()->toArray(); 


        $lstEstados = array();
        $oficiales = Oficial::where('Activo', 1)->get();

       // return $concesionarios;
        
        //Cargo El Oficial Nulo para los casos sin Oficial Asignados
        $oRecord_0 = new \stdClass();
        $oRecord_0->CodOrigen = 0;
        $oRecord_0->NomOrigen = 0;
        $oRecord_0->CodOficial = 0;
        $oRecord_0->NomOficial = 'Sin Oficial Asignado';
        $oRecord_0->Concesionario = 0;
        $oRecord_0->Asignados = 0;
        $oRecord_0->SinGestionar = 0;
        $oRecord_0->TelefonoMal = 0;
        $oRecord_0->DejeMensaje = 0;
        $oRecord_0->EntrevistaPendiente = 0;
        $oRecord_0->NoCompra = 0;
        $oRecord_0->VendePlan = 0;
        $oRecord_0->Compro = 0;
        $oRecord_0->EnGestion = 0;
        $oRecord_0->PasarAVenta = 0;

        $lstEstados[0] = $oRecord_0;

        foreach ($oficiales as $of) {
            $oRecord = new \stdClass();

            $oRecord->CodOrigen = 0;
            $oRecord->NomOrigen = 0;
            $oRecord->CodOficial = $of->Codigo;
            $oRecord->NomOficial = $of->Nombre;

            $oRecord->Concesionario = 0;

            $oRecord->Asignados = 0;
            $oRecord->SinGestionar = 0;
            $oRecord->TelefonoMal = 0;

            $oRecord->DejeMensaje = 0;
            $oRecord->EntrevistaPendiente = 0;
            $oRecord->NoCompra = 0;

            $oRecord->VendePlan = 0;
            $oRecord->Compro = 0;
            $oRecord->EnGestion = 0;
            $oRecord->PasarAVenta = 0;

            $lstEstados[$of->Codigo] = $oRecord;
        } 

        
        foreach ($estados as $est) {
            $oDet = new \stdClass();
            if (!is_null($est['AvanceCalculado'])){
                $oDet->Avance = $est['AvanceCalculado'];
            }else{
                $oDet->Avance = $est['Avance'];
            }

            
            if(!($utils->enOtraSociedadOPropioMerge($est['Nombres'], $est['Apellido'])) && $oDet->Avance < 84 && $est['HaberNeto'] > 30000 && !(is_null($est['CodOficial']))){

                
                if (is_null($est['CodOficial'])){
                    $codOf = 0;
                }else{
                    $codOf = (int) $est['CodOficial'];
                }

                if (is_null($est['CodEstado'])){
                    $codEstado = 0;
                }else{
                    $codEstado = (int) $est['CodEstado'];
                }
        
                switch($codEstado){
                    case 0:
                        $lstEstados[$codOf]->SinGestionar += 1;
                    break;
                    case 1: //TelefonoMal
                        $lstEstados[$codOf]->TelefonoMal += 1;
                    break;
                    case 2: //DejeMsg
                        $lstEstados[$codOf]->DejeMensaje += 1;
                    break;
                    case 3: //EntrevistaPendiente
                        $lstEstados[$codOf]->EntrevistaPendiente += 1;
                    break;
                    case 4: //NoCompra - No Le interesa
                        $lstEstados[$codOf]->NoCompra += 1;
                    break;
                    case 5: //VendePlan
                        $lstEstados[$codOf]->VendePlan += 1;
                    break;
                    case 6: //Compro
                        $lstEstados[$codOf]->Compro += 1;
                    break;
                    case 7: //En Gestion
                        $lstEstados[$codOf]->EnGestion += 1;
                    break;
                    case 8: //Pasar a Venta
                        $lstEstados[$codOf]->PasarAVenta += 1;
                    break;
                    $lstEstados[$codOf]->Asignados += 1;
                }  
            }
        }
        

        //return $lstEstados;
        $totaldatos = array();

        $arrEstados = array();
        $arrEstados = $lstEstados;
        $result['Estados'] = $arrEstados;

        $result['TotalDatos'] = $totaldatos;

        return $result;

    }

}