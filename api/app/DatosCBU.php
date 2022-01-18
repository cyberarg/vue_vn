<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $Fecha
 * @property double $CotizacionCompra
 * @property double $CotizacionVenta
 */
class DatosCBU extends Model
{
    protected $table = 'hnweb_datos_cbu';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * @var array
     */
    protected $fillable = ['ID_Dato', 'Marca', 'Concesionario', 'Grupo', 'Orden', 'HaberNeto',
    'ImporteATransferir', 'TitularCuenta', 'CUIT', 'NroCuenta', 'AliasCBU', 'CBU',
    'Banco','EmailCliente'];



    public $timestamps = false;
    
}