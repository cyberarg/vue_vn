<template>
  <v-app class="contenedor">
    <div>
      <div class="spacerh"></div>
      <v-card color="grey lighten-4">
        <!--
        <v-row>
          <v-col cols="12" sm="6" lg="8" class="paddedl">
            <v-combobox
              v-model="selectAnio"
              :items="itemsAnios"
              label="Año"
              @change="refreshEERR"
            ></v-combobox>
          </v-col>
        </v-row>
        -->

         <v-row align="start">
          <v-col cols="12" sm="12" lg="12" md="12">
            <GridComponentStock
              :pars="{
                grid: 'Grid1',
              }"
              :datos="this.datosDetalleGrid1"
              :headers="header_2"
              :loadingState="this.loadingdetalle_grid1"
              loadingText="Cargando datos..."
            ></GridComponentStock>
          </v-col>
        </v-row>

        <v-row align="start">
          <v-col cols="12" sm="12" lg="12" md="12">
            <GridComponentStock
              :pars="{
                grid: 'Grid2',
              }"
              :datos="this.datosDetalleGrid2"
              :headers="header_2"
              :loadingState="this.loadingdetalle_grid2"
              loadingText="Cargando datos..."
            ></GridComponentStock>
          </v-col>
        </v-row>
        <!--
        <v-row align="start">
          <v-col cols="12" sm="12" lg="12" md="12">
            <GridComponentStock
              :pars="{
                grid: 'Grid3',
              }"
              :datos="this.datosDetalleGrid3"
              :headers="header_anual"
              :loadingState="this.loadingdetalle_grid3"
              loadingText="Cargando datos..."
            ></GridComponentStock>
          </v-col>
        </v-row>
        -->
      </v-card>
    </div>
  </v-app>
</template>


<script>
import GridComponentStock from "@/components/propios/GridComponentStock.vue";
import { mapState, mapActions } from "vuex";
import moment from "moment";

