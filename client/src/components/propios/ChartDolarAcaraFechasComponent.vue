<template>
  <div class="contenedor">
    <v-row class="paddedleft">
       <v-col cols="4" md="4">
              <v-select
                
                dense
                :items="itemsPeriodos"
                item-text="Nombre"
                item-value="Codigo"
                label="Desde"
                v-model="fechad"
          
              ></v-select>
            </v-col>
        <v-col cols="4" md="4">
              <v-select
                dense
                :items="itemsPeriodos"
                item-text="Nombre"
                item-value="Codigo"
                label="Hasta"
                v-model="fechah"
                
              ></v-select>
            </v-col>
            <v-col cols="4" sm="4">
              <v-btn
                icon
                @click="getDatosSeries()"
                :disabled="disableButton"
              >
                <v-icon>mdi-refresh</v-icon>
              </v-btn>
            </v-col>
 
    </v-row>
    <apexchart
    ref="demoChart"
      type="line"
      height="300"
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
      fechad: null,
      fechah: null,
      //disableButton:false,
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

      periods:[],
      series: [],

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
          height: 300,
          type: "line",
          
          toolbar: {
              show: true,
              offsetX: 0,
              offsetY: -35,
              tools: {
              download: true,
              selection: false,
              zoom: false,
              zoomin: false,
              zoomout: false,
              pan: false,
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
        /*
        title: {
          text: "Indices Mensuales - Enero 2015 a Agosto 2020",
          align: "left",
        },
        */
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
           categories: ['2019-10-01', '2019-11-01', '2019-12-01',
                      '2020-01-01', '2020-02-01', '2020-03-01', '2020-04-01', '2020-05-01', '2020-06-01'],
          */
          categories: [],
          type: "datetime",
          
          title: {
            text: "Periodo",
          },
        },
        yaxis: {
          title: {
            text: "%",
          },
          min: 0,
          max: 120,
        },
        
        legend: {
          // Muestra los nombres de las Series
          show: true,
          position: "top",
          horizontalAlign: "right",
          floating: true,
          offsetY: 0,
          offsetX:  -5,
        },
        
         noData: {
            text: 'Seleccione Rango de Fechas'
          }
      },
    };
  },

  /*
  mounted() {
    this.$nextTick(function () {
      this.getDatosSeries();
    })
  },
  */

  computed: {
    ...mapState("graphindice", ["loadingDatos", "seriesDB", "periodos", "minValue", "maxValue", "dataStatus"]),

    disableButton(){
      return this.fechad == null || this.fechah == null;
    }
  },

    created() {
    this.getPeriodos();
  },

  methods: {
    ...mapActions({ getSeries: "graphindice/getSeries" }),
    async getDatosSeries() {
      
      this.$refs.demoChart.updateOptions({
       noData: {
            text: 'Cargando Datos...'
          }
      });

      //console.log(moment(this.fechad));
       let pars = {
        reporteFechas: 1,
        fechaD: this.fechad,
        fechaH: this.fechah
      };
      await this.getSeries(pars);
      this.series = this.seriesDB;
      this.periods = this.periodos;

      this.$refs.demoChart.updateOptions({
        xaxis: {
          categories: this.periods
        },
        yaxis: {
          title: {
            text: "%",
          },
          min: this.minValue,
          max: parseInt(this.maxValue) + 10
        }
      })

     // console.log(pars);
    },

     getPeriodos() {
      var currentDate = new Date();
      var initialDate = new Date(2015, 0, 1);

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
          Codigo: `${moment(fecha).year() + "" + (moment(fecha).month() + 1)}`,
          Nombre: `${
            this.monthNames[parseInt(moment(fecha).month())] +
            " " +
            moment(fecha).year()
          }`,
        };
      }
      console.log(period);
      this.itemsPeriodos = period.reverse();
    },
  },
};
</script>

<style lang="scss" scoped>
.contenedor {
  height: 372px;
  width: 100%;
}

.paddedleft {
  padding-left: 18px;
}

</style>
