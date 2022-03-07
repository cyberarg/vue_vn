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
class PersonalCalendario extends Model
{
    protected $table = 'hnweb_personal_sector';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * @var array
     */
    protected $fillable = ['Sector', 'Nombre', 'CodOficial'];


    public $timestamps = false;
    
}