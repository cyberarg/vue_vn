<template>
  <v-app>
    <h3></h3>
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
        <v-row class="padded">
          <v-col cols="4">
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
          <v-col cols="4">
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
          <v-col cols="4">
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
      </v-card-title>

      <v-data-table
        dense
        fixed-header
        height="550"
        :headers="headers"
        :items="myitems"
        :search="search"
        item-key="pars.itemkey"
        :items-per-page="cantItems"
        class="elevation-1"
        :loading="loading"
        loading-text="Cargando Datos... Aguarde"
      >
        <template v-slot:item="{ item, headers }">
          <template v-if="(pars.origen = 'gestiondatos')">
            <tr :class="setClass(item)">
              <!--
            <td v-for="column in headers">
              {{ item[column.value] }}
            </td>
              -->
              <td align="center" width="1%">{{ item.Grupo }}-{{ item.Orden }}</td>
              <td align="center">{{ getTextConc(item.Concesionario) }}</td>
              <td align="center">${{ Math.round(item.HaberNeto) | numFormat }}</td>
              <td align="start">{{ item.ApeNom }}</td>
              <!--
              <td width="1%" align="center">{{ item.CPG }}</td>
              <td width="1%" align="center">{{ item.CAD }}</td>
              -->
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
      </v-data-table>

      <v-card-actions v-show="exportable">
        <v-spacer></v-spacer>
        <v-btn cclass="ma-2" outlined text @click="exportExcel">
          <v-icon left>mdi-file-excel-outline</v-icon>Excel
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-app>
</template>
<script>
import { mapState, mapActions } from "vuex";
import XLSX from "xlsx";
import moment from "moment";

export default {
  props: {
    pars: {
      type: Object,
      required: true
    },
    headers: {
      type: Array,
      required: true
    }
  },

  data() {
    return {
      search: "",
      cantItems: 15,
      loading: true,
      codMarcaSelected: null,
      listMarcas: [
        { Codigo: 2, Nombre: "Fiat" },
        { Codigo: 5, Nombre: "Volkswagen" }
      ],
      codConcesSelected: null,
      listC: [],
      listConcesionarios: [
        { Codigo: 0, Nombre: "Todos" },
        { Codigo: 1, Nombre: "Sauma", Marca: 5 },
        { Codigo: 2, Nombre: "Sapac", Marca: 5 },
        { Codigo: 3, Nombre: "Amendola", Marca: 5 },
        { Codigo: 4, Nombre: "AutoCervo", Marca: 2 },
        { Codigo: 5, Nombre: "AutoNet", Marca: 2 },
        { Codigo: 6, Nombre: "Car Group", Marca: 2 }
      ]
    };
  },

  created() {
    //this.$store.dispatch(this.module + "/getData", this.api);
  },

  computed: {
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

    myitems() {
      if (typeof this.pars.items !== "undefined") {
        this.setData(this.pars.items);
        return this.pars.items;
      }
      this.setData(this.items);
      this.loading = false;
      return this.items;
    },

    exportable() {
      if (typeof this.pars.exportable !== "undefined") {
        return this.pars.exportable;
      } else {
        return true;
      }
    },

    ...mapState("gestiondatos", [
      "items",
      "items_filtered",
      "showItemsFiltered"
    ])
  },

  methods: {
    exportExcel: function() {
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
      this.listC = this.listConcesionarios.filter(function(item) {
        return item.Marca === value.Codigo;
      });
    },

    filterConcesionaria(value) {
      console.log(value);
      if (value.Codigo == 0) {
      } else {
        this.filterData(value.Codigo);
      }
    },

    setClass(item) {
      //console.log(item);

      if (item.EsDatoNuevo == 1) {
        return "classDatoNuevo"; //NARANJA
      } else {
        if (
          (item.CodEstado == 0 || item.CodEstado == null) &&
          item.Retrabajar == 1 &&
          item.AvanceAutomatico >= 75 &&
          item.AvanceAutomatico <= 83
        ) {
          if (item.AvanceAutomatico == 83) {
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
          return "Sapac";
          break;
        case "3":
          return "Amendola";
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
      this.$router.push({ name: "detalledato", params: { id: item.ID } });
    },

    formatFecha(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM/YYYY");
      }
    },

    ...mapActions({
      mostrarDato: "gestiondatos/mostrarDato",
      setData: "gestiondatos/setData",
      filterData: "gestiondatos/filterData"
    })
  }

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
<style lang="scss" scoped>
.table-header {
  thead {
    background-color: black;
  }
}

.fullw {
  width: 100%;
}

.v-data-table td {
  font-size: 12px;
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
