<template>
  <div>
    <h3></h3>
    <v-card color="grey lighten-4">
      <v-card-title>
        {{ pars.titleform }}
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

        <v-btn class="ma-2" color="primary" outlined text @click="getDatos()">
          <v-icon left>mdi-refresh</v-icon>Actualizar
        </v-btn>
        <!--<v-switch v-model="showOficiales" label="Cbo Oficiales" class="mt-2"></v-switch>-->
      </v-card-title>
      <v-row class="padded">
        <v-col cols="4">
          <template>
            <v-combobox
              v-if="showOficiales"
              v-model="codOficialSelected"
              item-text="Nombre"
              item-value="Codigo"
              :items="listOficiales"
              label="Oficial"
            ></v-combobox>
            <v-combobox
              v-else
              v-model="codSupervisorSelected"
              item-text="Nombre"
              item-value="Codigo"
              :items="listSupervisores"
              label="Supervisor"
            ></v-combobox>
          </template>
        </v-col>
        <v-col cols="4">
          <v-btn
            class="ma-2"
            small
            :disabled="disablePasarSinGestion"
            outlined
            text
            @click="pasarSinGestionar()"
          >
            <v-icon left>mdi-undo-variant</v-icon>Pasar a Sin Gestionar
          </v-btn>
          <v-btn
            class="ma-2"
            small
            :disabled="disableAsignar"
            outlined
            text
            @click="asignarOficial()"
          >
            <v-icon left>{{ userIcon }}</v-icon>
            Asignar {{ getTextAsignacion }}
          </v-btn>
        </v-col>
        <v-col cols="4">
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Buscar"
            single-line
            hide-details
          ></v-text-field>
        </v-col>
      </v-row>

      <v-data-table
        fixed-header
        height="50vh"
        dense
        :headers="computedHeaders"
        :items="items"
        :item-class="setClass"
        :search="search"
        :items-per-page="50"
        item-key="ID"
        class="elevation-1"
        :loading="loading"
        loading-text="Cargando Datos... Aguarde"
        no-data-text="No hay datos disponibles"
        show-select
        v-model="selected"
        :single-select="singleSelect"
      >
        <template v-slot:item.GrupoOrden="{ item }"
          >{{ item.Grupo }}-{{ item.Orden }}</template
        >

        <template v-slot:item.ApeNom="{ item }"
          >{{ item.Apellido }}, {{ item.Nombres }}</template
        >
        <template v-slot:item.HaberNeto="{ item }"
          >${{ Math.round(item.HaberNeto) | numFormat }}</template
        >
        <template v-slot:item.PrecioMaximoCompra="{ item }">
          {{ getPrecioMaxCompra(item.Avance, item.HaberNeto) }}
        </template>

        <template v-slot:item.FechaVtoCuota2="{ item }">
          {{ formatFecha(item.FechaVtoCuota2) }}
        </template>

        <template v-slot:item.FechaUltObs="{ item }">
          {{ formatFecha(item.FechaUltObs) }}
        </template>
        <template v-slot:item.VerDatos="{ item }">
          <v-btn text @click="getDato(item)">
            <v-icon left>mdi-text-search</v-icon>Ver Dato
          </v-btn>
        </template>

         <template v-slot:item.EsDatoNuevo="{ item }">
        </template>
        

        <template v-slot:body.append="{ headers }">
          <td colspan="4"></td>
          <td>${{ Math.round(getCountTotalHN) | numFormat }}</td>
          <td>Total = {{ getCountTotal }}</td>
          <td>Sel = {{ getCountSelected }}</td>
        </template>
        <!--
        <template v-slot:top>
          <v-toolbar flat>
            <v-spacer></v-spacer>
            <v-switch
              v-model="showSinOficial"
              label="Filtrar Sin Asignar"
              class="mt-2"
            ></v-switch>
          </v-toolbar>
        </template>
        -->

        <template v-slot:top>
          <v-expansion-panels focusable>
            <v-expansion-panel>
              <v-expansion-panel-header> Filtros </v-expansion-panel-header>
              <v-expansion-panel-content>
                <v-container fluid>
                  <v-row>
                    <v-col cols="3">
                      <v-row class="pa-6">
                        <v-combobox
                          v-model="oficialFilterValue"
                          item-text="Nombre"
                          item-value="Codigo"
                          :items="listOficialesFilter"
                          label="Oficial"
                        ></v-combobox>
                      </v-row>
                    </v-col>
                    <v-col cols="3">
                      <v-row class="pa-6">
                        <v-select
                          :items="statesList"
                          v-model="stateFilterValue"
                          label="Estado"
                        ></v-select>
                      </v-row>
                    </v-col>
                    <v-col cols="2">
                      <v-row class="pa-10">
                        <v-slider
                          label="Avance"
                          v-model="sliderAvance"
                          thumb-label="always"
                          max="83"
                          min="45"
                        ></v-slider>
                      </v-row>
                    </v-col>
                    <v-col cols="2">
                      <v-row class="pa-10">
                        <v-text-field
                          label="Haber Neto"
                          v-model="sliderHaberNeto"
                          class="mt-0 pt-0"
                          type="number"
                        ></v-text-field>
                      </v-row>
                    </v-col>
                    <v-col cols="2">
                      <v-row class="pa-10">
                        <v-switch
                          label="Solo Datos Nuevos"
                          v-model="solodatonuevo"
                          class="mt-0 pt-0"
                        ></v-switch>
                      </v-row>
                    </v-col>
                  </v-row>
                </v-container>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
        </template>
      </v-data-table>

      <v-card-actions>
        <v-btn
          cclass="ma-2"
          color="primary"
          :disabled="disablePasarSinGestion"
          outlined
          text
          @click="recycleDatos"
        >
          <v-icon left>mdi-recycle-variant</v-icon>Reciclar
        </v-btn>
        <v-spacer></v-spacer>

        <v-btn cclass="ma-2" color="success" outlined text @click="exportExcel">
          <v-icon left>mdi-file-excel-outline</v-icon>Excel
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>
<script>
import { mapState, mapActions } from "vuex";
import XLSX from "xlsx";
import moment from "moment";

