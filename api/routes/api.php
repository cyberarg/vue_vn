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

Route::post('/calulohnstock', 'UtilsController@getValorHaberNeto');

Route::post('/getprecios', 'UtilsController@getPrecioLista');

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function(){
    Route::post('signin', 'SignInController');
    Route::post('signout', 'SignOutController');
    Route::get('me', 'MeController');
});

Route::post('/concesionarios', 'ParametrosController@getConcesionarios');

Route::post('/changepassword', 'UserController@changePassword');

//Route::get('oficiales', 'OficialController');
Route::resource('cotizaciondolar', 'CotizacionDolarController');
Route::resource('oficiales', 'OficialController');
Route::resource('supervisores', 'SupervisorController');

Route::post('/estadogestion', 'EstadoGestionController@getDatos');
//Route::post('/estadogestion', 'EstadoGestionRealController@getDatos');


//Route::resource('estadogestion', 'EstadoGestionController');

Route::resource('gestiondatos', 'GestionDatosController', ['parameters' => [
    'index' => 'oficial']]);

Route::post('/getdatos', 'GestionDatosController@getDatos');
Route::post('/showdato', 'GestionDatosController@showDato');
Route::post('/updatedato', 'GestionDatosController@updateDato');

Route::post('/getdatosweb', 'GestionDatosWebController@getDatos');
Route::post('/showdatoweb', 'GestionDatosWebController@showDato');
Route::post('/updatedatoweb', 'GestionDatosWebController@updateDato');
Route::post('/altadatoweb', 'GestionDatosWebController@createDato');
Route::resource('observacionesweb', 'ObservacionWebController');
Route::post('/getobservacionesweb', 'ObservacionWebController@getObservacion');

Route::post('/search_grupo', 'GestionDatosWebController@searchByGrupoBrand');


Route::resource('asignaciondatos', 'AsignacionDatosController');
Route::post('/getdatosasignacion', 'AsignacionDatosController@getDatosAsignar');

Route::post('/asignardatos', 'AsignacionDatosController@asginarDatos');
Route::post('/pasarsingestion', 'AsignacionDatosController@pasarASinGestionar');
Route::post('/reciclardato', 'AsignacionDatosController@reciclarDato');

Route::post('/importardatos', 'ImportarDatosController@importarDatos');
Route::post('/procesardatos', 'ImportarDatosController@procesarRegistros');

Route::post('/importarhn', 'ImportarHNController@importarDatosHN');
Route::post('/procesarhn', 'ImportarHNController@procesarRegistrosHN');

Route::post('/haberesnetos', 'HaberesNetosController@getDatosHaberesNetos');
Route::post('/haberesnetos_select', 'HaberesNetosController@getDatosHaberesNetos_Selecteds');


route::post('/haberesnetoscobrados', 'HaberesNetosController@getHaberesNetosCobrados');
route::post('/haberesnetoscobrados_select', 'HaberesNetosController@getHaberesNetosCobrados_Selecteds');


Route::post('/nuevocobrohn', 'HaberesNetosCobroController@grabarNuevoCobro');
Route::post('/getcobroshn', 'HaberesNetosCobroController@getCobrosHN');

Route::post('/calculadorahn', 'HaberesNetosController@getCalculoHN');
Route::post('/calculadorahnguido', 'HaberesNetosController@getCalculoHNGuido');
Route::get('/getmodeloshn', 'HaberesNetosController@getModelosHN');
Route::get('/getplaneshn', 'HaberesNetosController@getPlanesHN');
Route::get('/getplanesmodelhn', 'HaberesNetosController@getPlanesSinModeloHN');

Route::get('/getlistashn', 'HaberesNetosController@getListasHN');
Route::post('/getoperacionhn', 'HaberesNetosController@buscarOp');
Route::post('/grabarhn', 'HaberesNetosController@grabarHN');

Route::post('/checknrotransfer', 'HaberesNetosCompraController@checkNroTransfer');

Route::post('/cobrarhn', 'HaberesNetosCobroController@cobrarHN');

