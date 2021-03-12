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
class DatoWeb extends Model
{
    protected $table = 'datos_web_hn';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * @var array
     */
    protected $fillable = ['FullName', 'MarcaPlan', 'ModeloAhorro', 'CantidadCuotas', 'Telefono', 
    'Email', 'EstadoPlan', 'FechaLead', 
    'Marca', 'Grupo', 'Orden', 'Solicitud', 'NroDoc',
    'FechaVtoCuota2', 'Avance', 'HaberNeto',
    'CodOficial', 'CodSup', 'CodEstado'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    /*
    public function supervisor()
    {
        return $this->belongsTo('App\Supervisor', 'Supervisor', 'Codigo');
    }
*/
    public $timestamps = false;
    
}