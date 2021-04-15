<template>
  <v-app class="contenedor">
    <v-card color="grey lighten-4" class="padded">
      <v-row>
        <v-col cols="3" sm="3" lg="3">
          <v-combobox
            v-model="selectAnio"
            :items="itemsAnios"
            label="Año"
            @change="refreshProyectado"
          ></v-combobox>
        </v-col>
        <v-spacer></v-spacer>
        <!--
        <v-col cols="3" sm="3" lg="3">
          <v-combobox
            :value="selectFiltro"
            item-text="Nombre"
            item-value="Codigo"
            :items="itemsFiltro"
            label="Filtrar Titular HN"
            @change="setDefaultFiltered"
          ></v-combobox>
        </v-col>
        -->
      </v-row>
      <v-row>
        <v-col cols="12" sm="12" lg="12" md="12">
          <GridComponentProyectado
            :pars="{
              grid: 'DetalleMeses',
            }"
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
              grid: 'RentabilidadMeses',
            }"
            :datos="this.datosDetalleRentMeses"
            :headers="headMeses"
            :loadingState="this.loadingdetalle_proyec_renta_mes"
            loadingText="Cargando Rentabilidades Mensuales..."
          ></GridComponentProyectado>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" sm="12" lg="12" md="12">
          <GridComponentProyectado
            :pars="{
              grid: 'DetalleAnios',
            }"
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
            :datos="this.datosDetalleRentAnios"
            :headers="headAnios"
            :loadingState="this.loadingdetalle_proyec_renta_anios"
            loadingText="Cargando Rentabilidades Anuales..."
          ></GridComponentProyectado>
        </v-col>
      </v-row>
      <v-btn class="ma-2" 
              color="primary"
              outlined
              text 
              @click="createPDF(headersReportMeses, headersReportAnios, 'Proyectado')" >
        <v-icon left>mdi-printer</v-icon>Imprimir
      </v-btn>
    </v-card>
  </v-app>
</template>

