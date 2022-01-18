<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Codigo
 * @property string $Nombre
 */
class EscalaCalculo extends Model
{
    protected $table = 'hnweb_escalas_calculo_hn';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * @var array
     */
    protected $fillable = ['Marca', 'Plan', 'CodigoModelo', 'EscalaDesde', 'EscalaHasta', 'CantidadCuotasEscala','CuotasAcumuladasEscala', 'DifRecupero', 'DescuentoEscala'];

    public $timestamps = false;
   
}