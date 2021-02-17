<template>
  <div class="contenedor">
    <v-data-table
      dense
      :items="datos"
      :item-class="setClass"
      :headers="headers"
      class="elevation-1"
      :loading="loadingState"
      :loading-text="loadingText"
      no-data-text="No hay datos disponibles."
      hide-default-footer
      :disable-filtering="true"
      :disable-sort="true"
    >
      <template v-slot:item.RB="{ item }">{{ setValor(item.Item, item.RB) }}</template>
      <template v-slot:item.CG="{ item }">
        {{
        setValor(item.Item, item.CG)
        }}
      </template>
      <template v-slot:item.AN="{ item }">
        {{
        setValor(item.Item, item.AN)
        }}
      </template>
      <template v-slot:item.CE="{ item }">
        {{
        setValor(item.Item, item.CE)
        }}
      </template>
      <template v-slot:item.Total="{ item }">
        {{
        setValor(item.Item, item.Total)
        }}
      </template>

      <template v-slot:item.CostoUSD="{ item }">
        {{
        setValor("9", item.CostoUSD)
        }}
      </template>
      <template v-slot:item.CobrosUSD="{ item }">
        {{
        setValor("9", item.CobrosUSD)
        }}
      </template>
      <template v-slot:item.DiferenciaUSD="{ item }">
        {{
        setValor("9", item.DiferenciaUSD)
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
      if (valor == 0) {
        return "-";
      }

      switch (tipo) {
        case "1":
          return this.$options.filters.numFormat(valor, "0,0");
          break;
        case "2":
        case "3":
        case "4":
        case "6":
        case "8":
          if (valor < 0){
            return "(USD " + this.$options.filters.numFormat(valor * -1, "0,0") + ")";
          }
          return "USD " + this.$options.filters.numFormat(valor, "0,0");
          break;
        case "9":
          if (valor < 0){
            return "(USD " + this.$options.filters.numFormat(valor * -1, "0,0") + ")";
          }
          return "USD " + this.$options.filters.numFormat(valor, "0,0");
        case "10":
          return this.$options.filters.numFormat(valor, "0,0");
        default:
          return this.$options.filters.numFormat(valor, "0,0");
          break;
      }
    },

    formatFecha(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM/YYYY");
      }
    }, 

    setClass(item) {
      switch(item.Class){
        case "gray1":
          return "gray1";
        case "gray2":
          return "gray2";
        default:
          return "";
      }
    }
  }
};
</script>

<style scoped>
.maxHeight {
  height: 150px;
  max-height: 150px;
  padding-bottom: 5px;
}

.contenedor {
  width: 100%;
  height: 100%;
}

.v-data-table >>> td {
  font-size: 16px;
  white-space: nowrap;
}

.v-data-table >>> .gray1 {
  background: #e4e2e2;
}

.v-data-table >>> .gray2 {
  background: #989998;
}
</style>
