<template>
  <v-app class="fullw">
    <v-card class="padded" color="grey lighten-4">
      <v-card-title>
        Reporte Comisiones
        <v-divider class="mx-4" inset vertical></v-divider>

        <v-spacer></v-spacer>
        <v-combobox
          item-text="Nombre"
          item-value="Codigo"
          :items="itemsPeriodoTrim"
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
        <!--
        <v-row>
          <v-col cols="12" md="12">
            <GridSummaryReporteComisionesAnualComponent :headers="this.headersT"></GridSummaryReporteComisionesAnualComponent>
          </v-col>
        </v-row>
        -->
        <v-row>
          <v-col cols="12" md="12">
            <GridSummaryReporteComisionesComponent
              :headers="this.headers"
              :mesBase="this.mesSelected"
            ></GridSummaryReporteComisionesComponent>
          </v-col>
        </v-row>
      </div>
    </v-card>
  </v-app>
</template>

<script>
import GridSummaryReporteComisionesComponent from "@/components/propios/GridSummaryReporteComisionesComponent.vue";
import GridSummaryReporteComisionesAnualComponent from "@/components/propios/GridSummaryReporteComisionesAnualComponent.vue";

import moment from "moment";
import { mapState, mapActions } from "vuex";

export default {
  name: "reportecomisiones",
  components: {
    GridSummaryReporteComisionesComponent,
    GridSummaryReporteComisionesAnualComponent,
    //GridFormCrud
  },
  data() {
    return {
      codperiodo: "",
      mesSelected: 0,
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
        {'Codigo': 1, 'Nombre': "Trimestre 1 (Ene-Feb-Mar)"},
        {'Codigo': 2, 'Nombre': "Trimestre 2 (Abr-May-Jun)"},
        {'Codigo': 3, 'Nombre': "Trimestre 3 (Jul-Ago-Sept)"},
        {'Codigo': 4, 'Nombre': "Trimestre 4 (Oct-Nov-Dic)"},

      ],   

      headersT: [
        {
          text: "Oficial",
          align: "start",
          value: "NomOficial",
          width: "25%",
        },
        /*
        { text: "Objetivo", value: "Objetivo_T1", align: "center" },
        {
          text: "HN Vigentes",
          value: "Vigentes_T1",
          align: "center",
        },
        */
        {
          text: "% Cumplimiento",
          value: "PorcCumplimiento_T1",
          align: "center",
        },
        {
          text: "Comisiones",
          value: "Comision_T1",
          align: "center",
        },
         /*
        { text: "Objetivo", value: "Objetivo_T2", align: "center" },
        {
          text: "HN Vigentes",
          value: "Vigentes_T2",
          align: "center",
        },
        */
        {
          text: "% Cumplimiento",
          value: "PorcCumplimiento_T2",
          align: "center",
        },
        {
          text: "Comisiones",
          value: "Comision_T2",
          align: "center",
        },
         /*
        { text: "Objetivo", value: "Objetivo_T3", align: "center" },
        {
          text: "HN Vigentes",
          value: "Vigentes_T3",
          align: "center",
        },
        */
        {
          text: "% Cumplimiento",
          value: "PorcCumplimiento_T3",
          align: "center",
        },
        {
          text: "Comisiones",
          value: "Comision_T3",
          align: "center",
        },
         /*
        { text: "Objetivo", value: "Objetivo_T4", align: "center" },
        {
          text: "HN Vigentes",
          value: "Vigentes_T4",
          align: "center",
        },
        */
        {
          text: "% Cumplimiento",
          value: "PorcCumplimiento_T4",
          align: "center",
        },
        {
          text: "Comisiones",
          value: "Comision_T4",
          align: "center",
        },

        /*
        { text: "Objetivo", value: "Objetivo_T", align: "center" },
        {
          text: "HN Vigentes",
          value: "Vigentes_T",
          align: "center",
        },
        {
          text: "% Cumplimiento",
          value: "PorcCumplimiento_T",
          align: "center",
        },
        {
          text: "Comisiones",
          value: "Comision_T",
          align: "center",
        },
        */
      ],

      headers: [
        {
          text: "Oficial",
          align: "start",
          value: "NomOficial",
          width: "25%",
        },
        /*
        { text: "Objetivo", value: "Objetivo_0", align: "center" },
        {
          text: "HN Vigentes",
          value: "Comprados_0",
          align: "center",
        },
        */
        {
          text: "% Cumplimiento",
          value: "PorcCumplimiento_0",
          align: "center",
        },
        {
          text: "Casos Comisionables",
          value: "CantHN_0",
          align: "center",
        },
        {
          text: "Comisiones",
          value: "Comision_0",
          align: "center",
        },

        /*
        { text: "Objetivo", value: "Objetivo_1", align: "center" },
        {
          text: "HN Vigentes",
          value: "Comprados_1",
          align: "center",
        },
        */
        {
          text: "% Cumplimiento",
          value: "PorcCumplimiento_1",
          align: "center",
        },
        {
          text: "Casos Comisionables",
          value: "CantHN_1",
          align: "center",
        },
        {
          text: "Comisiones",
          value: "Comision_1",
          align: "center",
        },
        /*
        { text: "Objetivo", value: "Objetivo_2", align: "center" },
        {
          text: "HN Vigentes",
          value: "Comprados_2",
          align: "center",
        },
        */
        {
          text: "% Cumplimiento",
          value: "PorcCumplimiento_2",
          align: "center",
        },
        {
          text: "Casos Comisionables",
          value: "CantHN_2",
          align: "center",
        },
        {
          text: "Comisiones",
          value: "Comision_2",
          align: "center",
        },

         /* 
        { text: "Objetivo", value: "Objetivo_T", align: "center" },
        {
          text: "HN Vigentes",
          value: "Comprados_T",
          align: "center",
        },
        */
        {
          text: "% Cumplimiento",
          value: "PorcCumplimiento_T",
          align: "center",
        },
         {
          text: "Casos Comisionables",
          value: "CantHN_T",
          align: "center",
        },
        {
          text: "Comisiones",
          value: "Comision_T",
          align: "center",
        },
      ],
    };
  },

  created() {
    this.getPeriodos();
  },

  computed: {
    ...mapState("reportecomisiones", [
      "items",
      "loading",
      "datos",
      "itemsAnual",
      "loadingAnual",
      "datosAnual",
    ]),
  },

  methods: {
    ...mapActions({
      getReporte: "reportecomisiones/getReporte",
      getReporteAnual: "reportecomisiones/getReporteAnual",
    }),

    getReport() {
      console.log(this.codperiodo);
      this.mesSelected = this.codperiodo.Codigo;
      var pars = {
        periodo: this.codperiodo.Codigo,
      };

      this.getReporte(pars);
      this.getReporteAnual(pars);
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
