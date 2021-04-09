<template>
  <v-app class="contenedor">
    <div>
      <v-card color="grey lighten-4" class="padded-card">
        <v-card-title>
          Haberes Netos
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <template v-if="!esConcesionario" >
            <v-row align="end">
              <v-spacer></v-spacer>
              <template v-if="showListCE">
                <v-col cols="10" sm="10" lg="10">
                  <FilterConcesionarioMultipleChips @clickedFilters="getDatosHN_Filter" :listaCE="this.listConcesionariosNew" />
                </v-col>
              </template>
              <template v-else>
                Concesionario: RB - AutoNet - CarGroup - Volkswagen
              </template>
            </v-row>
            <!--
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
          --> 
          </template>
          
          <!--
          <v-btn
            class="ma-2"
            outlined
            color="blue darken-1"
            text
            @click="getDatosHN()"
          >
            <v-icon left>mdi-refresh</v-icon>Actualizar
          </v-btn>
          -->
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
            <v-row>
              <v-spacer></v-spacer>
              <v-col cols="3" sm="3" lg="3">
                <v-combobox
                  v-model="selectFiltro"
                  item-text="Nombre"
                  item-value="Codigo"
                  :items="itemsFiltro"
                  label="Filtrar Titular HN"
                  
                ></v-combobox>
              </v-col>
            </v-row>


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
              <v-spacer></v-spacer>
              <v-btn class="ma-2"  
                  color="primary"
                  outlined
                  text
                  @click="createPDF(headersReportHNV, datosHN_Vigentes, 'Vigentes', 1)" >
                <v-icon left>mdi-printer</v-icon>Imprimir
              </v-btn>
            </v-card>
            <v-spacer></v-spacer>
            <v-card-actions>
              <template v-if="showButons">
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
          <v-tab-item>
            <v-card>
              <GridComponentHN
                :pars="{
                  grid: 'HNCobrados',
                }"
                :datos_items="datosHN_Cobrados"
                :headers="headersCobrados"
                :loadingDatos="loadingHNC"
                :showButtons="showButons"
                :filtro_Titular="this.selectFiltro"
              ></GridComponentHN>
              
              <v-spacer></v-spacer>
              <v-btn class="ma-2" 
                     color="primary"
                      outlined
                      text 
                      @click="createPDF(headersReportHNC, datosHN_Cobrados, 'Cobrados', 2)" >
                <v-icon left>mdi-printer</v-icon>Imprimir
              </v-btn>
              
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
          <template v-if="!esConcesionario">
            <v-tab-item class="maxH2">
              <v-card>
                <HNResumenView
                  :pars="{
                    marcaSelected: this.codMarcaSel,
                    concesSelected: this.codConcesSel,
                  }"
                  :solo_CE="this.switch_CE"
                  :filtro_Titular="this.selectFiltro"
                  :concesSelecteds="this.codConcesionariosSelecteds"
                ></HNResumenView>
              </v-card>
            </v-tab-item>
          </template>
          <!--
          <v-tab-item>
            <v-card>
              <HNStockView
                :pars="{
                  marcaSelected: this.codMarcaSel,
                  concesSelected: this.codConcesSel,
                }"
                :solo_CE="this.switch_CE"
              ></HNStockView>
            </v-card>
          </v-tab-item>
          -->
          <v-tab-item class="maxHProy">
            <v-card>
              <HNProyectadosView
                :pars="{
                  marcaSelected: this.codMarcaSel,
                  concesSelected: this.codConcesSel,
                  nomConcesSelected: this.nomConcesSel,
                }"
                :solo_CE="this.switch_CE"
                :filtro_Titular="this.selectFiltro"
                :concesSelecteds="this.codConcesionariosSelecteds"
              ></HNProyectadosView>
             
            </v-card>
          </v-tab-item>
          <template v-if="!esConcesionario">
           <v-tab-item class="maxHPerf">
            <v-card>
              <HNResumenPerformanceView
                :pars="{
                  marcaSelected: this.codMarcaSel,
                  concesSelected: this.codConcesSel,
                }"
                :solo_CE="this.switch_CE"
              ></HNResumenPerformanceView>
            </v-card>
          </v-tab-item>
          </template>
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
import FilterConcesionarioMultipleChips from "@/components/propios/FilterConcesionarioMultipleChips.vue";
import GridComponentHN from "@/components/propios/GridComponentHN.vue";
import FormCompraHN from "@/components/propios/FormCompraHN.vue";
import HNProyectadosView from "@/views/HNProyectadosView.vue";
import HNResumenPerformanceView from "@/views/HNResumenPerformanceView.vue";
import HNEERRView from "@/views/HNEERRView.vue";
import HNStockView from "@/views/HNStockView.vue";
import HNResumenView from "@/views/HNResumenView.vue";
import { mapState, mapActions } from "vuex";
import moment from "moment";
import XLSX from "xlsx";

