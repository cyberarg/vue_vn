<template>
  <div>
    <div>
      <h3></h3>
      <v-card color="grey lighten-4">
        <v-data-table
          dense
          fixed-header
          height="58vh"
          locale="es"
          :headers="headers"
          :items="items"
          :items-per-page="-1"
          :hide-default-footer="true"
          :search="search"
          item-key="pars.itemkey"
          class="elevation-1"
          :loading="loading"
          loading-text="Cargando Datos... Aguarde"
          no-data-text="No hay datos disponibles."
        >
          <template v-slot:item="{ item }">
            <tr>
              <td>{{ item.NomOficial }}</td>
              <td @click="handleClick(item, 'Obs Totales', item.ObsTotales)">
                <v-layout justify-center class="rowclass">
                  {{
                  item.ObsTotales
                  }}
                </v-layout>
              </td>
              <td @click="handleClick(item, 'Obs Dif. G/O', item.ObsDifGyO)">
                <v-layout justify-center class="rowclass">
                  {{
                  item.ObsDifGyO
                  }}
                </v-layout>
              </td>
            </tr>
          </template>
        </v-data-table>

        <v-card-actions>
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
  </div>
</template>
<script>
import { mapState, mapActions } from "vuex";
import XLSX from "xlsx";

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
      search: "",
      showBotones: null,
      expanded: true,
      totalSub: 0,
      window: {
        width: 0,
        height: 0,
      },
    };
  },

  created() {
    //this.$store.dispatch(this.module + "/getData", this.api);
  },

  mounted() {
    this.checkEsConcesionario();
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
    ...mapState("reporteobservaciones", [
      "items",
      "loading",
      "datos",
      "empresa",
      "items_filtrados",
    ]),

    ...mapState("auth", [
      "login",
      "user",
      "esConcesionario",
      "codigoConcesionario",
    ]),
  },

  methods: {
    ...mapActions({
      showFiltrados: "reporteobservaciones/showFiltrados",
      showFiltradosByName: "reporteobservaciones/showFiltradosByName",
      getData: "reporteobservaciones/getData",
    }),

    getDatos() {
      var pars = {
        api: this.api,
        Marca: this.codConcesSelected.Marca,
        Concesionario: this.codConcesSelected.Codigo,
      };
      this.getData(pars);
      //this.$store.dispatch(this.module + "/getData", pars);
    },

    filterConcesionaria(value) {
      console.log(value);
    },

    filterListConcesionaria(value) {
      console.log(value);
      this.codConcesSelected = null;
      this.listC = [];
      if (value.Codigo == 0) {
        this.listC = this.listConcesionarios.find(function (item) {
          return item.Codigo === 0;
        });
        this.codConcesSelected = this.listC;
      } else {
        this.listC = this.listConcesionarios.filter(function (item) {
          return item.Marca === value.Codigo;
        });
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
        this.showBotones = true;
      }
    },

    exportExcel: function () {
      let data = XLSX.utils.json_to_sheet(this.items);
      const workbook = XLSX.utils.book_new();
      const filename = "devschile-admins";
      XLSX.utils.book_append_sheet(workbook, data, filename);
      XLSX.writeFile(workbook, `${filename}.xlsx`);
    },
    expandRows() {
      this.expanded = !this.expanded;
    },

    handleResize() {
      this.window.width = window.innerWidth;
      this.window.height = window.innerHeight - 50;
    },

    async handleClick(item, nombre, cantidad) {
      console.log(item);

      var pars = {};
      pars.codOficial = item.Oficial;
      pars.nomOficial = item.NomOficial;
      pars.Concesionario = item.Concesionario;
      // console.log(pars);

      //await this.showFiltrados(pars);
      await this.showFiltradosByName(pars);

      var titleForm =
        "Oficial: " +
        item.NomOficial +
        " - Categoria: " +
        nombre +
        " - " +
        cantidad +
        " Observaciones";

      this.$router.push({
        name: "detallereporte",
        params: {
          title: titleForm,
          items_f: this.items_filtrados,
          volverARuta: "reporteasignaciones",
          module: "reporteasignacion",
        },
      });
    },

    subTotalAsig(column) {
      return (
        function (subTotalAsig) {
          //var itemName = column.value;
          //console.log(column.Asignados);
          if (!isNaN(column.Asignados)) {
            return subTotalAsig + parseInt(column.Asignados);
          }
          return 0;
        },
        0
      );
      // console.log(column.Asignados);
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

    total(column) {
      return this.items.reduce(function (total, item) {
        //var itemName = column.value;
        //console.log(itemName);
        if (isNaN(item[column.value])) {
          return "";
        }
        return total + parseInt(item[column.value]);
      }, 0);
    },
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
<style lang="scss" scoped>
.table-header {
  thead {
    background-color: black;
  }
}

.itemclass td {
  font-size: 14px;
}

.rowclass {
  padding: 0;
}

.rowclassSub {
  padding: 0;
  font-weight: bold;
}

.rowclassGroup {
  font-weight: bold;
}

.padded {
  padding-left: 10px;
  padding-right: 10px;
}
</style>
