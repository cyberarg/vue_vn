<template>
  <v-app class="fullw">
    <v-card class="padded">
      <v-card-title>
        Reporte Compras Subite
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
              v-model.Codigo="codperiodo"
              @change="getReporte()"
            ></v-select>
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="6" md="6">
            <GridFormComponent
              :pars="{
                grid: 'Resumen',
                titleform: 'Resumen',
                routeapi: 'reportecompras',
                itemkey: 'Codigo',
                module: 'reportecompras',
                items: this.items_resumen,
                mostrarbuscar: false,
                exportable: false,
                disablepagination: true,
                disabledsort: true,
                hideheaders: false,
                loading: this.loading,
                loadingtext: this.loadingtextresumen
              }"
              :headers="[
                {
                  text: '',
                  align: 'start',
                  value: 'Nombre',
                  width: '60%'
                },
                { text: '', value: 'Cantidad', align: 'center', width: '40%' }
              ]"
            ></GridFormComponent>
          </v-col>
          <v-col cols="6" md="6">
            <GridFormComponent
              :pars="{
                grid: 'MesActual',
                titleform: 'Nuevos Casos Mes Actual',
                routeapi: 'reportecompras',
                itemkey: 'Codigo',
                module: 'reportecompras',
                items: this.items_casos_mes,
                mostrarbuscar: false,
                exportable: false,
                disablepagination: true,
                disabledsort: true,
                loading: this.loadingmes,
                loadingtext: this.loadingtextmes
              }"
              :headers="[
                {
                  text: '',
                  align: 'center',
                  value: 'Tipo'
                },
                { text: 'Casos', value: 'Casos', align: 'center' },
                { text: '%', value: 'Porcentaje', align: 'center' },
                { text: 'Monto HN', value: 'MontoHN', align: 'center' },
                { text: '%', value: 'PorcentajeHN', align: 'center' }
              ]"
            ></GridFormComponent>
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12" md="12">
            <GridFormComponent
              :pars="{
                grid: 'Universo',
                titleform: 'Universo Compra',
                routeapi: 'reportecompras',
                itemkey: 'Codigo',
                module: 'reportecompras',
                items: this.items_universo,
                mostrarbuscar: false,
                exportable: false,
                disablepagination: true,
                disabledsort: true,
                loading: this.loadinguniv,
                loadingtext: this.loadingtextuniv
              }"
              :headers="[
                {
                  text: '',
                  align: 'center',
                  value: 'Tipo'
                },
                { text: 'Casos', value: 'Casos', align: 'center' },
                { text: '%', value: 'Porcentaje', align: 'center' },
                { text: 'Monto HN', value: 'MontoHN', align: 'center' },
                { text: '%', value: 'Porcentaje', align: 'center' }
              ]"
            ></GridFormComponent>
          </v-col>
        </v-row>
      </div>
    </v-card>
  </v-app>
</template>

<script>
import GridFormComponent from "@/components/propios/NewGridFormComponent.vue";
//import GridFormCrud from "@/components/propios/GridFormCrud.vue";
import GridFormDatosComponent from "@/components/propios/GridFormComponent.vue";
import moment from "moment";
import { mapState, mapActions } from "vuex";

export default {
  name: "reportecompras",
  components: {
    GridFormComponent,
    GridFormDatosComponent
    //GridFormCrud
  },
  data() {
    return {
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
          text: "Tipo",
          align: "center",
          value: "Nombre"
        },
        { text: "Cantidad", value: "Cantidad", align: "center" }
      ],
      headers23: [
        {
          text: "Tipo",
          align: "center",
          value: "Tipo"
        },
        { text: "Casos", value: "Casos", align: "center" },
        { text: "%", value: "Porcentaje", align: "center" },
        { text: "Monto HN", value: "MontoHN", align: "center" },
        { text: "%", value: "Porcentaje", align: "center" }
      ]
    };
  },

  computed: {
    ...mapState("reporteacompras", [
      "items_resumen",
      "items_casos_mes",
      "items_universo",
      "loading",
      "loadingtextresumen",
      "loadingmes",
      "loadingtextmes",
      "loadinguniv",
      "loadingtextuniv",
      "items_filtrados",
      "mostrarInfo"
    ])
  },

  created() {
    this.getPeriodos();
  },

  methods: {
    ...mapActions({
      getResumen: "reporteacompras/getResumen"
    }),

    getReporte() {
      //console.log(this.codperiodo);
      this.getResumen(this.codperiodo);
    },

    getPeriodos() {
      var currentDate = new Date();
      var initialDate = new Date(currentDate.getFullYear() - 1, 0, 1);

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
  height: 100%;
}

.fullw {
  width: 100%;
}
</style>
