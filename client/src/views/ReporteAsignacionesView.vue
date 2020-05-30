<template>
  <v-app class="fullw">
    <v-card class="padded" color="grey lighten-4">
      <v-card-title>
        Asignaciones por Período
        <v-divider class="mx-4" inset vertical></v-divider>
      </v-card-title>
      <div class="card-body">
        <v-row>
          <v-col cols="6" md="6">
            <v-select
              dense
              :items="itemsPeriodos"
              item-text="Nombre"
              item-value="Codigo"
              label="Periodos"
              v-model="codperiodo"
              @change="getAsignaciones()"
            ></v-select>
          </v-col>
          <!--
          <v-col cols="4" md="4">{{ codperiodo }}</v-col>
          -->
        </v-row>
        <v-row v-show="showTable">
          <v-col cols="12" md="12">
            <GridSummaryFormAsignacionesComponent
              :pars="{
                titleform: 'Reporte Asignaciones por Período',
                routeapi: 'reporteasignacion',
                itemkey: 'Codigo',
                module: 'reporteasignacion'
              }"
              :headers="this.headers"
            ></GridSummaryFormAsignacionesComponent>
          </v-col>
        </v-row>
      </div>
    </v-card>
  </v-app>
</template>

<script>
import GridSummaryFormAsignacionesComponent from "@/components/propios/GridSummaryFormAsignacionesComponent.vue";
import moment from "moment";
import { mapState, mapActions } from "vuex";

export default {
  name: "reporteasignaciones",
  components: {
    GridSummaryFormAsignacionesComponent
    //GridFormCrud
  },
  data() {
    return {
      /*
      periodos: [
        { Codigo: "201701", Nombre: "Enero 2017" },
        { Codigo: "201702", Nombre: "Febrero 2017" },
        { Codigo: "201703", Nombre: "Marzo 2017" },
        { Codigo: "201704", Nombre: "Abril 2017" },
        { Codigo: "201705", Nombre: "Mayo 2017" }
      ],
      */
      search: "",
      codperiodo: "",
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
        "Diciembre"
      ],
      headers: [
        {
          text: "Oficial",
          align: "center",
          value: "NomOficial",
          width: "25%"
        },
        { text: "Asignados", value: "Asignados", align: "center" },
        {
          text: "Sin Gestionar",
          value: "SinGestionar",
          align: "center"
        },
        {
          text: "Telefono Mal",
          value: "TelefonoMal",
          align: "center"
        },
        {
          text: "Deje Mensaje",
          value: "DejeMensaje",
          align: "center"
        },
        {
          text: "Entrevista Pendiente",
          value: "EntrevistaPendiente",
          align: "center"
        },
        {
          text: "En Gestión",
          value: "EnGestion",
          align: "center"
        },
        {
          text: "No le Interesa",
          value: "NoLeInteresa",
          align: "center"
        },
        {
          text: "Vende Plan",
          value: "VendePlan",
          align: "center"
        },
        {
          text: "Plan Subite",
          value: "PlanSubite",
          align: "center"
        },
        {
          text: "Pasar a Venta",
          value: "PasarAVenta",
          align: "center"
        },
        {
          text: "En Otro Oficial",
          value: "EnOtroOficial",
          align: "center"
        }
      ]
    };
  },

  created() {
    this.getPeriodos();
  },

  computed: {
    ...mapState("reporteasignacion", [
      "items",
      "loading",
      "datos",
      "empresa",
      "showTable"
    ])
  },

  methods: {
    ...mapActions({
      getData: "reporteasignacion/getData"
    }),

    getAsignaciones() {
      //console.log(this.codperiodo);
      this.getData({ api: "reporteasignacion", periodo: this.codperiodo });
    },

    totalS(column, valor) {
      return valor.reduce(function(total, item) {
        // console.log(item);
        if (isNaN(item[column.value])) {
          return "";
        }
        return total + parseInt(item[column.value]);
      }, 0);
    },

    totalItem(valor) {
      return valor.reduce(function(total, item) {
        // console.log(item);
        if (isNaN(item[column.value])) {
          return "";
        }
        return total + parseInt(item[column.value]);
      }, 0);
    },

    getPeriodos() {
      var currentDate = new Date();
      var initialDate = new Date(2017, 0, 1);

      var monthDif = moment(initialDate).diff(moment(), "month") * -1;
      monthDif += 2;
      var i;
      var period = [];
      var fecha = new Date();
      for (i = 0; i < monthDif; i++) {
        fecha = moment(initialDate, "DD/MM/YYYY")
          .add(i, "months")
          .toDate("DD/MM/YYYY");

        period[i] = {
          Codigo: `${moment(fecha).year() + "" + (moment(fecha).month() + 1)}`,
          Nombre: `${this.monthNames[parseInt(moment(fecha).month())] +
            " " +
            moment(fecha).year()}`
        };
      }
      //console.log(period);
      this.itemsPeriodos = period.reverse();
    }
  }
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
