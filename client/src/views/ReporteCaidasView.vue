<template>
  <v-app class="fullw">
    <v-card class="padded" color="grey lighten-4">
      <v-card-title>
        Reporte Caidas
        <v-divider class="mx-4" inset vertical></v-divider>

        <v-spacer></v-spacer>
         <v-col cols="3" md="3">
          <v-select
            :items="itemsPeriodos"
            item-text="Nombre"
            item-value="Codigo"
            label="Periodos"
            v-model="codperiodo"
            @change="getReporte()"
          ></v-select>
        </v-col>
        <v-col cols="3" md="3">
          <v-btn
            class="ma-2"
            outlined
            color="blue darken-1"
            text
            @click="getReporte()"
            :disabled="disableButton"
          >
            <v-icon left>mdi-refresh</v-icon>Actualizar
          </v-btn>
        </v-col>
      </v-card-title>
      <div class="card-body">
        <GridSummaryReporteCaidas
          :headers="this.headers"
          :mesBase="this.mesSelected"
        ></GridSummaryReporteCaidas>
      </div>
    </v-card>
  </v-app>
</template>

<script>
import GridSummaryReporteCaidas from "@/components/propios/GridSummaryReporteCaidas.vue";

import moment from "moment";
import { mapState, mapActions } from "vuex";

export default {
  name: "reportecaidas",
  components: {
    GridSummaryReporteCaidas,
  
  },
  data() {
    return {

      codperiodo: "",
     
      itemsPeriodos: [],
      mesSelected: 0,
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

      headers: [
        {
          text: "Oficial",
          align: "start",
          value: "NomOficial",
          width: "25%",
          class: "fixed",
        },
        {
          text: "Compras",
          value: "Compras_1",
          align: "center",
        },
        { text: "Caidas", value: "Caidas_1", align: "center" },
        
        {
          text: "% Caidas",
          value: "PorcCaidas_1",
          align: "center",
        },
        
        {
          text: "Compras",
          value: "Compras_2",
          align: "center",
        },
        { text: "Caidas", value: "Caidas_2", align: "center" },
        {
          text: "% Caidas",
          value: "PorcCaidas_2",
          align: "center",
        },
        {
          text: "Compras",
          value: "Compras_3",
          align: "center",
        },
        { text: "Caidas", value: "Caidas_3", align: "center" },
        {
          text: "% Caidas",
          value: "PorcCaidas_3",
          align: "center",
        },
        {
          text: "Compras",
          value: "Compras_4",
          align: "center",
        },
        { text: "Caidas", value: "Caidas_4", align: "center" },
        {
          text: "% Caidas",
          value: "PorcCaidas_4",
          align: "center",
        },
        {
          text: "Compras",
          value: "Compras_5",
          align: "center",
        },
        { text: "Caidas", value: "Caidas_5", align: "center" },
        {
          text: "% Caidas",
          value: "PorcCaidas_5",
          align: "center",
        },
        {
          text: "Compras",
          value: "Compras_6",
          align: "center",
        },
        { text: "Caidas", value: "Caidas_6", align: "center" },
        {
          text: "% Caidas",
          value: "PorcCaidas_6",
          align: "center",
        },
      ],
    };
  },

  created() {
    this.getPeriodos();
  },

  computed: {
    ...mapState("reportecaidas", ["items", "loading", "datos"]),
  },

  methods: {
    ...mapActions({
      getReporte: "reportecaidas/getReporte",
    }),

    getReport() {
      console.log(this.codperiodo);
      this.mesSelected = this.codperiodo.Mes;
      var pars = {
        periodo: this.codperiodo.Codigo,
      };

      this.getReporte(pars);
      //this.getData({ api: "reporteasignacion", periodo: this.codperiodo });
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
      console.log(period);
      this.itemsPeriodos = period.reverse();
    },
  },
};
</script>

<style scoped>
.contenedor {
  width: 100%;
}

.padded {
  padding-left: 20px;
  padding-right: 20px;
}

.fullw {
  width: 100%;
  height: 100%;
}
</style>
