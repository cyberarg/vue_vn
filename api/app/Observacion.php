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
class Observacion extends Model
{
    protected $table = 'subite_obs';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * @var array
     */
    protected $fillable = ['ID_Datos', 'login', 'Fecha', 'Obs', 'Automatica'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    
    public function dato()
    {
        return $this->belongsTo('App\SubiteDato', 'ID_Datos', 'ID');
    }

    public $timestamps = false;
    
}