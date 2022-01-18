<template>
  <div>

    <apexchart
      ref="demoChart"
      type="bar"
      height="200"
      :options="chartOptions"
      :series="dataSeries"
    ></apexchart>

  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import moment from "moment";

export default {
  data: function () {
    return {
      labels_categories: [],
      series: [],
      cantAniosMax: 5, 
      dataSeries: [],
      seriesDB: [],
      /*
      series_hard: [{
            data: this.dataSeries,//[22928, 452100, 687600, 967296, 494148], // Datos RB

          }],
      */
      
          chartOptions: {

            labels: [moment().format('YYYY')], //['2025', '2024', '2023', '2022', '2021'],
           
            chart: {
              type: 'bar',
              height: 200,
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
            

            
            defaultLocale: "es",
            /*
            title: {
                text: 'Composición Cartera Actual',
                align: 'left',
                floating: true
            },
            */
          },
          plotOptions: {
            bar: {
              barHeight: '40%',
              distributed: false,
              horizontal: true,
              
            }
          },

          

          dataLabels: {
              enabled: true,
              textAnchor: 'start',
              style: {
                colors: ['#000000']
              },
              formatter: function (val, opt) {
                return "USD " + val   
              },
              offsetX: 0,
              dropShadow: {
                enabled: false
              }
          },
          xaxis: {
            categories: [moment().format('YYYY')],
          }
        },
        
    };
  },


  mounted() {
    this.$nextTick(function () {
     // this.getDatosSeries();
    })
  },

  computed: {
   // ...mapState("graphindice", ["loadingDatos", "seriesDB", "periodos", "minValue", "maxValue", "dataStatus"]),
    ...mapState("proyectadohn", ["cobros_anuales", "loadingdetalle_proyec_anios"]),
  },

  watch: {


    loadingdetalle_proyec_anios(newValue) {
      this.seriesDB = [];
      this.labels_categories = [];
      if (!newValue) {
        if (typeof(this.cobros_anuales) !== 'undefined'){
          this.setDatosSeries();
        }
        
      } 
    }
  },

  methods: {
    
    setDatosSeries() {

      for (let index = 0; index < this.cantAniosMax; index++) {
        this.labels_categories.unshift(parseInt(moment().format("YYYY")) + index); 
        this.seriesDB.unshift(this.cobros_anuales.Valores['A'+index]);
      }

      this.dataSeries = [{data: this.seriesDB}];

      this.$refs.demoChart.updateOptions({

        xaxis: {
          categories: this.labels_categories
        },
        /*
        yaxis: {
          title: {
            text: "%",
          },
          min: parseInt(this.minValue),
          max: parseInt(this.maxValue) + 10
        }
        */
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
