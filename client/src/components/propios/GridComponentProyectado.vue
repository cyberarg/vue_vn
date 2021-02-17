<template>
  <div class="contenedor">
    <v-data-table
      dense
      :items="datos"
      :headers="headers"
      class="elevation-1 small cell-height"
      :loading="loadingState"
      :loading-text="loadingText"
      no-data-text="No hay datos disponibles."
      hide-default-footer
      :disable-filtering="true"
      :disable-sort="true"
      :header-props="{ sortIcon: null }"
    >
      <template v-slot:item.Tipo="{ item }" >
        <div class="small titulo">
          {{ item.Tipo }}
        </div>
      </template>
      <template v-slot:item.Valores.M1="{ item }">
        <div class="small">
        {{ setValor(item.Tipo, item.Valores.M1) }}
        </div>
      </template>
      <template v-slot:item.Valores.M2="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Valores.M2)
        }}
         </div>
      </template>
      <template v-slot:item.Valores.M3="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Valores.M3)
        }}
         </div>
      </template>
      <template v-slot:item.Valores.M4="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Valores.M4)
        }}
         </div>
      </template>
      <template v-slot:item.Valores.M5="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Valores.M5)
        }}
         </div>
      </template>
      <template v-slot:item.Valores.M6="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Valores.M6)
        }}
         </div>
      </template>
      <template v-slot:item.Valores.M7="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Valores.M7)
        }}
         </div>
      </template>
      <template v-slot:item.Valores.M8="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Valores.M8)
        }}
         </div>
      </template>
      <template v-slot:item.Valores.M9="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Valores.M9)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.M10="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Valores.M10)
        }}
         </div>
      </template>
      <template v-slot:item.Valores.M11="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Valores.M11)
        }}
         </div>
      </template>
      <template v-slot:item.Valores.M12="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Valores.M12)
        }}
         </div>
      </template>
      <template v-slot:item.Valores.Total="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Valores.Total)
        }}
        </div>
      </template>

      <template v-slot:item.Valores.A0="{ item }">
        <div class="small">
        {{ setValor(item.Tipo, item.Valores.A0) }}
        </div>
      </template>
      <template v-slot:item.Valores.A1="{ item }">
        <div class="small">
        {{ setValor(item.Tipo, item.Valores.A1) }}
        </div>
      </template>
      <template v-slot:item.Valores.A2="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Valores.A2)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.A3="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Valores.A3)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.A4="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Valores.A4)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.A5="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Valores.A5)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.A6="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Valores.A6)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.A7="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Valores.A7)
        }}
        </div>
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
    }, 
  },

  computed: {},

  methods: {
    setValor(tipo, valor) {
      if (valor == 0) {
        return "-";
      }
      var lastChar = tipo.slice(tipo.length - 1);

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

.small {
  font-size: 0.75rem;
  white-space: nowrap;
  vertical-align: middle;
}

.v-data-table tbody td {
  height: 5px !important;
  font-size: 0.75rem;
  padding-right: 2px !important;
  padding-left: 2px !important;
}

.v-data-table tbody th {
  height: 5px !important;
  font-size: 0.75rem;
  padding-right: 2px !important;
  padding-left: 2px !important;
}

.titulo {
  font-weight: bold;
}

</style>
