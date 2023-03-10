<?php

namespace App\Http\Controllers;

use App\DatoWeb;
use App\ObservacionWeb;
use App\ReferenciaAvance;
use Illuminate\Http\Request;
use App\Http\Controllers\UtilsController;
use App\SubiteDatos;
use DateTime;
use DB;


class GestionDatosWebController extends Controller
{

    public function __construct(){
        //$this->middleware(['auth:api']);
     }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getDatos(Request $request)
    {

         /*
        return DB::select("SELECT DW.ID, FullName, MarcaPlan, ModeloAhorro, CantidadCuotas, Telefono, Email, EstadoPlan, 
        DATE_FORMAT(FechaLead,'%Y/%m/%d') AS FechaLead, Marca, Grupo, Orden, Solicitud, NroDoc, FechaVtoCuota2, 
        Avance, HaberNeto, PorcentajeValorHN, Domicilio, CodOficial, CodSup, CodEstado, PasarDato, EsDatoNuevo, 
        Telefono2, Email2, Plan, CPG, CAD, Comentarios, EsDatoViejo, OrigenLead, 
        (SELECT DATE_FORMAT(Fecha,'%Y/%m/%d')
        FROM datos_web_hn_obs 
        WHERE ID_DatoWeb = DW.ID ORDER BY Fecha DESC LIMIT 1) AS FechaUltObs
        FROM datos_web_hn DW");

       
        $lstDatos = array();
        $datosPendientes = array();
        $datosVerificados = array();

        $datosPendientes = DB::select("SELECT DW.ID, FullName, MarcaPlan, ModeloAhorro, CantidadCuotas, Telefono, Email, EstadoPlan, 
        DATE_FORMAT(FechaLead,'%Y/%m/%d') AS FechaLead, Marca, Grupo, Orden, Solicitud, NroDoc, FechaVtoCuota2, 
        Avance, HaberNeto, PorcentajeValorHN, Domicilio, CodOficial, CodSup, CodEstado, PasarDato, EsDatoNuevo, 
        Telefono2, Email2, Plan, CPG, CAD, Comentarios, EsDatoViejo, OrigenLead, 
        (SELECT DATE_FORMAT(Fecha,'%Y/%m/%d')
        FROM datos_web_hn_obs 
        WHERE ID_DatoWeb = DW.ID ORDER BY Fecha DESC LIMIT 1) AS FechaUltObs
        FROM datos_web_hn DW
        WHERE DW.DatoVerificado = 0");

        $datosVerificados = DB::select("SELECT DW.ID, FullName, MarcaPlan, ModeloAhorro, CantidadCuotas, Telefono, Email, EstadoPlan, 
        DATE_FORMAT(FechaLead,'%Y/%m/%d') AS FechaLead, Marca, Grupo, Orden, Solicitud, NroDoc, FechaVtoCuota2, 
        Avance, HaberNeto, PorcentajeValorHN, Domicilio, CodOficial, CodSup, CodEstado, PasarDato, EsDatoNuevo, 
        Telefono2, Email2, Plan, CPG, CAD, Comentarios, EsDatoViejo, OrigenLead, 
        (SELECT DATE_FORMAT(Fecha,'%Y/%m/%d')
        FROM datos_web_hn_obs 
        WHERE ID_DatoWeb = DW.ID ORDER BY Fecha DESC LIMIT 1) AS FechaUltObs
        FROM datos_web_hn DW
        WHERE DW.DatoVerificado = 1");

        $lstDatos['Pendientes'] = $datosPendientes;
        $lstDatos['Verificados'] = $datosVerificados;

        return $lstDatos;
        */
        return DatoWeb::whereNull('PasarDato')->get();
    }


