<template>
  <v-app>
    <div>
      <h3></h3>
      <v-card color="grey lighten-4">
        <v-card-title>
          {{ pars.titleform }}
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-switch v-model="showOficiales" label="Cbo Oficiales" class="mt-2"></v-switch>
          <v-spacer></v-spacer>
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
          <v-btn
            class="ma-2"
            :disabled="disablePasarSinGestion"
            outlined
            text
            @click="pasarSinGestionar()"
          >
            <v-icon left>mdi-undo-variant</v-icon>Pasar a Sin Gestionar
          </v-btn>
          <v-btn class="ma-2" :disabled="disableAsignar" outlined text @click="asignarOficial()">
            <v-icon left>{{ userIcon }}</v-icon>
            Asignar {{ getTextAsignacion }}
          </v-btn>
        </v-card-title>

        <v-data-table
          dense
          :headers="computedHeaders"
          :items="items"
          :search="search"
          item-key="ID"
          class="elevation-1"
          :loading="loading"
          loading-text="Cargando Datos... Aguarde"
          show-select
          v-model="selected"
          :single-select="singleSelect"
        >
          <template v-slot:item.GrupoOrden="{ item }">{{ item.Grupo }}-{{ item.Orden }}</template>

          <template v-slot:item.ApeNom="{ item }">{{ item.Apellido }}, {{ item.Nombres }}</template>
          <template v-slot:item.HaberNeto="{ item }">${{ Math.round(item.HaberNeto) | numFormat }}</template>
          <template
            v-slot:item.PrecioMaximoCompra="{ item }"
          >${{ Math.round(item.PrecioMaximoCompra) | numFormat }}</template>
          <template v-slot:item.FechaVtoCuota2="{ item }">{{ formatFecha(item.FechaVtoCuota2) }}</template>

          <template v-slot:item.FechaUltObs="{ item }">{{ formatFecha(item.FechaUltObs) }}</template>
          <template v-slot:item.VerDatos="{ item }">
            <v-btn text @click="getDato(item)">
              <v-icon left>mdi-text-search</v-icon>Ver Dato
            </v-btn>
          </template>

          <template v-slot:body.append="{ headers }">
            <td colspan="4"></td>
            <td>${{ Math.round(getCountTotalHN) | numFormat }}</td>
            <td>Total = {{ getCountTotal }}</td>
            <td>Sel = {{ getCountSelected }}</td>
          </template>
        </v-data-table>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn cclass="ma-2" outlined text @click="exportExcel">
            <v-icon left>mdi-file-excel-outline</v-icon>Excel
          </v-btn>
        </v-card-actions>
      </v-card>
    </div>
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
      codOficialSelected: null,
      codSupervisorSelected: null,
      textAsignacion: "Oficial",
      userIcon: "mdi-account-check-outline",
      showOficiales: true,
      singleSelect: false,
      selected: [],
      search: ""
    };
  },

  created() {
    this.$store.dispatch(this.module + "/getData", this.api);
  },

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
      return this.items.reduce(function(total, item) {
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
      return this.headers.filter(header => header.text !== "ID");
    },

    ...mapState("auth", ["login"]),
    ...mapState("asignaciondatos", [
      "items",
      "loading",
      "listOficiales",
      "listSupervisores",
      "unselect"
    ])
  },

  methods: {
    exportExcel: function() {
      let data = XLSX.utils.json_to_sheet(this.items);
      const workbook = XLSX.utils.book_new();
      const filename = "devschile-admins";
      XLSX.utils.book_append_sheet(workbook, data, filename);
      XLSX.writeFile(workbook, `${filename}.xlsx`);
    },

    async pasarSinGestionar() {
      console.log(this.selected);
      if (this.selected.length > 0) {
        var params = {
          data: this.selected
        };
        //console.log(params);
        await this.pasarASinGestionar(params);
        if (this.unselect) {
          this.selected = [];
          this.$store.dispatch(this.module + "/getData", this.api);
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
          login: this.login
        };
        console.log(params);
        await this.asignarDatos(params);
        if (this.unselect) {
          this.selected = [];
          this.$store.dispatch(this.module + "/getData", this.api);
        }
      }
    },

    formatFecha(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM/YYYY");
      }
    },

    getDato(item) {
      // this.mostrarDato(item.ID);
      //console.log(item.ID);
      this.$router.push({ name: "detalledato", params: { id: item.ID } });
    },

    ...mapActions({
      mostrarDato: "gestiondatos/mostrarDato",
      asignarDatos: "asignaciondatos/asignarDatos",
      pasarASinGestionar: "asignaciondatos/pasarASinGestionar"
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
  font-size: 16px;
  font-weight: bold;
  text-align: center;
}
</style>
