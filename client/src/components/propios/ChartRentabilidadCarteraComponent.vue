<template>
  <div>

    <apexchart type="pie" height="200" :options="chartOptions" :series="series"></apexchart>

  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import moment from "moment";

export default {
  data: function () {
    return {
      series: [1716136, 907936, 651640],
      chartOptions: {
        chart: {
          width: 200,
          type: 'pie',
        },
        toolbar: {
          show: false,
        },
        fill: {
          colors: ['#266fbd', '#46bd89', '#009ebe']
        },
        dataLabels: {
          enabled: true,
          colors: ['#266fbd', '#46bd89', '#009ebe']
          /*
          formatter: function (val) {
            return val + "%"
          },
          dropShadow: {
            //
          }
          */
        },
        labels: ['Inversión', 'Rentabilidad', 'Cobrados'],
        legend: {
          position: 'right',
          horizontalAlign: 'left', 
          show: true,
          customLegendItems: ['Inversión: USD 1716136', 'Rentabilidad: USD 907936', 'Cobrados: USD 651640'], // Datos RB
          offsetX: 0,
          offsetY: 0,
          labels: {
              colors: ['#266fbd', '#46bd89', '#009ebe']
          },
        },
        /*
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        */
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
