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
          <span class="negrita half">DEFINITIONS</span>
          <div class="article">
              The <span class="negrita">5 year Earnings Per Share (EPS) growth rate</span> is the weighted average of earnings per share growth for all securities in the portfolio projected for the past five fiscal years. Earnings per share for a company is defined as total earnings divided by shares outstanding. <span class="negrita">Active Share</span> is a measure of the percentage of stock holdings in a managers portfolio that differ from the benchmark index (based on holdings and weight of holdings). Active Share scores range from 0%-100%. A score of 100% means you are completely different from the benchmark. Active Share calculation may consolidate holdings with the same economic exposure. <span class="negrita">Alpha</span> (Jensen's) is a risk-adjusted performance measure that represents the average return on a portfolio or investment above or below that predicted by the capital asset pricing model (CAPM) given the portfolio's or investment's beta and the average market return. Prior to 6/30/2018 Alpha was calculated as the excess return of the fund versus benchmark. <span class="negrita">Beta</span> is a measure of the relative volatility of a fund to the market’s upward or downward movements. A beta greater than 1.0 identifies an issue or fund that will move more than the market, while a beta less than 1.0 identifies an issue or fund that will move less than the market. The Beta of the Market is always equal to 1. <span class="negrita">Bloomberg</span> stands for 'Bloomberg Global Identifier (BBGID)'. This is a unique 12 digit alphanumerical code designed to enable the identification of securities, such as the Morgan Stanley Investment Funds sub-funds at share class level, on a Bloomberg Terminal. The Bloomberg Terminal, a system provided by Bloomberg L.P., enables analysts to access and analyse real-time financial market data. Each Bloomberg code starts with the same BBG prefix, followed by nine further characters that we list here in this guide for each share class of each fund. Debt/equity (D/E) is a measure of a company’s financial leverage calculated by dividing its total liabilities by stockholders’ equity. Dividend yield is the ratio between how much a company pays out in dividends each year relative to its share price. Down Capture Ratio is a statistical measure of an investment manager’s overall performance in down-markets. Downside capture indicates how correlated a fund is to a market, when the market declines. Excess Return or value added (positive or negative) is the portfolio’s return relative to the return of the benchmark. Information ratio is the portfolio’s alpha or excess return per unit of risk, as measured by tracking error, versus the portfolio’s benchmark. ISIN is the international securities identification number (ISIN), a 12 digit code consisting of numbers and letters that distinctly identifies securities. NAV is the Net Asset Value per share of the Fund (NAV), which represents the value of the assets of a fund less its liabilities. Number of holdings provided are a typical range, not a maximum number. The portfolio may exceed this from time to time due to market conditions and outstanding trades. R squared measures how well an investment’s returns correlate to an index. An R squared of 1.00 means the portfolio performance is 100% correlated to the index’s, whereas a low rsquared means that the portfolio performance is less correlated to the index’s. Return on capital is a measure of a company’s efficiency at allocating the capital under its control to profitable investments, calculated by dividing net income minus dividends by total capital. Sales growth is the increase in sales over a specific period of time, often but not necessarily annually. Sharpe ratio is a risk-adjusted measure calculated as the ratio of excess return to standard deviation. The Sharpe ratio determines reward per unit of risk. The higher the Sharpe ratio, the better the historical risk-adjusted performance. Tracking error is the standard deviation of the difference between the returns of an investment and its benchmark. Upside/downside market capture measures annualized performance in up/down markets relative to the market benchmark. Volatility (Standard deviation) measures how widely individual performance returns, within a performance series, are dispersed from the average or mean value. Weighted median market capitalization is the point at which half of the market value of a portfolio or index is invested in stocks with a greater market cap, while the other half of the market value is invested in stocks with a lower market cap. 
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


