<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Codigo
 * @property string $Nombre
 * @property string $login
 * @property int $HNMayor40
 * @property int $Supervisor
 * @property Supervisor $supervisor
 */
class SubiteDatos extends Model
{
    protected $table = 'subite_datos';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * @var array
     */
    protected $fillable = ['Marca', 'Grupo', 'Orden', 'Solicitud', 
    'Apellido', 'Nombres', 'NroDoc', 'Telefono1', 'Telefono2', 'Telefono3','Telefono4',
    'Email1', 'Email2', 'FechaVtoCuota2', 'Avance', 'HaberNeto',
    'CodOficial', 'CodSup', 'CodEstado', 'FechaCompra', 'PrecioCompra',
    'FechaAltaRegistro', 'Domicilio','Origen', 'PrecioMaximoCompra', 
    'FechaUltimaAsignacion', 'Retrabajar', 'fecha_proceso_retrabajar', 
    'Vendido', 'Motivo', 'EsDatoNuevo', 'FechaOcultarDato', 
    'FechaFirmaCliente',  'TitularCompra', 'ComproGiama', 'FechaFirmaNvoTitular', 'FechaEnviadaTerminal', 'FechaOk', 'FechaCBUCargado', 'FechaEnvioMail',
    'ImporteATransferir', 'TitularCuenta', 'CUIT', 'NroCuenta', 'AliasCBU', 'CBU', 'NombreBanco', 'FechaVenta', 'FechaVentaCaida', 'MontoHNCompra', 'MontoCompraDato',
    'ComisionALiquidar'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    /*
    public function supervisor()
    {
        return $this->belongsTo('App\Supervisor', 'Supervisor', 'Codigo');
    }
*/
    public $timestamps = false;
    
}