export default {
  props: {
    pars: {
      type: Object,
      required: true,
    },
    headers: {
      type: Array,
      required: true,
    },
  },

  data() {
    return {
      // Filter models.
      oficialFilterValue: "",
      stateFilterValue: null,
      sliderAvance: 45,
      sliderHaberNeto: 15000,
      solodatonuevo:false,
      showSinOficial: false,
      codOficialSelected: null,
      codSupervisorSelected: null,
      textAsignacion: "Oficial",
      userIcon: "mdi-account-check-outline",
      showOficiales: true,
      singleSelect: false,
      selected: [],
      listOficialesFilter: [],
      search: "",
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

      statesList: [
        { text: "Todos", value: 0 },
        { text: "Sin Gestionar", value: "null" },
        { text: "Telefono Mal", value: "Telefono Mal" },
        { text: "Deje Mensaje", value: "Deje Mensaje" },
        { text: "Entrevista Pendiente", value: "Entrevista Pendiente" },
        { text: "No le interesa", value: "No le interesa" },
        { text: "Vende Plan", value: "Vende el Plan" },
        { text: "En Gestión", value: "En Gestion" },
      ],
    };
  },

  created() {
    //this.$store.dispatch(this.module + "/getData", this.api);
    this.getCombos();
  },
  /*
  watch: {
    showSinOficial(newValue) {
      //called whenever switch1 changes
      this.refreshGrid();
    },
  },
*/
  computed: {
    disableAsignar() {
      if (this.showOficiales) {
        if (this.codOficialSelected != null && this.selected.length > 0) {
          return false;
        } else {
          return true;
        }
      } else {
        if (this.codSupervisorSelected != null && this.selected.length > 0) {
          return false;
        } else {
          return true;
        }
      }
    },

    disablePasarSinGestion() {
      if (this.selected.length > 0) {
        return false;
      } else {
        return true;
      }
    },

    api() {
      return this.pars.routeapi;
    },
    module() {
      return this.pars.module;
    },

    getCountSelected() {
      return this.selected.length;
    },

    getCountTotal() {
      return this.items.length;
    },

    getCountTotalHN() {
      return this.items.reduce(function (total, item) {
        //var itemName = column.value;
        //console.log(itemName);
        if (isNaN(item["HaberNeto"])) {
          return "";
        }
        return total + parseInt(item["HaberNeto"]);
      }, 0);
    },

    getTextAsignacion() {
      if (this.showOficiales) {
        this.userIcon = "mdi-account-check-outline";
        return "Oficial";
      }
      this.userIcon = "mdi-account-tie";
      return "Supervisor";
    },

    computedHeaders() {
      //return this.headers.filter((header) => header.text !== "ID");

      //return this.headers_2.filter((headers_2) => headers_2.text !== "ID");

      return this.headers_2.filter((headers_2) => headers_2.ocultar !== true);
      /*
      return this.headers_2.filter(
        ((headers_2) => headers_2.text !== "ID") &&
          ((headers_2) => headers_2.text !== "CodEstado")
      );*/
    },

    headers_2() {
      return [
        {
          text: "Supervisor",
          value: "NomSup",
          align: "left",
          filterable: false,
        },
        {
          text: "Grupo Orden",
          value: "GrupoOrden",
          align: "center",
          filterable: true,
        },
        {
          text: "Solicitud",
          value: "Solicitud",
          align: "center",
          width: "1%",
          filterable: false,
        },
        {
          text: "Haber Neto",
          value: "HaberNeto",
          align: "center",
          width: "1%",
          filterable: true,
          filter: this.filterHaberNeto,
        },
        {
          text: "Nombre y Apellido",
          value: "ApeNom",
          align: "left",
          filterable: false,
        },
        { text: "ID", value: "ID", width: "1%", ocultar: true },

        {
          text: "Fecha Vto Cuota 2",
          value: "FechaVtoCuota2",
          align: "center",
          filterable: false,
        },
        {
          text: "Avance",
          value: "Avance",
          align: "center",
          width: "1%",
          filter: this.avanceFilter,
          filterable: true,
        },
        {
          text: "Avance Calculado",
          value: "AvanceCalculado",
          align: "center",
          width: "1%",
          filterable: false,
        },
        {
          text: "Oficial",
          value: "NomOficial",
          align: "center",
          filterable: true,
          filter: this.nameFilter,
        },

        {
          text: "Estado",
          value: "NomEstado",
          align: "left",
          filterable: true,
          filter: this.stateFilter,
        },
        {
          text: "Precio Max Compra",
          value: "PrecioMaximoCompra",
          align: "center",
          width: "1%",
          filterable: false,
        },
        {
          text: "Fecha Ult. Obs",
          value: "FechaUltObs",
          align: "center",
          width: "1%",
          filterable: false,
        },
       
        { text: "", value: "VerDatos", align: "end", width: "1%" , sortable: false},
         {
          text: "",
          value: "EsDatoNuevo",
          align: "center",
          filterable: true,
          filter: this.filterSoloDatoNuevo,
          width:"0%",
          sortable: false
        },
      ];
    },

    ...mapState("auth", ["login"]),
    ...mapState("asignaciondatos", [
      "items",
      "items_totales",
      "loading",
      "listOficiales",
      "listSupervisores",
      "unselect",
    ]),
  },

  methods: {
    /*
    refreshGrid() {
      this.filterSinAsignar(this.showSinOficial);
    },
  */

    async getCombos() {
      await this.$store.dispatch(this.module + "/loadCombos");
      if (!this.loading) {
        this.listOficialesFilter = this.listOficiales;
        this.listOficialesFilter.unshift({ Codigo: -1, Nombre: "Sin Oficial" });
        this.listOficialesFilter.unshift({ Codigo: 0, Nombre: "Todos" });
      }
    },

    filterSoloDatoNuevo(value){
      if (this.solodatonuevo) {
        return value === "1";
      }
      return true;
    },

    filterHaberNeto(value) {
      if (!this.sliderHaberNeto) {
        return true;
      }
      return parseInt(value) >= this.sliderHaberNeto;
    },

    nameFilter(value) {
      // If this filter has no value we just skip the entire filter.
      if (
        !this.oficialFilterValue ||
        this.oficialFilterValue.Nombre === "Todos"
      ) {
        return true;
      }
      // Check if the current loop value (The dessert name)
      // partially contains the searched word.
      /*
      if (value !== null) {
        return value
          .toLowerCase()
          .includes(this.oficialFilterValue.toLowerCase());
      }
      */

      if (this.oficialFilterValue.Codigo === -1) {
        return value === null;
      }

      if (value !== null) {
        return value === this.oficialFilterValue.Nombre;
      }
    },

    stateFilter(value) {
      // If this filter has no value we just skip the entire filter.
      if (!this.stateFilterValue || this.stateFilterValue == 0) {
        return true;
      }

      if (this.stateFilterValue === "null") {
        return value === null;
      }
      // Check if the current loop value (The calories value)
      // equals to the selected value at the <v-select>.
      return value === this.stateFilterValue;
    },

    avanceFilter(value) {
      if (!this.sliderAvance) {
        return true;
      }
      return parseInt(value) >= this.sliderAvance;
    },

    exportExcel: function () {
      let data = XLSX.utils.json_to_sheet(this.items);
      const workbook = XLSX.utils.book_new();
      const filename = "Asignacion-Datos";
      XLSX.utils.book_append_sheet(workbook, data, filename);
      XLSX.writeFile(workbook, `${filename}.xlsx`);
    },

    filterListConcesionaria(value) {
      this.codConcesSelected = null;
      this.listC = [];
      this.listC = this.listConcesionarios.filter(function (item) {
        return item.Marca === value.Codigo;
      });
    },

    getDatos() {
      var pars = {
        Marca: this.codConcesSelected.Marca,
        Concesionario: this.codConcesSelected.Codigo,
      };
      this.$store.dispatch(this.module + "/getDatos", pars);
      //this.setColection(this.items);
    },

    setSelected(value) {
      console.log(this.codConcesSelected);
    },

    getPrecioMaxCompra(avance, haberNeto) {
      var av = parseInt(avance);
      var hn = parseInt(haberNeto);
      var pmax = 0;

      switch (true) {
        case av >= 45 && av <= 61:
          pmax = hn * 0.2;
          break;
        case av >= 62 && av <= 66:
          pmax = hn * 0.3;
          break;
        case av >= 67 && av <= 69:
          pmax = hn * 0.35;
          break;
        case av >= 70 && av <= 79:
          pmax = hn * 0.4;
          break;
        case av >= 80 && av <= 83:
          pmax = hn * 0.5;
          break;
        default:
          pmax = 0;
          break;
      }

      if (pmax != 0) {
        return "$" + this.$options.filters.numFormat(pmax);
      }
      return "-";
    },

    /*
    filterConcesionaria(value) {
      this.filterData(value.Codigo);
    },
*/

    filterConcesionaria() {
      //console.log(this.codConcesSelected.Codigo);
      this.filterData(this.codConcesSelected.Codigo);
    },

    async pasarSinGestionar() {
      console.log(this.selected);
      if (this.selected.length > 0) {
        var params = {
          data: this.selected,
        };
        //console.log(params);
        await this.pasarASinGestionar(params);
        if (this.unselect) {
          this.selected = [];
          var pars = {
            Marca: this.codConcesSelected.Marca,
            Concesionario: this.codConcesSelected.Codigo,
          };
          this.$store.dispatch(this.module + "/getDatos", pars);
        }
      }
    },

    async recycleDatos() {
      console.log("Entro en Recycle");
      //console.log(this.selected);
      if (this.selected.length > 0) {
        var params = {
          data: this.selected,
        };
        console.log(params);
        await this.recycleDato(params);
        if (this.unselect) {
          this.selected = [];
          var pars = {
            Marca: this.codConcesSelected.Marca,
            Concesionario: this.codConcesSelected.Codigo,
          };
          this.$store.dispatch(this.module + "/getDatos", pars);
        }
      }
    },

    async asignarOficial() {
      if (this.selected.length > 0) {
        if (this.showOficiales) {
          this.codSupervisorSelected = 0;
        } else {
          this.codOficialSelected = 0;
        }

        var params = {
          data: this.selected,
          oficial: this.codOficialSelected,
          supervisor: this.codSupervisorSelected,
          concesionario: this.codConcesSelected.Codigo,
          marca: this.codConcesSelected.Marca,
          login: this.login,
        };
        console.log(params);
        await this.asignarDatos(params);
        if (this.unselect) {
          this.selected = [];
          var pars = {
            Marca: this.codConcesSelected.Marca,
            Concesionario: this.codConcesSelected.Codigo,
          };
          this.$store.dispatch(this.module + "/getDatos", pars);
        }
      }
    },

    formatFecha(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM/YYYY");
      }
    },

      setClass(item) {
      //console.log(item);

      if (item.EsDatoNuevo == "1") {
        return "classDatoNuevo"; //NARANJA
      } else {
        if (
          (item.CodEstado == "0" || item.CodEstado == null) &&
          parseInt(item.AvanceAutomatico) >= 75 &&
          parseInt(item.AvanceAutomatico) <= 83
        ) {
          if (parseInt(item.AvanceAutomatico) == 83) {
            return "classDatoLimite"; //VERDE OSCURO
          } else {
            return "classDatoEntreLimites"; //VERDE CLARO
          }
        } else {
          return "";
        }
      }
    },

    getDato(item) {
      // this.mostrarDato(item.ID);
      //console.log(item.ID);
      this.$router.push({
        name: "detalledato",
        params: {
          id: item.ID,
          Marca: item.Marca,
          Concesionario: item.Concesionario,
          modulo: "gestiondatos",
        },
      });
    },

    ...mapActions({
      setColection: "gestiondatos/setColection",
      mostrarDato: "gestiondatos/mostrarDato",
      asignarDatos: "asignaciondatos/asignarDatos",
      pasarASinGestionar: "asignaciondatos/pasarASinGestionar",
      recycleDato: "asignaciondatos/recycleDato",
      filterData: "asignaciondatos/filterData",
      filterSinAsignar: "asignaciondatos/filterSinAsignar",
    }),
  },


};
</script>
<style scoped>

.table-header thead {
  background-color: black;
}

.v-data-table >>> .classDatoNuevo {
  background: #f3be69;
}

.v-data-table >>> .classDatoLimite {
  background: #57b479;
}

.v-data-table >>> .classDatoEntreLimites {
  background: #bae5ca;
}

.classDatoNuevo {
  background: #f3be69;
}

.classDatoLimite {
  background: #57b479;
}

.classDatoEntreLimites {
  background: #bae5ca;
}

.fullw {
  width: 100%;
}

.padded {
  padding-left: 10px;
  padding-right: 10px;
}

.v-data-table td {
  font-size: 16px;
  font-weight: bold;
  text-align: center;
}
</style>
