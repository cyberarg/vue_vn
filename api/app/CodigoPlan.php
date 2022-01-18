<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Codigo
 * @property string $Nombre
 */
class CodigoPlan extends Model
{
    protected $table = 'hnweb_codigos_planes';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * @var array
     */
    protected $fillable = ['Marca', 'Plan', 'CodigoModelo', 'TipoPlan', 'Plazo', 'CuotaProrrDerechoAdm'];

    public $timestamps = false;
   
}