<script>
import GridComponentProyectado from "@/components/propios/GridComponentProyectado.vue";
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

    filtro_Titular: {
      type: Object,
      required: true,
    },

    concesSelecteds:{
     // type: Object,
      type: Array,
      required: true,
    }
  },

  name: "hnproyectados",
  components: {
    GridComponentProyectado,
  },

  data() {
    return {
      headersReportMeses: ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre', 'Total'],
      headersReportAnios: ['', '2021', '2022', '2023', '2024', '2025', '2026', 'Total'],
      selectAnio: 2021,
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
      /*
      headAnios: [
        { text: "", value: "Tipo", align: "left", width: "16%" },
        { text: "2020", value: "Valores.A0", align: "center", width: "12%" },
        { text: "2021", value: "Valores.A1", align: "center", width: "12%" },
        { text: "2022", value: "Valores.A2", align: "center", width: "12%" },
        { text: "2023", value: "Valores.A3", align: "center", width: "12%" },
        { text: "2024", value: "Valores.A4", align: "center", width: "12%" },
        { text: "2025", value: "Valores.A5", align: "center", width: "12%" },
        {
          text: "Total",
          value: "Valores.Total",
          align: "center",
          width: "12%",
        },
      ],
      */

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
    solo_CE(newValue, oldValue) {
      this.setDefaultDataSource(newValue);
    },

    filtro_Titular(newValue){
      this.tipoFiltro = newValue;
      this.setDefaultFiltered(newValue);
    },

    selectAnio(newValue){
      if (!newValue) {
          this.selectedAnio = this.selectAnio;
      } else {
        this.selectedAnio = 2021;  
      }
    },

    loadingdetalle_proyec_mes(newValue) {
      if (!newValue) {
        if (this.switchCE) {
          this.datosDetalleMeses = this.detalle_proyec_mes_CE;
        } else {
          this.datosDetalleMeses = this.detalle_proyec_mes;
        }
        this.setDefaultFiltered(this.tipoFiltro);
      } else {
        this.datosDetalleMeses = [];  
      }
    },

    loadingdetalle_proyec_renta_mes(newValue) {
      if (!newValue) {
        if (this.solo_CE) {
          this.datosDetalleRentMeses = this.detalle_proyec_renta_mes_CE;
        } else {
          this.datosDetalleRentMeses = this.detalle_proyec_renta_mes;
        }
      } else {
        this.datosDetalleRentMeses = [];
      }
    },

    loadingdetalle_proyec_anios(newValue) {
      if (!newValue) {
        if (this.solo_CE) {
          this.datosDetalleAnios = this.detalle_proyec_anios_CE;
        } else {
          this.datosDetalleAnios = this.detalle_proyec_anios;
        }
      } else {
        this.datosDetalleAnios = [];
      }
    },

    loadingdetalle_proyec_renta_anios(newValue) {
      if (!newValue) {
        if (this.solo_CE) {
          this.datosDetalleRentAnios = this.detalle_proyec_renta_anios_CE;
        } else {
          this.datosDetalleRentAnios = this.detalle_proyec_renta_anios;
        }
      } else {
        this.datosDetalleRentAnios = [];
      }
    },
  },

  computed: {
    ...mapState("haberneto", [
      "detalleMeses",
      "rentabilidadMeses",
      "detalleAnios",
      "rentabilidadAnios",
      "loadingDatos",
    ]),
    ...mapState("proyectadohn", [
      "detalle_proyec_mes",
      "detalle_proyec_mes_Giama",
      "detalle_proyec_mes_CE",
      "loadingdetalle_proyec_mes",
      "detalle_proyec_renta_mes",
      "detalle_proyec_renta_mes_Giama",
      "detalle_proyec_renta_mes_CE",
      "loadingdetalle_proyec_renta_mes",
      "detalle_proyec_anios",
      "detalle_proyec_anios_Giama",
      "detalle_proyec_anios_CE",
      "loadingdetalle_proyec_anios",
      "detalle_proyec_renta_anios",
      "detalle_proyec_renta_anios_Giama",
      "detalle_proyec_renta_anios_CE",
      "loadingdetalle_proyec_renta_anios",
    ]),

    codMarca() {
      return this.pars.marcaSelected;
    },

    arrayDatos(){
      let arr = [] 
      arr.push(this.detalle_proyec_mes);
      arr.push(this.detalle_proyec_renta_mes);
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

  created() {
    this.setDefaultDataSource(this.solo_CE);
  },

  methods: {
    ...mapActions({
      getHNProyectados: "proyectadohn/getHNProyectados",
    }),

    setDefaultDataSource(soloCE) {
      if (soloCE) {
        this.datosDetalleMeses = this.detalle_proyec_mes_CE;
        this.datosDetalleRentMeses = this.detalle_proyec_renta_mes_CE;
        this.datosDetalleAnios = this.detalle_proyec_anios_CE;
        this.datosDetalleRentAnios = this.detalle_proyec_renta_anios_CE;
      } else {
        this.datosDetalleMeses = this.detalle_proyec_mes;
        this.datosDetalleRentMeses = this.detalle_proyec_renta_mes;
        this.datosDetalleAnios = this.detalle_proyec_anios;
        this.datosDetalleRentAnios = this.detalle_proyec_renta_anios;
      }
    },

    setDefaultFiltered(valor){
        //console.log(valor.Nombre);
        this.nameFilterTitHN = valor.Nombre;
        switch(valor.Codigo){
          case 0: //Todos
            this.datosDetalleMeses = this.detalle_proyec_mes;
            this.datosDetalleRentMeses = this.detalle_proyec_renta_mes;
            this.datosDetalleAnios = this.detalle_proyec_anios;
            this.datosDetalleRentAnios = this.detalle_proyec_renta_anios;
          break;
          case 1: //Giama
            this.datosDetalleMeses = this.detalle_proyec_mes_Giama;
            this.datosDetalleRentMeses = this.detalle_proyec_renta_mes_Giama;
            this.datosDetalleAnios = this.detalle_proyec_anios_Giama;
            this.datosDetalleRentAnios = this.detalle_proyec_renta_anios_Giama;
          break;
          case 2: //Conces
              this.datosDetalleMeses = this.detalle_proyec_mes_CE;
              this.datosDetalleRentMeses = this.detalle_proyec_renta_mes_CE;
              this.datosDetalleAnios = this.detalle_proyec_anios_CE;
              this.datosDetalleRentAnios = this.detalle_proyec_renta_anios_CE;
          break;

        }
    },

    refreshProyectado() {
      console.log(this.pars);

      var params = {
        Marca: this.codMarca,
        Concesionario: this.codConcesionario,
        Empresa: 8,
        Anio: this.selectAnio,
        Filtros: "",
      };
      this.getHNProyectados(params);
    },

    formatFecha(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM/YYYY");
      }
    },

    setSimboloValor(tipo, valor){
      if (valor !== 0){
          switch(tipo){
            case "HN a Cobrar $":
            case "Rent. $":
                return "$ " + this.$options.filters.numFormat(valor, "0,0");
            case "Rent. USD":
            case "HN a Cobrar USD":
                return "USD " + this.$options.filters.numFormat(valor, "0,0");
            case "Casos a Cobrar":
                return "" + valor
            case "Rent. $ (%)":
            case "Rent. USD (%)":
            case "% Part.":  
                return Math.round(valor, 0) + "%"
        }
      }
      return "-" 
    },

    createPDF (arrHeadM, arrHeadA, titulo) {
      console.log('Llego');

        var items1 = this.datosDetalleMeses;
        var items2 = this.datosDetalleRentMeses;
        var items3 = this.datosDetalleAnios;
        var items4 = this.datosDetalleRentAnios;

        
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

        let rows1 = [];
        let rows2 = [];
        let rows3 = [];
        let rows4 = [];

        let subtitulo = 'Año: ' + this.selectAnio + ' - Concesionario: ' + concesSelectStr + ' -  Titular HN: ' + filtroTitHN;
        var heading = 'Proyectado de Cobros ' + titulo;

        let pdfName = 'Proyectado_Cobros_' + titulo;

        let pasada = 0;

        items1.forEach(element => {
          var temp1 = [
                element.Tipo,
                this.setSimboloValor(element.Tipo, element.Valores.M1),
                this.setSimboloValor(element.Tipo, element.Valores.M2),
                this.setSimboloValor(element.Tipo, element.Valores.M3),
                this.setSimboloValor(element.Tipo, element.Valores.M4),
                this.setSimboloValor(element.Tipo, element.Valores.M5),
                this.setSimboloValor(element.Tipo, element.Valores.M6),
                this.setSimboloValor(element.Tipo, element.Valores.M7),
                this.setSimboloValor(element.Tipo, element.Valores.M8),
                this.setSimboloValor(element.Tipo, element.Valores.M9),
                this.setSimboloValor(element.Tipo, element.Valores.M10),
                this.setSimboloValor(element.Tipo, element.Valores.M11),
                this.setSimboloValor(element.Tipo, element.Valores.M12),
                this.setSimboloValor(element.Tipo, element.Valores.Total), 
            ];
            rows1.push(temp1);
        });

        items2.forEach(element => {
          var temp2 = [
                element.Tipo,
                this.setSimboloValor(element.Tipo, element.Valores.M1),
                this.setSimboloValor(element.Tipo, element.Valores.M2),
                this.setSimboloValor(element.Tipo, element.Valores.M3),
                this.setSimboloValor(element.Tipo, element.Valores.M4),
                this.setSimboloValor(element.Tipo, element.Valores.M5),
                this.setSimboloValor(element.Tipo, element.Valores.M6),
                this.setSimboloValor(element.Tipo, element.Valores.M7),
                this.setSimboloValor(element.Tipo, element.Valores.M8),
                this.setSimboloValor(element.Tipo, element.Valores.M9),
                this.setSimboloValor(element.Tipo, element.Valores.M10),
                this.setSimboloValor(element.Tipo, element.Valores.M11),
                this.setSimboloValor(element.Tipo, element.Valores.M12),
                this.setSimboloValor(element.Tipo, element.Valores.Total), 
            ];
            rows2.push(temp2);
        });          
         
        items3.forEach(element => {
          var temp3 = [
                element.Tipo,
                this.setSimboloValor(element.Tipo, element.Valores.A0),
                this.setSimboloValor(element.Tipo, element.Valores.A1),
                this.setSimboloValor(element.Tipo, element.Valores.A2),
                this.setSimboloValor(element.Tipo, element.Valores.A3),
                this.setSimboloValor(element.Tipo, element.Valores.A4),
                this.setSimboloValor(element.Tipo, element.Valores.A5),
              //  this.setSimboloValor(element.Tipo, element.Valores.A6),
              //  this.setSimboloValor(element.Tipo, element.Valores.A7),
                this.setSimboloValor(element.Tipo, element.Valores.Total), 
            ];
            rows3.push(temp3);
        });
        
        items4.forEach(element => {
          var temp4 = [
                  element.Tipo,
                  this.setSimboloValor(element.Tipo, element.Valores.A0),
                  this.setSimboloValor(element.Tipo, element.Valores.A1),
                  this.setSimboloValor(element.Tipo, element.Valores.A2),
                  this.setSimboloValor(element.Tipo, element.Valores.A3),
                  this.setSimboloValor(element.Tipo, element.Valores.A4),
                  this.setSimboloValor(element.Tipo, element.Valores.A5),
                 // this.setSimboloValor(element.Tipo, element.Valores.A6),
                 // this.setSimboloValor(element.Tipo, element.Valores.A7),
                  this.setSimboloValor(element.Tipo, element.Valores.Total), 
              ];
              rows4.push(temp4);
          
        });



       
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
            doc.text(15, 15, 'Reporte Proyectado de Cobros')
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
          columns: arrHeadM,
          body: rows1,
          startY: 28,
        })

        doc.autoTable({
          styles: { fontSize: 8, halign: 'center' },
          columnStyles: { 0: { halign: 'left' } }, // Cells in first column centered 
          margin: { top: 10 },
          columns: arrHeadM,
          body: rows2
        })

        doc.autoTable({
          styles: { fontSize: 8, halign: 'center' },
          columnStyles: { 0: { halign: 'left' } }, // Cells in first column centered 
          margin: { top: 10 },
          columns: arrHeadA,
          body: rows3
        })

        doc.autoTable({
          styles: { fontSize: 8, halign: 'center' },
          columnStyles: { 0: { halign: 'left' } }, // Cells in first column centered 
          margin: { top: 10 },
          columns: arrHeadA,
          body: rows4
        })
        /*
       doc.autoTable(arrHeadM, rows1, { startY: 23 }, {bodyStyles: { fontSize: '8px' }});
       doc.autoTable(arrHeadM, rows2, { startY: 66 });
       doc.autoTable(arrHeadA, rows3, { startY: 115 });
       doc.autoTable(arrHeadA, rows4, { startY: 152 });
        */
        doc.autoTable({
            didDrawPage: function (data) {  
          },
        });
    

      // Creating footer and saving file
      /*
       var str = 'Pagina ' + doc.internal.getNumberOfPages()
       // Total page number plugin only available in jspdf v1.0+
        if (typeof doc.putTotalPages === 'function') {
          str = str + ' de ' + totalPagesExp
        }
        // Footer
   
          doc.setFontSize(8)
          doc.text(20, 190, 'Footer')

            // jsPDF 1.4+ uses getWidth, <1.4 uses .width

          //doc.text(5, 190, str)
        */
        doc.save(pdfName + '.pdf');
        
    }

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
