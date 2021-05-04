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
          >
            <v-icon left>mdi-refresh</v-icon>Actualizar
          </v-btn>
        </v-col>
        <v-col cols="1" md="1">
          <v-btn
            class="ma-2"
            outlined
            color="success"
            text
            @click="exportExcel"
          >
            <v-icon left>mdi-file-excel</v-icon>Detalle
          </v-btn>
        </v-col>
      </v-row>
      </v-card-title>
      <div class="card-body">
          <v-container>
            <v-row justify="center">
            
                <v-col cols="12" >
                  <v-row >
                    <v-col >
                    <CardComisionGridComponent @setNombreConc="nomConcesDetalle" @setExpand="exandDetail"  concesionario="RB" subtitle="Detalle por Concesionario" :headers="headers" :datos="datos_rb"></CardComisionGridComponent>
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
                      <CardComisionComponent :concesionario="item.NomConcesionario" :total="item.TotalHN" :cantidad="item.CantHN"></CardComisionComponent>
                    </v-col>
                  </v-row>
                  <v-row >
                    <v-col >
                      <CardComisionGridComponent concesionario="Pagos" subtitle="Detalle de Comisiones a Pagar" total="424607" cantidad="" :headers="headers2" :datos="datos2"></CardComisionGridComponent>
                    </v-col>
                  </v-row>
                
                </v-col>

            </v-row>
          </v-container>
  
      </div>
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
          value: "Destinatario",
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
      "detalle_rb_ce"
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

    }),

    nomConcesDetalle(value){
        this.nomConces = value;
    },

    exandDetail(value){
      this.expandedDetail = value;
    },

    exportExcel: function () {
      //let data = XLSX.utils.json_to_sheet(this.datos);
      let data_rb = XLSX.utils.json_to_sheet(this.detalle_rb);
      let data_ce = XLSX.utils.json_to_sheet(this.detalle_rb_ce);
      const workbook = XLSX.utils.book_new();
      const filename = "DetalleFacturacion";

      let sheetname = "DetalleFacturacion_RB";
      XLSX.utils.book_append_sheet(workbook, data_rb, sheetname);

      sheetname = "DetalleFacturacion_CE";
      XLSX.utils.book_append_sheet(workbook, data_ce, sheetname);

      XLSX.writeFile(workbook, `${filename}.xlsx`);
    },

    getReport() {
    
      console.log(this.codperiodo);
      this.mesSelected = this.codperiodo.Codigo;
      var pars = {
        periodo: this.codperiodo.Codigo,
      };

     this.getReporte(pars);

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
