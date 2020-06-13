<template>
  <v-app>
    <div>
      <h3></h3>
      <v-card color="grey lighten-4">
        <v-card-title>
          {{ pars.titleform }}
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Buscar"
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>

        <v-data-table
          dense
          fixed-header
          height="500"
          locale="es"
          :headers="headers"
          :items="items"
          :items-per-page="-1"
          :search="search"
          item-key="pars.itemkey"
          class="elevation-1"
          :loading="loading"
          group-by="NomOrigen"
          loading-text="Cargando Datos... Aguarde"
        >
          <template v-slot:item="{ item }">
            <template v-if="expanded">
              <tr>
                <td>{{ item.NomOficial }}</td>
                <td>
                  <v-layout justify-center class="rowclass">
                    {{
                    item.Asignados
                    }}
                  </v-layout>
                </td>
                <td>
                  <v-layout justify-center class="rowclass">
                    {{
                    item.SinGestionar
                    }}
                  </v-layout>
                </td>
                <td @click="handleClick(item, 'Telefono Mal', item.TelefonoMal, '1')">
                  <v-layout justify-center class="rowclass">
                    {{
                    item.TelefonoMal
                    }}
                  </v-layout>
                </td>
                <td>
                  <v-layout justify-center class="rowclass">
                    {{
                    item.DejeMensaje
                    }}
                  </v-layout>
                </td>
                <td>
                  <v-layout justify-center class="rowclass">
                    {{
                    item.NoCompra
                    }}
                  </v-layout>
                </td>
                <td>
                  <v-layout justify-center class="rowclass">
                    {{
                    item.EnGestion
                    }}
                  </v-layout>
                </td>
                <td>
                  <v-layout justify-center class="rowclass">{{ item.EntrevistaPendiente }}</v-layout>
                </td>

                <td>
                  <v-layout justify-center class="rowclass">
                    {{
                    item.VendePlan
                    }}
                  </v-layout>
                </td>
                <td>
                  <v-layout justify-center class="rowclass">
                    {{
                    item.PasarAVenta
                    }}
                  </v-layout>
                </td>
                <td>
                  <v-layout justify-center class="rowclass">
                    {{
                    item.Compro
                    }}
                  </v-layout>
                </td>
              </tr>
            </template>
            <template></template>
          </template>
          <template v-slot:group.header="{ items }">
            <td :colspan="headers.length" default="true" @click="expandRows" class="rowclassGroup">
              <strong>{{ items[0].NomOrigen }}</strong>
            </td>
          </template>
          <template v-slot:group.summary="{ headers, items }">
            <template>
              <td v-for="(column, index) in headers" default="true">
                <v-layout justify-center class="rowclassSub">
                  <strong>{{ totalS(column, items) }}</strong>
                </v-layout>
              </td>
            </template>
          </template>

          <template v-slot:body.append="{ headers }">
            <td v-for="(column, index) in headers" default="true">
              <v-layout justify-center class="rowclassSub">
                <strong>{{ total(column) }}</strong>
              </v-layout>
            </td>
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
      expanded: true,
      totalSub: 0,
      window: {
        width: 0,
        height: 0
      }
    };
  },

  created() {
    this.$store.dispatch(this.module + "/getData", this.api);
    window.addEventListener("resize", this.handleResize);
    this.handleResize();
  },

  destroyed() {
    window.removeEventListener("resize", this.handleResize);
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
    ...mapState("estadogestion", [
      "items",
      "loading",
      "datos",
      "empresa",
      "items_filtrados"
    ])
  },

  methods: {
    ...mapActions({
      showFiltrados: "estadogestion/showFiltrados"
    }),

    exportExcel: function() {
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

    async handleClick(item, nombre, cantidad, estado) {
      console.log(item);

      var codEstado = "";
      switch (estado) {
        case "-1":
          codEstado = "-1";
          break;
        case "-2":
          codEstado = "9";
          break;
        case "0":
          codEstado = null;
          break;
        default:
          codEstado = estado;
          break;
      }

      var pars = {};
      pars.codOficial = item.CodOficial;
      pars.codEstado = estado;
      console.log(pars);
      await this.showFiltrados(pars);
      var titleForm =
        "Oficial: " +
        item.NomOficial +
        " - Estado: " +
        nombre +
        " " +
        cantidad +
        " Operaciones";

      this.$router.push({
        name: "detallereporte",
        params: {
          title: titleForm,
          items_f: this.items_filtrados,
          volverARuta: "reporteasignaciones",
          module: "reporteasignacion"
        }
      });
    },

    subTotalAsig(column) {
      return (
        function(subTotalAsig) {
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
      return valor.reduce(function(total, item) {
        // console.log(item);
        if (isNaN(item[column.value])) {
          return "";
        }
        return total + parseInt(item[column.value]);
      }, 0);
    },

    total(column) {
      return this.items.reduce(function(total, item) {
        //var itemName = column.value;
        //console.log(itemName);
        if (isNaN(item[column.value])) {
          return "";
        }
        return total + parseInt(item[column.value]);
      }, 0);
    }
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
</style>
