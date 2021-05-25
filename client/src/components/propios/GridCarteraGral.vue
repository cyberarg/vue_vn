<template>
  <div class="contenedor">
    <v-data-table
      dense
      :headers="headers"
      :items="datos_cartera"
      :search="search"
      item-key="itemkey"
      class="elevation-1"
      :loading="loading_cartera"
      :loading-text="loadingtext"
      no-data-text="No hay datos disponibles"
      :hide-default-footer="true"
    >

    <template slot="body.append">
        <tr>
          <td class="total">Totales</td>
          <td class="totales">{{ sumField("Menor45") | numFormat("0,0") }}</td>
          <td class="totales">{{ sumField("Entre45y60") | numFormat("0,0") }}</td>
          <td class="totales">{{ sumField("Mayor60") | numFormat("0,0") }}</td>
          <td class="totales">{{ sumFields("Menor45", "Entre45y60", "Mayor60") | numFormat("0,0") }}</td>
        </tr>
      </template>

      
    </v-data-table>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  name: "gridcarteragralcomponent",
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
      default: "Cargando Datos Cartera... Aguarde...",
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
          text: "Marca",
          align: "start",
          value: "NomMarca",
        },
        { text: "Menor a 45", value: "Menor45", align: "center" },
        {
          text: "Entre 45 y 60",
          value: "Entre45y60",
          align: "center",
        },
        {
          text: "Mayor a 60",
          value: "Mayor60",
          align: "center",
        },
        
        {
          text: "Total",
          value: "CantDatos",
          align: "center",
        },
      ],
    };
  },

  created() {
    this.getDatosCartera();
  },

  computed: {
    ...mapState("tablerocontrol", [
      "dataStatus",
      "datos_cartera",
      "items_cartera",
      "loading_cartera",
    ]),
  },

  methods: {
    ...mapActions({ getDatosCartera: "tablerocontrol/getCarteraGral" }),

    sumField(key) {
      // sum data in give key (property)
      return this.items_cartera.reduce((a, b) => a + parseInt(b[key] || 0), 0);
    },

    sumFields(key1, key2, key3) {
      // sum data in give key (property)
      var tot1 = this.items_cartera.reduce((a, b) => a + parseInt(b[key1] || 0), 0);
      var tot2 = this.items_cartera.reduce((a, b) => a + parseInt(b[key2] || 0), 0);
      var tot3 = this.items_cartera.reduce((a, b) => a + parseInt(b[key3] || 0), 0);

      return parseInt(tot1) + parseInt(tot2) + parseInt(tot3) ;
    },
    /*
    getTotal(item) {
      var tot = 0;
      tot = parseInt(item.ComprasAyer) + parseInt(item.ComprasHoy);
      return this.$options.filters.numFormat(tot, "0,0");
    },
    */
  },
};
</script>

<style lang="scss" scoped>
.contenedor {
  height: 15px;
}

.total {
  font-weight: bold;
}

.totales {
  font-weight: bold;
  text-align: center;
}
</style>