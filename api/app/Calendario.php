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
class Calendario extends Model
{
    protected $table = 'hnweb_calendario_eventos';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * @var array
     */
    protected $fillable = ['FechaHoraInicio', 'FechaHoraFin', 'TipoEvento', 'Prioridad', 'Sector', 'Responsable', 'Observacion'];


    public $timestamps = false;
    
}