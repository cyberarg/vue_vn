<template>
  <div>
    <div>
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
          :hide-default-header="true"
          item-key="pars.itemkey"
          class="elevation-1"
          :loading="loading"
          loading-text="Cargando Datos... Aguarde"
          no-data-text="No hay datos disponibles."
        >
          <template v-slot:header="{ props }">
            <thead class="v-data-table-header">
              <tr>
                <th></th>
                <th colspan="3" class="text-center lineH1">
                  {{ getPeriodoName(5) }}
                </th>
                <th colspan="3" class="text-center lineH1">
                  {{ getPeriodoName(4) }}
                </th>
                <th colspan="3" class="text-center lineH1L">
                  {{ getPeriodoName(3) }}
                </th>
                <th colspan="3" class="text-center lineH1">
                  {{ getPeriodoName(2) }}
                </th>
                <th colspan="3" class="text-center lineH1">
                  {{ getPeriodoName(1) }}
                </th>
                <th colspan="3" class="text-center lineH1L">
                  {{ getPeriodoName(0) }}
                </th>
              </tr>
              <tr>
                <th class="text-center child-header fixed">Oficial</th>

                <th class="text-center lineH2">Objetivo</th>
                <th class="text-center lineH2">Compras</th>
                <th class="text-center lineH2">% Cumplimiento</th>

                <th class="text-center lineH2">Objetivo</th>
                <th class="text-center lineH2">Compras</th>
                <th class="text-center lineH2">% Cumplimiento</th>

                <th class="text-center lineH2">Objetivo</th>
                <th class="text-center lineH2">Compras</th>
                <th class="text-center lineH2">% Cumplimiento</th>

                <th class="text-center lineH2">Objetivo</th>
                <th class="text-center lineH2">Compras</th>
                <th class="text-center lineH2">% Cumplimiento</th>

                <th class="text-center lineH2">Objetivo</th>
                <th class="text-center lineH2">Compras</th>
                <th class="text-center lineH2">% Cumplimiento</th>

                <th class="text-center lineH2">Objetivo</th>
                <th class="text-center lineH2">Compras</th>
                <th class="text-center lineH2L">% Cumplimiento</th>
              </tr>
            </thead>
          </template>

          <template v-slot:item.Compras_1="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-layout
                  justify-center
                  v-on="on"
                  class="rowclass"
                  @click="handleClick(item, 'Compras', item.Compras_1)"
                  >{{ showValor(item.Compras_1) }}</v-layout
                >
              </template>
              <span>{{ getTooltipData("Compras", item.Compras_1) }}</span>
            </v-tooltip>
          </template>

          <template v-slot:item.Compras_2="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-layout
                  justify-center
                  v-on="on"
                  class="rowclass"
                  @click="handleClick(item, 'Compras', item.Compras_2)"
                  >{{ showValor(item.Compras_2) }}</v-layout
                >
              </template>
              <span>{{ getTooltipData("Compras", item.Compras_2) }}</span>
            </v-tooltip>
          </template>

          <template v-slot:item.Compras_3="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-layout
                  justify-center
                  v-on="on"
                  class="rowclass"
                  @click="handleClick(item, 'Compras', item.Compras_3)"
                  >{{ showValor(item.Compras_3) }}</v-layout
                >
              </template>
              <span>{{ getTooltipData("Compras", item.Compras_3) }}</span>
            </v-tooltip>
          </template>

          <template v-slot:item.Compras_4="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-layout
                  justify-center
                  v-on="on"
                  class="rowclass"
                  @click="handleClick(item, 'Compras', item.Compras_4)"
                  >{{ showValor(item.Compras_4) }}</v-layout
                >
              </template>
              <span>{{ getTooltipData("Compras", item.Compras_4) }}</span>
            </v-tooltip>
          </template>

          <template v-slot:item.Compras_5="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-layout
                  justify-center
                  v-on="on"
                  class="rowclass"
                  @click="handleClick(item, 'Compras', item.Compras_5)"
                  >{{ showValor(item.Compras_5) }}</v-layout
                >
              </template>
              <span>{{ getTooltipData("Compras", item.Compras_5) }}</span>
            </v-tooltip>
          </template>

          <template v-slot:item.Compras_6="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-layout
                  justify-center
                  v-on="on"
                  class="rowclass"
                  @click="handleClick(item, 'Compras', item.Compras_6)"
                  >{{ showValor(item.Compras_6) }}</v-layout
                >
              </template>
              <span>{{ getTooltipData("Compras", item.Compras_6) }}</span>
            </v-tooltip>
          </template>

          <template v-slot:item.Objetivo_1="{ item }">{{
            showValor(item.Objetivo_1)
          }}</template>
          <template v-slot:item.Objetivo_2="{ item }">{{
            showValor(item.Objetivo_2)
          }}</template>
          <template v-slot:item.Objetivo_3="{ item }">{{
            showValor(item.Objetivo_3)
          }}</template>
          <template v-slot:item.Objetivo_4="{ item }">{{
            showValor(item.Objetivo_4)
          }}</template>
          <template v-slot:item.Objetivo_5="{ item }">{{
            showValor(item.Objetivo_5)
          }}</template>
          <template v-slot:item.Objetivo_6="{ item }">{{
            showValor(item.Objetivo_6)
          }}</template>

          <template v-slot:item.PorcCumplimiento_1="{ item }">{{
            getPorcentaje(item, 1)
          }}</template>
          <template v-slot:item.PorcCumplimiento_2="{ item }">{{
            getPorcentaje(item, 2)
          }}</template>
          <template v-slot:item.PorcCumplimiento_3="{ item }">{{
            getPorcentaje(item, 3)
          }}</template>
          <template v-slot:item.PorcCumplimiento_4="{ item }">{{
            getPorcentaje(item, 4)
          }}</template>
          <template v-slot:item.PorcCumplimiento_5="{ item }">{{
            getPorcentaje(item, 5)
          }}</template>
          <template v-slot:item.PorcCumplimiento_6="{ item }">{{
            getPorcentaje(item, 6)
          }}</template>
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
import moment from "moment";
import XLSX from "xlsx";

