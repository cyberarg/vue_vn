<template>
  <v-app class="contenedor">
    <v-card color="grey lighten-4" class="padded">
      <v-row>
        <v-col cols="12" sm="6" lg="8">
          <v-combobox
            v-model="selectAnio"
            :items="itemsAnios"
            label="Año"
            @change="refreshResumen"
          ></v-combobox>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" sm="12" lg="12" md="12">
          <GridComponentResumenPerformance
            :pars="{
              grid: 'Resumen',
            }"
            :datos="this.datosGrid1"
            :headers="this.headers"
            :loadingState="this.loading_resumen"
            loadingText="Cargando Cuadro Resumen..."
          ></GridComponentResumenPerformance>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" sm="12" lg="12" md="12">
          <GridComponentResumenPerformance
            :pars="{
              grid: 'Flujo',
            }"
            :datos="this.datosGrid2"
            :headers="this.headers_saldo"
            :loadingState="this.loading_resumen"
            loadingText="Cargando Cuadro Saldo de Caja..."
          ></GridComponentResumenPerformance>
        </v-col>
      </v-row>
  
    </v-card>
  </v-app>
</template>

<script>
import GridComponentResumenPerformance from "@/components/propios/GridComponentResumenPerformance.vue";
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
    },
  },

  name: "hnresumenperformance",
  components: {
    GridComponentResumenPerformance,
  },

  data() {
    return {
      selectAnio: moment().format('YYYY'),
      datosDetalleMeses: [],
      itemsAnios: [
        "2020",
        "2021",

      ],

      headers_1: [
        { text: "", value: "Tipo", align: "left", width: "45px" },
        { text: "RB", value: "RB", align: "center", width: "20px" },
        {
          text: "Car Group",
          value: "CG",
          align: "center",
          width: "20px",
        },
        { text: "AutoNet", value: "AN", align: "center", width: "20px" },
       
        {
          text: "Total",
          value: "Total",
          align: "center",
          width: "20px",
        },
      ],

      headers_saldo: [
        { text: "", value: "Tipo", align: "left", width: "45px" },
        { text: "Comprado (Costo)", value: "CostoUSD", align: "center", width: "20px" },
        {
          text: "Cobrado",
          value: "CobrosUSD",
          align: "center",
          width: "20px",
        },
        {
          text: "Diferencia",
          value: "DiferenciaUSD",
          align: "center",
          width: "20px",
        },
      ],

       headers_ce: [
        { text: "", value: "Tipo", align: "left", width: "45px" },
        { text: "Giama", value: "RB", align: "center", width: "20px" },
        {
          text: "Concesionario",
          value: "CE",
          align: "center",
          width: "20px",
        },

       
        {
          text: "Total",
          value: "Total",
          align: "center",
          width: "20px",
        },
      ],

      null_headers: [
        { text: "", value: "Tipo", align: "left", width: "45px" },
        { text: "", value: "RB", align: "center", width: "20px" },
        {
          text: "",
          value: "CE",
          align: "center",
          width: "20px",
        },

       
        {
          text: "Total",
          value: "Total",
          align: "center",
          width: "20px",
        },
      ],
      
    };
  },

  watch: {
    solo_CE(newValue, oldValue) {
      this.setDefaultDataSource(newValue);
    },

    change_Marca(newValue, oldValue) {
      this.setDefaultHeaders(newValue);
    },

    loading_resumen(newValue) {
      if (!newValue) {
        if (this.switchCE) {
          this.datosGrid1 = this.detalle_resumen_CE;
          this.datosGrid2 = this.detalle_saldo_CE;
        } else {
          this.datosGrid1 = this.detalle_resumen;
          this.datosGrid2 = this.detalle_saldo;
        }
      } else {
        this.datosGrid2 = [];
      }
    },

  
  },

  computed: {
    ...mapState("resumenperformance", [
      "detalle_resumen_CE",
      "detalle_resumen",
      "detalle_saldo_CE",
      "detalle_saldo_RB",
      "detalle_saldo",
      "loading_resumen",
    ]),
  

    codMarca() {
      return this.pars.marcaSelected;
    },

    codConcesionario() {
      return this.pars.concesSelected;
    },
  },

  created() {
    this.setDefaultDataSource(this.solo_CE);
    this.headers = this.null_headers;
  },

  methods: {
    ...mapActions({
      getHNResumenPerformance: "resumenperformance/getHNResumenPerformance",
    }),

    setDefaultDataSource(soloCE) {
      if (soloCE) {
        this.datosGrid1 = this.detalle_resumen_CE;
        this.datosGrid2 = this.detalle_saldo_CE;
      } else {
        this.datosGrid1 = this.detalle_resumen;
        this.datosGrid2 = this.detalle_saldo;
      }
    },

    setDefaultHeaders(marca) {
      if (typeof(marca) !== "undefined"){
        console.log(marca);
        if(marca == 99){
          this.headers = this.headers_1;
        }else{
          this.headers = this.headers_ce;
        }
      }else{
        this.headers = this.null_headers;
      }
        
    },
      

    refreshResumen() {
      console.log(this.pars);

      var params = {
        //Marca: this.codMarca,
        Marca: 99,
        Concesionario: this.codConcesionario,
        Anio: this.selectAnio
      };
      this.setDefaultHeaders(this.codMarca);
      this.getHNResumenPerformance(params);
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
  padding-bottom: 10px;
}

.space {
  padding-right: 5px;
}

.fullw {
  width: 100%;
}

.maxH {
  height: 350px;
  max-height: 350px;
}

.contenedor {
}

.spacerh {
  padding-top: 10px;
}
</style>
