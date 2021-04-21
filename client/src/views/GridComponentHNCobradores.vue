<template>
  <v-app class="contenedor">
    <div>
      <v-card color="grey lighten-4" class="padded">
        <v-card-title>
          Haberes Netos
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <template v-if="!esConcesionario">
            <v-combobox
              item-text="Nombre"
              item-value="Codigo"
              :items="listMarcas"
              label="Marca"
              :value="codMarcaSelected"
              @change="filterListConcesionaria"
              class="padded"
            ></v-combobox>

            <v-combobox
              item-text="Nombre"
              item-value="Codigo"
              :items="listC"
              label="Concesionario"
              v-model="codConcesSelected"
              @change="setSelected"
              class="padded"
            ></v-combobox>

            <v-checkbox
              v-show="showchkConsolidado"
              dense
              id="chk_consolidado"
              v-model="selectedConsolidado"
              label="Consolidado"
            ></v-checkbox>
          </template>
          <v-btn
            class="ma-2"
            outlined
            color="blue darken-1"
            text
            @click="getDatosHN()"
          >
            <v-icon left>mdi-refresh</v-icon>Actualizar
          </v-btn>
        </v-card-title>
        <!--<div class="contenedor">-->
        <!--<v-container class="maxH2">-->

        <v-progress-linear
          v-if="loadingDatos"
          indeterminate
          color="primary"
        ></v-progress-linear>
        <v-tabs v-model="tab" class="elevation-2" grow>
          <v-tabs-slider></v-tabs-slider>

          <v-tab
            v-for="tab in tabitemsfilters"
            :key="tab.Codigo"
            :value="'tab-' + tab.Codigo"
          >
            {{ tab.Nombre }}
            <!--<v-icon>{{ setIcon() }}</v-icon>-->
          </v-tab>
        </v-tabs>

        <template v-if="showSwitch">
          <div class="rowCheckCE">
            <v-switch
              v-model="switch_CE"
              label="Ver Sólo Propios CE"
            ></v-switch>
          </div>
        </template>
        <template v-else>
          <div class="spacerh"></div>
        </template>

        <v-tabs-items v-model="tab">
          <!-- <v-tab-item v-for="tab in tabitems" :key="tab.Codigo">-->
          <v-tab-item color="grey lighten-4">
            <v-card>
              <GridComponentHN
                :pars="{
                  grid: 'HNVigentes',
                  cotizaciones: cotizacionesLocal,
                }"
                :datos_items="datosHN_Vigentes"
                :headers="headersVigentes"
                :loadingDatos="loadingHNV"
                :showButtons="showButons"
                :filtro_Titular="this.selectFiltro"
                
              ></GridComponentHN>
            </v-card>
            <v-spacer></v-spacer>
            <v-card-actions>
              <template v-if="showActionButtons">
                <v-row>
                  <v-col align="start">
                    <v-btn
                      cclass="ma-2"
                      color="primary"
                      outlined
                      text
                      @click="showModal"
                    >
                      <v-icon left>mdi-cart-plus</v-icon>Compra
                    </v-btn>
                  </v-col>
                  <v-spacer></v-spacer>

                  <v-col align="end">
                    <v-btn
                      cclass="ma-2"
                      color="success"
                      outlined
                      text
                      @click="exportExcel(datos)"
                    >
                      <v-icon left>mdi-file-excel-outline</v-icon>Excel
                    </v-btn>
                  </v-col>
                </v-row>
              </template>
            </v-card-actions>
          </v-tab-item>
          <!--
          <v-tab-item>
            <v-card>
              <GridComponentHN
                :pars="{
                  grid: 'HNCobrados',
                }"
                :datos="datosHN_Cobrados"
                :headers="headersCobrados"
                :loadingDatos="loadingHNC"
              ></GridComponentHN>
            </v-card>
            <v-card-actions>
              <template v-if="showButons">
                <v-row>
                  <v-spacer></v-spacer>

                  <v-col align="end">
                    <v-btn
                      cclass="ma-2"
                      color="success"
                      outlined
                      text
                      @click="exportExcel(datosHNCobrados)"
                    >
                      <v-icon left>mdi-file-excel-outline</v-icon>Excel
                    </v-btn>
                  </v-col>
                </v-row>
              </template>
            </v-card-actions>
          </v-tab-item>
        -->
        </v-tabs-items>
      </v-card>
      <template>
        <v-dialog
          v-model="dialogCompra"
          :scrollable="false"
          :hide-overlay="false"
          max-width="700px"
          ma-0
        >
          <FormCompraHN
            @hide="dialogCompra = false"
            @refresh="refreshHNVigentes()"
          ></FormCompraHN>
        </v-dialog>
      </template>
    </div>
  </v-app>
