<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use DB;
Use Session;
Use Auth;
Use Redirect;
use App\Estado;

class UtilsController extends Controller
{

	public function reversarFecha($fecha, $style)
    {
        switch ($style) {
            case 'DB':
                $date = implode("", array_reverse(explode("/", $fecha)));
            break;
            case 'FE':
                $date = implode("/", array_reverse(explode("/", $fecha)));
            break;
        }
        return $date;
    }

    public function getNombreEstado($codEstado){
        $nombre = Estado::where('Codigo', $codEstado)->pluck('Nombre');
        return $nombre;
    }

    public function getNombreMotivo($codMotivo){
        switch($codMotivo){
            case 0:
                $nombre = 'Espera el cobro';
            break;
            case 1:
                $nombre = 'Conflicto';
            break;
            case 2:
                $nombre = 'Llamar mas adelante';
            break;
            default:
                $nombre = '';
            break;
        }
        return $nombre;
    }

    public function getPrecioMaximoCompra($avance, $haberNeto){
        switch($avance){
            case  (45 <= $avance) && ($avance <= 61):
                return $haberNeto * 0.2;
            break;
            case (62 <= $avance) && ($avance <= 66):
                return $haberNeto * 0.3;
            break;
            case (67 <= $avance) && ($avance <= 69):
                return $haberNeto * 0.35;
            break;
            case (70 <= $avance) && ($avance <= 79):
                return $haberNeto * 0.4;
            break;
            case (80 <= $avance) && ($avance <= 83):
                return $haberNeto * 0.5;
            break;
            default:
            return 0;
        break;
        }

    }

    public function getAvanceAutomatico($FechaVtoCuota2){

        $avance = 0;

        $fvto2 = strtotime($FechaVtoCuota2);    

        $fecha = strtotime(now());

        if ($FechaVtoCuota2 === NULL){
            return 0;
        }else{
            $fvtoc2 = date_create(date('Y-m-d', $fvto2));
            $ff = date_create(date('Y-m-d', $fecha));       
    
            if (checkdate(date('m', $fvto2), date('d', $fvto2), date('Y', $fvto2))){
                $diff = date_diff($fvtoc2 , $ff);
                $avance = ($diff->format('%y') * 12 + $diff->format('%m')) + 2;
            }
        }

        if ($avance > 84){
            $avance = 84;
        }

        return $avance;
    }

    //Public function getValoresUSD(ByRef i As cls_HN_Item, ByRef oError As cls_Error)
    Public function getValoresUSD($i, $oError){
        $ayer = date('d/m/Y',now() - (24 * 60 * 60));
        $hoy =date('d/m/Y',now());

        $fechaCompra = $i->FechaCompra;
        $fechaCobro = $i->FechaCobro;

        if ($fechaCompra == $hoy){
            $fechaCompra = $ayer;
        } 
        $fechaCompra = strtotime($fechaCompra);

        if ($fechaCobro == $hoy){
            $fechaCobro = $ayer;
        } 
        if (!is_null($i->FechaCobro)){
            $fechaCobro = strtotime($fechaCobro);
        }
        

        if (!$this->getCompraUSD($fechaCompra, $i)){
            if (!$this->getCompraUSD($fechaCompra, $i)){ // -1
                if (!$this->getCompraUSD($fechaCompra, $i)){ // -2
                    if (!$this->getCompraUSD($fechaCompra, $i)){ // -3
                        if (!$this->getCompraUSD($fechaCompra, $i)){ // -4
                            $this->getCompraUSD($fechaCompra, $i); // -5
                        }
                    }
                }
            }
        }

        if (!is_null($i->FechaCobro)){
            if (!$this->getCobroUSD($fechaCobro, $i)){
                if (!$this->getCobroUSD($fechaCobro, $i)){ // -1
                    if (!$this->getCobroUSD($fechaCobro, $i)){ // -2
                        if (!$this->getCobroUSD($fechaCobro, $i)){ // -3
                            if (!$this->getCobroUSD($fechaCobro, $i)){ // -4
                                $this->getCobroUSD($fechaCobro, $i); // -5
                            }
                        }
                    }
                }
            }
        }

        if (!is_null($i->FechaCobro)){
            $f = $fechaCobro;
        }else{
            $f = strtotime($ayer);
        }
    
        if (!$this->getHNUSD($f, $i)){
            if (!$this->getHNUSD($f, $i)){ // -1
                if (!$this->getHNUSD($f, $i)){ // -2
                    if (!$this->getHNUSD($f, $i)){ // -3
                        if (!$this->getHNUSD($f, $i)){ // -4
                            $this->getHNUSD($f, $i); // -5
                        }
                    }
                }
            }
        }

    }
        
    


        
}