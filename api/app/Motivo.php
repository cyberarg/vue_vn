<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Codigo
 * @property string $Nombre
 */
class Motivo extends Model
{
    protected $table = 'subite_motivos';
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