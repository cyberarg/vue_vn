<template>
  <v-app class="contenedor">
    <div class="card">
      <v-card-title>
        <template v-if="!esConcesionario">
          <v-row justify="end">
            <v-col lg="4" md="4" sm="4" xs="12">
              <v-combobox
                label="Marca"
                item-text="Nombre"
                item-value="Codigo"
                :items="listMarcas"
                :value="codMarcaSelected"
                @change="filterListConcesionaria"
              ></v-combobox>
            </v-col>
            <v-col lg="4" md="4" sm="4" xs="12">
              <v-combobox
                item-text="Nombre"
                item-value="Codigo"
                :items="listC"
                label="Concesionario"
                v-model="codConcesSelected"
                @change="getModelo"
              ></v-combobox>
            </v-col>
            <v-col lg="2" md="2" sm="2" xs="12">
              <v-btn
                class="ma-2"
                outlined
                color="blue darken-1"
                text
                @click="getModelo()"
              >
                <v-icon left>mdi-refresh</v-icon>Actualizar
              </v-btn>
            </v-col>
          </v-row>
        </template>
        <template v-else>
          <v-row justify="end">
            <v-col md="2">
              <v-btn
                class="ma-2"
                outlined
                color="blue darken-1"
                text
                @click="getModelo()"
              >
                <v-icon left>mdi-refresh</v-icon>Actualizar
              </v-btn>
            </v-col>
          </v-row>
        </template>
      </v-card-title>

      <v-progress-linear
        indeterminate
        color="primary"
        v-show="this.loading"
      ></v-progress-linear>

      <div class="card-body">
        <v-tabs v-model="tab" class="elevation-2" grow>
          <template v-if="generarModelo">
            <v-tab v-for="tab in tabitemshide" :key="tab.Codigo">
              {{ tab.Nombre }}
            </v-tab>
          </template>
          <template v-else>
            <v-tab v-for="tab in tabitems" :key="tab.Codigo">
              {{ tab.Nombre }}
            </v-tab>
          </template>
        </v-tabs>
        <v-tabs-items v-model="tab" class="maxH">
          <!-- <v-tab-item v-for="tab in tabitems" :key="tab.Codigo">-->
          <v-tab-item class="maxH">
            <v-card flat>
              <template v-if="generarModelo">
                <FormCrearModeloHN
                  :pars="{
                    titleform: 'Generar Modelo Haberes Netos',
                    routeapi: 'modelohn',
                    module: 'modelohn',
                  }"
                  :items="{
                    item: datos,
                  }"
                  :disableGuardar="this.disableGuardar"
                ></FormCrearModeloHN>
              </template>
              <template v-else>
                <FormModeloHN
                  :pars="{
                    titleform: 'Modelo Haberes Netos',
                    routeapi: 'modelohn',
                    module: 'modelohn',
                  }"
                  :items="{
                    item: datos,
                  }"
                ></FormModeloHN>
              </template>
            </v-card>
          </v-tab-item>
          <v-tab-item class="maxH">
            <v-card flat>
              <FormCumplimientoHN
                :pars="{
                  titleform: 'Cumplimiento Modelo',
                  routeapi: 'modelohn',
                  module: 'cumplimientohn',
                }"
                :items="{
                  item: datos,
                }"
              ></FormCumplimientoHN>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </div>
    </div>
  </v-app>
</template>

<script>
import FormCrearModeloHN from "@/components/propios/FormCrearModeloHN.vue";
import FormModeloHN from "@/components/propios/FormModeloHN.vue";
import FormCumplimientoHN from "@/components/propios/FormCumplimientoHN.vue";
import { mapState, mapActions } from "vuex";

export default {
  name: "modelohn",
  components: {
    FormModeloHN,
    FormCumplimientoHN,
    FormCrearModeloHN,
  },

  data() {
    return {
      tab: null,
      disableGuardar: false,
      tabitems: [
        { Codigo: 1, Nombre: "Modelo" },
        { Codigo: 2, Nombre: "Cumplimiento" },
      ],
      tabitemshide: [{ Codigo: 1, Nombre: "Nuevo Modelo" }],
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

  created() {
    //this.getModelo();
  },

  computed: {
    ...mapState("modelohn", [
      "loading",
      "dataStatus",
      "datos",
      "generarModelo",
    ]),
    ...mapState("auth", [
      "login",
      "user",
      "esConcesionario",
      "esVinculo",
      "codigoConcesionario",
    ]),
  },

  mounted() {
    this.checkEsConcesionario();
  },

  methods: {
    ...mapActions({
      getModeloControl: "modelohn/getModeloControl",
    }),

    filterListConcesionaria(value) {
      this.codConcesSelected = null;
      this.listC = [];
      this.listC = this.listConcesionarios.filter(function (item) {
        return item.Marca === value.Codigo;
      });
    },

    getModelo() {
      if (typeof this.codConcesSelected.Codigo !== "undefined") {
        var pars = {
          Marca: this.codConcesSelected.Marca,
          Concesionario: this.codConcesSelected.Codigo,
        };

        this.getModeloControl(pars);
      }
      return;
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

        this.getModelo();
      } else {
        if (this.esVinculo) {
          this.disableGuardar = true;
          this.listMarcas.splice(0, 1);
        }
      }
    },
  },
};
</script>

<style scoped>
.contenedor {
  width: 100%;
  background-color: #eeeeee;
}

.maxH {
  width: 100%;
}
</style>
