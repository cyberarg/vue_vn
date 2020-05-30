<template>
  <v-app>
    <div class="contenedor">
      <v-card color="grey lighten-4" class="padded">
        <v-card-title>
          Haberes Netos
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
        </v-card-title>
        <v-row>
          <v-col cols="12" sm="6" lg="8">
            <v-combobox v-model="select" :items="items" label="Tipo de Compra" multiple chips></v-combobox>
          </v-col>
          <v-col cols="12" sm="6" lg="2">
            <v-checkbox label="Interempresa"></v-checkbox>
          </v-col>
          <v-col cols="12" sm="6" lg="2">
            <v-btn class="ma-2" outlined text @click>Actualizar</v-btn>
          </v-col>
        </v-row>
        <v-tabs v-model="tab" class="elevation-2" grow>
          <v-tabs-slider></v-tabs-slider>
          <v-tab v-for="tab in tabitems" :key="tab.Codigo">{{ tab.Nombre }}</v-tab>
        </v-tabs>
        <div class="spacerh"></div>
        <v-tabs-items v-model="tab">
          <!-- <v-tab-item v-for="tab in tabitems" :key="tab.Codigo">-->
          <v-tab-item>
            <v-card flat>
              <GridComponentHN
                :pars="{
                grid: 'HNVigentes',
                }"
                :datos="datos"
                :headers="headers"
                :loadingDatos="loadingDatos"
              ></GridComponentHN>
            </v-card>
          </v-tab-item>
          <v-tab-item>
            <v-card flat>
              <GridComponentHN
                :pars="{
                grid: 'HNCobrados',
                }"
                :datos="hncobrados"
                :headers="headerscobrados"
                :loadingDatos="loadingDatos"
              ></GridComponentHN>
            </v-card>
          </v-tab-item>
          <v-tab-item>
            <v-card flat>Resumen</v-card>
          </v-tab-item>
          <v-tab-item>
            <v-card flat>EERR</v-card>
          </v-tab-item>
          <v-tab-item>
            <v-card flat>Proyectado</v-card>
          </v-tab-item>
        </v-tabs-items>
        <v-card-actions>
          <v-btn cclass="ma-2" outlined text @click>
            <v-icon left>mdi-cart-arrow-down</v-icon>Compra
          </v-btn>
          <v-btn cclass="ma-2" outlined text @click>
            <v-icon left>mdi-file-import-outline</v-icon>Imp. HN Subite
          </v-btn>
          <div class="space"></div>
          <v-btn cclass="ma-2" outlined text @click>
            <v-icon left>mdi-calculator</v-icon>Calculo Aut. Monto Cobro
          </v-btn>
          <div class="space"></div>
          <v-btn cclass="ma-2" outlined text @click>
            <v-icon left>mdi-refresh</v-icon>Calculo Aut. Rescindidos
          </v-btn>
          <v-spacer></v-spacer>

          <v-btn cclass="ma-2" outlined text @click>
            <v-icon left>mdi-file-excel-outline</v-icon>Excel
          </v-btn>
          <div class="space"></div>
          <v-btn cclass="ma-2" outlined text @click>
            <v-icon left>mdi-arrow-left</v-icon>Volver
          </v-btn>
        </v-card-actions>
      </v-card>
    </div>
  </v-app>
</template>

<script>
import GridComponentHN from "@/components/propios/GridComponentHN.vue";
import { mapState, mapActions } from "vuex";
import moment from "moment";

