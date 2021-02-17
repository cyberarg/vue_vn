<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $Fecha
 * @property double $CotizacionCompra
 * @property double $CotizacionVenta
 */
class ModeloHN extends Model
{
    protected $table = 'hnweb_modelo_control';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * @var array
     */
    protected $fillable = ['Marca', 'Concesionario', 'GraboModelo', 'ObjCotizDolar', 'ObjSupuestoCobro',
    'ObjInversionInicial', 'ObjCantCasos', 'ObjHNPromedio', 'ObjCostoTotal', 'ObjDuration', 'ObjTIRCompuesta',
    'RealConcInversionInicial','RealConcCantCasos','RealConcHNPromedio','RealConcDuration','RealConcTIRCompuesta',
    'RealGiamaInversionInicial','RealGiamaCantCasos', 'RealGiamaHNPromedio', 'RealGiamaDuration', 'RealGiamaTIRCompuesta'];



    public $timestamps = false;
    
}