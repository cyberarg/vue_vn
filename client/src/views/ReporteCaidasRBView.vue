<template>
  <v-app class="fullw">
    <v-card class="padded" color="grey lighten-4">
      <v-card-title>
        Reporte Caidas
        <v-divider class="mx-4" inset vertical></v-divider>
   
        <v-row align="end">
            <v-col cols="5" sm="5" lg="5">
              <v-container fluid>
                <v-radio-group
                  v-model="radios"
                  row
                  mandatory
                >
                  <v-radio
                    label="Por Oficial"
                    value="radio-oficial"
                  ></v-radio>
                  <v-radio
                    label="Por Concesionario"
                    value="radio-ce"
                  ></v-radio>
                </v-radio-group>
              </v-container>
            </v-col>
            <v-col cols="7" sm="7" lg="7">
              <template v-if="radios == 'radio-oficial'">
                  <FilterOficialMultipleChips @clickedFilters="setSelectedsOf" :listaOficiales="this.oficiales"/>
              </template>
              <template v-else>
                <FilterConcesionarioMultipleChips @clickedFilters="setSelectedsCE" :listaCE="this.listConcesionariosNew" />
              </template>
              
            </v-col>
         </v-row>
      </v-card-title>
      <div class="card-body">
        <v-data-table
          dense
          fixed-header
          locale="es"
          :headers="headers"
          :items="itemsGrid"
          :items-per-page="-1"
          :hide-default-footer="true"
          :hide-default-header="true"
          item-key="pars.itemkey"
          class="elevation-1"
          :loading="loading"
          loading-text="Cargando Datos... Aguarde"
          no-data-text="No hay datos disponibles."
        >

          <template v-slot:top>
            <v-toolbar flat>
              <v-toolbar-title></v-toolbar-title>
              <v-spacer></v-spacer>
              <v-switch
                v-model="mostar_valores"
                inset
                label="Mostrar en $"
              ></v-switch>
            </v-toolbar>
          </template>

          <template v-slot:header="{ props }">
            <thead class="v-data-table-header">
              <tr>
                <th></th>
                <th class="text-center lineH1">
                  {{ getPeriodoName(5) }}
                </th>
                <th  class="text-center lineH1">
                  {{ getPeriodoName(4) }}
                </th>
                <th  class="text-center lineH1L">
                  {{ getPeriodoName(3) }}
                </th>
                <th class="text-center lineH1">
                  {{ getPeriodoName(2) }}
                </th>
                <th  class="text-center lineH1">
                  {{ getPeriodoName(1) }}
                </th>
                <th class="text-center lineH1L">
                  {{ getPeriodoName(0) }}
                </th>
                <th class="text-center lineH1L">
                  Total
                </th>
              </tr>
            </thead>
          </template>
          <template v-slot:item.Mes_1="{ item }" >
             {{ setSymbol(item.Mes_1, item.Fila) }} 
          </template>
          <template v-slot:item.Mes_2="{ item }" >
             {{ setSymbol(item.Mes_2, item.Fila) }} 
          </template>
          <template v-slot:item.Mes_3="{ item }" >
             {{ setSymbol(item.Mes_3, item.Fila) }} 
          </template>
          <template v-slot:item.Mes_4="{ item }" >
             {{ setSymbol(item.Mes_4, item.Fila) }} 
          </template>
          <template v-slot:item.Mes_5="{ item }" >
             {{ setSymbol(item.Mes_5, item.Fila) }} 
          </template>
          <template v-slot:item.Mes_6="{ item }" >
             {{ setSymbol(item.Mes_6, item.Fila) }} 
          </template>
          <template v-slot:item.Total="{ item }" >
             {{ setSymbol(item.Total, item.Fila) }} 
          </template>
        </v-data-table>
        
        <v-btn class="ma-2" 
              color="primary"
              outlined
              text 
              @click="createPDF(headers, itemsGrid, 'Ca??das')" >
          <v-icon left>mdi-printer</v-icon>Imprimir
        </v-btn>
      </div>
    </v-card>
  </v-app>
</template>

<script>
import GridSummaryReporteCaidas from "@/components/propios/GridSummaryReporteCaidas.vue";
import FilterConcesionarioMultipleChips from "@/components/propios/FilterConcesionarioMultipleChips.vue";
import FilterOficialMultipleChips from "@/components/propios/FilterOficialMultipleChips.vue";