export default {
  props: {
    headers: {
      type: Array,
      required: true,
    },
    mesBase: {
      type: Number,
      required: true,
    },
  },

  data() {
    return {
      showBotones: true,
      expanded: true,
      totalSub: 0,
    };
  },

  created() {
    //this.$store.dispatch(this.module + "/getData", this.api);
  },

  computed: {
    ...mapState("reportecomprasobjetivos", [
      "dataStatus",
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
      showFiltrados: "reportecomprasobjetivos/showFiltrados",
    }),

    getTooltipData(nombre, cantidad) {
      if (cantidad > 0) {
        this.showToolTip = true;
        if (cantidad == 1) {
          return "Haga click para ver la compra.";
        }
        return "Haga click para ver las " + cantidad + " compras.";
      } else {
        this.showToolTip = false;
      }
    },

    showValor(valor) {
      if (valor > 0 && valor !== null) {
        return this.$options.filters.numFormat(valor, "0,0");
      }
      return "-";
    },

    getPorcentaje(item, periodo) {
      var porcentaje = 0;

      switch (periodo) {
        case 1:
          if (item.Objetivo_1 > 0 && item.Objetivo_1 !== null) {
            porcentaje = item.Compras_1 / item.Objetivo_1;

            return this.$options.filters.numFormat(porcentaje, "%0,0");
          }
          break;
        case 2:
          if (item.Objetivo_2 > 0 && item.Objetivo_2 !== null) {
            porcentaje = item.Compras_2 / item.Objetivo_2;

            return this.$options.filters.numFormat(porcentaje, "%0,0");
          }
          break;
        case 3:
          if (item.Objetivo_3 > 0 && item.Objetivo_3 !== null) {
            porcentaje = item.Compras_3 / item.Objetivo_3;

            return this.$options.filters.numFormat(porcentaje, "%0,0");
          }
          break;
        case 4:
          if (item.Objetivo_4 > 0 && item.Objetivo_4 !== null) {
            porcentaje = item.Compras_4 / item.Objetivo_4;

            return this.$options.filters.numFormat(porcentaje, "%0,0");
          }
          break;
        case 5:
          if (item.Objetivo_5 > 0 && item.Objetivo_5 !== null) {
            porcentaje = item.Compras_5 / item.Objetivo_5;

            return this.$options.filters.numFormat(porcentaje, "%0,0");
          }
          break;
        case 6:
          if (item.Objetivo_6 > 0 && item.Objetivo_6 !== null) {
            porcentaje = item.Compras_6 / item.Objetivo_6;

            return this.$options.filters.numFormat(porcentaje, "%0,0");
          }
          break;
      }

      return "-";
    },

    getPeriodoName(periodo) {
      //console.log(periodo);
      var month;
      var baseMonth;
      if (this.mesBase == 0) {
        baseMonth = moment().month() + 1;
      } else {
        baseMonth = this.mesBase;
      }

      if (baseMonth > 5) {
        month = baseMonth - periodo;
      }

      return this.getMonthName(month);
    },

    getMonthName(mes) {
      //console.log(mes);
      switch (mes) {
        case 1:
          return "Enero";
          break;
        case 2:
          return "Febrero";
          break;
        case 3:
          return "Marzo";
          break;
        case 4:
          return "Abril";
          break;
        case 5:
          return "Mayo";
          break;
        case 6:
          return "Junio";
          break;
        case 7:
          return "Julio";
          break;
        case 8:
          return "Agosto";
          break;
        case 9:
          return "Septiembre";
          break;
        case 10:
          return "Octubre";
          break;
        case 11:
          return "Noviembre";
          break;
        case 12:
          return "Diciembre";
          break;
      }
    },

    exportExcel: function () {
      let data = XLSX.utils.json_to_sheet(this.items);
      const workbook = XLSX.utils.book_new();
      const filename = "devschile-admins";
      XLSX.utils.book_append_sheet(workbook, data, filename);
      XLSX.writeFile(workbook, `${filename}.xlsx`);
    },

    async handleClick(item, nombre, cantidad) {
      console.log(item);

      var pars = {};
      pars.codOficial = item.CodOficial;

      // console.log(pars);
      await this.showFiltrados(pars);
      var titleForm = "Oficial: " + item.Nombre + " - " + cantidad + " Compras";

      this.$router.push({
        name: "detallereporte",
        params: {
          title: titleForm,
          items_f: this.items_filtrados,
          volverARuta: "reportecomprasobjetivos",
          module: "reportecomprasobjetivos",
        },
      });
    },
  },
};
</script>
<style lang="scss" scoped>
.table-header {
  thead {
    background-color: black;
  }
}

.fixed {
  position: sticky;
  left: 0;
  z-index: 1;
  background: rgb(0, 0, 0);
}

.lineH1 {
  font-weight: bold;
  border-left: 1px solid #000000;
  border-right: 1px solid #000000;
}

.lineH1L {
  font-weight: bold;
  border-left: 1px solid #000000;
}

.lineH2 {
  border-left: 1px solid #000000;
  border-right: 1px solid #000000;
}

.lineH2L {
  border-left: 1px solid #000000;
}

.lineL {
  border-left: 1px solid #000000;
}

.lineR {
  border-right: 1px solid #000000;
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
