<template>
  <v-app>
    <v-data-table
      dense
      :items="datos"
      :headers="headers"
      class="elevation-1"
      :loading="loadingDatos"
      loading-text="Cargando Datos... Aguarde"
    >
      <template
        v-slot:item.Operacion.Empresa="{ item }"
      >{{ getTextEmpresa(item.Operacion.Empresa) }}</template>
      <template v-slot:item.Titular="{ item }">{{ getTextTipoTitular(item.Titular) }}</template>
      <template v-slot:item.FechaCompra="{ item }">{{ formatFecha(item.FechaCompra) }}</template>
      <template v-slot:item.Rescindido="{ item }">
        <v-simple-checkbox v-model="item.Rescindido == 1" disabled></v-simple-checkbox>
      </template>
      <template
        v-slot:item.Operacion.TipoPlan="{ item }"
      >{{ getTextTipoPlan(item.Operacion.TipoPlan) }}</template>
      <template v-slot:item.TipoCompra="{ item }">{{ getTextTipoCompra(item.TipoCompra) }}</template>
    </v-data-table>
  </v-app>
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
    loadingDatos: {
      type: Boolean,
      required: true
    }
  },
  methods: {
    formatFecha(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM/YYYY");
      }
    },

    getTextEmpresa(valor) {
      switch (valor) {
        case "1":
          return "Gestión Financiera S.A.";
          break;
        case "3":
          return "AutoNet S.A.";
          break;
        case "6":
          return "AutoCervo S.A.";
          break;
        case "8":
          return "Car Group Fusión";
          break;
        default:
          return valor;
          break;
      }
    },

    getTextTipoTitular(valor) {
      switch (valor) {
        case "1":
          return "Tercero";
          break;
        case "2":
          return "Propio";
          break;
        default:
          return valor;
          break;
      }
    },

    getTextTipoPlan(valor) {
      switch (valor) {
        case "1":
          return "100%";
          break;
        case "2":
          return "70/30";
          break;
        case "3":
          return "60/40";
          break;
        default:
          return valor;
          break;
      }
    },

    getTextTipoCompra(valor) {
      switch (valor) {
        case "1":
          return "Finanzas";
          break;
        case "2":
          return "Toma Plan";
          break;
        case "3":
          return "Corrección de Mora";
          break;
        case "4":
          return "Convencional";
          break;
        case "5":
          return "Conflictos";
          break;
        case "6":
          return "Corrección de Vta.";
          break;
        default:
          return valor;
          break;
      }
    }
  }
};
</script>

<style scoped>
</style>