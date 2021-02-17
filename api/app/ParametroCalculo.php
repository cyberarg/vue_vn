<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Codigo
 * @property string $Nombre
 */
class ParametroCalculo extends Model
{
    protected $table = 'hnweb_parametros_calculo_hn';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * @var array
     */
    protected $fillable = ['Marca', 'DerechoAdmision', 'Penalidad', 'ValorMovilPago', 'CargosAdministrativos'];

    public $timestamps = false;
   
}