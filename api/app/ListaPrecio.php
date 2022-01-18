<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Codigo
 * @property string $Nombre
 */
class ListaPrecio extends Model
{
    protected $table = 'listasprecios';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Codigo';

    /**
     * @var array
     */
    protected $fillable = ['Marca', 'Descripcion', 'Moneda', 'VigenciaDesde', 'MesVigenciaHN', 'VigenciaHasta', 'FechaAltaRegistro', 'UsuarioAltaRegistro'];

    public $timestamps = false;
   
}