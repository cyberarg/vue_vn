<template>
  <div class="isPadded">
    <v-data-table
      dense
      :headers="headers"
      :items="pars.items"
      :search="search"
      item-key="pars.itemkey"
      :class="elevationComp"
      :loading="pars.loading"
      :loading-text="pars.loadingtext"
      no-data-text="No hay datos disponibles"
      :hide-default-footer="ocultarPaginacion"
      :disable-sort="ocultarOredenamiento"
      :hide-default-header="ocultarHeaders"
    >
      
      <template v-slot:top>
        <template v-if="mostrarTitulo">
          <v-toolbar flat>
            <v-toolbar-title>{{ pars.titleform }}</v-toolbar-title>
          </v-toolbar>
        </template>
      </template>
      
      <template v-slot:item="{ item }">
        <template v-if="pars.grid == 'Resumen'">
          <tr>
            <td>{{ item.Nombre }}</td>
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <td
                  v-on="on"
                  class="center"
                  @click="showResumen(item.Nombre, item.Cantidad)"
                >{{ formatItem(item) }}</td>
              </template>
              <span>{{ getTooltipData(item) }}</span>
            </v-tooltip>
          </tr>
        </template>
        <template v-else>
          <tr>
            <td>{{ item.Tipo }}</td>
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <td
                  v-on="on"
                  class="center"
                  @click="showDetalle(item.Capa, item.Tipo, item.Casos, 0)"
                >{{ item.Casos }}</td>
              </template>

              <span>{{ getTooltipData(item.Casos) }}</span>
            </v-tooltip>
            <td class="center">{{ getPorcentaje(item) }}</td>
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <td
                  v-on="on"
                  class="center"
                  @click="showDetalle(item.Capa, item.Tipo, item.MontoHN, 1)"
                >${{ item.MontoHN | numFormat }}</td>
              </template>
              <span>{{ getTooltipDataHN(item.MontoHN) }}</span>
            </v-tooltip>
            <td class="center">{{ getPorcentajeHN(item) }}</td>
          </tr>
        </template>
      </template>

      <template v-slot:item.VerDatos="{ item }">
        <v-btn color="blue darken-1" text @click="getDato(item)">Ver Dato</v-btn>
      </template>
    </v-data-table>
  </div>
</template>
<script>
import { mapState, mapActions } from "vuex";
import XLSX from "xlsx";

