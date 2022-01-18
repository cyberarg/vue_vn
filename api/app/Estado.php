<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Codigo
 * @property string $Nombre
 */
class Estado extends Model
{
    protected $table = 'subite_estados';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Codigo';

    /**
     * @var array
     */
    protected $fillable = ['Nombre', 'Orden'];

    public $timestamps = false;
   
}