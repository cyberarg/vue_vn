<template>
    <div class="padded">

      <v-row>
        <v-col cols="12" sm="12" lg="12" md="12">
          <GridComponentProyectado
            :pars="{
              grid: 'DetalleMeses',
            }"
            :elevation="0"
            :datos="this.datosDetalleMeses"
            :headers="headMeses"
            :loadingState="this.loadingdetalle_proyec_mes"
            loadingText="Cargando Proyectados Mensuales..."
          ></GridComponentProyectado>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" sm="12" lg="12" md="12">
          <GridComponentProyectado
            :pars="{
              grid: 'DetalleAnios',
            }"
            :elevation="0"
            :datos="this.datosDetalleAnios"
            :headers="headAnios"
            :loadingState="this.loadingdetalle_proyec_anios"
            loadingText="Cargando Proyectados Anuales..."
          ></GridComponentProyectado>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" sm="12" lg="12" md="12">
          <GridComponentProyectado
            :pars="{
              grid: 'RentabilidadAnios',
            }"
            :elevation="0"
            :datos="this.datosDetalleRentAnios"
            :headers="headAnios"
            :loadingState="this.loadingdetalle_proyec_renta_anios"
            loadingText="Cargando Rentabilidades Anuales..."
          ></GridComponentProyectado>
        </v-col>
      </v-row>
    </div>
</template>

<script>
import GridComponentProyectado from "@/components/propios/GridComponentProyectado.vue";
import { mapState, mapActions } from "vuex";
import moment from "moment";


export default {

  name: "hnproyectadosfondo",
  components: {
    GridComponentProyectado,
  },

  data() {
    return {
      headersReportMeses: ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre', 'Total'],
      headersReportAnios: ['', '2021', '2022', '2023', '2024', '2025', '2026', 'Total'],
      selectAnio: moment().format("YYYY"),
      selectFiltro: { Codigo: 0, Nombre: "Todos" },
      tipoFiltro: {},
      arrayHeaders:[],
      datosDetalleMeses: [],
      datosDetalleRentMeses: [],
      datosDetalleAnios: [],
      datosDetalleRentAnios: [],
      
      itemsFiltro: [
        { Codigo: 0, Nombre: "Todos" },
        { Codigo: 1, Nombre: "Giama" },
        { Codigo: 2, Nombre: "Concesionario" },
      ],

      nameFilterTitHN: 'Todos',
     
      itemsAnios: [
        //"2020",
        "2021",
        "2022",
        "2023",
        "2024",
        "2025",
        "2026",
        "2027",
        "2028",
      ],
      hncobrados: [],
      headMeses: [
        { text: "", value: "Tipo", align: "left", width: "45px" },
        { text: "Enero", value: "Valores.M1", align: "center", width: "20px" },
        {
          text: "Febrero",
          value: "Valores.M2",
          align: "center",
          width: "20px",
        },
        { text: "Marzo", value: "Valores.M3", align: "center", width: "20px" },
        { text: "Abril", value: "Valores.M4", align: "center", width: "20px" },
        { text: "Mayo", value: "Valores.M5", align: "center", width: "20px" },
        { text: "Junio", value: "Valores.M6", align: "center", width: "20px" },
        { text: "Julio", value: "Valores.M7", align: "center", width: "20px" },
        { text: "Agosto", value: "Valores.M8", align: "center", width: "20px" },
        {
          text: "Septiembre",
          value: "Valores.M9",
          align: "center",
          width: "20px",
        },
        {
          text: "Octubre",
          value: "Valores.M10",
          align: "center",
          width: "20px",
        },
        {
          text: "Noviembre",
          value: "Valores.M11",
          align: "center",
          width: "20px",
        },
        {
          text: "Diciembre",
          value: "Valores.M12",
          align: "center",
          width: "20px",
        },
        {
          text: "Total",
          value: "Valores.Total",
          align: "center",
          width: "20px",
        },
      ],

      headAnios: [
        { text: "", value: "Tipo", align: "left", width: "16%" },
        //{ text: "2020", value: "Valores.A0", align: "center", width: "12%" },
        { text: "2021", value: "Valores.A0", align: "center", width: "12%" },
        { text: "2022", value: "Valores.A1", align: "center", width: "12%" },
        { text: "2023", value: "Valores.A2", align: "center", width: "12%" },
        { text: "2024", value: "Valores.A3", align: "center", width: "12%" },
        { text: "2025", value: "Valores.A4", align: "center", width: "12%" },
        { text: "2026", value: "Valores.A5", align: "center", width: "12%" },
        {
          text: "Total",
          value: "Valores.Total",
          align: "center",
          width: "12%",
        },
      ],
    };
  },

  watch: {

    loadingdetalle_proyec_mes(newValue) {
      if (!newValue) {
        this.setDefaultDataSource();
      } 
    },
  },

  computed: {

    ...mapState("proyectadohn", [
      "detalle_proyec_mes",

      "detalle_proyec_mes_CE",
      "loadingdetalle_proyec_mes",

      "detalle_proyec_anios",
      "detalle_proyec_anios_CE",

      "loadingdetalle_proyec_anios",
      "detalle_proyec_renta_anios",

      "detalle_proyec_renta_anios_CE",
      "loadingdetalle_proyec_renta_anios",
    ]),

    codMarca() {
      return this.pars.marcaSelected;
    },

    arrayDatos(){
      let arr = [] 
      arr.push(this.detalle_proyec_mes);
      arr.push(this.detalle_proyec_anios);
      arr.push(this.detalle_proyec_renta_anios);

      console.log(arr);
      return arr;
    },

    codConcesionario() {
      return this.pars.concesSelected;
    },

    selectedAnio(){
      return selectAnio;
    },

    nomConcesSelect(){
      console.log('Nombre Concesionario');
      return this.pars.nomConcesSelected;
    },
  },

/*
  created() {
    this.setDefaultDataSource();
  },

*/
  mounted(){
    //this.setDefaultDataSource();
  },

  methods: {

    setDefaultDataSource() {
        this.datosDetalleMeses = this.detalle_proyec_mes_CE;

        this.datosDetalleAnios = this.detalle_proyec_anios_CE;
        this.datosDetalleRentAnios = this.detalle_proyec_renta_anios_CE;
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
  padding-bottom: 10px;
}

</style>