</template>

<script>
import GridComponentHN from "@/components/propios/GridComponentHN.vue";
import FormCompraHN from "@/components/propios/FormCompraHN.vue";
import { mapState, mapActions } from "vuex";
import moment from "moment";
import XLSX from "xlsx";

export default {
  name: "haberesnetos",
  components: {
    GridComponentHN,
    FormCompraHN,
  },
  data() {
    return {
      codConcesionariosSelecteds: {},
      datosHN_Vigentes: [],
      datosHN_Cobrados: [],
      retriveData: false,
      dialogCompra: false,
      loadingCompra: false,
      tab: null,
      switch_CE: false,
      showSwitch: false,
      cargoDatos: false,
      isModalVisible: false,
      showButons: null,
      showActionButtons: false,
      codMarcaSel: null,
      codConcesSel: null,
      selectedConsolidado: false,
      showchkConsolidado: false,
      cotizaciones: [],
      selectFiltro: { Codigo: 0, Nombre: "Todos" },
      tabitemsCE: [
        { Codigo: 1, Nombre: "HN Vigentes" },
        /*
        { Codigo: 2, Nombre: "HN Cobrados" },
        { Codigo: 5, Nombre: "Proyectado" },
        */
      ],

      tabitems: [
        {
          Codigo: 1,
          Nombre: "HN Vigentes",
        },
        {
          Codigo: 2,
          Nombre: "HN Cobrados",
        },
        {
          Codigo: 3,
          Nombre: "Resumen",
        },
        {
          Codigo: 4,
          Nombre: "EERR",
        },
        {
          Codigo: 5,
          Nombre: "Proyectado",
        },
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
        "Compra Planes para Autos",
      ],
      codMarcaSelected: null,
      listMarcas: [
        { Codigo: 2, Nombre: "Fiat" },
        { Codigo: 5, Nombre: "Volkswagen" },
        { Codigo: 9, Nombre: "Ford" },
        { Codigo: 3, Nombre: "Peugeot" },
      ],
      codConcesSelected: null,
      listC: [],
      listConcesionarios: [
        { Codigo: 0, Nombre: "Todos", MostrarSwitch: false },
        { Codigo: 1, Nombre: "Sauma", Marca: 5, MostrarSwitch: true },
        { Codigo: 2, Nombre: "Iruña", Marca: 5, MostrarSwitch: true },
        { Codigo: 3, Nombre: "Amendola", Marca: 5, MostrarSwitch: true },
        { Codigo: 7, Nombre: "Luxcar", Marca: 5, MostrarSwitch: true },
        //{ Codigo: 4, Nombre: "AutoCervo", Marca: 2 },
        { Codigo: 8, Nombre: "RB", Marca: 2, MostrarSwitch: false },
        { Codigo: 5, Nombre: "AutoNet", Marca: 2, MostrarSwitch: false },
        { Codigo: 6, Nombre: "Car Group", Marca: 2, MostrarSwitch: false },
        { Codigo: 9, Nombre: "Sapac", Marca: 9, MostrarSwitch: true },
        { Codigo: 10, Nombre: "Alizze", Marca: 3, MostrarSwitch: true },
      ],
      hncobrados: [],
      headersVigentesfiat: [
        {
          text: "Empresa OP",
          align: "center",
          value: "Operacion.Empresa",
          width: "25%",
        },

        { text: "Titular HN", value: "ComproGiama", align: "center" },

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
          align: "center",
        },
        { text: "HN Subite", value: "HaberNetoSubite", align: "center" },
        {
          text: "HN Subite en U$S",
          value: "HaberNetoSubiteUSD",
          align: "center",
        },

        {
          text: "Rendimiento Total",
          value: "UtilidadActual",
          align: "center",
        },
        {
          text: "Precio Auto Actual",
          value: "Operacion.PrecioAutoActual",
          align: "center",
        },

        { text: "Duration", value: "DurationActual", align: "center" },
        { text: "Duration Compra", value: "DurationCompra", align: "center" },
        { text: "TIR", value: "TIRActual", align: "center" },

        { text: "TIR Spot", value: "TIRSpot", align: "center" },
        { text: "Fecha Cuota 84", value: "FechaCuota84", align: "center" },

        {
          text: "Conces. Propio",
          value: "ConcesionarioPropio",
          align: "center",
        },
        { text: "Nro. Transf.", value: "NroTransferencia", align: "center" },
        { text: "", value: "Accion", align: "center" },
      ],

      headersVigentesvw: [
        {
          text: "Empresa OP",
          align: "center",
          value: "Operacion.Empresa",
          width: "25%",
        },

        { text: "Titular HN", value: "ComproGiama", align: "center" },

        { text: "Grupo", value: "Operacion.Grupo", align: "center" },
        { text: "Orden", value: "Operacion.Orden", align: "center" },

        { text: "Fecha Compra", value: "FechaCompra", align: "center" },
        { text: "Tipo Compra", value: "TipoCompra", align: "center" },

        { text: "CPG", value: "Operacion.CPG", align: "center" },
        { text: "Avance", value: "Operacion.Avance", align: "center" },
        { text: "Plan", value: "Operacion.Plan", align: "center" },

        { text: "Monto Compra", value: "MontoCompra", align: "center" },
        {
          text: "Monto Compra en U$S",
          value: "MontoCompraDolares",
          align: "center",
        },
        { text: "HN Subite", value: "HaberNetoSubite", align: "center" },
        {
          text: "HN Subite en U$S",
          value: "HaberNetoSubiteUSD",
          align: "center",
        },

        {
          text: "Rendimiento Total",
          value: "UtilidadActual",
          align: "center",
        },
        {
          text: "Precio Auto Actual",
          value: "Operacion.PrecioAutoActual",
          align: "center",
        },

        { text: "Duration", value: "DurationActual", align: "center" },
        { text: "Duration Compra", value: "DurationCompra", align: "center" },
        { text: "TIR", value: "TIRActual", align: "center" },

        { text: "TIR Spot", value: "TIRSpot", align: "center" },
        { text: "Fecha Cuota 84", value: "FechaCuota84", align: "center" },
        { text: "Accion", value: "Accion", align: "center", width: "120px" },
      ],

      headersCobradosfiat: [
        {
          text: "Empresa OP",
          align: "center",
          value: "Operacion.Empresa",
          width: "25%",
        },
        { text: "Titular HN", value: "ComproGiama", align: "center" },
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
          align: "center",
        },
        { text: "Monto Cobro Real", value: "MontoCobroReal", align: "center" },
        {
          text: "Monto Cobro Real en U$S",
          value: "MontoCobroDolares",
          align: "center",
        },
        { text: "Fecha Cobro Real", value: "FechaCobroReal", align: "center" },
        {
          text: "Utilidad",
          value: "Utilidad",
          align: "center",
        },
        {
          text: "Utilidad en U$S",
          value: "UtilidadUSD",
          align: "center",
        },
        { text: "CPG", value: "Operacion.CPG", align: "center" },
        { text: "Avance", value: "Operacion.Avance", align: "center" },
        { text: "Tipo Plan", value: "Operacion.TipoPlan", align: "center" },
        { text: "Modelo", value: "Operacion.NomModelo", align: "center" },
        { text: "HN Subite", value: "HaberNetoSubite", align: "center" },
        {
          text: "Dif. de Cobro",
          value: "DifCobro",
          align: "center",
        },
        {
          text: "Precio Auto Actual",
          value: "PrecioAutoActual",
          align: "center",
        },
        { text: "Duration", value: "Duration", align: "center" },
        { text: "TIR", value: "TIR", align: "center" },
        { text: "TIR Spot", value: "TIRSpot", align: "center" },
        { text: "Fecha Cuota 84", value: "FechaCuota84", align: "center" },

        {
          text: "Conces. Propio",
          value: "ConcesionarioPropio",
          align: "center",
        },
        { text: "Nro. Transf.", value: "NroTransferencia", align: "center" },
        { text: "", value: "Accion", align: "center" },
      ],

      headersCobradosvw: [
        {
          text: "Empresa OP",
          align: "center",
          value: "Operacion.Empresa",
          width: "25%",
        },
        { text: "Titular HN", value: "ComproGiama", align: "center" },
        { text: "Grupo", value: "Operacion.Grupo", align: "center" },
        { text: "Orden", value: "Operacion.Orden", align: "center" },

        { text: "Fecha Compra", value: "FechaCompra", align: "center" },
        { text: "Tipo Compra", value: "TipoCompra", align: "center" },

        { text: "Monto Compra", value: "MontoCompra", align: "center" },
        {
          text: "Monto Compra en U$S",
          value: "MontoCompraDolares",
          align: "center",
        },
        { text: "Monto Cobro Real", value: "MontoCobroReal", align: "center" },
        {
          text: "Monto Cobro Real en U$S",
          value: "MontoCobroDolares",
          align: "center",
        },
        { text: "Fecha Cobro Real", value: "FechaCobroReal", align: "center" },
        {
          text: "Utilidad",
          value: "Utilidad",
          align: "center",
        },
        {
          text: "Utilidad en U$S",
          value: "UtilidadUSD",
          align: "center",
        },
        { text: "CPG", value: "Operacion.CPG", align: "center" },
        { text: "Avance", value: "Operacion.Avance", align: "center" },
        { text: "Plan", value: "Operacion.Plan", align: "start" },

        { text: "HN Subite", value: "HaberNetoSubite", align: "center" },
        {
          text: "Dif. de Cobro",
          value: "DifCobro",
          align: "center",
        },
        {
          text: "Precio Auto Actual",
          value: "Operacion.PrecioAutoActual",
          align: "center",
        },
        { text: "Duration", value: "Duration", align: "center" },
        { text: "TIR", value: "TIR", align: "center" },
        { text: "TIR Spot", value: "TIRSpot", align: "center" },
        { text: "Fecha Cuota 84", value: "FechaCuota84", align: "center" },
      ],
    };
  },

  watch: {
    switch_CE(newValue) {
      this.setDefaultDataSource(newValue);
    },

    loadingHNC(newValue) {
      if (!newValue) {
        if (this.switch_CE) {
          this.datosHN_Cobrados = this.datosHNCobradosCE;
        } else {
          this.datosHN_Cobrados = this.datosHNCobrados;
        }
      } else {
        this.datosHN_Cobrados = [];
      }
    },

    loadingHNV(newValue) {
      if (!newValue) {
        if (this.switch_CE) {
          this.datosHN_Vigentes = this.datosCE;
        } else {
          this.datosHN_Vigentes = this.datos;
        }
      } else {
        this.datosHN_Vigentes = [];
      }
    },

    dataStatus(newValue, oldValue) {
      if (newValue !== oldValue && newValue === "success") {
        this.cargoDatos = true;
      }
    },
  },

  computed: {
    ...mapState("haberneto", [
      "datos",
      "dataStatus",
      "datosGiama",
      "datosCE",
      "loadingDatos",
      "datosHNCobrados",
      "datosHNCobradosCE",
      "datosHNCobradosGiama",
      "loadingHNV",
      "loadingHNC",
    ]),
    ...mapState("auth", [
      "login",
      "user",
      "esConcesionario",
      "esVinculo",
      "codigoConcesionario",
    ]),
    ...mapState("cotizaciondolar", ["cotizacionesLocal"]),

    tabitemsfilters() {
      if (!this.esConcesionario && !this.esVinculo) {
        return this.tabitems;
      } else {
        return this.tabitemsCE;
      }
    },

    headersCobrados() {
      return this.headersCobradosvw;
      /*
      if (typeof this.codConcesSelected.Marca !== "undefined") {
        if (this.codConcesSelected.Marca == 2) {
          return this.headersCobradosfiat;
        } else {
          return this.headersCobradosvw;
        }
      }
      return [];
      */
    },

    setIcon() {
      if (this.retriveData) {
        switch (codigoTab) {
          case 1:
            if (this.loadingHNV) {
              return "fas fa-spinner fa-spin";
            } else {
              return "check-circle-outline";
            }

            return;
            break;
          case 2:
            return "fas fa-spinner fa-spin";
            break;
          case 3:
            return "fas fa-spinner fa-spin";
            break;
          case 4:
            return "fas fa-spinner fa-spin";
            break;
          case 5:
            return "fas fa-spinner fa-spin";
            break;
        }
        return "";
      }
      return "";
    },

    headersVigentes() {
      return this.headersVigentesvw;
      /*
      if (typeof this.codConcesSelected.Marca !== "undefined") {
        if (this.codConcesSelected.Marca == 2) {
          return this.headersVigentesfiat;
        } else {
          return this.headersVigentesvw;
        }
      }
      return [];
      */
    },
  },

  mounted() {
    this.checkEsConcesionario();
  },

  created() {
    this.setDefaultDataSource(this.switch_CE);
  },

  methods: {
    ...mapActions({
      getHNVigentes: "haberneto/getHNVigentes",
      getHNCobrados: "haberneto/getHNCobrados",
      getHNEERR: "eerrhn/getHNEERR",
      getHNProyectados: "proyectadohn/getHNProyectados",
      getResumenCompras: "resumenhn/getHNResumenCompras",
      getResumenCobros: "resumenhn/getHNResumenCobros",
    }),

    setDefaultDataSource(soloCE) {
      if (this.cargoDatos) {
        if (soloCE) {
          this.datosHN_Vigentes = this.datosCE;
          this.datosHN_Cobrados = this.datosHNCobradosCE;
        } else {
          this.datosHN_Vigentes = this.datos;
          this.datosHN_Cobrados = this.datosHNCobrados;
        }
      }
    },

    refreshHNVigentes() {
      var params = this.getParametersLlamados();

      this.getHNVigentes(params);
    },

    getParametersLlamados() {
      var pars = {
        Marca: this.codConcesSelected.Marca,
        Concesionario: this.codConcesSelected.Codigo,
        Empresa: 8,
        Anio: 2020,
        Filtros: "",
      };

      return pars;
    },

    getDatosHN() {
      //this.checkEsConcesionario();
      this.retriveData = true;
      let arrCE = [];

      switch(this.codigoConcesionario){
        case "6":
          arrCE.push({ Codigo: this.codigoConcesionario, Nombre: 'Car Group', Marca: 2, MostrarSwitch: false });
        break;
        case "10":
          arrCE.push({ Codigo: this.codigoConcesionario, Nombre: 'Alizze', Marca: 3, MostrarSwitch: false });
        break;
      };
      


      this.codConcesionariosSelecteds = arrCE;
      var pars = {
        Marca: 0,
        Concesionario: 0,
        Seleccionados: this.codConcesionariosSelecteds,
        Empresa: 8,
        Anio: moment().format('YYYY'),
        Filtros: "",
        ConsolidadoRB: this.selectedConsolidado,
      };
      console.log(pars);
      this.codMarcaSel = 0;
      this.codConcesSel = 0;
      //this.codMarcaSel = pars.Marca;
      //this.codConcesSel = pars.Concesionario;

      //console.log(pars);
      this.getHNVigentes(pars);
      /*
      this.getHNCobrados(pars);
      this.getHNEERR(pars);
      this.getHNProyectados(pars);
      this.getResumenCompras(pars);
      this.getResumenCobros(pars);
      */
    },

    exportExcel(items) {
      let data = XLSX.utils.json_to_sheet(items);

      //datosHNCobrados
      const workbook = XLSX.utils.book_new();
      const filename = "HN";
      XLSX.utils.book_append_sheet(workbook, data, filename);
      XLSX.writeFile(workbook, `${filename}.xlsx`);
    },

    filterListConcesionaria(value) {
      this.codConcesSelected = null;
      this.listC = [];
      this.listC = this.listConcesionarios.filter(function (item) {
        return item.Marca === value.Codigo;
      });
    },

    checkEsConcesionario() {
      if (this.esConcesionario) {
        var codC = parseInt(this.codigoConcesionario);
        console.log(codC);
        var itemC = {};
        itemC = this.listConcesionarios.find(function (item) {
          return item.Codigo === codC;
        });
        this.codConcesSelected = itemC;
        this.codMarcaSelected = itemC.Marca;
        this.showButons = true;
        this.showActionButtons = false;
        //this.showSwitch = true;
        this.showSwitch = false;
      } else {
        if (this.esVinculo) {
          this.listMarcas.splice(0, 1);
          this.showButons = false;
          this.showActionButtons = false;
        } else {
          this.showButons = true;
          this.showActionButtons = true;
        }
      }
      console.log('Cambio valor verdad:');
      console.log(this.esConcesionario);
      if (this.codigoConcesionario === "10"){
        this.switch_CE = !this.esConcesionario;
        
      }else{
        this.switch_CE = this.esConcesionario;
      }
      
    },

    setSelected(value) {
      //console.log(this.codConcesSelected);
      this.showchkConsolidado = this.codConcesSelected.Codigo == 8;

      if (!this.esConcesionario) {
        this.showSwitch = this.codConcesSelected.MostrarSwitch;
        if (!this.codConcesSelected.MostrarSwitch) {
          this.switch_CE = false;
        }
      }
    },

    showModal() {
      //å;
      //this.isModalVisible = true;
      // this.$router.push("comprahn");
      this.dialogCompra = true;
    },
    closeModal() {
      this.isModalVisible = false;
      this.dialogCompra = false;
    },

    formatFecha(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM/YYYY");
      }
    },

    getTextEmpresaPorCE(valor, concesionario) {
      console.log(concesionario);
      switch (concesionario) {
        case "4":
        case "5":
        case "6":
        case "8":
          return this.getTextEmpresaGiama(valor);
          break;

        default:
          return this.getTextEmpresaCE(concesionario);
          break;
      }
    },

    getTextEmpresaCE(valor) {
      console.log(valor);
      switch (valor) {
        case "1":
          return "SAUMA";
          break;
        case "2":
          return "IRUÑA";
          break;
        case "3":
          return "AMENDOLA";
          break;
        case "7":
          return "LUXCAR";
          break;
        case "9":
          return "SAPAC";
          break;
         case "10":
          return "ALIZZE";
          
      }
    },

    getTextEmpresa(valor) {
      switch (valor) {
        case "1":
          return "SAUMA";
          break;
        case "2":
          return "IRUÑA";
          break;
        case "3":
          return "AMENDOLA";
          break;
        case "7":
          return "LUXCAR";
          break;
        case "9":
          return "SAPAC";
          break;
        case "10":
          return "ALIZZE"
          break;
      }
    },

    getTextEmpresaGiama(valor) {
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
        case "10":
          return "RB";
          break;
        default:
          return valor;
          break;
      }
    },

    getNameCE(valor, codConces) {
      switch (valor) {
        case "1":
          return "SAUMA";
          break;
        case "2":
          return "IRUÑA";
          break;
        case "3":
          return "AMENDOLA";
          break;
        case "7":
          return "LUXCAR";
          break;
        case "9":
          return "SAPAC";
          break;
        case "10":
          return "ALIZZE"
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
        case "7":
            return "Para Venta de Adjudicados";
          break;
        case "8":
              return "Compra Planes para Autos";
              break;
        case "9":
              return "Mesa de planes";
              break;
        case "10":
              return "Traspaso Interempresa";
              break;
        default:
          return valor;
          break;
      }
    },
  },
};
</script>

<style scoped>
.padded {
  padding-left: 20px;
  padding-right: 20px;
  height: 100%;
}

.paddedb {
  padding-bottom: 2px;
}

.space {
  padding-right: 5px;
}

.fullw {
  width: 100%;
  height: 100%;
}

.contenedor {
  width: 100%;
}

.contenedorHW {
  width: 100%;
  height: 540px;
}

.maxH {
  height: 430px;
  max-height: 430px;
}

.maxH2BAK {
  height: 550px;
  max-height: 550px;
}

.maxH2 {
  height: 65vh;
}

.maxHEERR {
  height: 90vh;
}

.maxHProy {
  height: 97vh;
}

.spacerh {
  padding-top: 10px;
}

.rowCheckCE {
  display: flex;
  flex-direction: row-reverse;
  padding-right: 5px;
}
</style>
