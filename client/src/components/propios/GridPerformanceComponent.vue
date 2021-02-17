<template>
  <div class="contenedor">
    <v-data-table
      dense
      :headers="headers"
      :items="items"
      :search="search"
      item-key="itemkey"
      class="elevation-1"
      :loading="false"
      :loading-text="loadingtext"
      no-data-text="No hay datos disponibles"
      :hide-default-footer="true"
    >
      <template v-slot:item.Total="{item}"></template>

      <template slot="body.append">
        <tr>
          <td class="total">Media</td>
          <td class="totales"></td>
          <td class="totales"></td>
          <td class="totales"></td>
          <td class="totales"></td>
          <td class="totales"></td>
          <td class="totales"></td>
          <td />
        </tr>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  name: "gridperformancecomponent",
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
    nodatatext: {
      type: String,
      default: "No hay datos disponibles.",
    },
    dense: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      items: [],
      headers: [
        {
          text: "Oficial",
          align: "start",
          value: "NomOficial",
        },
        { text: "Volumen", value: "Volumen", align: "center" },
        {
          text: "Efectividad",
          value: "Efectividad",
          align: "center",
        },
        {
          text: "Margen de Mejora",
          value: "MargenMejora",
          align: "center",
        },
        {
          text: "Reconocimiento Prom.",
          value: "ReconocimientoProm",
          align: "center",
        },
        {
          text: "Duration",
          value: "Duration",
          align: "center",
        },
        {
          text: "TIR",
          value: "TIR",
          align: "center",
        },
        {
          text: "Score",
          value: "Score",
          align: "center",
        },
      ],
    };
  },

  created() {
    //this.getDatos();
  },

  computed: {
    /*
    ...mapState("tablerocontrol", [
      "dataStatus",
      "items",
      "datos",
      "items_filtrados",
      "loading",
    ]),
    */
  },

  methods: {
    //...mapActions({ getDatos: "tablerocontrol/getDatos" }),

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
  },
};
</script>

<style lang="scss" scoped>
.contenedor {
  height: 150px;
}

.total {
  font-weight: bold;
}

.totales {
  font-weight: bold;
  text-align: center;
}
</style>