export default {
  name: "haberesnetos",
  components: {
    GridComponentHN
    //GridFormCrud
  },
  data() {
    return {
      tab: null,
      tabitems: [
        { Codigo: 1, Nombre: "HN Vigentes" },
        { Codigo: 2, Nombre: "HN Cobrados" },
        { Codigo: 3, Nombre: "Resumen" },
        { Codigo: 4, Nombre: "EERR" },
        { Codigo: 5, Nombre: "Proyectado" }
      ],
      select: [],
      items: [
        "Finanzas",
        "Corrección de Mora",
        "Corrección de Vta.",
        "Convencional",
        "Conflictos",
        "Toma Plan",
        "Para Venta de Adjudicados",
        "Compra Planes para Autos"
      ],
      hncobrados: [],
      headers: [
        {
          text: "Empresa OP",
          align: "center",
          value: "Operacion.Empresa",
          width: "25%"
        },
        { text: "Grupo", value: "Operacion.Grupo", align: "center" },
        { text: "Orden", value: "Operacion.Orden", align: "center" },
        { text: "Titular", value: "Titular", align: "center" },
        { text: "Fecha Compra", value: "FechaCompra", align: "center" },
        { text: "Tipo Compra", value: "TipoCompra", align: "center" },
        { text: "Grupo Toma Plan", value: "GrupoTomaPlan", align: "center" },
        { text: "Orden Toma Plan", value: "OrdenTomaPlan", align: "center" },
        { text: "Rescindido", value: "Rescindido", align: "center" },
        { text: "CPG", value: "Operacion.CPG", align: "center" },
        { text: "Avance", value: "Operacion.Avance", align: "center" },
        { text: "Tipo Plan", value: "Operacion.TipoPlan", align: "center" },
        { text: "Modelo", value: "Operacion.NomModelo", align: "center" },
        { text: "Monto Compra", value: "MontoCompra", align: "center" },
        {
          text: "Monto Compra en U$S",
          value: "MontoCompraDolares",
          align: "center"
        },
        { text: "HN Subite", value: "HaberNetoSubite", align: "center" },
        {
          text: "HN Subite en U$S",
          value: "HaberNetoSubiteUSD",
          align: "center"
        },
        {
          text: "Utilidad Entrada en U$S",
          value: "UtilidadEntradaUSD",
          align: "center"
        },
        {
          text: "Precio Auto Actual",
          value: "PrecioAutoActual",
          align: "center"
        },
        { text: "Duration", value: "Duration", align: "center" },
        { text: "Duration Compra", value: "DurationCompra", align: "center" },
        { text: "TIR", value: "TIR", align: "center" },
        { text: "Fecha Cuota 84", value: "FechaCuota84", align: "center" },
        {
          text: "Conces. Propio",
          value: "ConcesionarioPropio",
          align: "center"
        },
        { text: "Nro. Transf.", value: "NroTransferencia", align: "center" }
      ],
      headerscobrados: [
        {
          text: "Empresa OP",
          align: "center",
          value: "Operacion.Empresa",
          width: "25%"
        },
        { text: "Grupo", value: "Operacion.Grupo", align: "center" },
        { text: "Orden", value: "Operacion.Orden", align: "center" },
        { text: "Titular", value: "Titular", align: "center" },
        { text: "Fecha Compra", value: "FechaCompra", align: "center" },
        { text: "Tipo Compra", value: "TipoCompra", align: "center" },
        { text: "Grupo Toma Plan", value: "GrupoTomaPlan", align: "center" },
        { text: "Orden Toma Plan", value: "OrdenTomaPlan", align: "center" },
        { text: "Rescindido", value: "Rescindido", align: "center" },
        { text: "Monto Compra", value: "MontoCompra", align: "center" },
        {
          text: "Monto Compra en U$S",
          value: "MontoCompraDolares",
          align: "center"
        },
        { text: "Monto Cobro Real", value: "MontoCobroReal", align: "center" },
        {
          text: "Monto Cobro Real en U$S",
          value: "MontoCobroRealDolares",
          align: "center"
        },
        { text: "Fecha Cobro Real", value: "FechaCobroReal", align: "center" },
        {
          text: "Utilidad",
          value: "Utilidad",
          align: "center"
        },
        {
          text: "Utilidad en U$S",
          value: "UtilidadUSD",
          align: "center"
        },
        { text: "CPG", value: "Operacion.CPG", align: "center" },
        { text: "Avance", value: "Operacion.Avance", align: "center" },
        { text: "Tipo Plan", value: "Operacion.TipoPlan", align: "center" },
        { text: "Modelo", value: "Operacion.NomModelo", align: "center" },
        { text: "HN Subite", value: "HaberNetoSubite", align: "center" },
        {
          text: "Dif. de Cobro",
          value: "DifCobro",
          align: "center"
        },
        {
          text: "Precio Auto Actual",
          value: "PrecioAutoActual",
          align: "center"
        },
        { text: "Duration", value: "Duration", align: "center" },
        { text: "TIR", value: "TIR", align: "center" },
        { text: "Fecha Cuota 84", value: "FechaCuota84", align: "center" },
        {
          text: "Conces. Propio",
          value: "ConcesionarioPropio",
          align: "center"
        },
        { text: "Nro. Transf.", value: "NroTransferencia", align: "center" }
      ]
    };
  },

  computed: {
    ...mapState("haberneto", ["datos", "loadingDatos"])
  },

  created() {
    this.getHNVigentes();
    this.showCotizaciones();
  },

  methods: {
    ...mapActions({
      getHNVigentes: "haberneto/getHNVigentes",
      getHNCobrados: "haberneto/getHNCobrados",
      showCotizaciones: "cotizaciondolar/getCotizacion"
    }),

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
.contenedor {
  width: 100%;
}

.padded {
  padding-left: 20px;
  padding-right: 20px;
  height: 100%;
}

.space {
  padding-right: 5px;
}

.spacerh {
  padding-top: 10px;
}
</style>
