<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoricoCompra extends Model
{
    protected $table = 'hnweb_historial_compras_caidas';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * @var array
     */
    protected $fillable = ['ID_Dato', 'Concesionario', 'Grupo', 'Orden', 'CodOficial', 'CodOficialUnificado',
    'Avance', 'HaberNeto', 'CodEstado', 'FechaCompra', 'PrecioCompra',
    'MotivoCaida', 'FechaCaida',  'FechaFirmaCliente', 'Vendido'
    ];


    public $timestamps = false;
    
}