Route::post('/getmodelocontrol', 'ModeloHNController@getModeloControl');
Route::post('/grabarmodelo', 'ModeloHNController@grabarModelo');

Route::post('/hnproyectadosce', 'HNProyectadoConcesionariosController@getListHNProyectados');
Route::post('/hnproyectados', 'HNProyectadoController@getListHNProyectados');
Route::post('/hnproyectados_select', 'HNProyectadoSelectsController@getListHNProyectados_Selected');


Route::post('/hnresumenperformance', 'HNResumenPerformanceController@getResumen');

Route::post('/hnstock', 'HNStockController@getListHNStock');
Route::post('/hneerr', 'HNEERRController@getListHNEERR');

Route::post('/hnresumencompras', 'HNResumenCompradosController@getListHNResumenComprados');
Route::post('/hnresumencompras_select', 'HNResumenCompradosSelectsController@getListHNResumenComprados_Selected');

Route::post('/hnresumencobros', 'HNResumenCobradosController@getListHNResumenCobrados');
Route::post('/hnresumencobros_select', 'HNResumenCobradosSelectsController@getListHNResumenCobrados_Selected');



Route::resource('observaciones', 'ObservacionController');
Route::post('/getobservaciones', 'ObservacionController@getObservacion');
Route::resource('combobox', 'ComboboxController');

//Route::resource('reporteasignacion', 'ReporteAsignacionController');

Route::post('/reporteasignacion', 'ReporteAsignacionController@getDatos');

Route::post('/reporteobservaciones', 'ReporteObservacionesOficialController@getReporteObservaciones');

Route::post('/reportecompras', 'ReporteComprasController@getReporte');

//Route::post('/reportecarteradashboard', 'ReporteComprasController@getReporteCarteraDashboardStoredProcedure');
Route::post('/reportecarteradashboard', 'ReporteComprasController@getReporteCarteraDashboard');
Route::post('/reportecarteradetalledashboard', 'ReporteComprasController@getReporteDetallePendientesCarteraDashboard');


Route::post('/reportecaidas', 'ReporteCaidasController@getReporte');
Route::post('/reportecomisiones', 'ReporteComisionesController@getReporte');

Route::post('/reportefacturacion', 'ReporteFacturacionController@getReporte');
Route::post('/detallefacturacion', 'ReporteFacturacionController@getDetalle');
Route::post('/detalleporconces', 'ReporteFacturacionController@getDetalleConcesionario');
Route::post('/detallecomisionista', 'ReporteFacturacionController@getDetalleComisionesTerceros');
Route::post('/detallegeneral', 'ReporteFacturacionController@getDetalleGeneral');



Route::post('/reportecomisionesanual', 'ReporteComisionesController@getReporteAnual');
Route::post('/reportecomisionesdetalle', 'ReporteComisionesController@getReporteDetalle');

Route::post('/tablerocontrol', 'TableroControlController@getDatos');
Route::post('/detalle_evol_compras', 'TableroControlController@getDetalleEvolucionCompras');

Route::post('/gestioncompras', 'GestionComprasController@getDatosComprados');
Route::post('/setfechascontrol', 'GestionComprasController@setFechasDatos');
Route::post('/savecbu', 'GestionComprasController@saveDatosCBU');
Route::post('/savetitularcompra', 'GestionComprasController@saveTitularCompra');
Route::post('/saveobsgestion', 'GestionComprasController@saveObservacionGestion');
Route::post('/pasarhnvigente', 'GestionComprasController@generarHNVigente');

Route::post('/reportecomprasresumen', 'ReporteComprasResumenController@getReporte');


Route::post('/reportecomprasceresumen', 'ReporteComprasCEResumenController@getReporte');


Route::post('/reporteacara', 'ReporteAcaraController@getReporte');

Route::post('/reporterentacartera', 'ReporteRentabilidadCarteraTotalController@getReporte');

Route::resource('reportecomprasmesactual', 'ReporteComprasMesActualController', ['parameters' => [
    'index' => 'list'
]]);




