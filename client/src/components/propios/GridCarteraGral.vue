<template>
  <div class="contenedor">
    <v-card hover elevation-2 color="grey lighten-4">
        <v-card-title>
          Reporte Cartera General 
          <v-spacer></v-spacer>
          <v-btn
                outlined
                color="success"
                text
                small
                :disabled="this.loading_items_detalle_cartera"
                :loading="this.getting_items_detalle_cartera"
                @click="detalleExcelPendientes()"
            >
                <v-icon left>mdi-file-excel</v-icon>Detalle Pendientes
            </v-btn>
        </v-card-title>
    <v-data-table
      dense
      :headers="headers"
      :hide-default-header="true"
      :items="datos_cartera"
      :item-class="style"
      :search="search"
      item-key="itemkey"
      class="elevation-1"
      :loading="loading_cartera"
      :loading-text="loadingtext"
      no-data-text="No hay datos disponibles"
      :hide-default-footer="true"
      :items-per-page="-1"
    >

      <template v-slot:header="{ props }">
        <thead class="v-data-table-header">
          <tr>
              <th class="text-center child-header"></th>
              <th class="text-center child-header lineH2LB" colspan="3">Avance Menor a 45</th>
              <th class="text-center child-header lineH2LB" colspan="3">Avance Entre 45 y 60</th>
              <th class="text-center child-header lineH2LB" colspan="3">Avance Mayor a 60</th>
              <th class="text-center child-header lineH2LB" colspan="3">Total</th>
          </tr>
          <tr>
            <th class="text-center child-header">Marca</th>

            <th class="text-center lineH2">Cantidad</th>
            <th class="text-center lineH2">% HN Bajo</th>
            <th class="text-center lineH2">% Trabajados</th>

            <th class="text-center lineH2">Cantidad</th>
            <th class="text-center lineH2">% HN Bajo</th>
            <th class="text-center lineH2">% Trabajados</th>


            <th class="text-center lineH2">Cantidad</th>
            <th class="text-center lineH2">% HN Bajo</th>
            <th class="text-center lineH2">% Trabajados</th>


            <th class="text-center lineH2">Cantidad</th>
            <th class="text-center lineH2">% HN Bajo</th>
            <th class="text-center lineH2">% Trabajados</th>
          </tr>
        </thead>
      </template>

              

      <template v-slot:item.NomMarca="{ item }">
          {{ item.NomMarca }}
      </template>

      <template v-slot:item.Menor45.Cantidad="{ item }">
          {{ item.Menor45.Cantidad }}
      </template>

      <template v-slot:item.Menor45.CantHNBajo="{ item }">
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
              <v-layout justify-center v-on="on" class="rowclass" >{{ getPorcentaje(item.Menor45.Cantidad, item.Menor45.CantHNBajo) }}</v-layout> 
          </template>
          <span>{{ getTooltipData(item.Menor45.CantHNBajo) }}</span>
        </v-tooltip>
      </template>

      <template v-slot:item.Menor45.CantTrabajados="{ item }">
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
              <v-layout justify-center v-on="on" class="rowclass" >{{ getPorcentaje(item.Menor45.Cantidad, item.Menor45.CantTrabajados) }}</v-layout> 
          </template>
          <span>{{ getTooltipData(item.Menor45.CantTrabajados) }}</span>
        </v-tooltip>
      </template>

      

      <template v-slot:item.Entre45y60.Cantidad="{ item }">
          {{ item.Entre45y60.Cantidad }}
      </template>

      <template v-slot:item.Entre45y60.CantHNBajo="{ item }">
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
              <v-layout justify-center v-on="on" class="rowclass" >{{ getPorcentaje(item.Entre45y60.Cantidad, item.Entre45y60.CantHNBajo) }}</v-layout> 
          </template>
          <span>{{ getTooltipData(item.Entre45y60.CantHNBajo) }}</span>
        </v-tooltip>
          
      </template>

      <template v-slot:item.Entre45y60.CantTrabajados="{ item }">
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
              <v-layout justify-center v-on="on" class="rowclass" >{{ getPorcentaje(item.Entre45y60.Cantidad, item.Entre45y60.CantTrabajados) }}</v-layout> 
          </template>
          <span>{{ getTooltipData(item.Entre45y60.CantTrabajados) }}</span>
        </v-tooltip>
      </template>

      <template v-slot:item.Mayor60.Cantidad="{ item }">
          {{ item.Mayor60.Cantidad }}
      </template>

      <template v-slot:item.Mayor60.CantHNBajo="{ item }">
          <v-tooltip bottom>
          <template v-slot:activator="{ on }">
              <v-layout justify-center v-on="on" class="rowclass" >{{ getPorcentaje(item.Mayor60.Cantidad, item.Mayor60.CantHNBajo) }}</v-layout> 
          </template>
          <span>{{ getTooltipData(item.Mayor60.CantHNBajo) }}</span>
        </v-tooltip>
      </template>

      <template v-slot:item.Mayor60.CantTrabajados="{ item }">
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
              <v-layout justify-center v-on="on" class="rowclass" >{{ getPorcentaje(item.Mayor60.Cantidad, item.Mayor60.CantTrabajados) }}</v-layout> 
          </template>
          <span>{{ getTooltipData(item.Mayor60.CantTrabajados) }}</span>
        </v-tooltip>
      </template>

      <template v-slot:item.CantDatos="{ item }">
          {{ item.CantDatos }}
      </template>

      <template v-slot:item.CantDatos.CantHNBajo="{ item }">
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
              <v-layout justify-center v-on="on" class="rowclass" > {{ getPorcentaje(item.CantDatos, item.TotalesHNBajo) }}</v-layout> 
          </template>
          <span>{{ getTooltipData(item.TotalesHNBajo) }}</span>
        </v-tooltip>
      </template>
      <template v-slot:item.CantDatos.CantTrabajados="{ item }">
          
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
              <v-layout justify-center v-on="on" class="rowclass" > {{ getPorcentaje(item.CasosTrabajables, item.TotalesTrabajados) }}</v-layout> 
          </template>
          <span>{{ getTooltipData(item.TotalesTrabajados) }}</span>
        </v-tooltip>
      </template>

      <template slot="body.append">
        <tr>
          <td class="total">Totales</td>
          <td class="totales">{{ sumFieldCantidad("Menor45") | numFormat("0,0") }}</td>
          <td class="totales">{{ sumFieldCantidadPorc("Menor45", "CantHNBajo") }}</td>
          <td class="totales">{{ sumFieldCantidadPorc("Menor45", "CantTrabajados") }}</td>
          
          <td class="totales">{{ sumFieldCantidad("Entre45y60") | numFormat("0,0") }}</td>
          <td class="totales">{{ sumFieldCantidadPorc("Entre45y60", "CantHNBajo")  }}</td>
          <td class="totales">{{ sumFieldCantidadPorc("Entre45y60", "CantTrabajados") }}</td>

          <td class="totales">{{ sumFieldCantidad("Mayor60") | numFormat("0,0") }}</td>
          <td class="totales">{{ sumFieldCantidadPorc("Mayor60", "CantHNBajo") }}</td>
          <td class="totales">{{ sumFieldCantidadPorc("Mayor60", "CantTrabajados") }}</td>

          <td class="totales">{{ sumFieldCantidadTotal("CantDatos") | numFormat("0,0") }}</td>
          <td class="totales">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                  <v-layout justify-center v-on="on" class="rowclass" > {{ sumFieldCantidadPorcTotal("CantDatos", "TotalesHNBajo") }}</v-layout> 
              </template>
              <span>{{ this.getTooltipData(this.sumFieldCantidadTotal("TotalesHNBajo"))}}</span>
            </v-tooltip>
          </td>

          <td class="totales">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                  <v-layout justify-center v-on="on" class="rowclass" > {{ sumFieldCantidadPorcTotal("CasosTrabajables", "TotalesTrabajados") }}</v-layout> 
              </template>
              <span>{{ this.getTooltipData(this.sumFieldCantidadTotal("TotalesTrabajados"))}}</span>
            </v-tooltip>
          </td>
        </tr>
      </template>

      
    </v-data-table>
     </v-card>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import XLSX from "xlsx";
