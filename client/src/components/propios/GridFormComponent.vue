<template>
  <div>
    <h3></h3>
    <v-card>
      <v-card-title>
        {{ pars.titleform }}
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-text-field
          v-show="mostrarbuscar"
          v-model="search"
          append-icon="mdi-magnify"
          label="Buscar"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>

      <v-data-table
        dense
        :headers="headers"
        :items="myitems"
        :search="search"
        item-key="pars.itemkey"
        class="elevation-1"
        :loading="loading"
        loading-text="Cargando Datos... Aguarde"
      >
        <template v-slot:item.ApeNom="{ item }">
          {{ item.Apellido }}, {{ item.Nombres }}
        </template>

        <template v-slot:item.HaberNeto="{ item }">
          ${{ Math.round(item.HaberNeto) | numFormat }}
        </template>

        <template v-slot:item.PrecioMaximoCompra="{ item }">
          ${{ Math.round(item.PrecioMaximoCompra) | numFormat }}
        </template>

        <template v-slot:item.PrecioCompra="{ item }">
          ${{ Math.round(item.PrecioCompra) | numFormat }}
        </template>

        <template v-slot:item.GrupoOrden="{ item }">
          {{ item.Grupo }}/{{ item.Orden }}
        </template>

        <template v-slot:item.FechaCompra="{ item }">
          {{ formatFecha(item.FechaCompra) }}
        </template>

        <template v-slot:item.Motivo="{ item }">
          {{ getTextMotivo(item.Motivo) }}
        </template>

        <template v-slot:item.FechaUltObs="{ item }">
          {{ formatFecha(item.FechaUltObs) }}
        </template>
        <template v-slot:item.VerDatos="{ item }">
          <v-btn
            class="ma-2"
            outlined
            color="blue darken-1"
            text
            @click="getDato(item)"
            >Ver Dato</v-btn
          >
        </template>
      </v-data-table>

      <v-card-actions v-show="exportable">
        <v-spacer></v-spacer>

        <v-btn
          cclass="ma-2"
          outlined
          color="blue darken-1"
          text
          @click="exportExcel"
          >Excel</v-btn
        >
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
      required: true
    },
    headers: {
      type: Array,
      required: true
    }
  },

  data() {
    return {
      search: ""
    };
  },

  created() {
    this.$store.dispatch(this.module + "/getData", this.api);
  },

  computed: {
    api() {
      return this.pars.routeapi;
    },
    module() {
      return this.pars.module;
    },
    grupoorden() {
      return "32323466";
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
        return this.pars.items;
      }
      return this.items;
    },

    exportable() {
      if (typeof this.pars.exportable !== "undefined") {
        return this.pars.exportable;
      } else {
        return true;
      }
    },

    ...mapState("gestiondatos", ["items", "loading"])
  },

  methods: {
    exportExcel: function() {
      let data = XLSX.utils.json_to_sheet(this.items);
      const workbook = XLSX.utils.book_new();
      const filename = "devschile-admins";
      XLSX.utils.book_append_sheet(workbook, data, filename);
      XLSX.writeFile(workbook, `${filename}.xlsx`);
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
      //console.log(item.ID);
      this.mostrarDato(item.ID);
      this.$router.push({ name: "detalledato", params: { id: item.ID } });
    },

    formatFecha(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM/YYYY");
      }
    },

    ...mapActions({
      mostrarDato: "gestiondatos/mostrarDato"
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
  font-size: 8px;
}
</style>
