<template>
  <div>
    <!--
    <v-btn class="ma-2 primary"  @click="createPDF()" >
      <v-icon left>mdi-content-save-outline</v-icon>Imprimir
    </v-btn>
    -->
    <v-data-table
      dense
      :items="datos"
      :headers="headers"
      class="elevation-1 small cell-height"
      :loading="loadingState"
      :loading-text="loadingText"
      no-data-text="No hay datos disponibles."
      hide-default-footer
      :header-props="{ sortIcon: null }"
      ref="myTable"
    >
    
      <template v-slot:item.Tipo="{ item }" >
        <div class="small titulo">
          {{ item.Tipo }}
        </div>
      </template>
      <template v-slot:item.Valores.M1="{ item }" >
        <div class="small">
          {{ setValor(item.Tipo, item.Fila, item.Valores.M1) }}
        </div>
      </template>
      <template v-slot:item.Valores.M2="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Fila, item.Valores.M2)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.M3="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Fila, item.Valores.M3)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.M4="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Fila, item.Valores.M4)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.M5="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Fila, item.Valores.M5)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.M6="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Fila, item.Valores.M6)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.M7="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Fila, item.Valores.M7)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.M8="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Fila, item.Valores.M8)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.M9="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Fila, item.Valores.M9)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.M10="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Fila, item.Valores.M10)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.M11="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Fila, item.Valores.M11)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.M12="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Fila, item.Valores.M12)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.Total="{ item }">
        <div class="small">
        {{
        setValor(item.Tipo, item.Fila, item.Valores.Total)
        }}
        </div>
      </template>
      <template v-slot:item.Anio="{ item }">
        <div class="small titulo">
        {{
        item.Anio
        }}
        </div>
      </template>

      <template v-slot:item.Valores.HN="{ item }">
        <div class="small ">
        {{
        setValorAnios('HN', item.Valores.HN)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.RentARS="{ item }">
        <div class="small"> 
        {{
        setValorAnios('RentARS', item.Valores.RentARS)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.RentARS_Porc="{ item }">
        <div class="small">
        {{
        setValorAnios('RentARS_Porc', item.Valores.RentARS_Porc)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.RentUSD="{ item }">
        <div class="small">
        {{
        setValorAnios('RentUSD', item.Valores.RentUSD)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.RentUSD_Porc="{ item }">
        <div class="small">
        {{
        setValorAnios('RentUSD_Porc', item.Valores.RentUSD_Porc)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.Duration="{ item }">
        <div class="small">
        {{
        setValorAnios('Duration', item.Valores.Duration)
        }}
        </div>
      </template>
      <template v-slot:item.Valores.TIR="{ item }">
        <div class="small">
        {{
        setValorAnios('TIR', item.Valores.TIR)
        }}
        </div>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import moment from "moment";
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

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
    tipoGrid:{
      type:String,
      required:true
    },
    loadingState: {
      type: Boolean,
      required: true
    },
    loadingText: {
      type: String,
      required: true
    }
  },

  computed: {},

  methods: {

    style(item) {
      return "small";
    },
    
    setValor(tipo, fila, valor) {
      var lastChar = tipo.slice(tipo.length - 2);

      if (valor == 0) {
        return "-";
      }

      switch (fila) {
        case 1:
        case 5:
          return this.$options.filters.numFormat(valor, "$0,0");
          break;
        case 2:
        case 7:
        case 11:
          return "USD " + this.$options.filters.numFormat(valor, "0,0");
          break;
        case 3:
          return this.$options.filters.numFormat(valor, "0,0");
          break;
        case 4:
          return this.$options.filters.numFormat(valor, "0.0");
          break;
        case 6:
        case 9:
        case 10:
        case 12:
        case 13:
          return this.$options.filters.numFormat(valor, "0,0") + "%";
          break;
        case 8:
          if (this.tipoGrid == "Cobros"){
            return this.$options.filters.numFormat(valor, "0,0") + "%";
          }
          return "USD " +  this.$options.filters.numFormat(valor, "0,0");
          break;
        default:
          return this.$options.filters.numFormat(valor, "0,0");
          break;
      }
    },

    setValorAnios(tipo, valor) {
      if (valor == 0) {
        return "-";
      }
      switch (tipo) {
        case "HN":
        case "RentARS":
          return this.$options.filters.numFormat(valor, "$0,0");
          break;

        case "RentUSD":
          return "USD " + this.$options.filters.numFormat(valor, "0,0");
          break;

        case "RentARS_Porc":
        case "RentUSD_Porc":
        case "TIR":
          return this.$options.filters.numFormat(valor, "0,0") + "%";
          break;
        case "Duration":
          return this.$options.filters.numFormat(valor, "0.0");
          break;

        default:
          return this.$options.filters.numFormat(valor, "0,0");
          break;
      }
    },

    formatFecha(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM/YYYY");
      }
    }, 

    createPDF () {
      console.log('Llego');
        var source1 =  this.$refs["myTable"];
        let rows1 = [];
        var source2 =  this.$refs["myTable"];
        let rows2 = [];
       //let columnHeader = ['Start Time', 'Term', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
       let columnHeader1 = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre', 'Total']
       //let columnHeader1 = headers;
       let columnHeader2 = ['', 'HN Comprados $', 'Rent. $', 'Rent. $ (%)', 'Rent. USD', 'Remt. USD (%)', 'Duration', 'TIR'];

        var heading = 'Reporte Resumen - Compras';

        let pdfName = 'ReporteResumen';
        source1.items.forEach(element => {
            var temp = [ 
                element.Tipo,
                Math.round(element.Valores.M1, 0),
                Math.round(element.Valores.M2, 0),
                Math.round(element.Valores.M3, 0),
                Math.round(element.Valores.M4, 0),
                Math.round(element.Valores.M5, 0),
                Math.round(element.Valores.M6, 0),
                Math.round(element.Valores.M7, 0),
                Math.round(element.Valores.M8, 0),
                Math.round(element.Valores.M9, 0),
                Math.round(element.Valores.M10, 0),
                Math.round(element.Valores.M11, 0),
                Math.round(element.Valores.M12, 0),
                Math.round(element.Valores.Total,0), 
            ];
            rows1.push(temp);
        });

         source2.items.forEach(element => {
            var temp = [ 
                element.Anio,
                Math.round(element.Valores.HN, 0),
                Math.round(element.Valores.RentARS, 0),
                Math.round(element.Valores.RentARS_Porc, 0),
                Math.round(element.Valores.RentUSD, 0),
                Math.round(element.Valores.RentUSD_Porc, 0),
                Math.round(element.Valores.Duration, 0),
                Math.round(element.Valores.TIR, 0),
                Math.round(element.Valores.Total,0), 
            ];
            rows2.push(temp);
        });
        var doc = new jsPDF({orientation: 'landscape'});

      // text is placed using x, y coordinates
      //doc.setFontSize(16).text(heading, 0.5, 1.0);
      // create a line under heading 
      doc.setLineWidth(0.01).line(0.5, 1.1, 8.0, 1.1);

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
            doc.text(5, 5, 'Reporte Resumen - Compras')

            // Footer
            var str = 'Pagina ' + doc.internal.getNumberOfPages()
            // Total page number plugin only available in jspdf v1.0+
            if (typeof doc.putTotalPages === 'function') {
              str = str + ' de ' + totalPagesExp
            }
            doc.setFontSize(10)

            // jsPDF 1.4+ uses getWidth, <1.4 uses .width

            doc.text(10, 0, str)
          },
        });

        doc.autoTable(columnHeader1, rows1, { startY: 10 });
        doc.autoTable(columnHeader2, rows2, { startY: 10 });

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
   
            doc.setFontSize(10)

            // jsPDF 1.4+ uses getWidth, <1.4 uses .width

            doc.text(5, 190, str)

        doc.save(pdfName + '.pdf');
        
    }
  }
};
</script>

<style>

  .small {
    font-size: 0.75rem;
    white-space: nowrap;
    vertical-align: middle;
  }

  .v-data-table tbody td {
    height: 5px !important;
    font-size: 0.75rem;
    padding-right: 2px !important;
    padding-left: 2px !important;
  }

  .v-data-table tbody th {
    height: 5px !important;
    font-size: 0.75rem;
    padding-right: 2px !important;
    padding-left: 2px !important;
  }

  .titulo {
    font-weight: bold;
  }

</style>

<!-- 

.v-data-table .v-data-table-header tr td {
    font-size: 0.75rem !important;
    height: 3px !important;
    padding-right: 2px !important;
    padding-left: 2px !important;
    font-weight: bold;
    white-space: nowrap;
    text-align: center;
  }

  .v-data-table .v-data-table-header tr th {
    font-size: 0.75rem !important;
    height: 3px !important;
    padding-right: 2px !important;
    padding-left: 2px !important;
    font-weight: bold;
    white-space: nowrap;
    text-align: center;
  }

-->