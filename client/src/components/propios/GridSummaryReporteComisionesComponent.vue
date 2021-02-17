<template>
  <div>
    <div>
      <v-card color="grey lighten-4">
        <v-data-table
          dense
          fixed-header
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
                <th />
                <th colspan="3" class="text-center lineH1">
                  {{ getPeriodoName(2) }}
                </th>
                <th colspan="3" class="text-center lineH1">
                  {{ getPeriodoName(1) }}
                </th>
                <th colspan="3" class="text-center lineH1L">
                  {{ getPeriodoName(0) }}
                </th>
                <th colspan="3" class="text-center lineH1L">
                  Totales Trimestre
                </th>
              </tr>
              <tr>
                <th class="text-center child-header">Oficial</th>

                <!--
                <th class="text-center lineH2">Objetivo</th>
                <th class="text-center lineH2">Comprados</th>
                -->
                <th class="text-center lineH2">% Cumplimiento</th>
                <th class="text-center lineH2">Casos Comisionables</th>
                <th class="text-center lineH2">Comisión</th>

                <!--
                <th class="text-center lineH2">Objetivo</th>
                <th class="text-center lineH2">Comprados</th>
                -->
                <th class="text-center lineH2">% Cumplimiento</th>
                <th class="text-center lineH2">Casos Comisionables</th>
                <th class="text-center lineH2">Comisión</th>

                <!--
                <th class="text-center lineH2">Objetivo</th>
                <th class="text-center lineH2">Comprados</th>
                -->
                <th class="text-center lineH2">% Cumplimiento</th>
                <th class="text-center lineH2">Casos Comisionables</th>
                <th class="text-center lineH2">Comisión</th>

                <!--
                <th class="text-center lineH2">Objetivo</th>
                <th class="text-center lineH2">Comprados</th>
                -->
                <th class="text-center lineH2">% Cumplimiento</th>
                <th class="text-center lineH2L">Casos Comisionables</th>
                <th class="text-center lineH2L">Comisión</th>
              </tr>
            </thead>
          </template>

          <!--
          <template v-slot:item.Vigentes_0="{item}">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-layout
                  justify-center
                  v-on="on"
                  class="rowclass"
                  @click="handleClick(item, 'Vigentes', item.Vigentes_0)"
                >{{ showValor(item.Vigentes_0, false) }}</v-layout>
              </template>
              <span>{{ getTooltipData('Vigentes', item.Vigentes_0)}}</span>
            </v-tooltip>
          </template>

          <template v-slot:item.Vigentes_1="{item}">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-layout
                  justify-center
                  v-on="on"
                  class="rowclass"
                  @click="handleClick(item, 'Vigentes', item.Vigentes_1)"
                >{{ showValor(item.Vigentes_1, false) }}</v-layout>
              </template>
              <span>{{ getTooltipData('Vigentes', item.Vigentes_1)}}</span>
            </v-tooltip>
          </template>

          <template v-slot:item.Vigentes_2="{item}">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-layout
                  justify-center
                  v-on="on"
                  class="rowclass"
                  @click="handleClick(item, 'Vigentes', item.Vigentes_2)"
                >{{ showValor(item.Vigentes_2, false) }}</v-layout>
              </template>
              <span>{{ getTooltipData('Vigentes', item.Vigentes_2)}}</span>
            </v-tooltip>
          </template>
