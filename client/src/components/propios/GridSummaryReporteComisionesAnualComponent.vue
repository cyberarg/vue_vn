<template>
  <div>
    <div>
      <v-card color="grey lighten-4">
        <v-data-table
          dense
          fixed-header
          locale="es"
          :headers="headers"
          :items="itemsAnual"
          :items-per-page="-1"
          :hide-default-footer="true"
          :hide-default-header="true"
          item-key="pars.itemkey"
          class="elevation-1"
          :loading="loadingAnual"
          loading-text="Cargando Datos... Aguarde"
          no-data-text="No hay datos disponibles."
        >
          <template v-slot:header="{ props }">
            <thead class="v-data-table-header">
              <tr>
                <th />
                <th colspan="4" class="text-center lineH1">Trimestre 1</th>
                <th colspan="4" class="text-center lineH1">Trimestre 2</th>
                <th colspan="4" class="text-center lineH1L">Trimestre 3</th>
                <th colspan="4" class="text-center lineH1L">Trimestre 4</th>
                <!--
                <th colspan="4" class="text-center lineH1L">Total Anual</th>
                -->
              </tr>
              <tr>
                <th class="text-center child-header">Oficial</th>

                <th class="text-center lineH2">Objetivo</th>
                <th class="text-center lineH2">Vigentes</th>
                <th class="text-center lineH2">% Cumplimiento</th>
                <th class="text-center lineH2">Comisión</th>

                <th class="text-center lineH2">Objetivo</th>
                <th class="text-center lineH2">Vigentes</th>
                <th class="text-center lineH2">% Cumplimiento</th>
                <th class="text-center lineH2">Comisión</th>

                <th class="text-center lineH2">Objetivo</th>
                <th class="text-center lineH2">Vigentes</th>
                <th class="text-center lineH2">% Cumplimiento</th>
                <th class="text-center lineH2">Comisión</th>

                <th class="text-center lineH2">Objetivo</th>
                <th class="text-center lineH2">Vigentes</th>
                <th class="text-center lineH2">% Cumplimiento</th>
                <th class="text-center lineH2L">Comisión</th>

                <!--
                <th class="text-center lineH2">Objetivo</th>
                <th class="text-center lineH2">Vigentes</th>
                <th class="text-center lineH2">% Cumplimiento</th>
                <th class="text-center lineH2L">Comisión</th>
                -->
              </tr>
            </thead>
          </template>

          <template
            v-slot:item.Vigentes_T1="{item}"
          >{{ showValoresTrim(item.Vigentes_1, item.Vigentes_2, item.Vigentes_3) }}</template>
          <template
            v-slot:item.Objetivo_T1="{item}"
          >{{ showValoresTrim(item.Objetivo_1, item.Objetivo_2, item.Objetivo_3) }}</template>
          <template v-slot:item.Comision_T1="{item}">{{ showValorComision(item, 1, true) }}</template>
          <template v-slot:item.PorcCumplimiento_T1="{item}">{{ getPorcentaje(item, 1) }}</template>

          <template
            v-slot:item.Vigentes_T2="{item}"
          >{{ showValoresTrim(item.Vigentes_4, item.Vigentes_5, item.Vigentes_6) }}</template>
          <template
            v-slot:item.Objetivo_T2="{item}"
          >{{ showValoresTrim(item.Objetivo_4, item.Objetivo_5, item.Objetivo_6)}}</template>
          <template v-slot:item.Comision_T2="{item}">{{ showValorComision(item, 2, true) }}</template>
          <template v-slot:item.PorcCumplimiento_T2="{item}">{{ getPorcentaje(item, 2) }}</template>

          <template
            v-slot:item.Vigentes_T3="{item}"
          >{{ showValoresTrim(item.Vigentes_7, item.Vigentes_8, item.Vigentes_9) }}</template>
          <template
            v-slot:item.Objetivo_T3="{item}"
          >{{ showValoresTrim(item.Objetivo_7, item.Objetivo_8, item.Objetivo_9) }}</template>
          <template v-slot:item.Comision_T3="{item}">{{ showValorComision(item, 3, true) }}</template>
          <template v-slot:item.PorcCumplimiento_T3="{item}">{{ getPorcentaje(item, 3) }}</template>

          <template
            v-slot:item.Vigentes_T4="{item}"
          >{{ showValoresTrim(item.Vigentes_10, item.Vigentes_11, item.Vigentes_12) }}</template>
          <template
            v-slot:item.Objetivo_T4="{item}"
          >{{ showValoresTrim(item.Objetivo_10, item.Objetivo_11, item.Objetivo_12) }}</template>
          <template v-slot:item.Comision_T4="{item}">{{ showValorComision(item, 4, true) }}</template>
          <template v-slot:item.PorcCumplimiento_T4="{item}">{{ getPorcentaje(item, 4) }}</template>

          <!--
          <template v-slot:item.Vigentes_T="{item}">{{ showValorT(item, false, false) }}</template>
          <template v-slot:item.Objetivo_T="{item}">{{ showValorT(item, true, false) }}</template>
          <template v-slot:item.Comision_T="{item}">{{ showValorComisionT(item, true) }}</template>
          <template v-slot:item.PorcCumplimiento_T="{item}">{{ getPorcentajeT(item) }}</template>
          -->
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
    headers: {
      type: Array,
      required: true,
    },
  },

  data() {
    return {
      showBotones: false,
      expanded: true,
      totalSub: 0,
    };
  },

  created() {
    //this.$store.dispatch(this.module + "/getData", this.api);
  },

  computed: {
    ...mapState("reportecomisiones", [
      "dataStatusAnual",
      "itemsAnual",
      "loadingAnual",
      "datosAnual",
      "empresa",
      "items_filtradosAnual",
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
          return "Haga click para ver el HN Vigente.";
        }
        return "Haga click para ver los " + cantidad + " HN Vigentes.";
      } else {
        this.showToolTip = false;
      }
    },

    showValorVigentes(item, trimestre) {
      var valor = 0;

      switch (trimestre) {
        case 1:
          //valor =
          return (
            parseInt(item.Vigentes_1) +
            parseInt(item.Vigentes_2) +
            parseInt(item.Vigentes_3)
          );
          break;
        case 2:
          //valor =
          return (
            parseInt(item.Vigentes_4) +
            parseInt(item.Vigentes_5) +
            parseInt(item.Vigentes_6)
          );
          break;
        case 3:
          //valor =
          return (
            parseInt(item.Vigentes_7) +
            parseInt(item.Vigentes_8) +
            parseInt(item.Vigentes_9)
          );
          break;
        case 4:
          //valor =
          return (
            parseInt(item.Vigentes_10) +
            parseInt(item.Vigentes_11) +
            parseInt(item.Vigentes_12)
          );
          break;
      }

      //return valor;
    },

    showValoresTrim(valor1, valor2, valor3) {
      var sum = parseInt(valor1) + parseInt(valor2) + parseInt(valor3);
      if (sum > 0) {
        return this.$options.filters.numFormat(sum, "0,0");
      }
      return "-";
    },

    showItem(valor1, valor2, valor3) {
      console.log(item);
    },

    showValorObjetivos(item, trimestre) {
      var valor = 0;

      switch (trimestre) {
        case 1:
          //valor =
          return (
            parseInt(item.Objetivo_1) +
            parseInt(item.Objetivo_2) +
            parseInt(item.Objetivo_3)
          );
          break;
        case 2:
          //valor =
          return (
            parseInt(item.Objetivo_4) +
            parseInt(item.Objetivo_5) +
            parseInt(item.Objetivo_6)
          );
          break;
        case 3:
          //valor =
          return (
            parseInt(item.Objetivo_7) +
            parseInt(item.Objetivo_8) +
            parseInt(item.Objetivo_9)
          );
          break;
        case 4:
          //valor =
          return (
            parseInt(item.Objetivo_10) +
            parseInt(item.Objetivo_11) +
            parseInt(item.Objetivo_12)
          );
          break;
      }

      //return valor;
    },

    showValorT(item, sonObjetivos, mostrarMoneda) {
      var sumatoria = 0;
      if (sonObjetivos) {
        sumatoria =
          //parseInt(item.Objetivo_0) +
          parseInt(item.Objetivo_1) + parseInt(item.Objetivo_2);
      } else {
        sumatoria =
          //parseInt(item.Objetivo_0) +
          parseInt(item.Vigentes_1) + parseInt(item.Vigentes_2);
      }

      console.log(sumatoria);
      if (sumatoria > 0) {
        if (mostrarMoneda) {
          return this.$options.filters.numFormat(sumatoria, "$0,0");
        }
        return this.$options.filters.numFormat(sumatoria, "0,0");
      }

      return "-";
    },

    showValorComisionT() {
      return "";
    },

    getPorcentajeT(item) {
      var sumatoriaObj = 0;
      var sumatoriaVigentes = 0;
      var porcentajeTotal = 0;
      sumatoriaObj =
        //parseInt(item.Objetivo_0) +
        parseInt(item.Objetivo_1) + parseInt(item.Objetivo_2);
      sumatoriaVigentes =
        //parseInt(item.Objetivo_0) +
        parseInt(item.Vigentes_1) + parseInt(item.Vigentes_2);

      if (sumatoriaObj > 0 && sumatoriaObj !== null) {
        porcentajeTotal = sumatoriaVigentes / sumatoriaObj;

        return this.$options.filters.numFormat(porcentajeTotal, "%0,0");
      }

      return "-";
    },

    showValorComision(item, trimestre, mostrarMoneda) {
      var porcentaje = 0;
      var comision = 0;
      var comisionFinal = 0;

      var sumObjetivo = 0;
      var sumVigentes = 0;
      var sumComision = 0;

      switch (trimestre) {
        case 1:
          sumObjetivo =
            parseInt(item.Objetivo_1) +
            parseInt(item.Objetivo_2) +
            parseInt(item.Objetivo_3);
          sumVigentes =
            parseInt(item.Vigentes_1) +
            parseInt(item.Vigentes_2) +
            parseInt(item.Vigentes_3);
          sumComision =
            parseInt(item.Comision_1) +
            parseInt(item.Comision_2) +
            parseInt(item.Comision_3);
          break;
        case 2:
          sumObjetivo =
            parseInt(item.Objetivo_4) +
            parseInt(item.Objetivo_5) +
            parseInt(item.Objetivo_6);
          sumVigentes =
            parseInt(item.Vigentes_4) +
            parseInt(item.Vigentes_5) +
            parseInt(item.Vigentes_6);
          sumComision =
            parseInt(item.Comision_4) +
            parseInt(item.Comision_5) +
            parseInt(item.Comision_6);
          break;
        case 3:
          sumObjetivo =
            parseInt(item.Objetivo_7) +
            parseInt(item.Objetivo_8) +
            parseInt(item.Objetivo_9);
          sumVigentes =
            parseInt(item.Vigentes_7) +
            parseInt(item.Vigentes_8) +
            parseInt(item.Vigentes_9);
          sumComision =
            parseInt(item.Comision_7) +
            parseInt(item.Comision_8) +
            parseInt(item.Comision_9);
          break;
        case 4:
          sumObjetivo =
            parseInt(item.Objetivo_10) +
            parseInt(item.Objetivo_11) +
            parseInt(item.Objetivo_12);
          sumVigentes =
            parseInt(item.Vigentes_10) +
            parseInt(item.Vigentes_11) +
            parseInt(item.Vigentes_12);
          sumComision =
            parseInt(item.Comision_10) +
            parseInt(item.Comision_11) +
            parseInt(item.Comision_12);
          break;
      }
      console.log(sumObjetivo);
      if (sumObjetivo > 0) {
        porcentaje = sumVigentes / sumObjetivo;
        comision = sumComision;
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

    getPorcentaje(item, trimestre) {
      var sumObjetivo = 0;
      var sumVigentes = 0;
      var porcentaje = 0;

      switch (trimestre) {
        case 1:
          sumObjetivo =
            parseInt(item.Objetivo_1) +
            parseInt(item.Objetivo_2) +
            parseInt(item.Objetivo_3);
          sumVigentes =
            parseInt(item.Vigentes_1) +
            parseInt(item.Vigentes_2) +
            parseInt(item.Vigentes_3);
          break;
        case 2:
          sumObjetivo =
            parseInt(item.Objetivo_4) +
            parseInt(item.Objetivo_5) +
            parseInt(item.Objetivo_6);
          sumVigentes =
            parseInt(item.Vigentes_4) +
            parseInt(item.Vigentes_5) +
            parseInt(item.Vigentes_6);
          break;
        case 3:
          sumObjetivo =
            parseInt(item.Objetivo_7) +
            parseInt(item.Objetivo_8) +
            parseInt(item.Objetivo_9);
          sumVigentes =
            parseInt(item.Vigentes_7) +
            parseInt(item.Vigentes_8) +
            parseInt(item.Vigentes_9);
          break;
        case 4:
          sumObjetivo =
            parseInt(item.Objetivo_10) +
            parseInt(item.Objetivo_11) +
            parseInt(item.Objetivo_12);
          sumVigentes =
            parseInt(item.Vigentes_10) +
            parseInt(item.Vigentes_11) +
            parseInt(item.Vigentes_12);

          break;
      }

      if (sumObjetivo > 0) {
        porcentaje = sumVigentes / sumObjetivo;
        return this.$options.filters.numFormat(porcentaje, "%0,0");
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
