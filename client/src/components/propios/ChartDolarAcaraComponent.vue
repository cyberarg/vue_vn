<template>
  <div class="contenedor">

    <apexchart
    ref="demoChart"
      type="line"
      height="350"
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
          height: 350,
          type: "line",
          
          toolbar: {
              show: true,
              offsetX: 0,
              offsetY: -20,
              tools: {
                  download: true,
                  selection: true,
                  zoom: true,
                  zoomin: true,
                  zoomout: true,
                  pan: true,
                  reset: true | '<img src="/static/icons/reset.png" width="20">',
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
        title: {
          text: "Indices Mensuales",
          align: "left",
        },
        /*
        grid: {
          borderColor: "#e7e7e7",
          row: {
            colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
            opacity: 0.5,
          },
        },
        */
        xaxis: {

          /*
          categories: ['2015-01-01','2015-02-01','2015-03-01','2015-04-01','2015-05-01','2015-06-01','2015-07-01','2015-08-01','2015-09-01','2015-10-01','2015-11-01','2015-12-01',
                      '2016-01-01','2016-02-01','2016-03-01','2016-04-01','2016-05-01','2016-06-01','2016-07-01','2016-08-01','2016-09-01','2016-10-01','2016-11-01','2016-12-01',
                      '2017-01-01','2017-02-01','2017-03-01','2017-04-01','2017-05-01','2017-06-01','2017-07-01','2017-08-01','2017-09-01','2017-10-01','2017-11-01','2017-12-01',
                      '2018-01-01','2018-02-01','2018-03-01','2018-04-01','2018-05-01','2018-06-01','2018-07-01','2018-08-01','2018-09-01','2018-10-01','2018-11-01','2018-12-01',
                      '2019-01-01','2019-02-01','2019-03-01','2019-04-01','2019-05-01','2019-06-01','2019-07-01', '2019-08-01', '2019-09-01', '2019-10-01', '2019-11-01', '2019-12-01',
                      '2020-01-01', '2020-02-01', '2020-03-01', '2020-04-01', '2020-05-01', '2020-06-01', '2020-07-01', '2020-08-01', '2020-09-01', '2020-10-01'],
          */
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

        title: {
          text: "Indices Mensuales - Enero 2015 a " + lastPeriodStr,
          align: "left",
        },

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
