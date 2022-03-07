<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Codigo
 * @property string $Nombre
 * @property string $login
 * @property int $HNMayor40
 * @property int $Supervisor
 * @property Supervisor $supervisor
 */
class EventoCalendario extends Model
{
    protected $table = 'hnweb_tipo_evento_calendario';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * @var array
     */
    protected $fillable = ['Nombre', 'NombreCorto', 'Color'];


    public $timestamps = false;
    
}