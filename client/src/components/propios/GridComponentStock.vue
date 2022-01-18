<template>
  <div>
    <v-data-table
      dense
      :items="datos"
      :headers="headers"
      :item-class="setClass"
      class="elevation-1"
      :loading="loadingState"
      :loading-text="loadingText"
      no-data-text="No hay datos disponibles."
      hide-default-footer
    >
     
        <template v-slot:item.Tipo="{ item }" >
        <div class="titulo">
          {{ item.Tipo }}
        </div>
      </template>
        <template v-slot:item.M1="{ item }">{{ setValor(item.Tipo, item.M1) }}</template>
        <template v-slot:item.M2="{ item }">
          {{
          setValor(item.Tipo, item.M2)
          }}
        </template>
        <template v-slot:item.M3="{ item }">
          {{
          setValor(item.Tipo, item.M3)
          }}
        </template>
        <template v-slot:item.M4="{ item }">
          {{
          setValor(item.Tipo, item.M4)
          }}
        </template>
        <template v-slot:item.M5="{ item }">
          {{
          setValor(item.Tipo, item.M5)
          }}
        </template>
        <template v-slot:item.M6="{ item }">
          {{
          setValor(item.Tipo, item.M6)
          }}
        </template>
        <template v-slot:item.M7="{ item }">
          {{
          setValor(item.Tipo, item.M7)
          }}
        </template>
        <template v-slot:item.M8="{ item }">
          {{
          setValor(item.Tipo, item.M8)
          }}
        </template>
        <template v-slot:item.M9="{ item }">
          {{
          setValor(item.Tipo, item.M9)
          }}
        </template>
        <template v-slot:item.M10="{ item }">
          {{
          setValor(item.Tipo, item.M10)
          }}
        </template>
        <template v-slot:item.M11="{ item }">
          {{
          setValor(item.Tipo, item.M11)
          }}
        </template>
        <template v-slot:item.M12="{ item }">
          {{
          setValor(item.Tipo, item.M12)
          }}
        </template>


        <template v-slot:item.StockInicial="{ item }">
          {{
          setValor(item.Tipo, item.StockInicial)
          }}
        </template>
        <template v-slot:item.Compras="{ item }">
          {{
          setValor(item.Tipo, item.Compras)
          }}
        </template>
        <template v-slot:item.Cobros="{ item }">
          {{
          setValor(item.Tipo, item.Cobros)
          }}
        </template>
        <template v-slot:item.StockFinal="{ item }">
          {{
          setValor(item.Tipo, item.StockFinal)
          }}
        </template>
         <template v-slot:item.VariacionTotal="{ item }">
          {{
          setValor(item.Tipo, item.VariacionTotal)
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
    setValor(tipo = '', valor) {
      var lastChar = tipo.slice(tipo.length - 1);

      if (valor == 0 || valor == "0" || typeof valor == "undefined") {
        return "-";
      }


      switch (this.pars.grid) {
        
        case "Grid1":
          /*
          if (tipo == 'Tipo de Cambio'){
              return "$ " + this.$options.filters.numFormat(valor, "0,0");
          }
          return this.$options.filters.numFormat(valor, "0.00") + '%';
          */
          return valor;
          break;
        
        case "Grid2":
          if (valor < 0){
              let valNeg = valor * -1;
              return "(USD " + this.$options.filters.numFormat(valNeg, "0,0") + ")";
          }else{
            return "USD " + this.$options.filters.numFormat(valor, "0,0");
          }
          
          break;
        case "Grid3":
          return "USD " + this.$options.filters.numFormat(valor, "0,0");
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
  height: 180px;
  max-height: 180px;
  padding-bottom: 5px;
}

.titulo {
  font-weight: bold;
}

.v-data-table >>> td {
  font-size: 12px !important;
  height: 10px !important;
  white-space: nowrap;
  vertical-align: middle;
}

.v-data-table >>> th {
  font-size: 12px !important;
  height: 10px !important;
  padding-right: 4px !important;
}

.v-data-table >>> .gray1 {
  background: #e4e2e2;
}

.v-data-table >>> .gray2 {
  background: #989998;
}
</style>