export default {
  name: "NewGrid",
  props: {
    pars: {
      type: Object,
      required: true
    },
    solo_tabla:{
      type: Boolean,
      default: null
    },
    elevation: {
      type: Number,
      default: null
    },
    padding: {
      type: Number,
      default: null
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
    //this.$store.dispatch(this.module + "/getData", this.api);
  },

  computed: {

    elevationComp(){
      if (this.elevation !== null ){
        return 'elevation-' + this.elevation + ' table-striped';
      } 
      return 'elevation-5';
    },

    isPadded(){
      if (this.padding !== null ){
        return '';
      } 
      return 'padded';
    },

    mostrarTitulo(){
      if (this.solo_tabla !== null){
        return false;
      }
      return true;
    },

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

    exportable() {
      if (typeof this.pars.exportable !== "undefined") {
        return this.pars.exportable;
      } else {
        return true;
      }
    },

    ocultarPaginacion() {
      if (typeof this.pars.disablepagination !== "undefined") {
        return this.pars.disablepagination;
      } else {
        return true;
      }
    },
    ocultarOredenamiento() {
      if (typeof this.pars.disabledsort !== "undefined") {
        return this.pars.disabledsort;
      } else {
        return true;
      }
    },

    ocultarHeaders() {
      if (typeof this.pars.hideheaders !== "undefined") {
        return this.pars.hideheaders;
      } else {
        return false;
      }
    },

    ...mapState("reporteacompras", ["items_filtrados"])
  },

  methods: {
    exportExcel: function() {
      let data = XLSX.utils.json_to_sheet(this.items);
      const workbook = XLSX.utils.book_new();
      const filename = "devschile-admins";
      XLSX.utils.book_append_sheet(workbook, data, filename);
      XLSX.writeFile(workbook, `${filename}.xlsx`);
    },

    formatItem(item) {
      if (item.Nombre == "Nuevos Casos Mes Actual - Haber Neto") {
        return "$" + this.$options.filters.numFormat(item.Cantidad);
      } else {
        return item.Cantidad;
      }
    },

    getPorcentaje(item) {
      //console.log(item.TotalCasos);
      if (item.Tipo == "Total") {
        return "-";
      } else {
        if (item.TotalCasos > 0) {
          return Math.round((item.Casos * 100) / item.TotalCasos) + "%";
        }
        return "";
      }
    },

    getPorcentajeHN(item) {
      if (item.Tipo == "Total") {
        return "-";
      } else {
        if (item.TotalMontoHN > 0) {
          return Math.round((item.MontoHN * 100) / item.TotalMontoHN) + "%";
        }
        return "";
      }
    },

    getTooltipDataHN(cantidad) {
      if (cantidad > 0) {
        this.showToolTip = true;

        return "Haga click para ver el detalle del Monto HN $" + cantidad + ".";
      } else {
        this.showToolTip = false;
      }
    },

    getTooltipData(item) {
      if (item.Nombre == "Nuevos Casos Mes Actual - Haber Neto") {
        this.showToolTip = true;
        return (
          "Haga click para ver el detalle del Monto HN $" + item.Cantidad + "."
        );
      } else {
        if (item.Cantidad > 0) {
          this.showToolTip = true;
          if (item.Cantidad == 1) {
            return "Haga click para ver la operación.";
          }

          return "Haga click para ver las " + item.Cantidad + " operaciones.";
        } else {
          this.showToolTip = false;
        }
      }
    },

    async showResumen(tipo, cantidad) {
      var prop = "";

      switch (tipo) {
        case "SGA":
          prop = "EsPropio";
          break;
        case "Propios y Otras Sociedades":
          prop = "PropiosyOtrasSoc";
          break;
        case "Propios":
          prop = "EsPropio";
          break;
        case "Otras Sociedades":
          prop = "EsOtrasSociedades";
          break;
        case "Universo Compra":
          prop = "EsUniverso";
          break;
      }

      await this.showResumenPropios(prop);
      var titleForm = "Detalle de " + tipo + " - ";
      if (tipo == "Nuevos Casos Mes Actual - Haber Neto") {
        titleForm = titleForm + "Monto HN: $";
      } else {
        titleForm = titleForm + "Cantidad: ";
      }
      titleForm = titleForm + cantidad;

      this.$router.push({
        name: "detallereporte",
        params: {
          title: titleForm,
          volverARuta: "reportecompras",
          items_f: this.items_filtrados
        }
      });
    },

    async showDetalle(capa, tipo, cantidad, esMontoHN) {
      var prop = capa;
      var titleForm = "Detalle de " + tipo + " - ";

      if (esMontoHN) {
        titleForm = titleForm + "Monto HN: $";
      } else {
        titleForm = titleForm + "Cantidad: ";
      }
      titleForm = titleForm + cantidad;

      switch (this.pars.grid) {
        case "MesActual":
          await this.showDetalleAvanceMes(prop);
          break;
        case "Universo":
          await this.showDetalleUniverso(prop);
          break;
      }

      this.$router.push({
        name: "detallereporte",
        params: {
          title: titleForm,
          volverARuta: "reportecompras",
          items_f: this.items_filtrados
        }
      });
    },

    ...mapActions({
      showResumenPropios: "reporteacompras/showResumenPropios",
      showDetalleAvanceMes: "reporteacompras/showDetalleAvanceMes",
      showDetalleUniverso: "reporteacompras/showDetalleUniverso"
    })
  }
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

.center {
  text-align: center;
}

.v-data-table td {
  //font-size: 18px;
}

.bold {
  font-weight: bold;
}
</style>
