<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Codigo
 * @property string $Nombre
 */
class EstadoWeb extends Model
{
    protected $table = 'datos_web_hn_estados';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Codigo';

    /**
     * @var array
     */
    protected $fillable = ['Nombre'];

    public $timestamps = false;
   
}