<template>
  <v-app class="fullw">
    <v-card class="padded" color="grey lighten-4">

      <v-card-title>
        Reporte Facturación
        <v-divider class="mx-4" inset vertical></v-divider>

      <v-row justify="center">
        <v-col cols="3" md="3">
        <v-combobox
          item-text="Nombre"
          item-value="Codigo"
          :items="itemsPeriodos"
          label="Periodo"
          v-model="codperiodo"
          @change="getReport()"
        ></v-combobox>
        </v-col>
        <v-col cols="3" md="3">
          <v-btn
            class="ma-2"
            outlined
            color="blue darken-1"
            text
            @click="getReport()"
            :disabled="this.loading"
          >
            <v-icon left>mdi-refresh</v-icon>Actualizar
          </v-btn>
        </v-col>
        
                  
      </v-row>
      </v-card-title>

            <v-row justify="center">
            
                <v-col cols="12" >
                  <v-row >
                    <v-col >
                    <CardComisionGridComponent :loadingData="this.loading" :esGridPagos="false" @detalleExcelCE="getDetalleExcelCE" codConcesionario="8" @setNombreConc="nomConcesDetalle" @setExpand="exandDetail"  concesionario="RB" subtitle="Detalle por Concesionario" :headers="headers" :datos="datos_rb"></CardComisionGridComponent>
                    </v-col>
                  </v-row>
                  <v-row v-if="expand_filtrados">
                    <v-col>
                      <DetalleGridFacturacionComponent :titulo="titulo_detalle" :headers="headers_detalle" :datos="items_filtrados"></DetalleGridFacturacionComponent>
                    </v-col>
                  </v-row>
                  <v-row  >
                    <v-col
                    v-for="(item, i) in datos_ce"
                    :key="i"
                    
                  >
                      <CardComisionComponent @detalleExcelCE="getDetalleExcelCE" :codConcesionario="item.Concesionario" :concesionario="item.NomConcesionario" :total="item.TotalHN" :facturar="item.AFacturar" :cantidad="item.CantHN"></CardComisionComponent>
                    </v-col>
                  </v-row>
                  <v-row >
                    <v-col >
                      <CardComisionGridComponent :loadingData="this.loadingdetalle" :esGridPagos="true" concesionario="Pagos" subtitle="Detalle de Comisiones a Pagar" :totalComisiones="totalComisiones" cantidad="" :headers="headers2" :datos="detalle_comisionistas"></CardComisionGridComponent>
                    </v-col>
                  </v-row>
                
                </v-col>

            </v-row>

      <v-card-actions>
         <v-btn
            class="mb-2"
            outlined
            color="error"
            text
            :loading="this.loadingdetalle"
            :disabled="this.loadingdetalle"
            @click="createPDF"
          >
            <v-icon left>mdi-file-pdf-outline</v-icon>Resumen
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            class="mb-2"
            outlined
            color="success"
            text
            :loading="this.loadingdetalle"
            :disabled="this.loadingdetalle"
            @click="exportExcel"
          >
            <v-icon left>mdi-file-excel</v-icon>Detalle General
          </v-btn>
        
      </v-card-actions>

    </v-card>
  </v-app>
</template>

<script>
import CardComisionComponent from "@/components/propios/CardComisionComponent.vue";
import CardComisionGridComponent from "@/components/propios/CardComisionGridComponent.vue";
import DetalleGridFacturacionComponent from "@/components/propios/DetalleGridFacturacionComponent.vue";

import moment from "moment";
import { mapState, mapActions } from "vuex";
import XLSX from "xlsx";
import jsPDF from 'jspdf'

