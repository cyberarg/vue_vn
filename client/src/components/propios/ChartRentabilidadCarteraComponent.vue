<template>
  <div>

    <apexchart type="pie" height="200" :options="chartOptions" :series="series"></apexchart>
    <div class="detalle_renta">
      {{this.rentabilidadCalculada}}
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import moment from "moment";

export default {
  data: function () {
    return {
      series: [],//[1716136, 907936, 651640],
      rentabilidadCalculada: '',
      chartOptions: {
        chart: {
          width: 200,
          type: 'pie',
        },
        toolbar: {
          show: false,
        },
        colors: ['#7f8285', '#0e6d80', '#009ebe'],
        fill: {
          colors: ['#7f8285', '#0e6d80', '#009ebe']
        },
        
        dataLabels: {
          /*
          enabled: false,
          colors: ['#266fbd', '#46bd89', '#009ebe']
          */

          /*
          formatter: function (val) {
            return val + "%"
          },
          dropShadow: {
            //
          }
          */
        },
        labels: ['Inversión', 'Proy. Cobro', 'Cobrados'],
        legend: {
          position: 'right',
          horizontalAlign: 'left', 
          show: true,
          //customLegendItems: ['Inversión: USD 1716136', 'Rentabilidad: USD 907936', 'Cobrados: USD 651640'], // Datos RB
          offsetX: 0,
          offsetY: 0,
          colors: ['#7f8285', '#0e6d80', '#009ebe'],
          /*
          labels: {
              colors: ['#266fbd', '#46bd89', '#009ebe'],
              useSeriesColors: false
          },
          */
        },
        
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
    ...mapState("reporterentacartera", ["detalle_proyectado", "detalle_historico", "loading_renta", "dataStatus"]),
    ...mapState("proyectadohn", ["cobros_anuales", "rentabilidades_anuales", "loadingdetalle_proyec_anios"]),
  },

  watch:{

    loading_renta(newValue) {
      this.series = [];

      if (!newValue) {
        if (typeof(this.detalle_historico) !== 'undefined'){
          this.setDatosSeries();
        }
      } 
    }

  },

  methods: {
    
    setDatosSeries() {

      let teoricoCobro = 0;
      let rentaAnual = 0;
      let inversion = 0;
      let objHistorico = {};
      let objProyectado = {};
      let renglonRentabilidad = 0;
      
      objHistorico = this.detalle_historico.shift();
      objProyectado = this.detalle_proyectado.shift();

      rentaAnual = parseInt(objProyectado.RentabilidadTeorica);
      teoricoCobro =  parseInt(objProyectado.HN_TotalACobrar);

      inversion = (teoricoCobro - rentaAnual) + parseInt(objHistorico.CostoHistorico);

      this.series.push(parseInt(inversion)); //Inversion
      this.series.push(teoricoCobro); //Proy. Cobro
      this.series.push(parseInt(objHistorico.CobroHistorico)); //CobroHistorico

      renglonRentabilidad = (parseInt(teoricoCobro) - parseInt(inversion)) + parseInt(objHistorico.CobroHistorico);
    
      this.rentabilidadCalculada = 'Rentabilidad: USD ' + String(this.$options.filters.numFormat(renglonRentabilidad));
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

.detalle_renta {
  font-size: 12px;
  font-weight: bold;
  text-align: center;
}

</style>
