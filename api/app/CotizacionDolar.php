<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $Fecha
 * @property double $CotizacionCompra
 * @property double $CotizacionVenta
 */
class CotizacionDolar extends Model
{
    protected $table = 'cotizaciondolarhistorico';
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