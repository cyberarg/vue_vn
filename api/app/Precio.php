<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Codigo
 * @property string $Nombre
 */
class Precio extends Model
{
    protected $table = 'precios';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Codigo';

    /**
     * @var array
     */
    protected $fillable = ['Marca', 'Codigo', 'CodigoModelo', 'Precio', 'FechaAltaRegistro', 'UsuarioAltaRegistro'];

    public $timestamps = false;
   
}