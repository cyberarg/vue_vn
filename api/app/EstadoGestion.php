<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Codigo
 * @property string $Nombre
 */
class EstadoGestion extends Model
{
    public $Estado;
    public $NomEstado;
    public $CodEstado;
    public $CodOficial;
    public $NomOficial;
    public $CodOficialOrigen;
    public $NomOficialOrigen;
    public $Oficial; //As cls_Subite_Oficiales
    public $SinGestionar; // As Integer
    public $TelefonoMal; // As Integer
    public $DejeMensaje ; //As Integer
    public $EntrevistaPendiente; //As Integer
    public $NoLeInteresa ; //As Integer
    public $VendePlan; // As Integer
    public $PlanSubite; // As Integer
    public $Empresa; // As cls_Empresa
    public $EnGestion; // As Integer
    public $Supervisor; // As New cls_Supervisor
    public $Origen; // As cls_Subite_Origenes
    public $PasarAVenta; // As Integer
    public $EnOtroOficial; // As Integer
   
}