<template>
  <div class="contenedor">
    <v-card hover elevation-2 color="grey lighten-4">
      <v-card-title>
        Evolución Compras
        <v-spacer></v-spacer>
        <v-btn
            outlined
            color="success"
            text
            small
            :disabled="this.loading"
            :loading="this.loading"
            @click="detalleExcel()"
        >
            <v-icon left>mdi-file-excel</v-icon>Detalle
        </v-btn>
      </v-card-title>      

        
      <v-data-table
        dense
        :headers="headers"
        :items="items"
        :search="search"
        item-key="itemkey"
        class="elevation-1"
        :loading="loading"
        :loading-text="loadingtext"
        no-data-text="No hay datos disponibles"
        :hide-default-footer="true"
        :items-per-page="-1"
      >
        <template v-slot:item.Total="{item}">{{getTotal(item)}}</template>

        <template slot="body.append">
          <tr>
            <td class="total">Totales</td>
            <td class="totales">{{ sumField("ComprasAyer") | numFormat("0,0") }}</td>
            <td class="totales">{{ sumField("ComprasHoy") | numFormat("0,0") }}</td>
            <td class="totales">{{ sumFields("ComprasAyer", "ComprasHoy") | numFormat("0,0") }}</td>
          </tr>
        </template>

      </v-data-table>
    </v-card>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  name: "gridcontrolcomponent",
  props: {
    itemkey: {
      type: String,
      default: "",
    },
    search: {
      type: String,
      default: "",
    },
    loadingtext: {
      type: String,
      default: "Cargando Datos... Aguarde...",
    },
    dense: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      headers: [
        {
          text: "Concesionario",
          align: "start",
          value: "NomConcesionario",
        },
        { text: "Hasta Ayer", value: "ComprasAyer", align: "center" },
        {
          text: "Hoy",
          value: "ComprasHoy",
          align: "center",
        },
        {
          text: "Total",
          value: "Total",
          align: "center",
        },
      ],
    };
  },

  created() {
    this.getDatos();
  },

  computed: {
    ...mapState("tablerocontrol", [
      "dataStatus",
      "items",
      "datos",
      "items_filtrados",
      "loading",
      "items_detalle_compras"
    ]),
  },

  methods: {
    ...mapActions({ 
        getDatos: "tablerocontrol/getDatos", 
        getDetalle: "tablerocontrol/getDetalle", 
      }),

    sumField(key) {
      // sum data in give key (property)
      return this.items.reduce((a, b) => a + parseInt(b[key] || 0), 0);
    },

    sumFields(key1, key2) {
      // sum data in give key (property)
      var tot1 = this.items.reduce((a, b) => a + parseInt(b[key1] || 0), 0);
      var tot2 = this.items.reduce((a, b) => a + parseInt(b[key2] || 0), 0);
      return parseInt(tot1) + parseInt(tot2);
    },

    getTotal(item) {
      var tot = 0;
      tot = parseInt(item.ComprasAyer) + parseInt(item.ComprasHoy);
      return this.$options.filters.numFormat(tot, "0,0");
    },

    async detalleExcelPendientes(){
      /*
      await this.getDetalle();

      console.log(this.items_detalle_compras);
      this.generateExcel();
      */

    },


    generateExcel(){
      
      const workbook = XLSX.utils.book_new();
      const filename = "DetalleCompras";
      
      let sheetname1 = "";
      let sheetname2 = "";

      //console.log(this.detalle_gral);
      this.items_detalle_compras.forEach(element => {
         
          let dataAyer = element.HastaAyer;
          let dataHoy = element.Hoy;

          let data1 = XLSX.utils.json_to_sheet(dataAyer);
          let data2 = XLSX.utils.json_to_sheet(dataHoy);
         
          sheetname1 = 'Hasta Ayer';
          sheetname2 = 'Hoy';
         
          XLSX.utils.book_append_sheet(workbook, data1, sheetname1);
          XLSX.utils.book_append_sheet(workbook, data2, sheetname2);
        });
       
      

      XLSX.writeFile(workbook, `${filename}.xlsx`);


    },

  },
};
</script>

<style lang="scss" scoped>

.total {
  font-weight: bold;
}

.totales {
  font-weight: bold;
  text-align: center;
}
</style>