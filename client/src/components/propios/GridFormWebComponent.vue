<template>
  <div>
    <v-card color="grey lighten-4">
      <v-card-title>
        {{ pars.titleform }}
        <v-divider class="mx-4" inset vertical></v-divider>
        <!--
        <v-combobox
          v-show="mostrarCombo"
          item-text="Nombre"
          item-value="Codigo"
          :items="listConcesionarios"
          label="Concesionario"
          :value="codConcesSelected"
          @change="filterConcesionaria"
        ></v-combobox>
        -->
        <v-spacer></v-spacer>
        <!--
        <template v-if="!esConcesionario">
          <v-row class="padded">
            <v-col cols="3">
              <v-combobox
                v-show="mostrarCombo"
                item-text="Nombre"
                item-value="Codigo"
                :items="listMarcas"
                label="Marca"
                :value="codMarcaSelected"
                @change="filterListConcesionaria"
                class="padded"
              ></v-combobox>
            </v-col>
            <v-col cols="3">
              <v-combobox
                v-show="mostrarCombo"
                item-text="Nombre"
                item-value="Codigo"
                :items="listC"
                label="Concesionario"
                v-model="codConcesSelected"
                @change="filterConcesionaria"
              ></v-combobox>
            </v-col>
            -->
            <v-col cols="3">
              <v-text-field
                v-show="mostrarbuscar"
                v-model="search"
                append-icon="mdi-magnify"
                label="Buscar"
                single-line
                hide-details
              ></v-text-field>
            </v-col>
          </v-row>
        </template>
        <template v-else>
          <v-text-field
            v-show="mostrarbuscar"
            v-model="search"
            append-icon="mdi-magnify"
            label="Buscar"
            single-line
            hide-details
          ></v-text-field>
        </template>
        <v-btn class="ma-2" color="primary" outlined text @click="getDatos()">
          <v-icon left>mdi-refresh</v-icon>Actualizar
        </v-btn>
      </v-card-title>

      <v-data-table
        dense
        fixed-header
        height="58vh"
        :headers="computedHeaders"
        :items="items"
        :item-class="setClass"
        :search="search"
        item-key="pars.itemkey"
        :items-per-page="cantItems"
        class="elevation-1"
        :loading="loadingDatos"
        loading-text="Cargando Datos... Aguarde"
        no-data-text="No hay datos disponibles"
      >
        <!--
        <template v-slot:item="{ item, headers }">
          <template v-if="(pars.origen = 'gestiondatos')">
            <tr :class="setClass(item)">
         
              <td align="center" width="1%">{{ item.Grupo }}-{{ item.Orden }}</td>
              <td align="center">{{ getTextConc(item.Concesionario) }}</td>
              <td align="center">${{ Math.round(item.HaberNeto) | numFormat }}</td>
              <td align="start">{{ item.ApeNom }}</td>
   
              <td align="center">{{ item.Avance }}</td>
              <td align="left">{{ getTextEstado(item.NomEstado) }}</td>
              <td align="left">{{ getTextMotivo(item.Motivo) }}</td>
              <td align="center">{{ item.FechaCompra }}</td>
              <td align="center">${{ Math.round(item.PrecioCompra) | numFormat }}</td>
              <td align="center">${{ Math.round(item.PrecioMaximoCompra) | numFormat }}</td>
              <td align="center">{{ formatFecha(item.FechaUltimaAsignacion) }}</td>
              <td align="center">{{ formatFecha(item.FechaUltObs) }}</td>
              <td>
                <v-btn text @click="getDato(item)">
                  <v-icon left>mdi-text-search</v-icon>Ver Dato
                </v-btn>
              </td>
            </tr>
          </template>
        </template>
        -->
        <!--<template v-if="(pars.origen = 'gestiondatos')">-->

        <template v-slot:item.Star="{ item }">
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <template v-if="item.TieneCambioReconocimiento == 1" v-on="on">
                <v-icon left>mdi-star</v-icon>
              </template>
            </template>
            <span>{{ getTooltipData() }}</span>
          </v-tooltip>
        </template>
        <template v-slot:item.Grupo="{ item }"
          >{{ item.Grupo }}-{{ item.Orden }}</template
        >
        <template v-slot:item.Concesionario="{ item }">
          {{ getTextConc(item.Concesionario) }}
        </template>
        <template v-slot:item.HaberNeto="{ item }">
          {{ item.HaberNeto | numFormat("$0,0") }}
        </template>
        <template v-slot:item.ApeNom="{ item }">{{ item.ApeNom }}</template>
        <template v-slot:item.Avance="{ item }">{{ item.Avance }}</template>
        <template v-slot:item.AvanceCalculado="{ item }">{{ item.AvanceCalculado }}</template>
        <template v-slot:item.NomEstado="{ item }">
          {{ getTextEstado(item.NomEstado) }}
        </template>
        <template v-slot:item.Motivo="{ item }">
          {{ getTextMotivo(item.Motivo) }}
        </template>
        <template v-slot:item.FechaCompra="{ item }">
          {{ item.FechaCompra }}
        </template>
        <template v-slot:item.PrecioCompra="{ item }">
          {{ item.PrecioCompra | numFormat("$0,0") }}
        </template>
        <template v-slot:item.PrecioMaximoCompra="{ item }">
          {{ item.PrecioMaximoCompra | numFormat("$0,0") }}
        </template>
        <template v-slot:item.FechaUltimaAsignacion="{ item }">
          {{ formatFecha(item.FechaUltimaAsignacion) }}
        </template>
        <template v-slot:item.FechaUltObs="{ item }">
          {{ formatFecha(item.FechaUltObs) }}
        </template>

        <template v-slot:item.VerDatos="{ item }">
          <v-btn text @click="getDato(item)">
            <v-icon left>mdi-text-search</v-icon>Ver Dato
          </v-btn>
        </template>

        <!--   </template> -->

        <!--
        <template v-else>
          <template v-slot:item.ApeNom="{ item }">{{ item.Apellido }}, {{ item.Nombres }}</template>

          <template v-slot:item.HaberNeto="{ item }">${{ Math.round(item.HaberNeto) | numFormat }}</template>

          <template
            v-slot:item.PrecioMaximoCompra="{ item }"
          >${{ Math.round(item.PrecioMaximoCompra) | numFormat }}</template>

          <template
            v-slot:item.PrecioCompra="{ item }"
          >${{ Math.round(item.PrecioCompra) | numFormat }}</template>

          <template v-slot:item.GrupoOrden="{ item }">{{ item.Grupo }}/{{ item.Orden }}</template>

          <template v-slot:item.FechaCompra="{ item }">{{ formatFecha(item.FechaCompra) }}</template>

          <template v-slot:item.Motivo="{ item }">{{ getTextMotivo(item.Motivo) }}</template>

          <template v-slot:item.FechaUltObs="{ item }">{{ formatFecha(item.FechaUltObs) }}</template>
          <template v-slot:item.VerDatos="{ item }">
            <v-btn text @click="getDato(item)">
              <v-icon left>mdi-text-search</v-icon>Ver Dato
            </v-btn>
          </template>
        </template>
        -->

        <template v-slot:top>
          <v-expansion-panels focusable>
            <v-expansion-panel>
              <v-expansion-panel-header> Filtros </v-expansion-panel-header>
              <v-expansion-panel-content>
                <v-container fluid>
                  <v-row>
                    <!--
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
                    -->
                    <v-col cols="3">
                      <v-row class="pa-6">
                        <v-select
                          :items="statesList"
                          v-model="stateFilterValue"
                          label="Estado"
                        ></v-select>
                      </v-row>
                    </v-col>
                    <v-col cols="3">
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
                    <!--
                    <v-col cols="3">
                      <v-row class="pa-10">
                        <v-text-field
                          label="Haber Neto"
                          v-model="sliderHaberNeto"
                          class="mt-0 pt-0"
                          type="number"
                        ></v-text-field>
                      </v-row>
                    </v-col>
                    -->
                  </v-row>
                </v-container>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
        </template>
      </v-data-table>

      <v-card-actions v-show="exportable">
        <v-spacer></v-spacer>
        <v-btn
          cclass="ma-2"
          color="success"
          outlined
          text
          @click="exportExcel"
          v-show="showBotones"
        >
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

      search: "",
      showTooltip: false,
      showBotones: null,
      //loading: true,
      cantItems: 15,
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
    };
  },

  created() {
    //this.$store.dispatch(this.module + "/getData", this.api);
  },
  mounted() {
    this.checkEsConcesionario();
  },

  computed: {
    ...mapState("gestiondatos", [
      "items",
      "items_filtered",
      "showItemsFiltered",
      "loadingDatos",
      "askData",
    ]),

    ...mapState("auth", [
      "login",
      "user",
      "esConcesionario",
      "esVinculo",
      "codigoConcesionario",
    ]),

    headers_2() {
      return [
        {
          text: "",
          value: "Star",
          align: "center",
          sorteable: true,
        },
        {
          text: "Grupo Orden",
          value: "Grupo",
          align: "center",
          sorteable: true,
          filterable: true,
        },

        {
          text: "Concesionario",
          value: "Concesionario",
          align: "center",
          filterable: false,
        },

        {
          text: "Haber Neto",
          value: "HaberNeto",
          align: "center",
          filterable: false,
        },
        {
          text: "Apellido y Nombre",
          value: "ApeNom",
          align: "left",
          sorteable: true,
          filterable: true,
        },
        /*
                {
                  text: 'Cuotas PG',
                  value: 'CPG',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Cutas AD',
                  value: 'CAD',
                  align: 'center',
                  width: '1%'
                },
                */
        {
          text: "Avance",
          value: "Avance",
          align: "center",
          filterable: true,
          filter: this.avanceFilter,
        },
        {
          text: "Avance Calculado",
          value: "AvanceCalculado",
          align: "center",
          filterable: false,

        },
        {
          text: "Estado",
          value: "NomEstado",
          align: "left",
          filterable: true,
          filter: this.stateFilter,
        },
        {
          text: "Motivo",
          value: "Motivo",
          align: "left",
          filterable: false,
        },
        {
          text: "Fecha Compra",
          value: "FechaCompra",
          align: "center",
          filterable: false,
        },
        {
          text: "Precio Compra",
          value: "PrecioCompra",
          align: "center",
          filterable: false,
        },
        {
          text: "Precio Max Compra",
          value: "PrecioMaximoCompra",
          align: "center",
          filterable: false,
        },
        {
          text: "Fecha Asignación",
          value: "FechaUltimaAsignacion",
          align: "center",
          filterable: false,
        },
        {
          text: "Fecha Ult. Obs",
          value: "FechaUltObs",
          align: "center",
          filterable: false,
        },
        { text: "", value: "VerDatos", align: "center", width: "1%" },
      ];
    },

    computedHeaders() {
      return this.headers_2.filter((headers_2) => headers_2.ocultar !== true);
    },

    api() {
      return this.pars.routeapi;
    },
    module() {
      return this.pars.module;
    },

    mostrarCombo() {
      if (typeof this.pars.showCombo !== "undefined") {
        return this.pars.showCombo;
      } else {
        return false;
      }
    },
    mostrarbuscar() {
      if (typeof this.pars.mostrarbuscar !== "undefined") {
        return this.pars.mostrarbuscar;
      } else {
        return true;
      }
    },

    /*
    myitems() {
      if (typeof this.pars.items !== "undefined") {
        this.setData(this.pars.items);
        return this.pars.items;
      } else {
        //this.setData(this.items);
        //this.setLoader();
        if (!this.askData) {
          this.getData("gestiondatos");
        }

        return this.items;
      }
    },
*/
    exportable() {
      if (typeof this.pars.exportable !== "undefined") {
        return this.pars.exportable;
      } else {
        return true;
      }
    },
  },

  methods: {
    getTooltipData() {
      return "Cambió el Reconocimiento";
      this.showToolTip = true;
    },

    stateFilter(value) {
      if (!this.stateFilterValue || this.stateFilterValue == 0) {
        return true;
      }

      if (this.stateFilterValue === "null") {
        return value === null;
      }

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
      const filename = "archivoexcel";
      XLSX.utils.book_append_sheet(workbook, data, filename);
      XLSX.writeFile(workbook, `${filename}.xlsx`);
    },

    filterListConcesionaria(value) {
      console.log(value);
      this.codConcesSelected = null;
      this.listC = [];
      this.listC = this.listConcesionarios.filter(function (item) {
        return item.Marca === value.Codigo;
      });
    },

    filterConcesionaria(value) {
      console.log(value);
      if (value.Codigo == 0) {
      } else {
        //this.filterData(value.Codigo);
      }
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
        this.showBotones = false;
      } else {
        if (this.esVinculo) {
          this.listMarcas.splice(0, 1);
          this.showBotones = false;
        } else {
          this.showBotones = true;
        }
      }
    },

    getDatos() {
      var pars = {
        Marca: this.codConcesSelected.Marca,
        Concesionario: this.codConcesSelected.Codigo,
      };
      this.getData(pars);
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

    getTextConc(conc) {
      switch (conc) {
        case "1":
          return "Sauma";
          break;
        case "2":
          return "Iruña";
          break;
        case "3":
          return "Amendola";
          break;
        case "4":
          return "AutoCervo";
          break;
        case "5":
          return "AutoNet";
          break;
        case "6":
          return "Car Group";
          break;
        case "7":
          return "Luxcar";
          break;
        case "8":
          return "RB";
          break;
        case "9":
          return "Sapac";
          break;
        default:
          return "";
          break;
      }
    },

    getTextEstado(estado) {
      if (estado != null) {
        return estado;
      } else {
        return "Sin Gestionar";
      }
    },

    getTextMotivo(codmotivo) {
      switch (codmotivo) {
        case "0":
          return "Espera el cobro";
          break;
        case "1":
          return "Conflicto";
          break;
        case "2":
          return "Llamar mas adelante";
          break;
        default:
          return "";
          break;
      }
    },

    getDato(item) {
      //console.log(item);
      //console.log(item.ID);
      //this.mostrarDato(item.ID);

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

    formatFecha(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM/YYYY");
      }
    },

    ...mapActions({
      mostrarDato: "gestiondatosweb/mostrarDato",
      getData: "gestiondatosweb/getData",
      setData: "gestiondatosweb/setData",
      filterData: "gestiondatosweb/filterData",
    }),
  },

  /*

Para cambiar el valor de alguna celda:
let data = XLSX.utils.json_to_sheet(
 this.dataToExport,
 {
   header: [‘transaction_date’, ‘business_name’, ‘credit’, ‘rate’]
 }
)
data[‘A1’].v = ‘Fecha’
data[‘B1’].v = ‘Empresa solicitante’
data[‘C1’].v = ‘Depósitos’
data[‘D1’].v = ‘Tasa’
const workbook = XLSX.utils.book_new()

*/
};
</script>
<style scoped>

.fullw {
  width: 100%;
}

.v-data-table >>> th {
  font-size: 14px !important;
  padding-left: 2px !important;
  padding-right: 2px !important;
  text-align: center;
}

.v-data-table >>> td {
  font-size: 12px !important;
  white-space: nowrap;
  padding-left: 2px !important;
  padding-right: 2px !important;
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

.padded {
  padding-left: 10px;
  padding-right: 10px;
}
</style>
