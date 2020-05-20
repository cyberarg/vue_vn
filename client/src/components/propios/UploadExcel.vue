<template>
  <v-app>
    <h3></h3>
    <v-card>
      <v-card-title>
        Importar Datos
        <v-spacer></v-spacer>
        <UploadExcelComponent
          :on-success="handleSuccess"
          :before-upload="beforeUpload"
        />
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
        :headers="headers"
        :items="tableData"
        :search="search"
        item-key="Grupo/Orden"
        class="elevation-1"
        :loading="loading"
        loading-text="Cargando Datos... Aguarde"
      >
        <template v-slot:item.GrupoOrden="{ item }">
          {{ item.Grupo }}/{{ item.Orden }}
        </template>
        <template v-slot:item.NroSolicitud="{ item }">
          <template v-if="obtuvoRespuesta">
            {{ item.NroSolicitud }}
          </template>
          <template v-else>
              <v-icon>fas fa-spinner fa-spin</v-icon>
          </template>
        </template>
        <template v-slot:item.Accion="{ item }">
          <template v-if="obtuvoRespuesta">
            {{ item.Accion }}
          </template>
          <template v-else>
              <v-icon>fas fa-spinner fa-spin</v-icon>
          </template>
        </template>
        <template v-slot:item.FechaVtoCuota="{ item }">
          <template v-if="obtuvoRespuesta">
            {{ formatFecha(item.FechaVtoCuota) }}
          </template>
          <template v-else>
              <v-icon>fas fa-spinner fa-spin</v-icon>
            </v-row>
          </template>
        </template>
        <template v-slot:item.ImporteHN="{ item }">
          ${{ Math.round(item.ImporteHN) | numFormat }}
        </template>
      </v-data-table>
    </v-card>
  </v-app>
</template>

<script>
import UploadExcelComponent from "@/components/UploadExcel/index.vue";
import DataTableActions from "@/components/data-tables/scenarios/DataTableActions.vue";
import { mapState, mapActions } from "vuex";
import moment from "moment";

export default {
  name: "UploadExcel",
  components: { UploadExcelComponent, DataTableActions },
  data() {
    return {
      search: "",
      loading: false,
      tableData: [],
      tableHeader: [],
      headers: [
        { text: "Grupo/Orden", value: "GrupoOrden" },
        { text: "Solicitud", value: "NroSolicitud", align: "center"  },
        { text: "Cliente", value: "ApellidoyNombre" },
        { text: "Domicilio", value: "Domicilio"},
        { text: "Telefono 1", value: "TelefonoFijo" },
        { text: "Telefono 2", value: "Celular"  },
        { text: "Fecha Vto. Cuota 2", value: "FechaVtoCuota", align: "center" },
        { text: "Haber Neto", value: "ImporteHN" },
        { text: "Accion", value: "Accion", align: "center" },
      ]
    };
  },

  computed: {
    ...mapState("importardatos", ["respuesta", "unselect", "obtuvoRespuesta"])
  },

  methods: {
    ...mapActions({
      importarDatos: "importardatos/importarDatos",
      procesarRegistros: "importardatos/procesarRegistros"
    }),

    beforeUpload(file) {
      const isLt1M = file.size / 3584 / 3584 < 1;
      if (isLt1M) {
        this.loading = true;
        return true;
      }
      this.$message({
        message: "Please do not upload files larger than 3m in size.",
        type: "warning"
      });
      this.loading = false;
      return false;
    },
    handleSuccess({ results, header }) {
      this.tableData = results;
      this.tableHeader = header;
      this.loading = true;
      this.conciliarDatos(results);
    },

    async conciliarDatos(coleccion) {
      await this.importarDatos(coleccion);
      this.tableData = this.respuesta;
      this.loading = false;
    },

    async procesarRegistrosHN() {
      if (this.selected.length > 0) {
        this.loading = true;
        var params = {
          data: this.selected,
          login: localStorage.getItem("login")
        };
        //console.log(params);
        await this.procesarRegistros(params);
        if (this.unselect) {
          this.selected = [];
          this.loading = false;
        }
      }
    },
    formatFecha(fecha) {
      if (fecha != "" && this.obtuvoRespuesta) {
        return moment(fecha).format("DD/MM/YYYY");
      } else {
        return "";
      }
    }
  }
};
</script>

<style scoped>

.v-data-table {
  font-size: 8px;
}

</style>