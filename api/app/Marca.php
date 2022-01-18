<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Codigo
 * @property string $Nombre
 */
class Marca extends Model
{
    protected $table = 'hnweb_marcas';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Codigo';

    /**
     * @var array
     */
    protected $fillable = ['Nombre', 'MostrarReporteCartera'];

    public $timestamps = false;
   
}