export default {
  name: "reportefacturacion",
  components: {
    CardComisionComponent,
    CardComisionGridComponent,
    DetalleGridFacturacionComponent

  },
  data() {
    return {
      nomConces: "",
      headers:[
        {
          text: "Concesionario",
          align: "start",
          value: "NomConcesionario",
       
        },
        {
          text: "Total",
          align: "center",
          value: "TotalHN",
          width:"33%"
        },
        {
          text: "Casos",
          align: "end",
          value: "CantHN",
          width:"33%"
        },
      ],

      headers2:[
        {
          text: "Destinatario",
          align: "start",
          value: "Comisionista",
        },
        {
          text: "Porcentaje",
          align: "center",
          value: "Porcentaje",
        },
        {
          text: "Concesionarios",
          align: "start",
          value: "Concesionarios",
        },
        {
          text: "Aclaracion",
          align: "start",
          value: "Aclaracion",
        },
        
        {
          text: "Importe",
          align: "center",
          value: "Total",
          width:"33%"
        },
      ],

      headers_detalle:[
        {
          text: "Grupo/Orden",
          align: "center",
          value: "GrupoOrden",
        },
        {
          text: "Avance",
          align: "center",
          value: "Avance",
        },
        {
          text: "Haber Neto",
          align: "end",
          value: "HaberNetoOriginal",
        },
        {
          text: "Precio Compra",
          align: "end",
          value: "MontoCompra",
        },
      ],


      datos2:[
        {
            Destinatario: 'Demarco 2%',
            Total: 139879,
            Cantidad: 0,
        },
        {
            Destinatario: 'Daniela Fernandez Variable Trim',
            Total: 36487,
            Cantidad: 0,
        },
      ],

      codperiodo: "",
      mesSelected: 0,
      itemsPeriodos: [],
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

      expandedDetail: false
      
    };
  },

  created() {
    this.getPeriodos();
  },

  computed: {
    ...mapState("reportefacturacion", [
      "loading",
      "datos_ce",
      "datos_rb",
      "loading_filter",
      "show_filtrados",
      "items_filtrados",
      "detalle_rb",
      "detalle_rb_ce",
      "loadingdetalle",
      "detalle_conces",
      "detalle_gral",
      "detalle_comisionistas",
      "totalComisiones",
      "loadingdetalle_ce",
      "periodo_selected",
      "concesionarios_facturados",
      "table_rb",
      "table_gral",
      "disableButtonResumenPDF",
      "disableButtonDetalleGralXLS"
    ]),

    expand_filtrados(){
        return this.show_filtrados && this.expandedDetail;
    },

    titulo_detalle(){
      if (typeof(this.nomConces) !== undefined ){
          return "Detalle " + this.nomConces;
      }
    },

    doblecols(){

      if (this.datos_ce.length > 0){
        let cant;
        cant = this.datos_ce.length * 2;

        if (cant > 12){
            return 12;
        }
        return cant;
      }
      return 12;
    },

    cantcols(){
      
      if (this.datos_ce.length > 0){
        return 2;
        //return 6 / this.items_ce.length;
      }
      return 0;
    }
  },

  methods: {
    ...mapActions({
      getReporte: "reportefacturacion/getReporte",
      getDetalleReporte: "reportefacturacion/getDetalleReporte",
      getDetallePor_CE_Filtered: "reportefacturacion/getDetallePor_CE",
      getDetalleConcesionario: "reportefacturacion/getDetalleConcesionario",
      getDetalleGeneral: "reportefacturacion/getDetalleGeneral",
    }),

    async getDetalleExcelCE(value) {

     // console.log(value);
      let pars = {
        periodo: this.periodo_selected,
        CodConcesionario: value,
        ComproGiama: 0
      }
      //console.log(pars)
      await this.getDetalleConcesionario(pars);

      let data = XLSX.utils.json_to_sheet(this.detalle_conces);
      const workbook = XLSX.utils.book_new();
      const filename = "DetalleFacturacion_" + this.nomConcesDetalle(value);

      let sheetname = "DetalleFacturacion";
      XLSX.utils.book_append_sheet(workbook, data, sheetname);

      XLSX.writeFile(workbook, `${filename}.xlsx`);
    },


    nomConcesDetalle(value){
        switch (parseInt(value)){
            case 1:
              return 'Sauma';
            case 2:
              return 'Iruna';
            case 3:
              return 'Amendola';
            case 4:
              return 'AutoCervo';
            case 5:
              return 'AutoNet';
            case 6:
              return 'CarGroup';
            case 7:
              return 'Luxcar';
            case 8:
              return 'RB';  
            case 9:
              return 'Sapac';
            case 10:
              return 'Alizze';
            default:
              return '';
        }
    },

    exandDetail(value){
      this.expandedDetail = value;
    },

    async exportExcel() {
      // console.log(value);
      let consFacturar = '';
      let pasadaStr = 1;

      this.datos_ce.forEach(element => {
          if (pasadaStr == 1){
            consFacturar = '8,' + element.Concesionario;
          }else{
            consFacturar = consFacturar + ',' + element.Concesionario;
          }
          pasadaStr ++;
        });
      

      let pars = {
        periodo: this.periodo_selected,
        ConcesionariosSelecteds: consFacturar
      }
      console.log(pars)
      await this.getDetalleGeneral(pars);

      
      const workbook = XLSX.utils.book_new();
      const filename = "DetalleFacturacion_General";

      
      let sheetname = "";

      //console.log(this.detalle_gral);
      this.detalle_gral.forEach(element => {
         
          let data = XLSX.utils.json_to_sheet(element);
         
          sheetname = element[0].Concesionario;
         
          XLSX.utils.book_append_sheet(workbook, data, sheetname);
        });
       
      

      XLSX.writeFile(workbook, `${filename}.xlsx`);
    },


  setSymbol(valor, formato){ // 1- Nro Simple, 2- Nro con simbolo moneda, 3- Nro  con simbolo USD al principio, 4-Nro Porcentual, 5- Nro con simbolo concatenado % al final
      if (valor == 0){
        return "-"
      }

      switch(formato){
        case 1:
          return this.$options.filters.numFormat(valor, "0,0");
        case 2:
          return this.$options.filters.numFormat(valor, "$0,0");
        case 3:
          return 'USD ' + this.$options.filters.numFormat(valor, "0,0");
        case 4:
           return this.$options.filters.numFormat(valor, "%0,0");
        case 5:
          return this.$options.filters.numFormat(valor, "0,0") +  '%';
        default:
          return this.$options.filters.numFormat(valor, "0,0");
      }

  },


    getPeriodoName(period){

      return this.itemsPeriodos.find(function(item) {
        return item.Codigo === period;
      }).Nombre;
    },
        


   createPDF () {
      console.log(this.table_gral);
      var source1 = this.table_gral;
      let rows1 = [];

      let periodo = this.getPeriodoName(this.periodo_selected);
      //let titulo = 'Período: '+ periodo;
      let subtitulo = '';
      var heading = 'Resumen Facturacion - Período: ' + periodo;
      let pdfName = 'Resumen_Facturacion_' + periodo;


      console.log(this.table_rb);

      for (var prop in this.table_gral) {
        var element = this.table_gral[prop];

        var temp = [
                element.Nombre,
                this.setSymbol(element.Casos, 1),
                this.setSymbol(element.HN, 2),
                this.setSymbol(element.AFacturar, 2),
                
            ];
            rows1.push(temp);
        
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


        // RESUMEN TOTALES
        doc.autoTable({
          styles: { fontSize: 8, halign: 'center' },
          
          head: [
            [
              {
                content: 'RB',
                colSpan: 6,
                styles: { halign: 'center', fillColor: [22, 160, 133], minCellHeight: 5 },
              },
            ],
          ],
          tableWidth: 100,
          body: [
            ['AutoNet', 'Iruña', 'Luxcar','Amendola', 'Sauma', 'Cargroup'],
            ['$231910', '$160132', '$329706', '$-', '$78106', '$538194'],
          ],
          startY: 25,
         // body: rows1,
        })

        // RESUMEN TOTALES
        doc.autoTable({
          styles: { fontSize: 8, halign: 'center' },
          
          head: [
            [
              {
                content: 'Total RB',
                colSpan: 1,
                rowSpan: 2,
                styles: { halign: 'center', valign: 'middle', fillColor: [22, 160, 133], minCellHeight: 13 },
              },
              {
                content: 'Cargroup',
                colSpan: 1,
                rowSpan: 2,
                styles: { halign: 'center', valign: 'middle', fillColor: [6, 22, 133], minCellHeight: 13 },
              },
              {
                content: 'Sauma',
                colSpan: 1,
                rowSpan: 2,
                styles: { halign: 'center', valign: 'middle', fillColor: [16, 22, 133], minCellHeight: 13 },
              },
              {
                content: 'Iruña',
                colSpan: 1,
                rowSpan: 2,
                styles: { halign: 'center', valign: 'middle', fillColor: [16, 22, 133], minCellHeight: 13 },
              },
              {
                content: 'Allize',
                colSpan: 1,
                rowSpan: 2,
                styles: { halign: 'center', valign: 'middle', fillColor: [16, 22, 133], minCellHeight: 13 },
              },
              {
                content: 'TOTAL',
                colSpan: 1,
                rowSpan: 2,
                styles: { halign: 'center', valign: 'middle', minCellHeight: 13},
              },

            ],
          ],


          tableWidth: 120,
          margin: {left: 113 },
          body: [
              ['$231910', '$160132', '$329706', '$-', '$78106', '$538194'],
              ['$231910', '$160132', '$329706', '$-', '$78106', '$538194'],
          ],
          startY: 25,
         // body: rows1,
        })

        // TABLA GENERAL
        doc.autoTable({
          styles: { fontSize: 9, halign: 'center' },
          columnStyles: { 
            0:{ halign: 'left' }, 
            1:{ halign: 'center' }, 
            2:{ halign: 'right' }, 
            3:{ halign: 'right' }, 

          }, // Cells in first column centered 
          margin: { top: 10 },
          tableWidth: 'wrap',
          columns: ['Empresa','Casos', 'HN', 'A Facturar'],
          body: rows1,
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
        
    },



    getReport() {

      console.log(this.codperiodo);
      this.mesSelected = this.codperiodo.Codigo;
      var pars = {
        periodo: this.codperiodo.Codigo,
      };

     this.getReporte(pars);
     this.getDetalleReporte(pars);

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
  },
};
</script>

<style scoped>
.contenedor {
  width: 100%;
}

.row {
  padding: 0;
  margin: 0;
}

.maxcontent {
  height: 50vh;
  max-height: 50vh;
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