-->
          <!--
          <template v-slot:item.Comprados_0="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-layout
                  justify-center
                  v-on="on"
                  class="rowclass"
                  @click="handleClick(item, 'Compras', item.Comprados_0)"
                  >{{ showValor(item.Comprados_0, false) }}</v-layout
                >
              </template>
              <span>{{ getTooltipData("Comprados", item.Comprados_0) }}</span>
            </v-tooltip>
          </template>

          <template v-slot:item.Comprados_1="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-layout
                  justify-center
                  v-on="on"
                  class="rowclass"
                  @click="handleClick(item, 'Compras', item.Comprados_1)"
                  >{{ showValor(item.Comprados_1, false) }}</v-layout
                >
              </template>
              <span>{{ getTooltipData("Comprados", item.Comprados_1) }}</span>
            </v-tooltip>
          </template>

          <template v-slot:item.Comprados_2="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-layout
                  justify-center
                  v-on="on"
                  class="rowclass"
                  @click="handleClick(item, 'Compras', item.Comprados_2)"
                  >{{ showValor(item.Comprados_2, false) }}</v-layout
                >
              </template>
              <span>{{ getTooltipData("Comprados", item.Comprados_2) }}</span>
            </v-tooltip>
          </template>
          -->

          <!--
          <template v-slot:item.Objetivo_0="{ item }">{{
            showValor(item.Objetivo_0, false)
          }}</template>
          <template v-slot:item.Objetivo_1="{ item }">{{
            showValor(item.Objetivo_1, false)
          }}</template>
          <template v-slot:item.Objetivo_2="{ item }">{{
            showValor(item.Objetivo_2, false)
          }}</template>
 -->
          <template v-slot:item.Comision_0="{ item }">{{
            showValorComision(item, 0, true)
          }}</template>
          <template v-slot:item.Comision_1="{ item }">{{
            showValorComision(item, 1, true)
          }}</template>
          <template v-slot:item.Comision_2="{ item }">{{
            showValorComision(item, 2, true)
          }}</template>
         
          <template v-slot:item.PorcCumplimiento_0="{ item }">
            <template v-if="getPorcentaje(item, 0) === true">
              <v-icon medium>mdi-check</v-icon>
            </template>
            <template v-else>
                {{ getPorcentaje(item, 0) }}
            </template>
            
         </template>
          <template v-slot:item.PorcCumplimiento_1="{ item }">
            <template v-if="getPorcentaje(item, 1) === true">
              <v-icon medium>mdi-check</v-icon>
            </template>
            <template v-else>
                {{ getPorcentaje(item, 1) }}
            </template>
          </template>
          <template v-slot:item.PorcCumplimiento_2="{ item }">
            <template v-if="getPorcentaje(item, 2) === true">
              <v-icon medium>mdi-check</v-icon>
            </template>
            <template v-else>
                {{ getPorcentaje(item, 2) }}
            </template>
          </template>
          <!--
          <template v-slot:item.Comprados_T="{ item }">{{
            showValorT(item, false, false)
          }}</template>
          <template v-slot:item.Objetivo_T="{ item }">{{
            showValorT(item, true, false)
          }}</template>
          -->
          <template v-slot:item.Comision_T="{ item }">{{
            showValorComisionT(item, true)
          }}</template>
           <template v-slot:item.CantHN_T="{ item }">{{
            showValorT_HN(item)
          }}</template>
          <template v-slot:item.PorcCumplimiento_T="{ item }">{{
            getPorcentajeT(item)
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
import XLSX from "xlsx";
import moment from "moment";

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
      mesPeriodo2: null,
      mesPeriodo1: null,
      mesPeriodo0: null,
    };
  },

  created() {
    //this.$store.dispatch(this.module + "/getData", this.api);
  },

  computed: {
    ...mapState("reportecomisiones", [
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
      showFiltrados: "reportecomisiones/showFiltrados",
    }),

    getTooltipData(nombre, cantidad) {
      if (cantidad > 0) {
        this.showToolTip = true;
        if (cantidad == 1) {
          return "Haga click para ver el caso comprado.";
        }
        return "Haga click para ver los " + cantidad + " casos comprados.";
      } else {
        this.showToolTip = false;
      }
    },

    showValor(valor, mostrarMoneda) {
      if (valor > 0 && valor !== null) {
        if (mostrarMoneda) {
          return this.$options.filters.numFormat(valor, "$0,0");
        }
        return this.$options.filters.numFormat(valor, "0,0");
      }
      return "-";
    },

    showValorT(item, sonObjetivos, mostrarMoneda) {
      var sumatoria = 0;
      if (sonObjetivos) {
        sumatoria =
          //parseInt(item.Objetivo_0) +
          parseInt(item.Objetivo_0) +
          parseInt(item.Objetivo_1) +
          parseInt(item.Objetivo_2);
      } else {
        sumatoria =
          //parseInt(item.Objetivo_0) +
          parseInt(item.Comprados_0) +
          parseInt(item.Comprados_1) +
          parseInt(item.Comprados_2);
      }

      //console.log(sumatoria);
      if (sumatoria > 0) {
        if (mostrarMoneda) {
          return this.$options.filters.numFormat(sumatoria, "$0,0");
        }
        return this.$options.filters.numFormat(sumatoria, "0,0");
      }

      return "-";
    },

    showValorT_HN(item) {
      var sumatoria = 0;
     
        sumatoria =
          //parseInt(item.Objetivo_0) +
          parseInt(item.CantHN_0) +
          parseInt(item.CantHN_1) +
          parseInt(item.CantHN_2);

      //console.log(sumatoria);
      if (sumatoria > 0) {
        return this.$options.filters.numFormat(sumatoria, "0,0");
      }

      return "-";
    },


    showValorComisionT(item, mostrarMoneda) {
      var porcentaje_0 = 0;
      var porcentaje_1 = 0;
      var porcentaje_2 = 0;
      var porcentaje_T = 0;

      var comision_0 = 0;
      var comision_1 = 0;
      var comision_2 = 0;
      var comision_T = 0;

      var comisionFinal = 0;

      if (item.Objetivo_0 > 0 && item.Objetivo_0 !== null) {
        porcentaje_0 = item.Comprados_0 / item.Objetivo_0;
        comision_0 = item.Comision_0;
      } else {
      }

      if (item.Objetivo_1 > 0 && item.Objetivo_1 !== null) {
        porcentaje_1 = item.Comprados_1 / item.Objetivo_1;
        comision_1 = item.Comision_1;
      } else {
      }

      if (item.Objetivo_2 > 0 && item.Objetivo_2 !== null) {
        porcentaje_2 = item.Comprados_2 / item.Objetivo_2;
        comision_2 = item.Comision_2;
      } else {
      }

      comision_T =
        parseInt(comision_0) + parseInt(comision_1) + parseInt(comision_2);
      porcentaje_T =
        (parseInt(porcentaje_0) +
          parseInt(porcentaje_1) +
          parseInt(porcentaje_2)) /
        3;

      switch (true) {
        case porcentaje_T == 0:
          return "-";
          break;
        case porcentaje_T < 1:
          comisionFinal = comision_T * 0.5;
          break;
        case porcentaje_T == 1:
          comisionFinal = comision_T;
          break;
        case porcentaje_T > 1:
          comisionFinal = comision_T;
          break;
      }
      return this.$options.filters.numFormat(comisionFinal, "$0,0");
    },

    /*
    showValorComisionT(item, mostrarMoneda) {
      var sumatoriaCom = 0;
      sumatoriaCom =
        parseInt(item.Comision_0) +
        parseInt(item.Comision_1) +
        parseInt(item.Comision_2);

      if (sumatoriaCom == 0) {
        return "-";
      }

      if (mostrarMoneda) {
        return this.$options.filters.numFormat(sumatoriaCom, "$0,0");
      }
    },
  */
    getPorcentajeT(item) {
      var sumatoriaObj = 0;
      var sumatoriaComprados = 0;
      var porcentajeTotal = 0;
      sumatoriaObj =
        parseInt(item.Objetivo_0) +
        parseInt(item.Objetivo_1) +
        parseInt(item.Objetivo_2);
      sumatoriaComprados =
        parseInt(item.Comprados_0) +
        parseInt(item.Comprados_1) +
        parseInt(item.Comprados_2);

      if (sumatoriaObj > 0 && sumatoriaObj !== null) {
        porcentajeTotal = sumatoriaComprados / sumatoriaObj;
        if (porcentajeTotal >= 100){
            return '<v-icon>mdi-refresh</v-icon>';
        }else{
          return this.$options.filters.numFormat(porcentajeTotal, "%0,0");
        }
        
      }

      return "-";
    },

    showValorComision(item, periodo, mostrarMoneda) {
      if (this.mesPeriodo2 == null) {
        this.mesPeriodo2 = item.Periodo2;
        this.mesPeriodo1 = item.Periodo1;
        this.mesPeriodo0 = item.Periodo0;
      }

      var porcentaje = 0;
      var comision = 0;
      var comisionFinal = 0;

      switch (periodo) {
        case 0:
          if (item.Objetivo_0 > 0 && item.Objetivo_0 !== null) {
            //porcentaje = item.Comprados_0 / item.Objetivo_0;
            porcentaje = item.Comprados_0 / item.Objetivo_0;
            comision = item.Comision_0;
          }
          break;
        case 1:
          if (item.Objetivo_1 > 0 && item.Objetivo_1 !== null) {
            //porcentaje = item.Comprados_1 / item.Objetivo_1;
            porcentaje = item.Comprados_1 / item.Objetivo_1;
            comision = item.Comision_1;
            //return this.$options.filters.numFormat(porcentaje, "%0,0");
          }
          break;
        case 2:
          if (item.Objetivo_2 > 0 && item.Objetivo_2 !== null) {
            porcentaje = item.Comprados_2 / item.Objetivo_2;
            comision = item.Comision_2;
            //return this.$options.filters.numFormat(porcentaje, "%0,0");
          }
          break;
      }

      switch (true) {
        case porcentaje == 0:
          return "-";
          break;
        case porcentaje < 1:
          comisionFinal = comision * 0.5;
          break;
        case porcentaje == 1:
          comisionFinal = comision;
          break;
        case porcentaje > 1:
          comisionFinal = comision;
          break;
      }
      return this.$options.filters.numFormat(comisionFinal, "$0,0");
    },

    getPeriodoName(periodo) {
      //console.log(periodo);
      var month;
      var baseMonth;
      if (this.mesBase == 0) {
        baseMonth = moment().month() + 2;
      } else {
        baseMonth = this.mesBase * 3;
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

    getPorcentaje(item, periodo) {
      var porcentaje = 0;
      let percent = 0;
      switch (periodo) {
        case 0:
          if (item.Objetivo_0 > 0 && item.Objetivo_0 !== null) {
            //porcentaje = item.Comprados_0 / item.Objetivo_0;
            porcentaje = item.Comprados_0 / item.Objetivo_0;

            percent = this.$options.filters.numFormat(porcentaje, "%0,0");
          }
          break;
        case 1:
          if (item.Objetivo_1 > 0 && item.Objetivo_1 !== null) {
            //porcentaje = item.Comprados_1 / item.Objetivo_1;
            porcentaje = item.Comprados_1 / item.Objetivo_1;

            percent = this.$options.filters.numFormat(porcentaje, "%0,0");
          }
          break;
        case 2:
          if (item.Objetivo_2 > 0 && item.Objetivo_2 !== null) {
            porcentaje = item.Comprados_2 / item.Objetivo_2;

            percent = this.$options.filters.numFormat(porcentaje, "%0,0");
          }
          break;
      }
    
      if (porcentaje >= 1){
        return true;
      }else{
        return percent;
      }

      return "-";
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