import jsPDF from 'jspdf'

export default {
  name: "gridcarteragralcomponent",
  props: {
    itemkey: {
      type: String,
      default: "",
    },
    search: {
      type: String,
      default: "",
    },
    loadingtext: {
      type: String,
      default: "Cargando Datos Cartera... Aguarde...",
    },
    dense: {
      type: Boolean,
      default: false,
    },
    detalle_ces: {
      type: Boolean,
      default: false,
    },

  },
  data() {
    return {
      headers: [
        {
          text: "Marca",
          align: "start",
          value: "NomMarca",
        },
        { 
          text: "Menor a 45", 
          value: "Menor45.Cantidad", 
          align: "center" 
        },
        { 
          text: "Porc. HN Bajo", 
          value: "Menor45.CantHNBajo",  
          align: "center" 
        },
        { 
          text: "Porc. Trabajados", 
          value: "Menor45.CantTrabajados",  
          align: "center" 
        },
        {
          text: "Entre 45 y 60",
          value: "Entre45y60.Cantidad",
          align: "center",
        },
        { 
          text: "Porc. HN Bajo", 
          value: "Entre45y60.CantHNBajo", 
          align: "center" 
        },
        { 
          text: "Porc. Trabajados", 
          value: "Entre45y60.CantTrabajados", 
          align: "center" 
        },
        {
          text: "Mayor a 60",
          value: "Mayor60.Cantidad",
          align: "center",
        },

        { 
          text: "Porc. HN Bajo", 
          value: "Mayor60.CantHNBajo", 
          align: "center" 
        },
        { 
          text: "Porc. Trabajados", 
          value: "Mayor60.CantTrabajados", 
          align: "center" 
        },
        
        {
          text: "Total",
          value: "CantDatos",
          align: "center",
        },

        { 
          text: "Porc. HN Bajo", 
          value: "CantDatos.CantHNBajo", 
          align: "center" 
        },
        { 
          text: "Porc. Trabajados", 
          value: "CantDatos.CantTrabajados", 
          align: "center" 
        },
      ],
    };
  },

  created() {
    if (!(this.detalle_ces)){
      this.getDatosCartera();
    }
    
  },

  computed: {
    ...mapState("tablerocontrol", [
      "dataStatus",
      "datos_cartera",
      "items_cartera",
      "loading_cartera",
      "loading_items_detalle_cartera",
      "items_detalle_cartera",
      "getting_items_detalle_cartera"
    ]),
  },

  methods: {
    ...mapActions({ 
      getDatosCartera: "tablerocontrol/getCarteraGral",
      getDetalle: "tablerocontrol/getDetallePendientesCarteraGral" 
    }),

    style(item) {
      if (item.EsFilaMarca == 1) {
        return "negrita";
      }
    },

    async detalleExcelPendientes(){
      
      await this.getDetalle();

      console.log(this.items_detalle_cartera);
      this.generateExcel();

    },


    generateExcel(){
      
      const workbook = XLSX.utils.book_new();
      const filename = "DetallePendientes";
      
      let sheetname1 = "";
      let sheetname2 = "";

      //console.log(this.detalle_gral);
      this.items_detalle_cartera.forEach(element => {
         
          let nomMarca = element.NomMarca.toUpperCase();
          let data45_60 = element.Entre45y60;
          let data60 = element.Mayor60;

          let data1 = XLSX.utils.json_to_sheet(data45_60.lstPendientes);
          let data2 = XLSX.utils.json_to_sheet(data60.lstPendientes);
         
          sheetname1 = nomMarca + ' - Entre 45 y 60';
          sheetname2 = nomMarca + ' - Mayor a 60';
         
          XLSX.utils.book_append_sheet(workbook, data1, sheetname1);
          XLSX.utils.book_append_sheet(workbook, data2, sheetname2);
        });
       
      

      XLSX.writeFile(workbook, `${filename}.xlsx`);


    },
    

    getPorcentaje(total, parte){
      if (parte == 0){
        return "-";
      }
      if (total > 0){
        return this.$options.filters.numFormat((parte / total), "%0,0"); 
      }
    },

    getTooltipDataTotales(key){
      this.getTooltipData(this.sumFieldCantidadTotal(key));
    },

    getTooltipData(valor){
      if (valor > 0){
        return "Cant. Casos: " + valor;
      }
    },

    sumFieldCantidadPorcTotal(key,key2){
      let totCasos = 0;
      let totParte = 0;
      this.items_cartera.forEach(element => {
        if (this.detalle_ces == element['EsFilaMarca']){
            totCasos += element[key];
            totParte += element[key2];
        }
      });

      if (totCasos > 0){
        return this.$options.filters.numFormat((totParte / totCasos), "%0,0"); 
      }
      return "-"

   },

   sumFieldCantidadPorc(key,key2){
      let totCasos = 0;
      let totParte = 0;
      this.items_cartera.forEach(element => {
        if (this.detalle_ces == element['EsFilaMarca']){
          totCasos += element[key]['Cantidad'];
          totParte += element[key][key2];
        }
      });

      if (totCasos > 0){
        return this.$options.filters.numFormat((totParte / totCasos), "%0,0"); 
      }
      return "-"

   },

    sumFieldCantidad(key) {

      let tot = 0;
      this.items_cartera.forEach(element => {
        if (this.detalle_ces == element['EsFilaMarca']){
          tot += element[key]['Cantidad'];
        }
          
      });

      return tot;

    },

    sumFieldCantidadTotal(key) {

      let tot = 0;
      this.items_cartera.forEach(element => {
        if (this.detalle_ces == element['EsFilaMarca']){
          tot += element[key];
        }
      });

      return tot;

    },

    sumFieldsCantidad(key1, key2, key3) {
 
      let tot = 0;
      this.items_cartera.forEach(element => {
          tot += element[key1]['Cantidad'];
      });

      return tot;

    },

  },
};
</script>

<style scoped>
.contenedor {
  height: 15px;
}

.v-data-table >>> .negrita {
  font-weight: bold;
}

.lineH1 {
  font-weight: bold;
  border-left: 1px solid #000000;
  border-right: 1px solid #000000;
}

.lineH1L {
  font-weight: bold;
  border-left: 1px solid #000000;
}

.lineH2 {
  border-left: 1px solid #000000;
  border-right: 1px solid #000000;
}

.lineH2L {
  border-left: 1px solid #000000;
}

.lineH2LB {
  border-left: 1px solid #000000;
  border-bottom: 1px solid #000000;
}

.lineL {
  border-left: 1px solid #000000;
}

.lineR {
  border-right: 1px solid #000000;
}

.total {
  font-weight: bold;
}

.totales {
  font-weight: bold;
  text-align: center;
}

.rowclass {
  padding: 0;
}

.rowclassBtn {
  padding-top: 2;
}
</style>