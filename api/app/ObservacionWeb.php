<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ObservacionWeb extends Model
{
    protected $table = 'datos_web_hn_obs';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * @var array
     */
    protected $fillable = ['ID_DatoWeb', 'login', 'Fecha', 'Obs'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    
    public function dato()
    {
        return $this->belongsTo('App\DatoWeb', 'ID_DatoWeb', 'ID');
    }

    public $timestamps = false;
    
}