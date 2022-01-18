<?php

namespace App\Http\Controllers;
 
use App\SubiteDatos;
use App\DatosCBU;
use App\Oficial;
use App\ObservacionGestionCompra;
use App\HistoricoCompra;
use Illuminate\Http\Request;
use DB;
use ArrayObject;
use App\Http\Controllers\UtilsController;

class GestionComprasController extends Controller
{
   
    public function getDatosComprados(Request $request){
        $arr = array();
        //$datos = new ArrayObject($arr);
        $datos = array();
        $oficial = $request->oficial;
        $objOficial = Oficial::find($oficial);

        

        if ($oficial != 29){

            if (isset($objOficial->CodigoAutoCervo)){
                $oficialAC = $objOficial->CodigoAutoCervo;
            }else{
                $oficialAC = 0;
            }
    
            if (isset($objOficial->CodigoAutoNet)){
                $oficialAN = $objOficial->CodigoAutoNet;
            }else{
                $oficialAN = 0;
            }
            
            if (isset($objOficial->CodigoCarGroup)){
                $oficialCG = $objOficial->CodigoCarGroup;
            }else{
                $oficialCG = 0;
            }

            $fieldName = 'CodOficial';
            $symbol = '=';
            $fieldValue = $oficial;
            $fieldValueAC = $oficialAC;
            $fieldValueAN = $oficialAN;
            $fieldValueCG = $oficialCG;
        }else{
            $fieldName = 'ID';
            $symbol = '>';
            $fieldValue = 0;
            $fieldValueAC = 0;
            $fieldValueAN = 0;
            $fieldValueCG = 0;
        }

        if ($request->Concesionario == 0){ // Si vino con Conceionario TODOS chequeo la marca a ver si se selecciono una o son TODAS las marcas
            $datosAC = array();
            $datosAN = array();
            $datosCG = array();
            $datosVW = array();
            
            switch ($request->Marca){
                case 0: //TODAS las marcas
       
                    $datosAC = SubiteDatos::on('AC')->where('CodEstado', 5)->where($fieldName, $symbol, $fieldValueAC)->whereNull('Vendido')->get()->toArray(); // static method
                    $datosAN = SubiteDatos::on('AN')->where('CodEstado', 5)->where($fieldName, $symbol, $fieldValueAN)->whereNull('Vendido')->get()->toArray(); // static method
                    $datosCG = SubiteDatos::on('CG')->where('CodEstado', 5)->where($fieldName, $symbol, $fieldValueCG)->whereNull('Vendido')->get()->toArray(); // static method
                    $datosVW = SubiteDatos::on('GF')->where('CodEstado', 5)->where($fieldName, $symbol, $fieldValue)->whereNull('Vendido')->get()->toArray(); // static method

                break;
                case 2: //FIAT
    
                    $datosAC = SubiteDatos::on('AC')->where('CodEstado', 5)->where($fieldName, $symbol, $fieldValueAC)->whereNull('Vendido')->get()->toArray(); // static method
                    $datosAN = SubiteDatos::on('AN')->where('CodEstado', 5)->where($fieldName, $symbol, $fieldValueAN)->whereNull('Vendido')->get()->toArray(); // static method
                    $datosCG = SubiteDatos::on('CG')->where('CodEstado', 5)->where($fieldName, $symbol, $fieldValueCG)->whereNull('Vendido')->get()->toArray(); // static method
                break;
                default: //BASE GF

                    $datosVW = SubiteDatos::on('GF')->where('Marca', $request->Marca)->where('CodEstado', 5)->where($fieldName, $symbol, $fieldValue)->whereNull('Vendido')->get()->toArray(); // static method

                break;

            }

            $datos = array_merge($datos, $datosAC, $datosAN, $datosCG, $datosVW);
            
        }else{
            $uC = new UtilsController;
            $db = $uC->getDabaseName($request->Marca, $request->Concesionario);
         
            switch ($db) {
                case 'AC':
                    $fieldValue = $fieldValueAC;
                break;
                case 'AN':
                    $fieldValue = $fieldValueAN;
                break;
                case 'CG':
                    $fieldValue = $fieldValueCG;
                break;
                default:
                    $fieldValue = $fieldValue;
                break;
            } 

            $datos = SubiteDatos::on($db)->where('Concesionario',$request->Concesionario)->where($fieldName, $symbol, $fieldValue)->where('CodEstado', 5)->whereNull('Vendido')->get()->toArray(); // static method
            //$datos = SubiteDatos::on($db)->where('Concesionario',$request->Concesionario)->where('CodEstado', 5)->whereNull('Vendido')->get();
            
        }

        $datosConObs = array();

        foreach ($datos as  $key => $gestion) {

            $uC = new UtilsController;
            $db = $uC->getDabaseName($gestion['Marca'], $gestion['Concesionario']);

            if (!$uC->checkEsPropioOtraSoc($gestion['Apellido'], $gestion['Nombres'])){

                if ($gestion['Marca'] == 2){
                    if ($gestion['FechaVtoCuota2'] !== NULL){
                        $gestion['Avance'] = $this->getAvanceAutomatico(strtotime($gestion['FechaVtoCuota2']));
                    }else{
                        $gestion['Avance'] = 0;
                    }
                
                }

                $gestion['Observaciones'] = ObservacionGestionCompra::on($db)->where('ID_Datos', $gestion['ID'])->orderBy('Fecha', 'desc')->get();

                array_push($datosConObs, $gestion); 
            }

        }

        return $datosConObs;
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

                $avance = (($diff->format('%a') / 365) * 12) + 2;
                $avance = round($avance, 0);
            }
        }

        if ($avance > 84){
            $avance = 84;
        }

        return $avance;
    }

    public function saveTitularCompra(Request $request){

       $uC = new UtilsController;
       $db = $uC->getDabaseName($request->Marca, $request->Concesionario);

       $dato = SubiteDatos::on($db)->findOrFail($request->ID_Dato);

       $dato->TitularCompra = $request->TitularCompra;

       $dato->save();

       return $dato;
    }

    public function saveDatosCBU(Request $request){

        $uC = new UtilsController;
        $db = $uC->getDabaseName($request->Marca, $request->Concesionario);

        $datoCBU = SubiteDatos::on($db)->findOrFail($request->ID_Dato);

        $datoCBU->TitularCuenta = $request->TitularCuenta;
        $datoCBU->NombreBanco = $request->NombreBanco;
        $datoCBU->CBU = $request->CBU;
        $datoCBU->AliasCBU = $request->AliasCBU;
        $datoCBU->CUIT =$request->CUIT;
        $datoCBU->NroCuenta = $request->NroCuenta;
        
        $datoCBU->save();

       return $datoCBU;
    }

    public function setFechasDatos(Request $request){

        $uC = new UtilsController;

        $db = $uC->getDabaseName($request->Marca, $request->Concesionario);

        $dato = SubiteDatos::on($db)->findOrFail($request->ID);

        $esFirmaCliente = false;

        switch ($request->TipoFecha){
            case 1:
                $dato->FechaFirmaCliente = $request->FechaAGuardar;
                $esFirmaCliente = true;
            break;
            case 2:
                $dato->FechaFirmaNvoTitular = $request->FechaAGuardar;
            break;
            case 3:
                $dato->FechaEnviadaTerminal = $request->FechaAGuardar;
            break;
            case 4:
                $dato->FechaOk = $request->FechaAGuardar;
            break;
            case 5:
                $dato->FechaCBUCargado = $request->FechaAGuardar;
            break;
            case 6:
                $dato->FechaEnvioMail = $request->FechaAGuardar;
            break;

        }

        if ($esFirmaCliente){

            $result = DB::select("CALL hnweb_set_historial_datos(3, ".$request->ID.
            ", ".$request->Concesionario.
            ", NULL, NULL,
            NULL, NULL,
            NULL, NULL,
            NULL, NULL,
            NULL, NULL,
            NULL, '".
            $request->FechaAGuardar."', NULL, NULL);");

            /*
            $hist_id = HistoricoCompra::where('ID_Dato', $dato->ID)->where('Concesionario', $dato->Concesionario)->orderBy('ID', 'desc')->take(1)->get();
        
            if ($hist_id){
                
                $id = $hist_id[0]->ID;
                $hist = HistoricoCompra::where('ID', '=', $id)->firstOrFail();

                $hist->FechaFirmaCliente = $dato->FechaFirmaCliente;

                $hist->save();
            }
            */
        }

        $dato->save();

        return $dato;
    }

    public function saveObservacionGestion(Request $request){

        $uC = new UtilsController;
        $db = $uC->getDabaseName($request->Marca, $request->Concesionario);

        $obsGestion = new ObservacionGestionCompra;
        $obsGestion->setConnection($db);

        $obsGestion->ID_Datos = $request->ID_Datos;
        $obsGestion->Fecha = $request->Fecha;
        $obsGestion->login = $request->login;
        $obsGestion->Obs = $request->Obs;

        $obsGestion->save();

       return $obsGestion;
    }

    public function generarHNVigente(Request $request){

    }

}