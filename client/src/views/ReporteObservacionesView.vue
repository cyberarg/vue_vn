<template>
  <v-app class="fullw">
    <v-card class="padded" color="grey lighten-4">
      <v-card-title>
        Observaciones por Oficial
        <v-divider class="mx-4" inset vertical></v-divider>
        <!--
        <v-spacer></v-spacer>
        <v-combobox
          item-text="Nombre"
          item-value="Codigo"
          :items="listMarcas"
          label="Marca"
          :value="codMarcaSelected"
          @change="filterListConcesionaria"
          class="padded"
        ></v-combobox>

        <v-combobox
          item-text="Nombre"
          item-value="Codigo"
          :items="listC"
          label="Concesionario"
          v-model="codConcesSelected"
          @change="setSelected"
          class="padded"
        ></v-combobox>
-->
        <v-text-field
          type="date"
          :min="getMin"
          :max="getToday"
          label="Fecha Desde"
          placeholder="Fecha Desde"
          v-model="fechaDesde"
          required
          class="padded"
        ></v-text-field>

        <v-text-field
          type="date"
          class="fillable"
          :filled="filled"
          :min="getMin"
          :max="getToday"
          label="Fecha Hasta"
          placeholder="Fecha Hasta"
          v-model="fechaHasta"
          required
        ></v-text-field>
        <v-spacer></v-spacer>
        <v-btn
          class="ma-2"
          outlined
          color="blue darken-1"
          text
          @click="getReporte()"
        >
          <v-icon left>mdi-refresh</v-icon>Actualizar
        </v-btn>
      </v-card-title>
      <div class="card-body">
        <v-row>
          <v-col cols="12" md="12">
            <GridSummaryReporteObservacionesComponent
              :pars="{
                titleform: 'Reporte Observaciones por Oficial',
                routeapi: 'reporteobservaciones',
                itemkey: 'Codigo',
                module: 'reporteobservaciones',
              }"
              :headers="this.headers"
            ></GridSummaryReporteObservacionesComponent>
          </v-col>
        </v-row>
      </div>
    </v-card>
  </v-app>
</template>

<script>
import GridSummaryReporteObservacionesComponent from "@/components/propios/GridSummaryReporteObservacionesComponent.vue";
import moment from "moment";
import { mapState, mapActions } from "vuex";

export default {
  name: "reporteobservaciones",
  components: {
    GridSummaryReporteObservacionesComponent,
    //GridFormCrud
  },
  data() {
    return {
      search: "",
      fechaDesde: "",
      fechaHasta: "",
      codMarcaSelected: null,
      listMarcas: [
        { Codigo: 2, Nombre: "Fiat" },
        { Codigo: 5, Nombre: "Volkswagen" },
        { Codigo: 9, Nombre: "Ford" },
        { Codigo: 3, Nombre: "Peugeot" },
      ],
      codConcesSelected: null,
      listC: [],
      listConcesionarios: [
        { Codigo: 0, Nombre: "Todos" },
        { Codigo: 1, Nombre: "Sauma", Marca: 5 },
        { Codigo: 2, Nombre: "Iruña", Marca: 5 },
        { Codigo: 3, Nombre: "Amendola", Marca: 5 },
        { Codigo: 7, Nombre: "Luxcar", Marca: 5 },
        { Codigo: 4, Nombre: "AutoCervo", Marca: 2 },
        { Codigo: 5, Nombre: "AutoNet", Marca: 2 },
        { Codigo: 6, Nombre: "Car Group", Marca: 2 },
        { Codigo: 9, Nombre: "Sapac", Marca: 9 },
        { Codigo: 10, Nombre: "Alizze", Marca: 3 },
      ],
      headers: [
        {
          text: "Oficial",
          align: "center",
          value: "NomOficial",
          width: "25%",
        },

        { text: "Observaciones Totales", value: "ObsTotales", align: "center" },
        {
          text: "Observacioes Distinto GyO",
          value: "ObsDifGyO",
          align: "center",
        },
      ],
    };
  },

  created() {
    //this.getPeriodos();
  },

  computed: {
    ...mapState("reporteobservaciones", ["items", "loading", "datos"]),

    getToday() {
      return moment().format("YYYY-MM-DD");
    },

    getMin() {
      return "2020-01-01";
    },
  },

  methods: {
    ...mapActions({
      getData: "reporteobservaciones/getData",
    }),

    filterListConcesionaria(value) {
      this.codConcesSelected = null;
      this.listC = [];
      this.listC = this.listConcesionarios.filter(function (item) {
        return item.Marca === value.Codigo;
      });
    },

    setSelected(value) {
      console.log(this.codConcesSelected);
    },

    getReporte() {
      //console.log(this.codperiodo);
      var pars = {
        // Marca: this.codConcesSelected.Marca,
        // Concesionario: this.codConcesSelected.Codigo,
        FechaDesde: this.fechaDesde,
        FechaHasta: this.fechaHasta,
        api: "reporteobservaciones",
      };
      console.log(pars);
      this.getData(pars);
      //this.getData({ api: "reporteasignacion", periodo: this.codperiodo });
    },

    totalS(column, valor) {
      return valor.reduce(function (total, item) {
        // console.log(item);
        if (isNaN(item[column.value])) {
          return "";
        }
        return total + parseInt(item[column.value]);
      }, 0);
    },

    totalItem(valor) {
      return valor.reduce(function (total, item) {
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