import moment from "moment";
import { mapState, mapActions } from "vuex";
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'


export default {
  name: "reportecaidas",
  components: {
    GridSummaryReporteCaidas,
    FilterConcesionarioMultipleChips,
    FilterOficialMultipleChips
  },
  data() {
    return {
      itemsGrid:[],
      mostar_valores:false,
      radios: null,
      codperiodo: "",
      detalleFiltro:'',
      filterSelected:[],
      listConcesionariosNew: [
        { Codigo: 1, Nombre: "Sauma", Marca: 5, MostrarSwitch: true },
        { Codigo: 2, Nombre: "Iru??a", Marca: 5, MostrarSwitch: true },
        { Codigo: 3, Nombre: "Amendola", Marca: 5, MostrarSwitch: true },
        { Codigo: 7, Nombre: "Luxcar", Marca: 5, MostrarSwitch: true },
        { Codigo: 4, Nombre: "AutoCervo", Marca: 2 },
        //{ Codigo: 8, Nombre: "RB", Marca: 2, MostrarSwitch: false },
        { Codigo: 5, Nombre: "AutoNet", Marca: 2, MostrarSwitch: false },
        { Codigo: 6, Nombre: "Car Group", Marca: 2, MostrarSwitch: false },
        { Codigo: 9, Nombre: "Sapac", Marca: 9, MostrarSwitch: true },
        { Codigo: 10, Nombre: "Alizze", Marca: 3, MostrarSwitch: true },
      ],
      itemsPeriodos: [],
      mesSelected: 0,
      mesBase: 0,
      monthNames: [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre",
      ],

      headers: [
        {
          text: "",
          align: "start",
          value: "Tipo",
          width: "25%",
          class: "fixed",
        },
        {
          text: "",
          value: "Mes_1",
          align: "center",
        },
       {
          text: "",
          value: "Mes_2",
          align: "center",
        },
        {
          text: "",
          value: "Mes_3",
          align: "center",
        },
        {
          text: "",
          value: "Mes_4",
          align: "center",
        },
        {
          text: "",
          value: "Mes_5",
          align: "center",
        },
        {
          text: "",
          value: "Mes_6",
          align: "center",
        },
        {
          text: "Total",
          value: "Total",
          align: "center",
        },
      ],
    };
  },

  created() {
    this.getPeriodos();
    this.getDatosComboOficiales();
    this.setDefaultDataSource(this.mostar_valores);
  },


  watch: {
    mostar_valores(newValue) {
      this.setDefaultDataSource(newValue);
    },
  },

  computed: {
    ...mapState("parametros", [
        "loadingOficiales",
        "dataStatusMsg",
        "dataStatus",
        "oficiales"
        
    ]),

    ...mapState("reportecaidas", [
      "dataStatus",
      "items",
      "items_valores",
      "loading",
      "datos",
      "empresa",
      "items_filtrados",
    ]),

    ...mapState("auth", [
      "login",
      "user",
      "esConcesionario",
      "codigoConcesionario",
    ]),
  },

  methods: {
    ...mapActions({
      getReporte: "reportecaidas/getReporte",
    }),

    ...mapActions({
        getDatosComboOficiales: "parametros/getOficiales",
    }),

    setDefaultDataSource(value){
      if (value){
        this.itemsGrid = this.items_valores;
      }else{
        this.itemsGrid = this.items;
      }
    },

    setSymbol(valor, fila){
        if (valor == 0){
          return "-"
        }
        if (fila == 5){
            return this.$options.filters.numFormat(valor, "%0,0");
        }else{
          if (this.mostar_valores){
            return this.$options.filters.numFormat(valor, "$0,0");
          }
          return this.$options.filters.numFormat(valor, "0,0");
        }
    },

    setSelectedsCE(value){
      //console.log(value);
      this.getReport('C', value);
    },

    setSelectedsOf(value){
      //console.log(value);
      this.getReport('O', value);
    },

    async getReport(tipo, seleccion) {
      let conces = 0;
      let oficiales = 0;
      this.mesSelected = this.codperiodo.Mes;

      this.detalleFiltro = ''

      switch(tipo){
        case 'C':
          conces = seleccion;
          this.detalleFiltro = 'Por Concesionario'
        break;
        case 'O':
          oficiales = seleccion;
          this.detalleFiltro = 'Por Oficial'
        break;
      }
      this.filterSelected = seleccion;
      var pars = {
        Periodo: this.codperiodo.Codigo,
        SelectedsCE: conces,
        SelectedsOf: oficiales
      };
      //console.log(pars);

     await this.getReporte(pars);
     this.setDefaultDataSource(this.mostar_valores);
    },

    getPeriodos() {
      var currentDate = new Date();
      var initialDate = new Date(2020, 5, 1);

      var monthDif = moment(initialDate).diff(moment(), "month") * -1;
      monthDif += 1;
      var i;
      var period = [];
      var fecha = new Date();
      for (i = 0; i < monthDif; i++) {
        fecha = moment(initialDate, "DD/MM/YYYY")
          .add(i, "months")
          .toDate("DD/MM/YYYY");

        period[i] = {
          Mes: moment(fecha).month() + 1,
          Codigo: `${moment(fecha).year() + "" + (moment(fecha).month() + 1)}`,
          Nombre: `${
            this.monthNames[parseInt(moment(fecha).month())] +
            " " +
            moment(fecha).year()
          }`,
        };
      }
      //console.log(period);
      this.itemsPeriodos = period.reverse();
    },

    getPeriodoName(periodo) {
      //console.log(periodo);
      var month;
      var baseMonth;
      if (this.mesBase == 0) {
        baseMonth = moment().month() + 1;
      } else {
        baseMonth = this.mesBase;
      }
      if (baseMonth > 5) {
        month = baseMonth - periodo;
      }else{
        month = 12 - periodo + baseMonth;
        if (month > 12){
          month = month - 12;
        }
      }
      //console.log(month);
      return this.getMonthName(month);
    },

    getMonthName(mes) {
      //console.log(mes);
      switch (mes) {
        case 1:
          return "Enero";
          break;
        case 2:
          return "Febrero";
          break;
        case 3:
          return "Marzo";
          break;
        case 4:
          return "Abril";
          break;
        case 5:
          return "Mayo";
          break;
        case 6:
          return "Junio";
          break;
        case 7:
          return "Julio";
          break;
        case 8:
          return "Agosto";
          break;
        case 9:
          return "Septiembre";
          break;
        case 10:
          return "Octubre";
          break;
        case 11:
          return "Noviembre";
          break;
        case 12:
          return "Diciembre";
          break;
      }
    },

    createPDF (headers, items, titulo) {
      //console.log('Llego');
      var source1 = items;
      let rows1 = [];

      var itemsSelect = this.filterSelected;
      var itemsSelectStr = '';
      var detalleF = this.detalleFiltro;
      var pasadaStr = 1;
      itemsSelect.forEach(element => {
          if (pasadaStr == 1){
            itemsSelectStr = element.Nombre;
          }else{
            itemsSelectStr = itemsSelectStr + ', ' + element.Nombre;
          }
          pasadaStr ++;
        });
      
      let subtitulo = detalleF + ': ' + itemsSelectStr;
      var heading = 'Reporte - ' + titulo;
      let pdfName = 'Reporte_Caidas';


      source1.forEach(element => {
          var temp = [
              element.Tipo,
              this.setSymbol(element.Mes_1, element.Fila),
              this.setSymbol(element.Mes_2, element.Fila),
              this.setSymbol(element.Mes_3, element.Fila),
              this.setSymbol(element.Mes_4, element.Fila),
              this.setSymbol(element.Mes_5, element.Fila),
              this.setSymbol(element.Mes_6, element.Fila),
              this.setSymbol(element.Total, element.Fila),

          ];
          rows1.push(temp);
      });
        

       
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
          styles: { fontSize: 10, halign: 'center' },
          columnStyles: { 
            0:{ halign: 'left' }, 
            1:{ halign: 'center' }, 
            2:{ halign: 'center' }, 
            3:{ halign: 'center' }, 
            4:{ halign: 'center' }, 
            5:{ halign: 'center' }, 
            6:{ halign: 'center' }, 
            7:{ halign: 'center' }, 
          }, // Cells in first column centered 
          margin: { top: 10 },
          columns: ['', this.getPeriodoName(5), this.getPeriodoName(4), this.getPeriodoName(3), this.getPeriodoName(2), this.getPeriodoName(1), this.getPeriodoName(0), 'Total'],
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
.contenedor {
  width: 100%;
}

.padded {
  padding-left: 20px;
  padding-right: 20px;
}

.fullw {
  width: 100%;
  height: 100%;
}
</style>
