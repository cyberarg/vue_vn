<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Codigo
 * @property string $Nombre
 */
class Concesionario extends Model
{
    protected $table = 'hnweb_concesionarios';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * @var array
     */
    protected $fillable = ['Nombre', 'MarcaDefault', 'CodigoConcesionaria'];

    public $timestamps = false;
   
}