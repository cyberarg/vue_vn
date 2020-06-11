<template>
  <v-app>
    <div>
      <h3></h3>
      <v-card color="grey lighten-4">
        <!--
        <v-card-title>
          {{ pars.titleform }}
          <v-divider class="mx-4" inset vertical></v-divider>
        </v-card-title>
        -->
        <v-data-table
          dense
          :headers="headers"
          :items="items"
          :items-per-page="-1"
          item-key="pars.itemkey"
          class="dataTable elevation-1"
          :loading="loading"
          group-by="NomOrigen"
          loading-text="Cargando Datos... Aguarde"
          hide-default-footer
          single-select
        >
          <template v-slot:item="{ item }">
            <template v-if="expanded">
              <tr class="itemclass">
                <td>{{ item.NomOficial }}</td>
                <td @click="handleClick(item, 'Asignados', item.Asignados, '-1')">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-layout justify-center v-on="on" class="rowclass bold">{{ item.Asignados }}</v-layout>
                    </template>
                    <span>
                      {{
                      getTooltipData("Asignados", item.Asignados)
                      }}
                    </span>
                  </v-tooltip>
                </td>
                <td @click="handleClick(item, 'Sin Gestionar', item.SinGestionar, '0')">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-layout justify-center v-on="on" class="rowclass">
                        {{
                        item.SinGestionar
                        }}
                      </v-layout>
                    </template>
                    <span>
                      {{
                      getTooltipData("Sin Gestionar", item.SinGestionar)
                      }}
                    </span>
                  </v-tooltip>
                </td>
                <td @click="handleClick(item, 'Telefono Mal', item.TelefonoMal, '1')">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-layout justify-center v-on="on" class="rowclass">
                        {{
                        item.TelefonoMal
                        }}
                      </v-layout>
                    </template>
                    <span>
                      {{
                      getTooltipData("Telefono Mal", item.TelefonoMal)
                      }}
                    </span>
                  </v-tooltip>
                </td>
                <td @click="handleClick(item, 'Deje Mensaje', item.DejeMensaje, '2')">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-layout justify-center v-on="on" class="rowclass">
                        {{
                        item.DejeMensaje
                        }}
                      </v-layout>
                    </template>
                    <span>
                      {{
                      getTooltipData("Deje Mensaje", item.DejeMensaje)
                      }}
                    </span>
                  </v-tooltip>
                </td>
                <td
                  @click="handleClick(item, 'Entrevista Pendiente', item.EntrevistaPendiente, '3')"
                >
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-layout
                        justify-center
                        v-on="on"
                        class="rowclass"
                      >{{ item.EntrevistaPendiente }}</v-layout>
                    </template>
                    <span>
                      {{
                      getTooltipData(
                      "Entrevista Pendiente",
                      item.EntrevistaPendiente
                      )
                      }}
                    </span>
                  </v-tooltip>
                </td>
                <td @click="handleClick(item, 'En Gestion', item.EnGestion, '7')">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-layout justify-center v-on="on" class="rowclass">
                        {{
                        item.EnGestion
                        }}
                      </v-layout>
                    </template>
                    <span>
                      {{
                      getTooltipData("En Gestion", item.EnGestion)
                      }}
                    </span>
                  </v-tooltip>
                </td>
                <td @click="handleClick(item, 'No Le Interesa', item.NoLeInteresa, '4')">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-layout justify-center v-on="on" class="rowclass">
                        {{
                        item.NoLeInteresa
                        }}
                      </v-layout>
                    </template>
                    <span>
                      {{
                      getTooltipData("No Le Interesa", item.NoLeInteresa)
                      }}
                    </span>
                  </v-tooltip>
                </td>
                <td @click="handleClick(item, 'Vende Plan', item.VendePlan, '5')">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-layout justify-center v-on="on" class="rowclass">
                        {{
                        item.VendePlan
                        }}
                      </v-layout>
                    </template>
                    <span>
                      {{
                      getTooltipData("Vende Plan", item.VendePlan)
                      }}
                    </span>
                  </v-tooltip>
                </td>
                <td @click="handleClick(item, 'Plan Subite', item.PlanSubite, '6')">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-layout justify-center v-on="on" class="rowclass">
                        {{
                        item.PlanSubite
                        }}
                      </v-layout>
                    </template>
                    <span>
                      {{
                      getTooltipData("Plan Subite", item.PlanSubite)
                      }}
                    </span>
                  </v-tooltip>
                </td>
                <td @click="handleClick(item, 'PasarAVenta', item.PasarAVenta, '8')">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-layout justify-center v-on="on" class="rowclass">
                        {{
                        item.PasarAVenta
                        }}
                      </v-layout>
                    </template>
                    <span>
                      {{
                      getTooltipData("Pasar A Venta", item.PasarAVenta)
                      }}
                    </span>
                  </v-tooltip>
                </td>
                <td @click="handleClick(item, 'En Otro Oficial', item.EnOtroOficial, '-2')">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-layout justify-center v-on="on" class="rowclass">
                        {{
                        item.EnOtroOficial
                        }}
                      </v-layout>
                    </template>
                    <span>
                      {{
                      getTooltipData("En Otro Oficial", item.EnOtroOficial)
                      }}
                    </span>
                  </v-tooltip>
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
      showToolTip: false
    };
  },

  created() {
    // this.$store.dispatch(this.module + "/getData", this.api);
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
    ...mapState("reporteasignacion", [
      "items",
      "loading",
      "datos",
      "empresa",
      "items_filtrados"
    ])
    // ...mapState("estadogestion", ["items", "loading", "datos", "empresa"])
  },

  methods: {
    ...mapActions({
      showFiltrados: "reporteasignacion/showFiltrados"
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
    getTooltipData(nombre, cantidad) {
      if (cantidad > 0) {
        this.showToolTip = true;
        if (cantidad == 1) {
          return nombre + " -  Haga click para ver la operación.";
        }
        return (
          nombre + " -  Haga click para ver las " + cantidad + " operaciones."
        );
      } else {
        this.showToolTip = false;
      }
    },

    handleClick2(item, nombre, cantidad) {
      console.log(
        nombre + "Haga click para ver las " + cantidad + " operaciones."
      );
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
.dataTable {
  font-size: 12px;
}

.itemclass td {
  font-size: 14px;
}

.rowclass {
  padding: 0;
}

.bold {
  font-weight: bold;
}

.rowclassSub {
  padding: 0;
  font-weight: bold;
}

.rowclassGroup {
  font-weight: bold;
}

table.v-table tbody td:first-child,
table.v-table tbody td:not(:first-child),
table.v-table tbody th:first-child,
table.v-table tbody th:not(:first-child),
table.v-table thead td:first-child,
table.v-table thead td:not(:first-child),
table.v-table thead th:first-child,
table.v-table thead th:not(:first-child) {
  padding: 0 12px;
}
</style>