export default {
  props: {
    pars: {
      type: Object,
      required: true,
    },
    solo_CE: {
      type: Boolean,
      required: true,
      default: false,
    },
  },

  name: "hnstock",
  components: {
    GridComponentStock,
  },

  data() {
    return {
      selectAnio: moment().format('YYYY'),
      itemsAnios: ["2019", "2020", "2021"],
      hncobrados: [],
      datosDetalleGrid1: [],
      datosDetalleGrid2: [],
      datosDetalleGrid3: [],
      header: [
        {
          text: "",
          value: "Tipo",
          align: "start",
          width: "",
        },
        { text: "Enero", value: "Valores.M1", align: "center", width: "" },
        { text: "Febrero", value: "Valores.M2", align: "center", width: "" },
        { text: "Marzo", value: "Valores.M3", align: "center", width: "" },
        { text: "Abril", value: "Valores.M4", align: "center", width: "" },
        { text: "Mayo", value: "Valores.M5", align: "center", width: "" },
        { text: "Junio", value: "Valores.M6", align: "center", width: "" },
        { text: "Julio", value: "Valores.M7", align: "center", width: "" },
        { text: "Agosto", value: "Valores.M8", align: "center", width: "" },
        {
          text: "Septiembre",
          value: "Valores.M9",
          align: "center",
          width: "",
        },
        { text: "Octubre", value: "Valores.M10", align: "center", width: "" },
        {
          text: "Noviembre",
          value: "Valores.M11",
          align: "center",
          width: "",
        },
        {
          text: "Diciembre",
          value: "Valores.M12",
          align: "center",
          width: "",
        },
        { text: "Total", value: "Valores.Total", align: "center", width: "" },
      ],

     

      header_2: [
        {
          text: "",
          value: "Tipo",
          align: "start",
          width: "",
        },
        { text: "Enero", value: "M1", align: "center", width: "" },
        { text: "Febrero", value: "M2", align: "center", width: "" },
        { text: "Marzo", value: "M3", align: "center", width: "" },
        { text: "Abril", value: "M4", align: "center", width: "" },
        { text: "Mayo", value: "M5", align: "center", width: "" },
        { text: "Junio", value: "M6", align: "center", width: "" },
        { text: "Julio", value: "M7", align: "center", width: "" },
        { text: "Agosto", value: "M8", align: "center", width: "" },
        {
          text: "Septiembre",
          value: "M9",
          align: "center",
          width: "",
        },
        { text: "Octubre", value: "M10", align: "center", width: "" },
        {
          text: "Noviembre",
          value: "M11",
          align: "center",
          width: "",
        },
        {
          text: "Diciembre",
          value: "M12",
          align: "center",
          width: "",
        },
        //{ text: "Total", value: "", align: "center", width: "" },
      ],
    };
  },

  watch: {
    solo_CE(newValue, oldValue) {
      this.setDefaultDataSource(newValue);
    },

    loadingdetalle_grid1(newValue) {
      if (!newValue) {
        if (this.solo_CE) {
          this.datosDetalleGrid1 = this.detalle_grid1;
        }else{
          this.datosDetalleGrid1 = this.detalle_grid1;
        }
      } else {
        this.datosDetalleGrid1 = [];
      }
    },

    loadingdetalle_grid2(newValue) {
      if (!newValue) {
        if (this.solo_CE) {
          this.datosDetalleGrid2 = this.detalle_grid2_CE;
        } else {
          this.datosDetalleGrid2 = this.detalle_grid2;
        }
      } else {
        this.datosDetalleGrid2 = [];
      }
    },

    loadingdetalle_grid3(newValue) {
      if (!newValue) {
        if (this.solo_CE) {
          this.datosDetalleGrid3 = this.detalle_grid3_CE;
        } else {
          this.datosDetalleGrid3 = this.detalle_grid3;
        }
      } else {
        this.datosDetalleGrid3 = [];
      }
    },
  },

  computed: {
    ...mapState("stockhn", [
      "detalle_grid1",
      "detalle_grid1_CE",
      "loadingdetalle_grid1",
      "detalle_grid2",
      "detalle_grid2_CE",
      "loadingdetalle_grid2",
      "detalle_grid3",
      "detalle_grid3_CE",
      "loadingdetalle_grid3",
    ]),

    codMarca() {
      return this.pars.marcaSelected;
    },

    codConcesionario() {
      return this.pars.concesSelected;
    },
    //...mapState("cotizaciondolar", ["cotizacionesLocal"])
  },

  created() {
    this.setDefaultDataSource(this.solo_CE);
  },

  methods: {
    ...mapActions({
      getHNStock: "stockhn/getHNStock",
    }),

    setDefaultDataSource(soloCE) {
      if (soloCE) {
        this.datosDetalleGrid1 = this.detalle_grid1;
        this.datosDetalleGrid2 = this.detalle_grid2_CE;
       // this.datosDetalleGrid3 = this.detalle_grid3;
      } else {
        this.datosDetalleGrid1 = this.detalle_grid1;
        this.datosDetalleGrid2 = this.detalle_grid2;
       // this.datosDetalleGrid3 = this.detalle_grid3;
      }
    },

    refreshEERR() {
      var pars = {
        Marca: this.codMarca,
        Concesionario: this.codConcesionario,
        Empresa: 8,
        Anio: this.selectAnio,
        Filtros: "",
      };
      //console.log(pars);
      this.getHNEERR(pars);
    },

    formatFecha(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM/YYYY");
      }
    },
  },
};
</script>

<style scoped>
.padded {
  padding-left: 20px;
  padding-right: 20px;
  padding-bottom: 50px;
}

.paddedl {
  padding-left: 20px;
}

.space {
  padding-right: 5px;
}

.contenedor {
}

.fullw {
  width: 100%;
}

.maxH {
  height: 350px;
  max-height: 350px;
}

.spacerh {
  padding-top: 10px;
}
</style>