import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

export default {
  name: "haberesnetos",
  components: {
    FilterConcesionarioMultipleChips,
    GridComponentHN,
    FormCompraHN,
    HNProyectadosView,
    HNResumenPerformanceView,
    HNResumenView,
    HNEERRView,
    HNStockView
  },
  data() {
    return {

      showListCE: true,

      headersReportHNV: ['Conces.', 'Titular', 'Grupo', 'Orden', 'Fecha Compra',
       'Monto Compra', 'Monto Compra USD', 'HN Subite', 'HN Subite USD', 'Util.', 
       'Duration', 'Dur. Compra', 'TIR', 'TIR Spot', 'Fecha Cuota 84'],

      headersReportHNC: ['Conces.', 'Titular', 'Grupo', 'Orden', 'Fecha Compra',
       'Monto Compra', 'Monto Compra USD', 'Monto Cobro', 'Monto Cobro USD', 'Fecha Cobro',
       'Util.', 'Util. USD'], 

      itemsFiltro: [
        { Codigo: 0, Nombre: "Todos" },
        { Codigo: 1, Nombre: "Giama" },
        { Codigo: 2, Nombre: "Concesionario" },
      ],
      selectFiltro: { Codigo: 0, Nombre: "Todos" },

      listCE:[],
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
      codMarcaSel: null,
      codConcesSel: null,
      nomConcesSel: '',
      //codConcesionariosSelecteds: null,
      codConcesionariosSelecteds: {},
      selectedConsolidado: false,
      showchkConsolidado: false,
      cotizaciones: [],
      tabitemsCE: [
        { Codigo: 1, Nombre: "HN Vigentes" },
        { Codigo: 2, Nombre: "HN Cobrados" },
        //{ Codigo: 4, Nombre: "Stock" },
        { Codigo: 5, Nombre: "Proyectado" },
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
          Nombre: "Análisis de la Gestión",
        },
        /* 
        {
          Codigo: 4,
          Nombre: "Stock",
        },
         */
        {
          Codigo: 5,
          Nombre: "Proyectado",
        },
        {
          Codigo: 6,
          Nombre: "Resumen Performance",
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
        { Codigo: 99, Nombre: "Giama" },
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
        { Codigo: 99, Nombre: "RB - AutoNet - CarGroup - Volkswagen", Marca: 99, MostrarSwitch: false },
      ],

      listConcesionariosAux: [
        { Codigo: 99, Nombre: "RB - AutoNet - CarGroup - Volkswagen", Marca: 99, MostrarSwitch: false },
      ],

      listConcesionariosNew: [
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
        //{ Codigo: 99, Nombre: "RB - AutoNet - CarGroup - Volkswagen", Marca: 99, MostrarSwitch: false },
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
        { text: "Acción", value: "Accion", align: "center" },
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
        { text: "Acción", value: "Accion", align: "center" },

      ],
    };
  },

  watch: {



    tab(newValue){
      console.log('Cambio de Tab:');
      console.log(newValue);

      if (newValue == 4){
        this.showListCE = false;
        if (!this.loading_resumen){
          
          var params = {
            //Marca: this.codMarca,
            Marca: 99,
            Concesionario: 0,
            Anio: moment().format('YYYY'),
          };

          this.getHNResumenPerformance(params);
        }
      }else{
        this.showListCE = true;
      }
    },


    switch_CE(newValue) {
      //this.setDefaultDataSource(newValue);
    },

    selectFiltro(newValue) {
      console.log(newValue);
      this.setDefaultFiltered(newValue);
    },

    
    loadingHNC(newValue) {
      if (!newValue) {
        console.log(this.selectFiltro.Codigo);
        switch(this.selectFiltro.Codigo){
          case 0:
            this.datosHN_Cobrados = this.datosHNCobrados;
          break;
          case 1:
            this.datosHN_Cobrados = this.datosHNCobradosGiama;
          break;
          case 2:
            this.datosHN_Cobrados = this.datosHNCobradosCE;
          break;
        }
        
      } else {
        this.datosHN_Cobrados = this.datosHNCobrados;
      }
    },

    loadingHNV(newValue) {
      if (!newValue) {
        console.log(this.selectFiltro.Codigo);
        switch(this.selectFiltro.Codigo){
          case 0:
            this.datosHN_Vigentes = this.datos;
          break;
          case 1:
            this.datosHN_Vigentes = this.datosGiama;
          break;
          case 2:
            this.datosHN_Vigentes = this.datosCE;
          break;
        }
      } else {
        this.datosHN_Vigentes = this.datos;
      }
    },
    
    dataStatus(newValue, oldValue) {
      if (newValue !== oldValue && newValue === "success") {
        this.cargoDatos = true;
      }
    },
  },

  computed: {
    ...mapState("parametros", [
        "loadingConcesionarios",
        "dataStatusMsg",
        "dataStatus",
        "concesionarios"
        
    ]),

     ...mapState("resumenperformance", [
        "loading_resumen",       
    ]),

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
    //this.setDefaultDataSource(this.switch_CE);
  },

  methods: {

    ...mapActions({
        getDatosComboCE: "parametros/getConcesionarios",
    }),

    ...mapActions({
      getHNVigentes: "haberneto/getHNVigentes",
      getHNCobrados: "haberneto/getHNCobrados",
      getHNEERR: "eerrhn/getHNEERR",
      getHNStock: "stockhn/getHNStock",
      getHNProyectados: "proyectadohn/getHNProyectados",
      getHNResumenPerformance: "resumenperformance/getHNResumenPerformance",
      getResumenCompras: "resumenhn/getHNResumenCompras",
      getResumenCobros: "resumenhn/getHNResumenCobros",
    }),

    /*
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
    */

     setDefaultFiltered(valor){
        switch(valor.Codigo){
          case 0: //Todos
            this.datosHN_Vigentes = this.datos;
            this.datosHN_Cobrados = this.datosHNCobrados;
          break;
          case 1: //Giama
            this.datosHN_Vigentes = this.datosGiama;
            this.datosHN_Cobrados = this.datosHNCobradosGiama;
          break;
          case 2: //Conces
            this.datosHN_Vigentes = this.datosCE;
            this.datosHN_Cobrados = this.datosHNCobradosCE;
          break;

        }
        console.log(this.datosHN_Vigentes);
    },

    refreshHNVigentes() {
      var params = this.getParametersLlamados();

      //this.getHNVigentes(params);
    },

    getParametersLlamados() {
      var pars = {
        Marca: this.codConcesSelected.Marca,
        Concesionario: this.codConcesSelected.Codigo,
        Empresa: 8,
        Anio: moment().format('YYYY'),
        Filtros: "",
      };

      return pars;
    },

     getDatosHN_Filter(value) {
      //this.checkEsConcesionario();
      this.codConcesionariosSelecteds = value;
      this.retriveData = true;
      var pars = {
        Marca: 0,
        Concesionario: 0,
        Seleccionados: this.codConcesionariosSelecteds,
        Empresa: 8,
        Anio: moment().format('YYYY'),
        Filtros: "",
        ConsolidadoRB: this.selectedConsolidado,
      };

      this.codMarcaSel = 0;
      this.codConcesSel = 0;


      console.log(pars);
      this.datosHN_Vigentes = null;
      this.datosHN_Cobrados = null;
      this.getHNVigentes(pars);
      
      this.getHNCobrados(pars);
      
      //this.getHNEERR(pars);
      this.getResumenCompras(pars);
      this.getResumenCobros(pars);
      this.getHNProyectados(pars);

      var params = {
        //Marca: this.codMarca,
        Marca: 99,
        Concesionario: 0,
        Anio: moment().format('YYYY'),
      };

      this.getHNResumenPerformance(params);

      /*
      this.getHNStock(pars);

      /*
      
      this.getHNResumenPerformance(pars);
      */
    },


    getDatosHN() {
      //this.checkEsConcesionario();
      
      this.retriveData = true;
      var pars = {
        Marca: this.codConcesSelected.Marca,
        Concesionario: this.codConcesSelected.Codigo,
        Empresa: 8,
        Anio: moment().format('YYYY'),
        Filtros: "",
        ConsolidadoRB: this.selectedConsolidado,
      };

      this.codMarcaSel = pars.Marca;
      this.codConcesSel = pars.Concesionario;
      this.nomConcesSel =  this.codConcesSelected.Nombre;
      
      /*
      //console.log(pars);
      this.getHNVigentes(pars);
      this.getHNCobrados(pars);
      this.getHNEERR(pars);
      this.getHNStock(pars);
      this.getHNProyectados(pars);
      this.getHNResumenPerformance(pars);
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

      if (value.Codigo == 99) {
        this.listC = this.listConcesionarios.find(function (item) {
          return item.Codigo === 99;
        });
        this.codConcesSelected = this.listC;
      }
    },

    async checkEsConcesionario() {
      let itemC = {};

      if (this.esConcesionario) {
        var codC = parseInt(this.codigoConcesionario);
        /*
        let parsCE = {
          Marca: -1, 
          Concesionario: codC
        }


        itemC = await this.getDatosComboCE(parsCE);
        */
        //var itemC = {};
        itemC = this.listConcesionarios.find(function (item) {
          return item.Codigo === codC;
        });
        

        this.codConcesSelected = itemC.ID;
        this.codMarcaSelected = itemC.MarcaDefault;

        this.showButons = false;
        this.showSwitch = true;
      } else {
        if (this.esVinculo) {
          this.listMarcas.splice(0, 1);
          this.showButons = false;
        } else {

          let parsCE = {
            Marca: 0, 
            Concesionario: -1
          }
          //itemC = await this.getDatosComboCE(parsCE);
          this.showButons = true;
        }
      }
      this.switch_CE = this.esConcesionario;
     // this.listCE = itemC;
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
        case "10":
          return "RB";
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

    getValorDecimal(valor, cantDecimales){
        let num = parseFloat(valor)
        if (valor < 0){
          return '-'
        }
        return num.toFixed(cantDecimales)
    },

    comproGiama(tipo){
      if (tipo == 1){
        return "Giama"
      }
      return "Conces."
    },

    createPDF (headers, items, titulo, grid) {
      console.log('Llego');
        var source1 = items;
        let rows1 = [];

        var filtroTitHN = this.selectFiltro.Nombre;
        var conceSelect = this.codConcesionariosSelecteds;
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
        var heading = 'Haberes Netos - ' + titulo;

        let pdfName = 'Haberes_Netos_' + titulo;

        switch (grid){
            case 1:
              source1.forEach(element => {
                  var temp = [
                      this.getTextEmpresaPorCE(element.Operacion.Empresa, element.Operacion.Concesionario),
                      this.comproGiama(element.ComproGiama),
                      element.Operacion.Grupo,
                      element.Operacion.Orden,
                      this.formatFecha(element.FechaCompra),
                      "$"+this.$options.filters.numFormat(element.MontoCompra, "0,0"),
                      "USD "+this.$options.filters.numFormat(element.MontoCompraDolares, "0,0"),
                      "$"+this.$options.filters.numFormat(element.HaberNetoSubite, "0,0"),
                      "USD "+this.$options.filters.numFormat(element.HaberNetoSubiteUSD, "0,0"),
                      this.$options.filters.numFormat(element.UtilidadActual, "0,0")+"%",
                      this.getValorDecimal(element.DurationActual, 1),
                      this.getValorDecimal(element.DurationCompra, 1),
                      this.$options.filters.numFormat(element.TIRActual, "0,0")+"%", 
                      this.$options.filters.numFormat(element.TIRSpot, "0,0")+"%", 
                    this.formatFecha(element.FechaCuota84), 
                  ];
                  rows1.push(temp);
              });
            break;
            case 2:
              source1.forEach(element => {
                  var temp = [
                      this.getTextEmpresaPorCE(element.Operacion.Empresa, element.Operacion.Concesionario),
                      this.comproGiama(element.ComproGiama),
                      element.Operacion.Grupo,
                      element.Operacion.Orden,
                      this.formatFecha(element.FechaCompra),
                      "$"+ this.$options.filters.numFormat(element.MontoCompra, "0,0"),
                      "USD "+ this.$options.filters.numFormat(element.MontoCompraDolares, "0,0"),
                      "$"+ this.$options.filters.numFormat(element.MontoCobroReal, "0,0"),
                      "USD "+ this.$options.filters.numFormat(element.MontoCobroDolares, "0,0"),
                      this.formatFecha(element.FechaCobroReal), 
                      this.$options.filters.numFormat(element.Utilidad, "0,0")+"%",
                      this.$options.filters.numFormat(element.UtilidadUSD, "0,0")+"%",

                  ];
                  rows1.push(temp);
              });
            break;
        }
        

       
        var doc = new jsPDF({orientation: 'landscape'});
        doc.setFont("helvetica");
        doc.setFontSize(20)

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
            doc.setFontSize(1)

            // jsPDF 1.4+ uses getWidth, <1.4 uses .width

            //doc.text(10, 0, str)
          },
        });

        doc.autoTable({
          styles: { fontSize: 8, halign: 'center' },
          columnStyles: { 
            0:{ halign: 'left' }, 
            1:{ halign: 'left' }, 
            5:{ halign: 'right' }, 
            6:{ halign: 'right' }, 
            7:{ halign: 'right' }, 
            8:{ halign: 'right' }, 
          }, // Cells in first column centered 
          margin: { top: 10 },
          columns: headers,
          body: rows1,
          startY: 28,
        });

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

            //doc.text(5, 190, str)

        doc.save(pdfName + '.pdf');
        
    }
  },
};
</script>

<style scoped>
.padded {
  padding-left: 20px;
  padding-right: 20px;
  height: 100%;
}

.padded-card {
  padding-left: 5px;
  padding-right: 5px;
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

.maxHPerf {
  height: 63vh;
}

.spacerh {
  padding-top: 10px;
}

.rowCheckCE {
  display: flex;
  flex-direction: row-reverse;
  padding-right: 5px;
}

.combo {
  display: block;
  align-items: end;
  max-width: 150px;
  padding-right: 5px;
}
</style>
