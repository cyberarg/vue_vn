<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $Fecha
 * @property double $CotizacionCompra
 * @property double $CotizacionVenta
 */
class CotizacionDolarCCL extends Model
{
    protected $table = 'cotizaciondolarhistoricoccl';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    //protected $primaryKey = 'Fecha';

    /**
     * @var array
     */
    protected $fillable = ['Fecha', 'CotizacionCompra', 'CotizacionVenta'];

    public $timestamps = false;
    
}