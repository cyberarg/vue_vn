<template>
  <v-app class="contenedor" color="grey lighten-4">
    <v-card>
      <!--
      <v-row>
        <v-spacer></v-spacer>
        <v-col cols="3" sm="3" lg="3">
          <v-combobox
            :value="selectFiltro"
            item-text="Nombre"
            item-value="Codigo"
            :items="itemsFiltro"
            label="Filtrar Titular HN"
            :disabled="!loadingdetalle_grid1_compras"
            @change="setDefaultFiltered"
          ></v-combobox>
        </v-col>
      </v-row>
      -->
      <v-tabs v-model="tab" class="elevation-2" grow>
        <v-tabs-slider></v-tabs-slider>
        <v-tab v-for="tab in tabitems" :key="tab.Codigo">{{
          tab.Nombre
        }}</v-tab>
      </v-tabs>
      <div class="spacerh"></div>
      <v-tabs-items v-model="tab">
        <v-tab-item>
          <v-card color="grey lighten-4">
            <v-row align="start">
              <v-col cols="12" sm="12" lg="12" md="12">
                <GridComponentResumen
                  :pars="{
                    grid: 'Grid1Compra',
                  }"
                  :datos="this.datosDetalleGrid1_Compras"
                  :headers="header1"
                   tipoGrid="Compras"
                  :loadingState="this.loadingdetalle_grid1_compras"
                  loadingText="Cargando datos..."
                ></GridComponentResumen>
              </v-col>
            </v-row>
            <v-row align="start">
              <v-col cols="12" sm="12" lg="12" md="12">
                <GridComponentResumen
                  :pars="{
                    grid: 'Grid2Compra',
                  }"
                  :datos="this.datosDetalleGrid2_Compras"
                  :headers="header2compra"
                  tipoGrid="Compras"
                  :loadingState="this.loadingdetalle_grid2_compras"
                  loadingText="Cargando datos..."
                ></GridComponentResumen>
                <v-btn class="ma-2" 
                     color="primary"
                      outlined
                      text 
                      @click="createPDF(headersReportResumen, headersResumen, datosDetalleGrid1_Compras, datosDetalleGrid2_Compras, 'Compras', 1)" >
                  <v-icon left>mdi-printer</v-icon>Imprimir
                </v-btn>
              </v-col>
            </v-row>
            
          </v-card>
        </v-tab-item>
        <v-tab-item>
          <v-card color="grey lighten-4">
            <v-row align="start">
              <v-col cols="12" sm="12" lg="12" md="12">
                <GridComponentResumen
                  :pars="{
                    grid: 'Grid1Cobro',
                  }"
                  :datos="this.datosDetalleGrid1_Cobros"
                  :headers="header1"
                  tipoGrid="Cobros"
                  :loadingState="this.loadingdetalle_grid1_cobros"
                  loadingText="Cargando datos..."
                ></GridComponentResumen>
              </v-col>
            </v-row>
            <v-row align="start">
              <v-col cols="12" sm="12" lg="12" md="12">
                <GridComponentResumen
                  :pars="{
                    grid: 'Grid2Cobro',
                  }"
                  :datos="this.datosDetalleGrid2_Cobros"
                  tipoGrid="Cobros"
                  :headers="header2cobro"
                  :loadingState="this.loadingdetalle_grid2_cobros"
                  loadingText="Cargando datos..."
                ></GridComponentResumen>
                <v-btn class="ma-2" 
                     color="primary"
                      outlined
                      text 
                      @click="createPDF(headersReportResumen, headersResumen, datosDetalleGrid1_Cobros, datosDetalleGrid2_Cobros, 'Cobros', 1)" >
                  <v-icon left>mdi-printer</v-icon>Imprimir
                </v-btn>
              </v-col>
            </v-row>
          </v-card>
        </v-tab-item>
         <v-tab-item>
          <v-card color="grey lighten-4">
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
                <!--
                <v-btn class="ma-2" 
                     color="primary"
                      outlined
                      text 
                      @click="createPDF([], [], this.loadingdetalle_grid2, this.loadingdetalle_grid3, 'Resumen - Stock', 2)" >
                  <v-icon left>mdi-printer</v-icon>Imprimir
                </v-btn>
                -->
              </v-col>
            </v-row>
          </v-card>
        </v-tab-item>
      </v-tabs-items>
    </v-card>
  </v-app>
