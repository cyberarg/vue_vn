<template>
  <v-app class="fullw">
    <v-card class="padded" color="grey lighten-4">
      <v-card-title>
        Asignaciones por Período
        <v-divider class="mx-4" inset vertical></v-divider>
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
        ></v-combobox>
        <v-spacer></v-spacer>
        <v-combobox
          item-text="Nombre"
          item-value="Codigo"
          :items="itemsPeriodos"
          label="Periodos"
          v-model="codperiodo"
          @change="getAsignaciones()"
        ></v-combobox>
        <v-btn
          class="ma-2"
          outlined
          color="blue darken-1"
          text
          @click="getAsignaciones()"
        >
          <v-icon left>mdi-refresh</v-icon>Actualizar
        </v-btn>
      </v-card-title>
      <div class="card-body">
        <v-row v-show="showTable">
          <v-col cols="12" md="12">
            <GridSummaryFormAsignacionesComponent
              :pars="{
                titleform: 'Reporte Asignaciones por Período',
                routeapi: 'reporteasignacion',
                itemkey: 'Codigo',
                module: 'reporteasignacion',
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
    GridSummaryFormAsignacionesComponent,
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
        "Diciembre",
      ],
      codMarcaSelected: null,
      listMarcas: [
        { Codigo: 2, Nombre: "Fiat" },
        { Codigo: 5, Nombre: "Volkswagen" },
        { Codigo: 9, Nombre: "Ford" },
        { Codigo: 3, Nombre: "Peugeot" },
        { Codigo: 7, Nombre: "Jeep" },
        { Codigo: 10, Nombre: "Citroen" },
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
        { Codigo: 12, Nombre: "Datos Web - Peugeot", Marca: 3 },
        { Codigo: 13, Nombre: "Datos Web - Fiat", Marca: 2 },
        { Codigo: 14, Nombre: "Datos Web - Jeep", Marca: 7 },
        { Codigo: 15, Nombre: "Datos Web - Volkswagen", Marca: 5 },
        { Codigo: 16, Nombre: "Datos Web - Ford", Marca: 9 },
        { Codigo: 17, Nombre: "Datos Web - Citroen", Marca: 10 },
      ],
      headers: [
        {
          text: "Oficial",
          align: "center",
          value: "NomOficial",
          width: "25%",
        },
        { text: "Asignados", value: "Asignados", align: "center" },
        {
          text: "Sin Gestionar",
          value: "SinGestionar",
          align: "center",
        },
        {
          text: "Telefono Mal",
          value: "TelefonoMal",
          align: "center",
        },
        {
          text: "Deje Mensaje",
          value: "DejeMensaje",
          align: "center",
        },
        {
          text: "Entrevista Pendiente",
          value: "EntrevistaPendiente",
          align: "center",
        },
        {
          text: "En Gestión",
          value: "EnGestion",
          align: "center",
        },
        {
          text: "No le Interesa",
          value: "NoLeInteresa",
          align: "center",
        },
        {
          text: "Vende Plan",
          value: "VendePlan",
          align: "center",
        },
        {
          text: "Plan Subite",
          value: "PlanSubite",
          align: "center",
        },
        {
          text: "Pasar a Venta",
          value: "PasarAVenta",
          align: "center",
        },
        {
          text: "En Otro Oficial",
          value: "EnOtroOficial",
          align: "center",
        },
      ],
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
      "showTable",
    ]),
  },

  methods: {
    ...mapActions({
      getData: "reporteasignacion/getData",
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

    getAsignaciones() {
      //console.log(this.codperiodo);
      var pars = {
        Marca: this.codConcesSelected.Marca,
        Concesionario: this.codConcesSelected.Codigo,
        periodo: this.codperiodo.Codigo,
        api: "reporteasignacion",
      };

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
