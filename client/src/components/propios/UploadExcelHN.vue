<template>
  <v-app>
    <h3></h3>
    <v-card>
      <v-card-title>
        Importación de Haber Neto
        <v-spacer></v-spacer>
        <UploadExcelComponent
          :on-success="handleSuccess"
          :before-upload="beforeUpload"
        />
        <v-spacer></v-spacer>

        <v-btn
          class="ma-2"
          outlined
          color="blue darken-1"
          text
          @click="procesarRegistrosHN()"
          >Procesasr</v-btn
        >
      </v-card-title>

      <v-data-table
        :headers="computedHeaders"
        :items="tableData"
        item-key="ID"
        class="elevation-1"
        :loading="loading"
        loading-text="Cargando Datos... Aguarde"
        show-select
        v-model="selected"
        :single-select="singleSelect"
      >
        <template v-slot:item.ImporteHN="{ item }">
          ${{ Math.round(item.ImporteHN) | numFormat }}
        </template>
        <template v-slot:item.PrecioMaximoCompra="{ item }">
          ${{ Math.round(item.PrecioMaximoCompra) | numFormat }}
        </template>
      </v-data-table>
    </v-card>
  </v-app>
</template>

<script>
import UploadExcelComponent from "@/components/UploadExcel/index.vue";
import DataTableActions from "@/components/data-tables/scenarios/DataTableActions.vue";
import { mapState, mapActions } from "vuex";

export default {
  name: "UploadExcelHN",
  components: { UploadExcelComponent, DataTableActions },
  data() {
    return {
      singleSelect: false,
      loading: false,
      selected: [],
      tableData: [],
      tableHeader: [],
      headers: [
        { text: "ID", value: "ID" },
        { text: "Grupo", value: "Grupo" },
        { text: "Orden", value: "Orden" },
        { text: "Haber Neto", value: "ImporteHN" },
        { text: "Avance", value: "Avance" },
        { text: "Precio Máx. Compra", value: "PrecioMaximoCompra" },
        { text: "Accion", value: "Accion" }
      ]
    };
  },
  methods: {
    ...mapActions({
      importarDatos: "importarhn/importarDatos",
      procesarRegistros: "importarhn/procesarRegistros"
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
      return false;
    },
    async handleSuccess({ results, header }) {
      //this.tableData = results;
      this.tableHeader = header;
      await this.importarDatos(results);
      this.loading = false;
      this.tableData = this.respuesta;
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
    }
  },
  computed: {
    ...mapState("importarhn", ["respuesta", "unselect", "loading"]),

    computedHeaders() {
      return this.headers.filter(header => header.text !== "ID");
    }
  }
};
</script>
