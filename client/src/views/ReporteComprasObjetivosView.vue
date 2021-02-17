<template>
  <v-app class="fullw">
    <v-card class="padded" color="grey lighten-4">
      <v-card-title>
        Reporte Compras / Objetivos
        <v-divider class="mx-4" inset vertical></v-divider>

        <v-spacer></v-spacer>
        <v-combobox
          item-text="Nombre"
          item-value="Codigo"
          :items="itemsPeriodos"
          label="Periodos"
          v-model="codperiodo"
          @change="getReport()"
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
      </v-card-title>
      <div class="card-body">
        <v-row>
          <v-col cols="12" md="12">
            <GridSummaryReporteComprasComponent
              :headers="this.headers"
              :mesBase="this.mesSelected"
            ></GridSummaryReporteComprasComponent>
          </v-col>
        </v-row>
      </div>
    </v-card>
  </v-app>
</template>

<script>
import GridSummaryReporteComprasComponent from "@/components/propios/GridSummaryReporteComprasComponent.vue";
import moment from "moment";
import { mapState, mapActions } from "vuex";

export default {
  name: "reportecomprasobjetivos",
  components: {
    GridSummaryReporteComprasComponent,
    //GridFormCrud
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

        { text: "Objetivo", value: "Objetivo_1", align: "center" },
        {
          text: "Compras",
          value: "Compras_1",
          align: "center",
        },
        {
          text: "% Cumplimiento",
          value: "PorcCumplimiento_1",
          align: "center",
        },

        { text: "Objetivo", value: "Objetivo_2", align: "center" },
        {
          text: "Compras",
          value: "Compras_2",
          align: "center",
        },
        {
          text: "% Cumplimiento",
          value: "PorcCumplimiento_2",
          align: "center",
        },

        { text: "Objetivo", value: "Objetivo_3", align: "center" },
        {
          text: "Compras",
          value: "Compras_3",
          align: "center",
        },
        {
          text: "% Cumplimiento",
          value: "PorcCumplimiento_3",
          align: "center",
        },

        { text: "Objetivo", value: "Objetivo_4", align: "center" },
        {
          text: "Compras",
          value: "Compras_4",
          align: "center",
        },
        {
          text: "% Cumplimiento",
          value: "PorcCumplimiento_4",
          align: "center",
        },

        { text: "Objetivo", value: "Objetivo_5", align: "center" },
        {
          text: "Compras",
          value: "Compras_5",
          align: "center",
        },
        {
          text: "% Cumplimiento",
          value: "PorcCumplimiento_5",
          align: "center",
        },

        { text: "Objetivo", value: "Objetivo_6", align: "center" },
        {
          text: "Compras",
          value: "Compras_6",
          align: "center",
        },
        {
          text: "% Cumplimiento",
          value: "PorcCumplimiento_6",
          align: "center",
        },
      ],
    };
  },

  created() {
    this.getPeriodos();
  },

  computed: {
    ...mapState("reportecomprasobjetivos", ["items", "loading", "datos"]),
  },

  methods: {
    ...mapActions({
      getReporte: "reportecomprasobjetivos/getReporte",
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

.padded {
  padding-left: 20px;
  padding-right: 20px;
}

.fullw {
  width: 100%;
  height: 100%;
}
</style>
