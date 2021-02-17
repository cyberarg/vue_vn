<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleCobrosHN extends Model
{
    protected $table = 'hnweb_detalles_cobros_hn';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Id';

    /**
     * @var array
     */
    protected $fillable = ['ID_HN', 'MontoCobrado', 'FechaCobrado', 'FechaAltaRegistro', 'UsuarioAltaRegistro'
    ];

    public $timestamps = false;
    
}