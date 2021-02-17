<template>
  <div>
    <v-data-table
      dense
      :items="datos"
      :headers="headers"
      class="elevation-1"
      :loading="loadingState"
      :loading-text="loadingText"
      no-data-text="No hay datos disponibles."
      hide-default-footer
    >
      <template v-slot:item.Valores.M1="{ item }">{{ setValor(item.Tipo, item.Valores.M1) }}</template>
      <template v-slot:item.Valores.M2="{ item }">
        {{
        setValor(item.Tipo, item.Valores.M2)
        }}
      </template>
      <template v-slot:item.Valores.M3="{ item }">
        {{
        setValor(item.Tipo, item.Valores.M3)
        }}
      </template>
      <template v-slot:item.Valores.M4="{ item }">
        {{
        setValor(item.Tipo, item.Valores.M4)
        }}
      </template>
      <template v-slot:item.Valores.M5="{ item }">
        {{
        setValor(item.Tipo, item.Valores.M5)
        }}
      </template>
      <template v-slot:item.Valores.M6="{ item }">
        {{
        setValor(item.Tipo, item.Valores.M6)
        }}
      </template>
      <template v-slot:item.Valores.M7="{ item }">
        {{
        setValor(item.Tipo, item.Valores.M7)
        }}
      </template>
      <template v-slot:item.Valores.M8="{ item }">
        {{
        setValor(item.Tipo, item.Valores.M8)
        }}
      </template>
      <template v-slot:item.Valores.M9="{ item }">
        {{
        setValor(item.Tipo, item.Valores.M9)
        }}
      </template>
      <template v-slot:item.Valores.M10="{ item }">
        {{
        setValor(item.Tipo, item.Valores.M10)
        }}
      </template>
      <template v-slot:item.Valores.M11="{ item }">
        {{
        setValor(item.Tipo, item.Valores.M11)
        }}
      </template>
      <template v-slot:item.Valores.M12="{ item }">
        {{
        setValor(item.Tipo, item.Valores.M12)
        }}
      </template>
      <template v-slot:item.Valores.Total="{ item }">
        {{
        setValor(item.Tipo, item.Valores.Total)
        }}
      </template>
    </v-data-table>
  </div>
</template>

<script>
import moment from "moment";

export default {
  props: {
    pars: {
      type: Object,
      required: true
    },
    headers: {
      type: Array,
      required: true
    },
    datos: {
      type: Array,
      required: true
    },
    loadingState: {
      type: Boolean,
      required: true
    },
    loadingText: {
      type: String,
      required: true
    }
  },

  computed: {},

  methods: {
    setValor(tipo, valor) {
      var lastChar = tipo.slice(tipo.length - 1);

      if (valor == 0 || typeof valor == "undefined") {
        return "-";
      }

      switch (this.pars.grid) {
        case "Grid1":
        case "Grid2":
          return "USD " + this.$options.filters.numFormat(valor, "0,0");
          break;
        case "Grid3":
          switch (tipo) {
            case "Rent. Total":
              return this.$options.filters.numFormat(valor, "%0,0");
              break;
            case "Duration":
              return this.$options.filters.numFormat(valor, "0.0");
              break;
            case "TIR":
              return this.$options.filters.numFormat(valor, "%0,0");
              break;
            default:
              return "USD " + this.$options.filters.numFormat(valor, "0,0");
              break;
          }

          break;
      }

      /*
      switch (lastChar) {
        case "$":
          return this.$options.filters.numFormat(valor, "$0,0");
          break;
        case "D":
          return "USD " + this.$options.filters.numFormat(valor, "0,0");
          break;
        case ")":
        case ".":
          return this.$options.filters.numFormat(valor, "0,0") + "%";
          break;
        default:
          return this.$options.filters.numFormat(valor, "0,0");
          break;
      }
      */
    },

    formatFecha(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM/YYYY");
      }
    }
  }
};
</script>

<style scoped>
.maxHeight {
  height: 180px;
  max-height: 180px;
  padding-bottom: 5px;
}

.v-data-table >>> td {
  font-size: 16px;
  white-space: pre;
}
</style>
