<template>
  <div>
    <v-card color="grey lighten-4">
      <v-card-title>
        Importar Datos
        <v-spacer></v-spacer>
        <UploadExcelComponent
          :on-success="handleSuccess"
          :before-upload="beforeUpload"
        />
        <v-btn
          class="ma-2"
          outlined
          color="blue darken-1"
          text
          @click="procesarRegistrosDatos()"
          :disabled="disableButton"
          ><v-icon left>mdi-cog-outline</v-icon>Procesar</v-btn
        >
      </v-card-title>

      <v-data-table
        :headers="headers"
        :items="tableData"
        item-key="Codigo"
        :items-per-page="-1"
        class="elevation-1"
        :loading="loading"
        loading-text="Cargando Datos... Aguarde"
        no-data-text="Seleccione la planilla de Datos a importar."
        show-select
        v-model="selected"
        :single-select="singleSelect"
      >
        <template v-slot:item.GrupoOrden="{ item }">
          {{ item.Grupo }}-{{ item.Orden }}
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
            {{ item.Accion.Texto }}
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
  </div>
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
      singleSelect: false,
      selected: [],
      disableButton: true,
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
    ...mapState("importardatos", ["respuesta", "unselect", "obtuvoRespuesta", "dataStatusMsg", "dataStatus"])
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
      alert('Atención - El tamaño del archivo no puede superar los 3 MB');
     console.log({
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

    preSelect(items) {

      items.forEach(element => {
        if (element.Procesar){
          this.selected.push(element);
        }
      });
     
    },

    async conciliarDatos(coleccion) {
      await this.importarDatos(coleccion);
      this.tableData = this.respuesta;
      this.preSelect(this.respuesta);
      this.disableButton = false;
      this.loading = false;
    },

    async procesarRegistrosDatos() {

      if (this.selected.length > 0) {
        this.loading = true;
        var params = {
          data: this.selected,
          login: localStorage.getItem("login")
        };
        console.log(params);
        await this.procesarRegistros(params);
          this.loading = false;
           this.disableButton = true;
          this.showSwal();
          

      }

    },
    showSwal() {
      //this.$swal("Good job!", dataStatusMsg, dataStatus);
      this.$swal(this.dataStatusMsg, "", this.dataStatus);
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