</template>


<script>
import GridComponentResumen from "@/components/propios/GridComponentResumen.vue";
import GridComponentStock from "@/components/propios/GridComponentStock.vue";
import { mapState, mapActions } from "vuex";
import moment from "moment";
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

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
    filtro_Titular:{
      type: Object,
      required:true
    },

    concesSelecteds:{
      //type: Object,
      type: Array,
      required: true,
    }
  },

  name: "hnresumen",
  components: {
    GridComponentResumen,
    GridComponentStock
  },
  data() {
    return {
      tab: null,
      headersReportResumen: ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre', 'Total'],
      headersResumen: ['', 'HN Comprados $', 'Rent. $', 'Rent. $ (%)', 'Rent. USD', 'Remt. USD (%)', 'Duration', 'TIR', 'Rent. USD Spot', 'Remt. USD Spot (%)', 'TIR Spot'],
     datosDetalleGrid1_Compras: [],
      datosDetalleGrid2_Compras: [],
      datosDetalleGrid1_Cobros: [],
      datosDetalleGrid2_Cobros: [],
      tabitems: [
        { Codigo: 1, Nombre: "Comprados" },
        { Codigo: 2, Nombre: "Cobrados" },
        { Codigo: 3, Nombre: "Stock" },
      ],
      /*
      itemsFiltro: [
        { Codigo: 0, Nombre: "Todos" },
        { Codigo: 1, Nombre: "Giama" },
        { Codigo: 2, Nombre: "Concesionario" },
      ],
      selectFiltro: { Codigo: 0, Nombre: "Todos" },
      */
      nameFilterTitHN: 'Todos',
      tipoFiltro:{},

      header1: [
        { text: "", value: "Tipo", align: "left" },
        { text: "Enero", value: "Valores.M1", align: "center" },
        { text: "Febrero", value: "Valores.M2", align: "center" },
        { text: "Marzo", value: "Valores.M3", align: "center" },
        { text: "Abril", value: "Valores.M4", align: "center" },
        { text: "Mayo", value: "Valores.M5", align: "center" },
        { text: "Junio", value: "Valores.M6", align: "center" },
        { text: "Julio", value: "Valores.M7", align: "center" },
        { text: "Agosto", value: "Valores.M8", align: "center" },
        { text: "Septiembre", value: "Valores.M9", align: "center" },
        { text: "Octubre", value: "Valores.M10", align: "center" },
        { text: "Noviembre", value: "Valores.M11", align: "center" },
        { text: "Diciembre", value: "Valores.M12", align: "center" },
        { text: "Total", value: "Valores.Total", align: "center" },
      ],

      header2compra: [
        { text: "", value: "Anio", align: "left" },
        { text: "HN Comprados $", value: "Valores.HN", align: "center" },
        { text: "Rent. $", value: "Valores.RentARS", align: "center" },
        { text: "Rent. $ (%)", value: "Valores.RentARS_Porc", align: "center" },
        { text: "Rent. USD", value: "Valores.RentUSD", align: "center" },
        {
          text: "Rent. USD (%)",
          value: "Valores.RentUSD_Porc",
          align: "center",
        },
        { text: "Duration", value: "Valores.Duration", align: "center" },
        { text: "TIR", value: "Valores.TIR", align: "center" },

        { text: "Rent. USD Spot", value: "Valores.RentUSD_Spot", align: "center" },
        {
          text: "Rent. USD Spot (%)",
          value: "Valores.RentUSD_Spot_Porc",
          align: "center",
        },
         { text: "TIR Spot", value: "Valores.TIR_Spot", align: "center" },
      ],

      header2cobro: [
        { text: "", value: "Anio", align: "left" },
        { text: "HN Cobrados $", value: "Valores.HN", align: "center" },
        { text: "Rent. $", value: "Valores.RentARS", align: "center" },
        { text: "Rent. $ (%)", value: "Valores.RentARS_Porc", align: "center" },
        { text: "Rent. USD", value: "Valores.RentUSD", align: "center" },
        {
          text: "Rent. USD (%)",
          value: "Valores.RentUSD_Porc",
          align: "center",
        },
        { text: "Duration", value: "Valores.Duration", align: "center" },
        { text: "TIR", value: "Valores.TIR", align: "center" },
      ],

      datosDetalleGrid1: [],
      datosDetalleGrid2: [],
      datosDetalleGrid3: [],

       header_anual: [
        {text: "Año", value: "Anio", align: "center", width: ""},
        {text: "Stock Inicial", value: "StockInicial", align: "center", width: ""},
        {text: "Compras", value: "Compras", align: "center", width: ""},
        {text: "Cobros", value: "Cobros", align: "center", width: ""},
        {text: "Stock Final", value: "StockFinal", align: "center", width: ""},
        {text: "Variación", value: "VariacionTotal", align: "center", width: ""},

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
    solo_CE(newValue) {
      this.setDefaultDataSource(newValue);
    },

    filtro_Titular(newValue){
      console.log(newValue);
      this.tipoFiltro = newValue;
      this.setDefaultFiltered(newValue);
    },

    loadingdetalle_grid1_compras(newValue) {
      if (!newValue) {
        if (this.solo_CE) {
          this.datosDetalleGrid1_Compras = this.detalle_grid1_compras_CE;
        } else {
          this.datosDetalleGrid1_Compras = this.detalle_grid1_compras;
        }
        console.log('Llanado al filtrado desde el loading detalle 1');
        this.setDefaultFiltered(this.tipoFiltro);

      } else {
        this.datosDetalleGrid1_Compras = [];
      }

    },

    loadingdetalle_grid2_compras(newValue) {
      if (!newValue) {
        if (this.solo_CE) {
          this.datosDetalleGrid2_Compras = this.detalle_grid2_compras_CE;
        } else {
          this.datosDetalleGrid2_Compras = this.detalle_grid2_compras;
        }
      } else {
        this.datosDetalleGrid2_Compras = [];
      }
    },

    loadingdetalle_grid1_cobros(newValue) {
      if (!newValue) {
        if (this.solo_CE) {
          this.datosDetalleGrid1_Cobros = this.detalle_grid1_cobros_CE;
        } else {
          this.datosDetalleGrid1_Cobros = this.detalle_grid1_cobros;
        }
        console.log('Llanado al filtrado desde el loading detalle 1 - Cobros');
        this.setDefaultFiltered(this.tipoFiltro);
      } else {
        this.datosDetalleGrid1_Cobros = [];
      }
    },

    loadingdetalle_grid2_cobros(newValue) {
      if (!newValue) {
        if (this.solo_CE) {
          this.datosDetalleGrid2_Cobros = this.detalle_grid2_cobros_CE;
        } else {
          this.datosDetalleGrid2_Cobros = this.detalle_grid2_cobros;
        }
      } else {
        this.datosDetalleGrid2_Cobros = [];
      }
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
        }else{
          this.datosDetalleGrid3 = this.detalle_grid3;
        }
      } else {
        this.datosDetalleGrid3 = [];
      }
    },

  },

  computed: {
    ...mapState("resumenhn", [
      "dataStatus",
      "detalle_grid1_compras",
      "detalle_grid1_cobros",
      "detalle_grid2_compras",
      "detalle_grid2_cobros",
      "detalle_grid1_compras_Giama",
      "detalle_grid1_compras_TotalGiama",
      "detalle_grid1_cobros_Giama",
      "detalle_grid1_cobros_TotalGiama",
      "detalle_grid2_compras_Giama",
      "detalle_grid2_compras_TotalGiama",
      "detalle_grid2_cobros_Giama",
      "detalle_grid2_cobros_TotalGiama",
      "detalle_grid1_compras_CE",
      "detalle_grid1_cobros_CE",
      "detalle_grid2_compras_CE",
      "detalle_grid2_cobros_CE",
      "loadingdetalle_grid1_compras",
      "loadingdetalle_grid1_cobros",
      "loadingdetalle_grid2_compras",
      "loadingdetalle_grid2_cobros",
    ]),

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
  },

  created() {
    this.setDefaultDataSource(this.solo_CE);
  },

  methods: {
    ...mapActions({
      getResumenCompras: "resumenhn/getHNResumenCompras",
      getResumenCobros: "resumenhn/getHNResumenCobros",
      getHNStock: "stockhn/getHNStock",
    }),

    setDefaultDataSource(soloCE) {
      if (soloCE) {
        this.datosDetalleGrid1_Compras = this.detalle_grid1_compras_CE;
        this.datosDetalleGrid2_Compras = this.detalle_grid2_compras_CE;
        this.datosDetalleGrid1_Cobros = this.detalle_grid1_cobros_CE;
        this.datosDetalleGrid2_Cobros = this.detalle_grid2_cobros_CE;

        this.datosDetalleGrid1 = this.detalle_grid1;
        this.datosDetalleGrid2 = this.detalle_grid2_CE;
        this.datosDetalleGrid3 = this.detalle_grid3_CE;
      } else {
        this.datosDetalleGrid1_Compras = this.detalle_grid1_compras;
        this.datosDetalleGrid2_Compras = this.detalle_grid2_compras;
        this.datosDetalleGrid1_Cobros = this.detalle_grid1_cobros;
        this.datosDetalleGrid2_Cobros = this.detalle_grid2_cobros;

        this.datosDetalleGrid1 = this.detalle_grid1;
        this.datosDetalleGrid2 = this.detalle_grid2;
        this.datosDetalleGrid3 = this.detalle_grid3;
      }
      console.log(this.tipoFiltro);
      if (this.tipoFiltro != {}){
        console.log('Llama al filtrado con la opcion seleccionada');
        this.setDefaultFiltered(this.tipoFiltro);
      }
    },

    setDefaultFiltered(valor){
        this.nameFilterTitHN = valor.Nombre;
        console.log('Filtro Selected en setDefault: '+valor.Codigo);
        switch(valor.Codigo){
          case 0: //Todos
            this.datosDetalleGrid1_Compras = this.detalle_grid1_compras;
            this.datosDetalleGrid2_Compras = this.detalle_grid2_compras;
            this.datosDetalleGrid1_Cobros = this.detalle_grid1_cobros;
            this.datosDetalleGrid2_Cobros = this.detalle_grid2_cobros;

            this.datosDetalleGrid1 = this.detalle_grid1;
            this.datosDetalleGrid2 = this.detalle_grid2;
            this.datosDetalleGrid3 = this.detalle_grid3;
          break;
          case 1: //Giama
              this.datosDetalleGrid1_Compras = this.detalle_grid1_compras_Giama;
              this.datosDetalleGrid2_Compras = this.detalle_grid2_compras_Giama;

              this.datosDetalleGrid1_Cobros = this.detalle_grid1_cobros_Giama;
              this.datosDetalleGrid2_Cobros = this.detalle_grid2_cobros_Giama;

              this.datosDetalleGrid1 = this.detalle_grid1;
              this.datosDetalleGrid2 = this.detalle_grid2_Giama;
              this.datosDetalleGrid3 = this.detalle_grid3_Giama;
          break;
          
          case 2: //Conces
              this.datosDetalleGrid1_Compras = this.detalle_grid1_compras_CE;
              this.datosDetalleGrid2_Compras = this.detalle_grid2_compras_CE;

              this.datosDetalleGrid1_Cobros = this.detalle_grid1_cobros_CE;
              this.datosDetalleGrid2_Cobros = this.detalle_grid2_cobros_CE;

              this.datosDetalleGrid1 = this.detalle_grid1;
              this.datosDetalleGrid2 = this.detalle_grid2_CE;
              this.datosDetalleGrid3 = this.detalle_grid3_CE;
          break;

          case 3: // Total Giama
              this.datosDetalleGrid1_Compras = this.detalle_grid1_compras_TotalGiama;
              this.datosDetalleGrid2_Compras = this.detalle_grid2_compras_TotalGiama;

              this.datosDetalleGrid1_Cobros = this.detalle_grid1_cobros_TotalGiama;
              this.datosDetalleGrid2_Cobros = this.detalle_grid2_cobros_TotalGiama;

              this.datosDetalleGrid1 = this.detalle_grid1;
              this.datosDetalleGrid2 = this.detalle_grid2_Giama;
              this.datosDetalleGrid3 = this.detalle_grid3_Giama;
          break;

        }
    },

    formatFecha(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM/YYYY");
      }
    },

    getValorDecimal(valor, cantDecimales){
        let num = parseFloat(valor)
        return num.toFixed(cantDecimales)
    },

    setSimboloValor(fila, valor){

      if (valor !== 0){
          switch(fila){
          case 1:
          case 5:
              return "$ " + this.$options.filters.numFormat(valor, "0,0");
          case 2:
          case 7:
          case 8:
          case 11:
              return "USD " + this.$options.filters.numFormat(valor, "0,0");
          case 3:
              return "" + this.$options.filters.numFormat(valor, "0,0");
          case 4:
              return "" + valor;
          case 6:
          case 9:  
          case 10:
          case 12:
          case 13:
              return this.$options.filters.numFormat(valor, "0,0") + "%";
          case 7:

        }
      }
      return "-" 
    },

    createPDF (headers1, headers2, items1, items2, titulo, grid) {
      console.log('Llego');
        var source1 = items1;
        let rows1 = [];

        var source2 = items2;
        let rows2 = [];

        var filtroTitHN = this.nameFilterTitHN;
        var conceSelect = this.concesSelecteds;
        var concesSelectStr = '';
        var pasadaStr = 1;
        conceSelect.forEach(element => {
            if (pasadaStr == 1){
              concesSelectStr = element.Nombre;
            }else{
              concesSelectStr = concesSelectStr + ', ' + element.Nombre;
            }
            pasadaStr ++;
         });
       
        let subtitulo = 'Concesionario: ' + concesSelectStr + ' -  Titular HN: ' + filtroTitHN;
        var heading = 'Análisis de la Gestión - ' + titulo;

        let pdfName = 'Analisis_Gestion_' + titulo;

        switch (grid){
            case 1:
              source1.forEach(element => {
                  var temp = [
                      element.Tipo,
                      this.setSimboloValor(element.Fila, element.Valores.M1),
                      this.setSimboloValor(element.Fila, element.Valores.M2),
                      this.setSimboloValor(element.Fila, element.Valores.M3),
                      this.setSimboloValor(element.Fila, element.Valores.M4),
                      this.setSimboloValor(element.Fila, element.Valores.M5),
                      this.setSimboloValor(element.Fila, element.Valores.M6),
                      this.setSimboloValor(element.Fila, element.Valores.M7),
                      this.setSimboloValor(element.Fila, element.Valores.M8),
                      this.setSimboloValor(element.Fila, element.Valores.M9),
                      this.setSimboloValor(element.Fila, element.Valores.M10),
                      this.setSimboloValor(element.Fila, element.Valores.M11),
                      this.setSimboloValor(element.Fila, element.Valores.M12),
                      this.setSimboloValor(element.Fila, element.Valores.Total), 
                  ];
                  rows1.push(temp);
              });

              source2.forEach(element => {
                  var temp = [
                      element.Anio,
                      element.Valores.HN == 0 ? "-" : "$ " + Math.round(element.Valores.HN, 0),
                      element.Valores.RentARS == 0 ? "-" : "$ "+Math.round(element.Valores.RentARS, 0),
                      element.Valores.RentARS_Porc == 0 ? "-" : Math.round(element.Valores.RentARS_Porc, 0) + "%",
                      element.Valores.RentUSD == 0 ? "-" : "USD "+Math.round(element.Valores.RentUSD, 0),
                      element.Valores.RentUSD_Porc == 0 ? "-" : Math.round(element.Valores.RentUSD_Porc, 0) + "%",
                      element.Valores.Duration == 0 ? "-" : this.getValorDecimal(element.Valores.Duration, 1),
                      element.Valores.TIR == 0 ? "-" : Math.round(element.Valores.TIR, 0)+"%", 
                       
                  ];
                  rows2.push(temp);
              });
            break;
            case 2:
              /*
              source1.forEach(element => {
                  var temp = [
                      

                  ];
                  rows1.push(temp);
              });
              source2.forEach(element => {
                  var temp = [
                      

                  ];
                  rows2.push(temp);
              });
              */
            break;
        }
        

       
        var doc = new jsPDF({orientation: 'landscape'});
        doc.setFont("helvetica");
        doc.setFontSize(25)

      // text is placed using x, y coordinates
      //doc.setFontSize(16).text(heading, 0.5, 1.0);
      // create a line under heading 
      //doc.setLineWidth(0.01).line(0.5, 1.1, 8.0, 1.1);

      var totalPagesExp = '1'

        
        doc.autoTable({
            didDrawPage: function (data) {
            // Header
            doc.setTextColor(40)
            /*
            if (base64Img) {
              doc.addImage(base64Img, 'JPEG', data.settings.margin.left, 15, 10, 10)
            }
            */
            doc.text(15, 15, heading)
            doc.setFontSize(12)
            doc.text(15, 22, subtitulo)

            // Footer
            var str = 'Pagina ' + doc.internal.getNumberOfPages()
            // Total page number plugin only available in jspdf v1.0+
            if (typeof doc.putTotalPages === 'function') {
              str = str + ' de ' + totalPagesExp
            }
            doc.setFontSize(10)

            // jsPDF 1.4+ uses getWidth, <1.4 uses .width

            //doc.text(10, 0, str)
          },
        });

        doc.autoTable({
          styles: { fontSize: 8, halign: 'center' },
          columnStyles: { 0: { halign: 'left' } }, // Cells in first column centered 
          margin: { top: 10 },
          columns: headers1,
          body: rows1,
          startY: 28,
        });

        doc.autoTable({
          styles: { fontSize: 8, halign: 'center' },
          columnStyles: { 0: { halign: 'left' } }, // Cells in first column centered 
          margin: { top: 10 },
          columns: headers2,
          body: rows1
        }); 

        //doc.autoTable(headers1, rows1, { startY: 20 });
        //doc.autoTable(headers2, rows2, { startY: 100 });

        doc.autoTable({
            didDrawPage: function (data) {  
          },
        });
    

      // Creating footer and saving file
       var str = 'Pagina ' + doc.internal.getNumberOfPages()
       // Total page number plugin only available in jspdf v1.0+
        if (typeof doc.putTotalPages === 'function') {
          str = str + ' de ' + totalPagesExp
        }
        // Footer
   
           doc.setFontSize(1)

            // jsPDF 1.4+ uses getWidth, <1.4 uses .width

            doc.text(5, 190, str)

        doc.save(pdfName + '.pdf');
        
    }
    

  },
};
</script>

<style scoped>
.contenedor {
  /* solo para override del height del v-app */
}

.spacerh {
  padding-top: 15px;
}

.space {
  padding-right: 5px;
}
</style>
