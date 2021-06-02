<template>
  <v-app class="fullw">
    <v-card class="paddedb" color="grey lighten-4">
      <v-card-title>
        Reporte Cartera
        <v-divider class="mx-4" inset vertical></v-divider>
      </v-card-title>
      <div>
        <template v-if="!esConcesionario">
          <v-row justify="end">
            <v-col cols="3" md="3">
              <v-combobox
                item-text="Nombre"
                item-value="Codigo"
                :items="listMarcas"
                label="Marca"
                :value="codMarcaSelected"
                @change="filterListConcesionaria"
                class="pad"
              ></v-combobox>
            </v-col>
            <v-col cols="3" md="3">
              <v-combobox
                item-text="Nombre"
                item-value="Codigo"
                :items="listC"
                label="Concesionario"
                v-model="codConcesSelected"
                @change="filterConcesionaria"
              ></v-combobox>
            </v-col>

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
          </v-row>
        </template>
        <template v-else>
          <v-row justify="center">
            <v-col cols="4" md="4">
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
          </v-row>
        </template>

        <v-row v-show="showTable">
          <v-col cols="6" md="6">
            <NewGridFormComponent
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
                loadingtext: this.loadingtextresumen,
              }"
              :headers="[
                {
                  text: '',
                  align: 'start',
                  value: 'Nombre',
                  width: '60%',
                },
                { text: '', value: 'Cantidad', align: 'center', width: '40%' },
              ]"
            ></NewGridFormComponent>
          </v-col>
          <v-col cols="6" md="6">
            <NewGridFormComponent
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
                loadingtext: this.loadingtextmes,
              }"
              :headers="[
                {
                  text: '',
                  align: 'center',
                  value: 'Tipo',
                },
                { text: 'Casos', value: 'Casos', align: 'center' },
                { text: '%', value: 'Porcentaje', align: 'center' },
                { text: 'Monto HN', value: 'MontoHN', align: 'center' },
                { text: '%', value: 'PorcentajeHN', align: 'center' },
              ]"
            ></NewGridFormComponent>
          </v-col>
        </v-row>

        <v-row v-show="showTable">
          <v-col cols="12" md="12">
            <NewGridFormComponent
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
                loadingtext: this.loadingtextuniv,
              }"
              :headers="[
                {
                  text: '',
                  align: 'center',
                  value: 'Tipo',
                },
                { text: 'Casos', value: 'Casos', align: 'center' },
                { text: '%', value: 'Porcentaje', align: 'center' },
                { text: 'Monto HN', value: 'MontoHN', align: 'center' },
                { text: '%', value: 'Porcentaje', align: 'center' },
              ]"
            ></NewGridFormComponent>
          </v-col>
        </v-row>
      </div>
    </v-card>
  </v-app>
</template>

<script>
import NewGridFormComponent from "@/components/propios/NewGridFormComponent.vue";

import moment from "moment";
import { mapState, mapActions } from "vuex";

export default {
  name: "reportecompras",
  components: {
    NewGridFormComponent,
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
        "Diciembre",
      ],
      headers: [
        {
          text: "Tipo",
          align: "center",
          value: "Nombre",
        },
        { text: "Cantidad", value: "Cantidad", align: "center" },
      ],
      headers23: [
        {
          text: "Tipo",
          align: "center",
          value: "Tipo",
        },
        { text: "Casos", value: "Casos", align: "center" },
        { text: "%", value: "Porcentaje", align: "center" },
        { text: "Monto HN", value: "MontoHN", align: "center" },
        { text: "%", value: "Porcentaje", align: "center" },
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
    };
  },

  computed: {
    disableButton() {
      return (
        this.codperiodo == "" ||
        this.codConcesSelected == null ||
        this.codMarcaSelected == null
      );
    },

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
      "showTable",
    ]),

    ...mapState("auth", [
      "login",
      "user",
      "esConcesionario",
      "esVinculo",
      "codigoConcesionario",
    ]),
  },

  created() {
    this.getPeriodos();
  },

  mounted() {
    this.checkEsConcesionario();
  },

  methods: {
    ...mapActions({
      getResumen: "reporteacompras/getResumen",
    }),

    filterListConcesionaria(value) {
      //console.log(value);
      this.codMarcaSelected = value;
      this.codConcesSelected = null;
      this.listC = [];
      var todos = {
        Codigo: 0,
        Nombre: "Todos",
        Marca: this.codMarcaSelected.Codigo,
      };
      this.listC = this.listConcesionarios.filter(function (item) {
        return item.Marca === value.Codigo;
      });
      this.listC.unshift(todos);
    },

    filterConcesionaria(value) {
      //console.log(value);
      this.codConcesSelected = value;
    },

    checkEsConcesionario() {
      if (this.esConcesionario) {
        var codC = parseInt(this.codigoConcesionario);
        console.log(codC);
        var itemC = {};
        itemC = this.listConcesionarios.find(function (item) {
          return item.Codigo === codC;
        });
        this.codConcesSelected = itemC;
        this.codMarcaSelected = itemC.Marca;
      }
      if (this.esVinculo) {
        this.listMarcas.splice(0, 1);
      }
    },

    getReporte() {
      //console.log(this.codperiodo);

      if (this.esConcesionario) {
        if (
          typeof this.codperiodo != "undefined" &&
          typeof this.codConcesSelected.Marca != "undefined" &&
          typeof this.codConcesSelected.Codigo != "undefined"
        ) {
          var params = {
            periodo: this.codperiodo,
            marca: this.codConcesSelected.Marca,
            concesionario: this.codConcesSelected.Codigo,
          };
          console.log(params);
          this.getResumen(params);
        }
      } else {
        if (
          typeof this.codperiodo != "undefined" &&
          typeof this.codMarcaSelected.Codigo != "undefined" &&
          typeof this.codConcesSelected.Codigo != "undefined"
        ) {
          var params = {
            periodo: this.codperiodo,
            marca: this.codMarcaSelected.Codigo,
            concesionario: this.codConcesSelected.Codigo,
          };
          console.log(params);
          this.getResumen(params);
        }
      }
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

.pad {
  padding-left: 10px;
  padding-right: 10px;
}

.padded {
  padding-left: 20px;
  padding-right: 20px;
  height: 100%;
}

.paddedb {
  padding-bottom: 20px;
}

.fullw {
  width: 100%;
  height: 100vh;
}
</style>
