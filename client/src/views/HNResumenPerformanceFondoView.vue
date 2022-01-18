<template>
   <div class="padded">
      <v-row>
        <v-col cols="12" sm="12" lg="12" md="12">
          <GridComponentResumenPerformance
            :pars="{
              grid: 'Resumen',
            }"
            :datos="this.datosGrid1"
            :headers="this.headers_ce"
            :loadingState="this.loading_resumen"
            loadingText="Cargando Cuadro Resumen..."
          ></GridComponentResumenPerformance>
        </v-col>
      </v-row>
      <!--
      <v-row>
        <v-col cols="12" sm="12" lg="12" md="12">
          <GridComponentResumenPerformance
            :pars="{
              grid: 'Flujo',
            }"
            :datos="this.datosGrid2"
            :headers="this.headers_saldo"
            :loadingState="this.loading_resumen"
            loadingText="Cargando Cuadro Saldo de Caja..."
          ></GridComponentResumenPerformance>
        </v-col>
      </v-row>
      -->
    </div>
</template>

<script>
import GridComponentResumenPerformance from "@/components/propios/GridComponentResumenPerformance.vue";
import { mapState, mapActions } from "vuex";
import moment from "moment";

export default {

  name: "hnresumenperformancefondo",
  components: {
    GridComponentResumenPerformance,
  },

  data() {
    return {
      selectAnio: moment().format('YYYY'),
      datosDetalleMeses: [],
      itemsAnios: [
        "2020",
        "2021",

      ],

      datosGrid1: [],
      datosGrid2: [],

      headers_saldo: [
        { text: "", value: "Tipo", align: "left", width: "45px" },
        { text: "Comprado (Costo)", value: "CostoUSD", align: "center", width: "20px" },
        {
          text: "Cobrado",
          value: "CobrosUSD",
          align: "center",
          width: "20px",
        },
        {
          text: "Diferencia",
          value: "DiferenciaUSD",
          align: "center",
          width: "20px",
        },
      ],

       headers_ce: [
        { text: "", value: "Tipo", align: "left", width: "45px" },
        { text: "Giama", value: "RB", align: "center", width: "20px" },
        {
          text: "Concesionario",
          value: "CE",
          align: "center",
          width: "20px",
        },

       
        {
          text: "Total",
          value: "Total",
          align: "center",
          width: "20px",
        },
      ],

      
    };
  },

  watch: {

    loading_resumen(newValue) {
      if (!newValue) {
          this.datosGrid1 = this.detalle_resumen_CE;
          this.datosGrid2 = this.detalle_saldo_CE;
      } else {
        this.datosGrid2 = [];
      }
    },

  
  },

  computed: {
    ...mapState("resumenperformance", [
      "detalle_resumen_CE",
      "detalle_resumen",
      "detalle_saldo_CE",
      "detalle_saldo_RB",
      "detalle_saldo",
      "loading_resumen",
    ]),
  
  },

  mounted() {
    this.setDefaultDataSource();
  },
  
  methods: {
    ...mapActions({
      getHNResumenPerformance: "resumenperformance/getHNResumenPerformance",
    }),

    setDefaultDataSource() {
        this.datosGrid1 = this.detalle_resumen_CE;
        this.datosGrid2 = this.detalle_saldo_CE;

    },
    

    formatFecha(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM/YYYY");
      }
    },
  },
};
</script>

<style scoped>
.padded {
  padding-left: 20px;
  padding-right: 20px;
  padding-bottom: 10px;
}

</style>
