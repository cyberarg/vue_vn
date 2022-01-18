<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Codigo
 * @property string $Nombre
 */
class ControlTransferencias extends Model
{

    public $Procesar; // Boolean
    public $Transferencia; // Integer
    public $CodEstado; // cls_Estado
    public $NomEstado; // cls_Estado
    //public $Estado; // cls_Estado
    public $CodSupervisor; // cls_Supervisor
    public $NomSupervisor; // cls_Supervisor
    //public $Supervisor; // cls_Supervisor
    public $Obs; // String
    public $FechaAsignacionSup; // Date

    public $TransferenciaPS; // Integer
    public $TransferenciaOP; // Integer
    public $TransferenciaHN; // Integer
    public $TransferenciaCompra; // Integer
    public $TransferenciaVenta; // Integer
   
}