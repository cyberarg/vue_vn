<template>
  <div class="contenedor">
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
    ]),
  },

  methods: {
    ...mapActions({ getDatos: "tablerocontrol/getDatos" }),

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

.total {
  font-weight: bold;
}

.totales {
  font-weight: bold;
  text-align: center;
}
</style>