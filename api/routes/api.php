<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function(){
    Route::post('signin', 'SignInController');
    Route::post('signout', 'SignOutController');

    Route::get('me', 'MeController');
    //Route::get('login', 'LoginController');
});
//Route::get('oficiales', 'OficialController');
Route::resource('cotizaciondolar', 'CotizacionDolarController');
Route::resource('oficiales', 'OficialController');
Route::resource('supervisores', 'SupervisorController');
Route::resource('estadogestion', 'EstadoGestionController');
Route::resource('gestiondatos', 'GestionDatosController');
Route::resource('asignaciondatos', 'AsignacionDatosController');
Route::post('/asignardatos', 'AsignacionDatosController@asginarDatos');
Route::post('/pasarsingestion', 'AsignacionDatosController@pasarASinGestionar');

Route::post('/importardatos', 'ImportarDatosController@importarDatos');
Route::post('/procesardatos', 'ImportarDatosController@procesarRegistros');

Route::post('/importarhn', 'ImportarHNController@importarDatosHN');
Route::post('/procesarhn', 'ImportarHNController@procesarRegistrosHN');

Route::get('/haberesnetos', 'HaberesNetosController@getDatosHaberesNetos');

Route::resource('observaciones', 'ObservacionController');
Route::resource('combobox', 'ComboboxController');

//Route::resource('reporteasignacion', 'ReporteAsignacionController');

Route::resource('reporteasignacion', 'ReporteAsignacionController', ['parameters' => [
    'index' => 'periodo'
]]);

Route::resource('reportecomprasresumen', 'ReporteComprasResumenController');
Route::resource('reportecomprasmesactual', 'ReporteComprasMesActualController', ['parameters' => [
    'index' => 'list'
]]);