    public function getDatosPendientes(Request $request)
    {

        return DB::select("SELECT DW.ID, FullName, MarcaPlan, ModeloAhorro, CantidadCuotas, Telefono, Email, EstadoPlan, 
        DATE_FORMAT(FechaLead,'%Y/%m/%d') AS FechaLead, Marca, Grupo, Orden, Solicitud, NroDoc, FechaVtoCuota2, 
        Avance, HaberNeto, PorcentajeValorHN, Domicilio, CodOficial, CodSup, CodEstado, PasarDato, EsDatoNuevo, 
        Telefono2, Email2, Plan, CPG, CAD, Comentarios, EsDatoViejo, OrigenLead, DatoVerificado, Modelo, CodigoModelo,
        (SELECT DATE_FORMAT(Fecha,'%Y/%m/%d')
        FROM datos_web_hn_obs 
        WHERE ID_DatoWeb = DW.ID ORDER BY Fecha DESC LIMIT 1) AS FechaUltObs
        FROM datos_web_hn DW
        WHERE DW.DatoVerificado = 0 AND IFNULL(DW.CodOficial, 2) = 2");

    }

    public function getDatosVerificados(Request $request)
    {

        return DB::select("SELECT DW.ID, FullName, MarcaPlan, ModeloAhorro, CantidadCuotas, Telefono, Email, EstadoPlan, 
        DATE_FORMAT(FechaLead,'%Y/%m/%d') AS FechaLead, Marca, Grupo, Orden, Solicitud, NroDoc, FechaVtoCuota2, 
        Avance, HaberNeto, PorcentajeValorHN, Domicilio, CodOficial, CodSup, CodEstado, PasarDato, EsDatoNuevo, 
        Telefono2, Email2, Plan, CPG, CAD, Comentarios, EsDatoViejo, OrigenLead, DatoVerificado, Modelo, CodigoModelo,
        (SELECT DATE_FORMAT(Fecha,'%Y/%m/%d')
        FROM datos_web_hn_obs 
        WHERE ID_DatoWeb = DW.ID ORDER BY Fecha DESC LIMIT 1) AS FechaUltObs
        FROM datos_web_hn DW
        WHERE DW.DatoVerificado = 1 AND IFNULL(DW.CodOficial, 2) = 2");

    }

    public function index(Request $request)
    {
        //
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return DatoWeb::findOrFail($id);
    }

    public function showDato(Request $request)
    {
        //dd(DatoWeb::findOrFail($request->id));        
        return DatoWeb::findOrFail($request->id);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request){
        //
    }

    public function getAvanceGrupo(Request $request){

        $grupoFrom = $request->Grupo - 10;
        $grupoTo = $request->Grupo + 10;

        $lstGrupos = ReferenciaAvance::where('Marca', $request->Marca)->whereBetween('Grupo', [$grupoFrom, $grupoTo]);
        $ref = 0;

        foreach ($lstGrupos as $lista) {

            if ($lista->Grupo == $request->Grupo){
                $ref = $lista;
                break;
            }

        }

        if ($ref == 0){
            //Buscar el mas cercano
        }

        return $ref;
        
    }

    public function searchByGrupoBrand(Request $request)
    {
        $grupo = $request->Grupo;
        $marca = $request->Marca;
        $cpg = $request->CPG;
        $cad = $request->CAD;
        $mesesLista = 2;

        $respuesta = array();
        $hn = array();
        $data_return = array();
        $utils = new UtilsController;
        $obj = new \stdClass;
        

        $respuesta = DB::select('CALL hnweb_get_informacion_dato_web('.$marca.', '.$grupo.');');
        $result = $respuesta[0];
        
        $obj = $result;
        $obj->Marca = $marca;
        $obj->CPG = $cpg;
        if (is_null($cad)){
            $cad = 0;
        }
        $obj->CAD = $cad;
        $obj->Porcentaje = 0;
       
        $data_return['CodigoModelo'] = $result->CodigoModelo;
        $obj->CodigoModelo = $data_return['CodigoModelo'];
        $data_return['NombreModelo'] = $result->NombreModelo;
        $data_return['FechaCalculoAvance'] = $result->FechaCalculoAvance;
        $data_return['AvanceCalculado'] = $utils->getAvanceAutomaticoDatosWeb(strtotime($result->FechaCalculoAvance));
        $obj->AvanceCalculado = $data_return['AvanceCalculado'];
        $data_return['CodigoPlan'] = $result->CodigoPlan;
        $data_return['TipoPlan'] = $result->TipoPlan;
        $data_return['Plazo'] = $result->Plazo;
        $data_return['CE_Origen'] = $result->CE_Origen;
        $data_return['ExactMatch'] = $result->ExactMatch;
        $data_return['GrupoTabla'] = $result->GrupoTabla;
        $data_return['HaberNeto'] = null;
        $data_return['ModeloMarca'] = null;
        $data_return['ValorAuto'] = null;
        $data_return['ModeloPlanes'] = null;

        if ($result->ExactMatch == 1){
            switch ($marca) {
                case 2:
                    $modelo_marca = $utils->getModelosSMarca($marca,2);
                    $data_return['ModeloMarca'] = $modelo_marca;
                break;

                case 5:
                    $hn = $utils->getHaberNetoDatoWeb($obj);
                    $hn_result = $hn[0];
                    $data_return['HaberNeto'] = $hn_result->HN_Real;
                break;

            }
        }else{

            $modelo_marca = $utils->getModelosSMarca($marca,2);
            $data_return['ModeloMarca'] = $modelo_marca;

            $modelo_planes = $utils->getPlanesSModelo($marca);
            $data_return['ModeloPlanes'] = $modelo_planes;
            
        }

        return $data_return;

    }

    public function getHaberNeto(Request $request){

        $utils = new UtilsController;
        $obj = new \stdClass;

        $obj->Marca = $request->Marca;
        $obj->CodigoModelo = $request->Modelo;
        $obj->CodigoPlan = $request->Plan;
        $obj->CPG = $request->CPG;
        $obj->CAD = $request->CAD == null ? 0 : $request->CAD;
        $obj->AvanceCalculado = $request->Avance;
        $obj->Porcentaje = $request->Porcentaje == null ? 'NULL' : $request->Porcentaje;
 
        $hn = $utils->getHaberNetoDatoWeb($obj);
        $hn_result = $hn[0];
        return $hn_result->HN_Real;
        
    }

    public function getHaberNetoFCA(Request $request){
        $utils = new UtilsController;
        $obj = new \stdClass;

        $obj->Marca = $request->Marca;
        $obj->ValorAuto = $request->ValorAuto;
        $obj->PagoBruto = $request->Porcentaje;
        $obj->TipoPlan = $request->TipoPlan;
        $obj->CantCuotas = $request->CantCuotas;
        $obj->CPG = $request->CPG;
 
        $hn = $utils->getHaberNetoDatoWebFCA($obj);
        $hn_result = $hn[0];
        return $hn_result->HaberNetoFCA;
    }

    public function createDato(Request $request)
    {

        
        $newDatoW = new DatoWeb();
        $newDatoW->setConnection('GF');
        $utils = new UtilsController;

        $newDatoW->FullName = $request->FullName;
        $newDatoW->Telefono = $request->Telefono;
        $newDatoW->Email = $request->Email;
        $newDatoW->MarcaPlan = $utils->getNombreMarcaDatoWeb($request->CodMarca);
        $newDatoW->ModeloAhorro = $request->ModeloAhorro;
        $newDatoW->CantidadCuotas = $request->CantidadCuotas;
        $newDatoW->EstadoPlan = $utils->getNombreEstadoPlanDatoWeb($request->EstadoPlan);

        $newDatoW->Marca = $request->CodMarca;
        $newDatoW->NroDoc = $request->Documento;
        $newDatoW->Grupo = $request->Grupo;
        $newDatoW->Orden = $request->Orden;
        $newDatoW->Avance = $request->Avance;
        
        $newDatoW->FechaLead = now();

        $newDatoW->save();

        if ($request->Obs != null){

            $newObs = new ObservacionWeb();
            $newObs->setConnection('GF');
            $newObs->ID_DatoWeb = $newDatoW->ID;
            $newObs->Obs = $request->Obs;
            $newObs->login = 'hnweb';
            $newObs->Fecha = now();
    
            $newObs->save();
            
        }

        return $newDatoW;

    }

    public function updateDato(Request $request)
    {

        $dato = DatoWeb::findOrFail($request->ID);
        $textObsAutom = "";
        $util = new UtilsController;

        $dato->FullName = $request->FullName;

        if ($dato->NroDoc != $request->NroDoc){
            $dato->NroDoc =  $request->NroDoc; 
        }
        
        if ($dato->Telefono != $request->Telefono){
            $dato->Telefono =  $request->Telefono; 
        }
   
        if ($dato->Email != $request->Email){
            $dato->Email =  $request->Email; 
        }

        if ($dato->Telefono2 != $request->Telefono2){
            $dato->Telefono2 =  $request->Telefono2; 
        }
   
        if ($dato->Email2 != $request->Email2){
            $dato->Email2 =  $request->Email2; 
        }

        if ($dato->Domicilio != $request->Domicilio){
            $dato->Domicilio = $request->Domicilio; 
        }

        if ($dato->PasarDato != $request->PasarDato){
            $dato->PasarDato = $request->PasarDato; 
        }

        if ($dato->Marca != $request->Marca){
            $dato->Marca = $request->Marca; 
        }

        if ($dato->Grupo != $request->Grupo){
            $dato->Grupo = $request->Grupo; 
        }

        if ($dato->Orden != $request->Orden){
            $dato->Orden = $request->Orden; 
        }

        if ($dato->HaberNeto != $request->HaberNeto){
            $dato->HaberNeto = $request->HaberNeto; 
        }

        if ($dato->CPG != $request->CPG){
            $dato->CPG = $request->CPG; 
        }

        if ($dato->CAD != $request->CAD){
            $dato->CAD = $request->CAD; 
        }

        if ($dato->Plan != $request->Plan){
            $dato->Plan = $request->Plan; 
        }

        if ($dato->Modelo != $request->Modelo){
            $dato->Modelo = $request->Modelo; 
        }

        if ($dato->CodigoModelo != $request->CodigoModelo){
            $dato->CodigoModelo = $request->CodigoModelo; 
        }
        
        if ($dato->Avance != $request->Avance){
            $dato->Avance = $request->Avance; 
        }

        if ($dato->FechaVtoCuota2 != $request->FechaVtoCuota2){
            $dato->FechaVtoCuota2 = $request->FechaVtoCuota2; 
        }

        if ($dato->PorcentajeValorHN != $request->PorcentajeValorHN){
            $dato->PorcentajeValorHN = $request->PorcentajeValorHN; 
        }

        if ($dato->DatoVerificado != $request->DatoVerificado){
            $dato->DatoVerificado = $request->DatoVerificado; 
        }

        $dato->EsDatoNuevo =  0;

        if ($request->CodEstado){
             $hoy = new DateTime('NOW');
             $ffHoy =  $hoy->format('Y-m-d H:i:s');

            if ($dato->CodOficial == null && $request->CodOficial != null){
                $dato->CodEstado = $request->CodEstado;

                if ($request->CodEstado == 5){ //Pasar A Asignaci??n

                    if (($request->DatoVerificado == 1) && ($request->CodOficial != null)){

                        //Generar Registro en SubiteDatos
                        $subite_dato = new SubiteDatos;

                        $subite_dato->ID_DatoWeb = $request->ID;
                        $subite_dato->Marca = $request->Marca;
                        $subite_dato->Concesionario = $util->getConcesionarioWebMarca($request->Marca);
                        $subite_dato->Grupo = $request->Grupo;
                        $subite_dato->Orden = $request->Orden;
                        $subite_dato->Apellido = $request->FullName;
                        $subite_dato->NroDoc = $request->NroDoc;
                        $subite_dato->Telefono1 = $request->Telefono;
                        $subite_dato->Email1 = $request->Email;
                        $subite_dato->Avance = $request->Avance;
                        $subite_dato->CodOficial = $request->CodOficial;
                        $subite_dato->CodSup = 60;
                        $subite_dato->EsDatoWeb = 1;
                        $subite_dato->EsDatoNuevo = 1;
                        $subite_dato->HaberNeto = $request->HaberNeto;
                        $subite_dato->CPG = $request->CPG;
                        $subite_dato->CAD = $request->CAD;
                        $subite_dato->Plan = $request->Plan;
                        $subite_dato->FechaAltaRegistro = now();
                        $subite_dato->FechaUltimaAsignacion = now();
                        $subite_dato->FechaInicioPlan = $request->FechaVtoCuota2;
                        $subite_dato->AvanceCalculado = $request->Avance;
                        $subite_dato->CuotasReconocidas = $request->PorcentajeValorHN;

                        $subite_dato->save();

                        $dato->CodOficial = $request->CodOficial;
                        $dato->CodSup = 60;

                    }
                
                }
            }else{
                if ($dato->CodEstado != $request->CodEstado){
                    $dato->CodEstado = $request->CodEstado; 
                }
            }


        
        }
        
        $dato->save();

        return $dato;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
