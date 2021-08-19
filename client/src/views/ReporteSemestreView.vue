<template>
  <v-app class="fullw">
    <v-card class="padded" color="grey lighten-4">
      <v-card-title>
        Reporte Semestral
        <v-divider class="mx-4" inset vertical></v-divider>

        <v-spacer></v-spacer>
        <v-combobox
          item-text="Nombre"
          item-value="Codigo"
          :items="itemsPeriodoTrim"
          label="Periodos"
          v-model="codperiodo"
        ></v-combobox>
        
        <v-combobox
          class="mx-4"
          item-text="Nombre"
          item-value="Codigo"
          :items="this.concesionarios"
          label="Concesionario"
          v-model="codConcesSelected"
        ></v-combobox>
        <v-btn
          class="ma-2"
          outlined
          color="blue darken-1"
          text
          @click="getReport()"
        >
          <v-icon left>mdi-refresh</v-icon>Actualizar
        </v-btn>
        <v-btn
          class="ma-2"
          outlined
          color="error"
          text
          @click="getPdf()"
        >
          <v-icon left>mdi-file-pdf-outline</v-icon>PDF
        </v-btn>
      </v-card-title>
      <div class="card-body" id="pdfDom">

        <div class="logo">
            <img src="../assets/images/logo-giama.png" width="100" height="130">
        </div>

        <v-row class="right_pad">
          <v-col cols="6" md="6">
            <v-row class="left_pad">
              <v-col cols="12" md="12">
                  <div class="head_intro">
                      Descripción 
                  </div>
                  <div class="title_intro">
                      Objetivo de la Inversión
                      <div class="text_intro justificado">
                        Rentabilización de activos residuales derivados del canal "Plan de ahorro" con un objetivo de inversión a mediano plazo.
                      </div>
                  </div>
                  <div class="title_intro">
                       Metodología de Inversión
                      <div class="text_intro justificado">
                        Adquisición de títulos de deuda en default a descuento, con alta correlación contra el tipo de cambio USD/$ <span class="cursiva">(Ver anexo pdf con análisis de correlación)</span>. La operación busca rentabilizar la cartera de dichos títulos a disposición del concesionario, buscando adquirir un porcentaje relevante de los mismos realizando un trabajo de adquisición por capas, limitando y diversificando la volatilidad del tipo de cambio, creando un flujo de fondos que retroalimente la inversión consiguiendo la sustentabilidad de la misma en el tiempo.
                      </div>
                  </div>
              </v-col>
            </v-row> 
            <div class="left_pad">
              <div class="head_intro">
                Composición Equipo
              </div>
            </div>
            <v-row class="left_pad">
              <v-col cols="12" md="12">
                  <GridPersonalFondoComponent></GridPersonalFondoComponent>
              </v-col>
            </v-row> 
          </v-col>
          <v-col cols="6" md="6" class="right_pad">
            <div class="head_intro">
              Resumen Cartera
            </div>
            <v-row >
                <v-col>
                 <NewGridFormComponent
                  :pars="{
                    grid: 'Resumen',
                    titleform: '',
                    routeapi: 'reportecompras',
                    itemkey: 'Codigo',
                    module: 'reportecompras',
                    items: this.items_resumen,
                    mostrarbuscar: false,
                    exportable: false,
                    disablepagination: true,
                    disabledsort: true,
                    hideheaders: true,
                    loading: this.loadinguniv,
                    loadingtext: this.loadingtextresumen,
                  }"
                  :solo_tabla="true"
                  :elevation="0"
                  :padding="0"
                  :headers="[
                    {
                      text: '',
                      align: 'start',
                      value: 'Nombre',
                      width: '60%',
                    },
                    { text: '', value: 'Cantidad', align: 'center', width: '40%' },
                  ]"
                ></NewGridFormComponent>
              </v-col>
            </v-row>
            <v-row >
              <v-col>
                <div class="head_intro">
                  Detalle Cartera
                </div>
              </v-col>
            </v-row>
            <v-row >
                <v-col>
                  <NewGridFormComponent
              :pars="{
                grid: 'Universo',
                titleform: '',
                routeapi: 'reportecompras',
                itemkey: 'Codigo',
                module: 'reportecompras',
                items: this.items_universo,
                mostrarbuscar: false,
                exportable: false,
                disablepagination: true,
                disabledsort: true,
                loading: this.loadinguniv,
                loadingtext: this.loadingtextuniv,
              }"
              :solo_tabla="true"
              :elevation="0"
              :padding="0"
              :headers="[
                {
                  text: '',
                  align: 'center',
                  value: 'Tipo',
                },
                { text: 'Casos', value: 'Casos', align: 'center' },
                { text: '%', value: 'Porcentaje', align: 'center' },
                { text: 'Monto HN', value: 'MontoHN', align: 'center' },
                { text: '%', value: 'Porcentaje', align: 'center' },
              ]"
            ></NewGridFormComponent>
              </v-col>
            </v-row>
            
          </v-col>
        </v-row>

        <div class="padded">
          <div class="head_intro right_pad">
            Evolución Métricas Relevantes
          </div>
        </div>
        <v-row>
          <v-col cols="12" md="12">
            <ChartsDolarFondoView></ChartsDolarFondoView>
          </v-col>
        </v-row>

        <div class="padded">
          <div class="head_intro right_pad">
            Avance Situación vs Objetivo
          </div>
        </div>
        <v-row class="padded">
          <v-col cols="12" md="12">
            <FormCumplimientoHNFondo
                :pars="{
                  titleform: 'Cumplimiento Modelo',
                  routeapi: 'modelohn',
                  module: 'cumplimientohn',
                }"
                :items="{
                  item: datos_modelo,
                }"
              ></FormCumplimientoHNFondo>
          </v-col>
        </v-row>

        <div class="padded">
          <div class="head_intro right_pad">
            Proyectado de Cobro
          </div>
        </div>
        <v-row >
          <v-col cols="12" md="12">
            
            <HNProyectadosFondoView></HNProyectadosFondoView>
          </v-col>
        </v-row>

        <div class="padded inline">
          <div class="head_intro half_left">
            Composición Cartera Actual
          </div>
          <div class="head_intro half right_pad">
            Rentabilidad Cartera Total
          </div>
        </div>
        <v-row>
          <v-col cols="6" md="6" class="left_pad">
            <ChartComposicionCarteraComponent></ChartComposicionCarteraComponent>
          </v-col>
          <v-col cols="6" md="6" class="right_pad">
            <ChartRentabilidadCarteraComponent></ChartRentabilidadCarteraComponent>
          </v-col>
        </v-row>

        <div class="padded second_page">
          <div class="footer_gray">
            Reporte Performance HN - {{this.detalle_subtitulo}}
          </div>
        </div>

        <div class="padded second_page">
          <div class="head_intro">
            Utilidad de Gestión
          </div>
        </div>
        <v-row>
          <v-col cols="12" md="12">
            <HNResumenPerformanceFondoView></HNResumenPerformanceFondoView>
          </v-col>
        </v-row>

        <div class="padded">
            <div class="head_intro my-3"></div>
            <span class="negrita half">Anexos</span>
        </div>
        
        <div class="padded inline">
          <div class="head_intro half_left">
            Correlación USD Oficial vs HN
          </div>
          <div class="head_intro half right_pad">
            Correlación USD Blue vs HN
          </div>
        </div>
        <div class="padded inline">
          <div class="graph_correl">
              <img src="../assets/images/correl_oficial.png" width="100%" height="100%">
          </div>
          <div class="graph_correl">
              <img src="../assets/images/correl_blue.png" width="100%" height="100%">
          </div>
        </div>

        <div class="padded">
          <div class="head_intro mt-15"></div>
          <span class="negrita half">Definiciones</span>
          <div class="article">
              El <span class="negrita">DETALLE DE CARTERA</span> contempla la totalidad de casos disponibles para la adquisición de los títulos de deuda. La misma en el caso de Fiat se alimenta de la totalidad de títulos con haber neto disponible en función al criterio determinado por la terminal denominado “Subite”. En el caso del resto de las marcas, se realiza una valuación de cada operación en función a las políticas comerciales de cada caso correspondiente ajustando el importe de cuotas pagas en función a los prorrateos correspondientes y los descuentos que deban realizarse. A su vez la cartera se actualiza mensualmente, con la información provista por el concesionario en cuestión. <span class="negrita">EVOLUCION DE METRICAS RELEVANTES</span> muestra un análisis de performance de las variables macro más relevantes para el negocio, realizando un seguimiento de las mismas a lo largo de un período de más de 15 años. Ademas, muestra la performance del último año para evaluación el desempeño de corto plazo de las mismas. <span class="negrita">AVANCE SITUACION VS OBJETIVO</span> muestra el avance del cumplimiento del objetivo planteado al inicio de la operatoria con el concesionario en cuestión. <span class="negrita">PROYECTADO DE COBRO</span> genera una visual del flujo de cobros futuros, posibilitando un seguimiento detallado de los cobros pendientes del año, así como visualizar los flujos de los años posteriores y su participación en el flujo total. <span class="negrita">COMPOSICION DE CARTERA ACTUAL</span> detalla de forma gráfica los haberes netos a cobrar agrupados por año calendario. <span class="negrita">RENTABILIDAD CARTERA TOTAL</span> presenta el desarrollo total de la inversión al día de la fecha, contemplando la rentabilidad total obtenida, diferenciando los cobros ya realizados, las proyecciones y la inversión incurrida. <span class="negrita">UTILIDAD DE GESTION</span> muestra el comportamiento del año calendario, de la cartera eme análisis diferenciando la "utilidad de gestión” (utilidad teórica obtenida al momento de la compra, es decir cobro estimado menos costo incurrido) de la "tenencia” (el resultado que se presente en la cartera por la diferencia entre la evolución del tipo de cambio $/USD y el aumento del precio del automotor). <span class="negrita">CORRELACION USD OFICIAL VS HN</span> este análisis de correlación se obtiene de realizar una regresión lineal entre el valor de las listas de precios del automotor y la evolución del tipo de cambio $/USD. El mismo obtiene un factor de R2 superior al 98% evidenciando la clara correlación de estas variables, pudiendo demostrar en el mediano largo plazo (obtenida con datos mensuales de mas de 15 años) que la evolución de la variable independiente (USD), explica casi la totalidad del aumento de los precios automotores. <span class="negrita">CORRELACION USD BLUE VS HN</span> este análisis de correlación se obtiene de realizar una regresión lineal entre el valor de las listas de precios del automotor y la evolución del tipo de cambio $/USD BLUE. El mismo obtiene un factor de R2 superior al 87% evidenciando la clara correlación de estas variables, pudiendo demostrar en el mediano largo plazo (obtenida con datos mensuales de mas de 15 años) que la evolución de la variable independiente (USD), explica gran parte del aumento de los precios automotores (Como se puede ver, en el largo plazo la correlación realizada con el tipo de cambio oficial, presenta un grado explicativo superior. Teniendo en cuenta que el modelo de inversión presenta un objetivo de mediano plazo, este correlaciona lógicamente mejor con el tipo de cambio oficial entendiendo que las variaciones mas pronunciadas del tipo de cambio “BLUE" no son sostenibles en el tiempo, por lo que finalmente este termina convergiendo al oficial, siendo esta volatilidad la que reduce el R2 del modelo).
          </div>
        </div>
      </div>
    </v-card>
  </v-app>
</template>

<script>
import ChartsDolarFondoView from "@/views/ChartsDolarFondoView";
import HNProyectadosFondoView from "@/views/HNProyectadosFondoView";
import FormCumplimientoHNFondo from "@/components/propios/FormCumplimientoHNFondo";
import NewGridFormComponent from "@/components/propios/NewGridFormComponent.vue";
import GridPersonalFondoComponent from "@/components/propios/GridPersonalFondoComponent.vue";
import ChartComposicionCarteraComponent from "@/components/propios/ChartComposicionCarteraComponent.vue";
import ChartRentabilidadCarteraComponent from "@/components/propios/ChartRentabilidadCarteraComponent.vue";
import HNResumenPerformanceFondoView from "@/views/HNResumenPerformanceFondoView";

import moment from "moment";
import { mapState, mapActions } from "vuex";

export default {
  name: "reportecomisiones",
  components: {
    ChartsDolarFondoView,
    HNProyectadosFondoView,
    FormCumplimientoHNFondo,
    NewGridFormComponent,
    GridPersonalFondoComponent,
    ChartComposicionCarteraComponent,
    ChartRentabilidadCarteraComponent,
    HNResumenPerformanceFondoView
  },
  data() {
    return {
      detalle_subtitulo: "",
      htmlTitle: "ReporteSemestral",
      codperiodo: "",
      mesSelected: 0,
      codConcesSelected: null,
      itemsPeriodos: [],
      monthNames: [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre",
      ],

      itemsPeriodoTrim:[
        {'Codigo': 1, 'Nombre': "Semestre 1 (Ene a Jun)"},
        {'Codigo': 2, 'Nombre': "Semestre 2 (Jul a Dic)"},

      ],   

      
    };
  },

  created() {
    this.getPeriodos();
    this.getDatosComboCE();
  },

  computed: {

    ...mapState("reporteacompras", [
      "items_resumen",
      "items_universo",
      "loadingtextresumen",
      "loadingmes",
      "loadingtextmes",
      "loadinguniv",
      "loadingtextuniv",
      "items_filtrados",
      "showTable",
    ]),

    ...mapState("parametros", [
      "loadingConcesionarios",
      "concesionarios",
      "dataStatus",
      "dataStatusMsg",
      "concesionarios_filtered",
    ]),

    ...mapState("modelohn", [
      "loading",
      "dataStatus",
      "datos_modelo",
      "generarModelo",
    ]),


  },

  methods: {
/*
    savePdf(){
      this.$PDFSave(this.$refs.exportPdf, "My Documents");
    },

    printPdf(){
      this.$easyPrint('exportPdf',"My Documents",'portrait');
    },
*/
    ...mapActions({
        getDatosComboCE: "parametros/getConcesionarios",
        getResumenCartera: "reporteacompras/getResumen",
        getModeloControl: "modelohn/getModeloControl",
        getHNProyectados: "proyectadohn/getHNProyectados",
        getHNResumenPerformance: "resumenperformance/getHNResumenPerformance",
        getReporteRentaCartera: "reporterentacartera/getReporteRentaCartera",
    }),

    getModelo() {
      if (typeof this.codConcesSelected !== "undefined") {
        var pars = {
          Concesionario: this.codConcesSelected
        };

        this.getModeloControl(pars);
      }
      return;
    },

    getReport() {
      if (typeof this.codConcesSelected !== "undefined") {
        
        let periodo =  moment().format("YYYY") + "6";


        let parametroCE = {
          Codigo: parseInt(this.codConcesSelected.ID),
          Marca: parseInt(this.codConcesSelected.MarcaDefault)
        }
        let arrC = [];

        arrC.push(parametroCE);

        this.mesSelected = this.codperiodo.Codigo;
      
        this.detalle_subtitulo = this.codConcesSelected.Nombre + " " + this.codperiodo.Codigo + "S21";

       //console.log(this.codConcesSelected);
       //console.log(arrC);

        var pars = {
          //periodo: this.codperiodo.Codigo,
          Marca: this.codConcesSelected.MarcaDefault,
          Concesionario: this.codConcesSelected.ID,
          Seleccionados:  arrC,
          Anio: moment().format("YYYY"),
          Filtros: "",
          periodo: periodo,
          marca: this.codConcesSelected.MarcaDefault,
          concesionario: this.codConcesSelected.ID,
          ReporteFondo: true,
        };

        this.getResumenCartera(pars);

        this.getModeloControl(pars);
      
        //console.log(this.codperiodo.Codigo);

        //this.getReporte(pars);
        //this.getReporteAnual(pars);
        this.getModelo(pars);
        this.getHNProyectados(pars);
        this.getHNResumenPerformance(pars);
        this.getReporteRentaCartera(pars);

      }
      return;
    },

    getPeriodos() {
      var currentDate = new Date();
      var initialDate = new Date(2020, 5, 1);

      var monthDif = moment(initialDate).diff(moment(), "month") * -1;
      monthDif += 1;
      var i;
      var period = [];
      var fecha = new Date();
      for (i = 0; i < monthDif; i++) {
        fecha = moment(initialDate, "DD/MM/YYYY")
          .add(i, "months")
          .toDate("DD/MM/YYYY");

        period[i] = {
          Mes: moment(fecha).month() + 1,
          Codigo: `${moment(fecha).year() + "" + (moment(fecha).month() + 1)}`,
          Nombre: `${
            this.monthNames[parseInt(moment(fecha).month())] +
            " " +
            moment(fecha).year()
          }`,
        };
      }
      //console.log(period);
      this.itemsPeriodos = period.reverse();
    },
  },
};
</script>

<style scoped>
.contenedor {
  width: 100%;
}

.logo {
  text-align: end;
  padding-right: 35px;
  padding-top: 10px;
  height: 130px;
}

.graph_correl {
  padding-top: 10px;
}

.cursiva {
  font-style: italic;
}

.padded {
  padding-left: 25px;
  padding-right: 25px;
}

.second_page {
  padding-top: 20px;
}

.article {
  font-size: 12px;
  width: 108em;
  height: auto;
  columns: 2;
  column-gap: 25px;
  column-fill: balance;
}

.negrita {
  font-weight: bold;
}

.left_pad {
  padding-left: 25px;
}

.right_pad {
  padding-right: 25px;
}

.half_left {
  width: 50%;
  margin-right: 5px;
}

.half {
  width: 50%;
}

.inline {
  display: flex;
}

.footer_gray {
  font-size: 12px;
  font-weight: lighter;
  font-style: italic;
  text-align: end;
  padding-right: 35px;
  border-top: 1px solid #8d8d8d;
  padding-bottom: 50px;
}

.head_intro {
  font-size: 17px;
  font-weight: bold;
  border-bottom: 1px solid #000000;
  padding-bottom: 3px;
}

.title_intro {
  font-weight: normal;
  font-size: 16px;
}

.text_intro {
  font-size: 14px;
  font-weight: lighter;
  padding-bottom: 2px;
}

.justificado {
  text-align: justify;
}

.fullw {
  width: 100%;
  height: 100%;
}
</style>


