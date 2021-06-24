<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ReferenciaAvance extends Model
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
    protected $fillable = ['Marca', 'Grupo', 'FechaCalculoAvance', 'AvanceCalculado'];


    public $timestamps = false;
    
}