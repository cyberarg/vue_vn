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
class HaberNeto extends Model
{
    protected $table = 'haberesnetosok';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Id';

    /**
     * @var array
     */
    protected $fillable = ['Marca', 'Grupo', 'Orden', 'EmpresaOrigenGyO', 
    'Titular', 'TipoCompra', 'Grupo_TomaPlan', 'Orden_TomaPlan', 'Rescindido', 
    'MontoCompra', 'MontoCobro', 'MontoCobroReal', 'FechaCobroReal', 'EnvioMailReclamoTerminal',
    'HaberNetoSubite', 'HaberNetoOriginal', 'SeDesrescindio', 'Transferencia', 
    'ConcesionarioPropio', 'FechaAltaRegistro', 'UsuarioAltaRegistro'
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