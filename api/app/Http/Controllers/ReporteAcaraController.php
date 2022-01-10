<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DateTime;
use DateInterval;
use \stdClass;
use App\Http\Controllers\UtilsController;

class ReporteAcaraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function getReporte(Request $request)
    {
       
        $series = array();
        $periodos = array();
        

        $db = "GF";
        
        if ($request->reporteFechas == 1){

            $util = new UtilsController;

            $fechaD = $util->getPrimerDiaPeriodo($request->fechaD); 
            $fechaH = $util->getPrimerDiaPeriodo($request->fechaH);


            //$seriesFiat = DB::connection($db)->select("CALL hnweb_reporte_indice_precios_fechas(2, '".$fechaD."', '".$fechaH."');");
            $seriesFiat = DB::connection($db)->select("CALL hnweb_reporte_indice_precios_fiat_fechas_3('".$fechaD."', '".$fechaH."');");
            

            //$seriesVW = DB::connection($db)->select('CALL hnweb_reporte_indice_precios_fechas(5);');
    
            $seriesAcara = DB::connection($db)->select("SELECT 'IPSA' AS Nombre, Periodo, Indice, Precio FROM hnweb_indices_ipsa WHERE Periodo BETWEEN '".$fechaD."' AND '".$fechaH."' ORDER BY Periodo ASC;");
    
            $seriesCCL = DB::connection($db)->select("CALL hnweb_reporte_indice_dolar_fechas('CCL', '".$fechaD."', '".$fechaH."');");
            $seriesOficial = DB::connection($db)->select("CALL hnweb_reporte_indice_dolar_fechas('Oficial', '".$fechaD."', '".$fechaH."');");

            $fechaInicial = new DateTime($fechaD);
            $fechaFinal = new DateTime($fechaH);
        }else{
            $seriesFiat = DB::connection($db)->select('CALL hnweb_reporte_indice_precios_v3(2);');

            //$seriesVW = DB::connection($db)->select('CALL hnweb_reporte_indice_precios(5);');
    
            //$seriesAcara = DB::connection($db)->select("SELECT 'IPSA' AS Nombre, Periodo, Indice, Precio FROM hnweb_indices_ipsa ORDER BY Periodo ASC;");
    
            $seriesCCL = DB::connection($db)->select("CALL hnweb_reporte_indice_dolar_v2('CCL');");
            $seriesOficial = DB::connection($db)->select("CALL hnweb_reporte_indice_dolar_v2('Oficial');");

            /*
            $fechaInicial = new DateTime('2015-01-01');
            $fechaFinal = new DateTime('2021-07-01');
            */

            $fechaInicial = new DateTime('2005-01-01');
            $fechaFinal = new DateTime('2022-01-01');
        }

        $intervalo = new DateInterval('P1M');

       
        while ($fechaInicial <= $fechaFinal){

            $periodos[] = $fechaInicial->format('Y-m-d');

            $fechaInicial->add($intervalo);

        }

        
        $s1Nombre = '';
        $s1Data = array();
        $s1Acum = 0;
        $s1Max = 0;
        $primeDato = 0;
        $pasadaF = 1;
        $s1Min = 0;

        foreach ($seriesFiat as $datoFiat) {
            $s1Nombre = $datoFiat->Nombre;
            
            if ($pasadaF == 1){
                $primeDato = $datoFiat->Valor;
                $s1Acum = $datoFiat->Valor;
                $s1Data[] = 0;
                $s1Min = 0;
            }
            
           // $s1Min = 0;
           if ($pasadaF > 1 && $primeDato > 0){
                $s1Acum = (($datoFiat->Valor / $primeDato) - 1) * 100;
                //$s1Acum += $datoFiat->CotizacionMes;
                $s1Data[] = round($s1Acum, 1);
                if ($s1Acum < $s1Min){
                    $s1Min = $s1Acum;
                }

                if ($s1Acum > $s1Max){
                    $s1Max = $s1Acum;
                }
            }
            

            /*
            if ($pasadaF > 1 && $primeDato > 0){
                $s1Acum +=  $datoFiat->CotizacionMes; 
                $s1Data[] = round($s1Acum, 1);

                if ($s1Acum < $s1Min){
                    $s1Min = $s1Acum;
                }
            }
            */

           // }
           $pasadaF++;
        }
        $serie1 = new \stdClass();
        $serie1->name = $s1Nombre;
        $serie1->data = $s1Data;

        
        $s2Nombre = '';
        $s2Data = array();
        $s2Acum = 0;
        $s2Max = 0;
        $primeDato = 0;
        $pasada = 1;
        $s2Min = 0;
        /*
        foreach ($seriesAcara as $datoAcara) {
        
            if ($pasada == 1){
                $primeDato = $datoAcara->Precio;
                $s2Data[] = 0;
                $s2Min = 0;
            }

            if ($pasada > 1 && $primeDato > 0){
                $s2Acum =  (($datoAcara->Precio / $primeDato) - 1) * 100; 
                $s2Data[] = round($s2Acum, 1);

                if ($s2Acum < $s2Min){
                    $s2Min = $s2Acum;
                }

                if ($s2Acum > $s2Max){
                    $s2Max = $s2Acum;
                }

            }
           $s2Nombre = $datoAcara->Nombre;
           $pasada++;
        }

        $serie2 = new \stdClass();
        $serie2->name = $s2Nombre;
        $serie2->data = $s2Data;
*/
        $s3Nombre = '';
        $s3Data = array();
        $s3Acum = 0;
        $s3Max = 0;
        $pasada = 0;
        $primeDato = 0;

        foreach ($seriesCCL as $datoCCL) {
            
            if ($datoCCL->EsPrimerDato == 1){
                $primeDato = $datoCCL->CotizacionMes;
                $s3Min = 0;
            }

           // if ($datoCCL->EsUltimoMes == 1 && $primeDato != 0){
            if ($primeDato != 0){
                $s3Acum = (($datoCCL->CotizacionMes / $primeDato) - 1) * 100;
                $s3Data[] = round($s3Acum, 1);

                if ($s3Acum < $s3Min){
                    $s3Min = $s3Acum;
                }

                if ($s3Acum > $s3Max){
                    $s3Max = $s3Acum;
                }
            }
           // }

            $s3Nombre =  $datoCCL->Nombre;
            $pasada++;
        }

        $serie3 = new \stdClass();
        $serie3->name = $s3Nombre;
        $serie3->data = $s3Data;

        $s4Nombre = '';
        $s4Data = array();
        $s4Acum = 0;
        $s4Max = 0;
        $pasada = 0;
        $primeDato = 0;

        foreach ($seriesOficial as $datoOficial) {
            
            if ($datoOficial->EsPrimerDato == 1){
                $primeDato = $datoOficial->CotizacionMes;
                $s4Min = 0;
            }

          //  if ($datoOficial->EsUltimoMes == 1 && $primeDato != 0){
             if ($primeDato != 0){
                $s4Acum = (($datoOficial->CotizacionMes / $primeDato) - 1) * 100;
                $s4Data[] = round($s4Acum, 1);

                if ($s4Acum < $s4Min){
                    $s4Min = $s4Acum;
                }

                if ($s4Acum > $s4Max){
                    $s4Max = $s4Acum;
                }

            }
          //  }

            $s4Nombre =  $datoOficial->Nombre;

            $pasada++;
        }

        $serie4 = new \stdClass();
        $serie4->name = $s4Nombre;
        $serie4->data = $s4Data;

        $mimValue = min($s1Min, $s2Min, $s3Min, $s4Min);
        //$maxValue = max($s1Acum, $s2Acum, $s3Acum, $s4Acum);
        $maxValue = max($s1Max, $s2Max, $s3Max, $s4Max);

       //$maxValue = max($s1Data, $s2Data, $s3Data, $s4Data);

        $series[] = $serie1;
       // $series[] = $serie2;
        $series[] = $serie3;
        $series[] = $serie4;
        
        $data['Series'] = $series;
        $data['MinValue'] = $mimValue;
        $data['MaxValue'] = $maxValue;
        $data['Periodos'] = $periodos;

        return $data;

    }



}