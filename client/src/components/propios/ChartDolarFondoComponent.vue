<template>
  <div>

    <apexchart
      ref="demoChart"
      type="line"
      height="250"
      :options="chartOptions"
      :series="this.series"
    ></apexchart>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import moment from "moment";

export default {
  data: function () {
    return {

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
    
      series: [

      ],
      chartOptions: {
        chart: {
         locales: [{
            "name": "es",
            "options": {
              "months": ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
              "shortMonths": ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
              "days": ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
              "shortDays": ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
              "toolbar": {
                  "exportToSVG": "Descargar SVG",
                  "exportToPNG": "Descargar PNG",
                  "exportToCSV": "Descargar CSV",
                  "menu": "Menu",
                  "selection": "Seleccion",
                  "selectionZoom": "Seleccion Zoom",
                  "zoomIn": "Acercar",
                  "zoomOut": "Alejar",
                  "pan": "Panning",
                  "reset": "Reset Zoom"
              }
            }
          }],
          defaultLocale: "es",
          height: 250,
          type: "line",
          
          toolbar: {
              show: false,
              offsetX: 0,
              offsetY: -20,
              tools: {
                  download: false,
                  selection: false,
                  zoom: false,
                  zoomin: false,
                  zoomout: false,
                  pan: false,
                  reset: false | '<img src="/static/icons/reset.png" width="20">',
                  customIcons: []
              },
              export: {
                //
              },
            autoSelected: 'zoom' 
          },
        },
        colors: [ "#910D1F", "#77B6EA", "#545454","#02A34D"],
        //colors: [ "#77B6EA", "#545454", "#02A34D"],
        dataLabels: {
          //Esto pone los cuadrados con el valor en el punto
          enabled: false,
        },
       
        stroke: { 
          width: 5,
          curve: 'straight'
        },
        /*
        title: {
          text: "Indices Mensuales",
          align: "left",
        },
        */
        xaxis: {

          type: "datetime",
          
          categories: ['2015','2016','2017','2018', '2019', '2020'],
          title: {
            text: "Periodo",
          },
        },
        yaxis: {
          title: {
            text: "%",
          },
          min: 0,
          max: 1300,
        },
        
        legend: {
          // Muestra los nombres de las Series
          position: "top",
          horizontalAlign: "right",
          floating: true,
          offsetY: -25,
          offsetX: -5,
        },
        
         noData: {
            text: 'Cargando Datos...'
          }
      },
    };
  },


  mounted() {
    this.$nextTick(function () {
      this.getDatosSeries();
    })
  },

  computed: {
    ...mapState("graphindice", ["loadingDatos", "seriesDB", "periodos", "minValue", "maxValue", "dataStatus"]),
  },

  methods: {
    ...mapActions({ getSeries: "graphindice/getSeries" }),
    async getDatosSeries() {
      let pars = {
        reporteFechas: 0
      };
      await this.getSeries(pars);
      this.series = this.seriesDB;
      this.period = this.periodos;
      let arrPer = this.periodos;
      let lastPeriodStr = '';

      lastPeriodStr = await this.getPeriodName(arrPer[arrPer.length - 1]);
      this.$refs.demoChart.updateOptions({
        /*
        title: {
          text: "Indices Mensuales - Enero 2015 a " + lastPeriodStr,
          align: "left",
        },
        */
        xaxis: {
          categories: this.period
        },
        yaxis: {
          title: {
            text: "%",
          },
          min: parseInt(this.minValue),
          max: parseInt(this.maxValue) + 10
        }
      })
    },

    getPeriodName(periodo){
      let perMoment = moment(periodo);
      return this.monthNames[parseInt(perMoment.month())] + " " + String(perMoment.year());

    }
  },
};
</script>

<style lang="scss" scoped>
.contenedor {
  height: 360px;
  width: 100%;
}

</style>
