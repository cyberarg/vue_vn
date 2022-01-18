<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Codigo
 * @property string $Nombre
 */
class ReferenciaDatoWeb extends Model
{
    protected $table = 'hnweb_referencias_avances';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * @var array
     */
    protected $fillable = ['Marca', 'Grupo', 'FechaCalculoAvance', 'AvanceCalculado', 'CodigoPlan', 'CodigoModelo', 'TipoPlan', 'CantidadCuotas', 'ConcesionarioOrigen'];


    public $timestamps = false;
   
}