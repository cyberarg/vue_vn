<template>
  <div>
    <v-card >
      <!--
      <v-card-title>
        {{ pars.titleform }}
        <v-divider class="mx-4" inset vertical></v-divider>

        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Buscar"
          single-line
          hide-details
        ></v-text-field>
        <v-spacer></v-spacer>
        <v-btn class="ma-2" color="primary" outlined text @click="getDatos()">
          <v-icon left>mdi-refresh</v-icon>Actualizar
        </v-btn>
      </v-card-title>
      -->
      <v-data-table
        dense
        fixed-header
        height="58vh"
        :headers="headers_component"
        :items="this.items"
        :item-class="setClass"
        :search="search"
        item-key="pars.itemkey"
        :items-per-page="cantItems"
        class="elevation-1"
        :loading="loadingDatos"
        loading-text="Cargando Datos... Aguarde"
        no-data-text="No hay datos disponibles"
      >
   
        <template v-slot:item.Star="{ item }">
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <template v-if="item.TieneCambioReconocimiento == 1" v-on="on">
                <v-icon left>mdi-star</v-icon>
              </template>
            </template>
            <span>{{ getTooltipData() }}</span>
          </v-tooltip>
        </template>
        
        <template v-slot:item.HaberNeto="{ item }">
          {{ item.HaberNeto | numFormat("$0,0") }}
        </template>
        <template v-slot:item.ApeNom="{ item }">{{ item.ApeNom }}</template>
        <template v-slot:item.Telefono="{ item }">{{ item.Telefono }}</template>
        <template v-slot:item.Avance="{ item }">{{ item.Avance }}</template>
        <template v-slot:item.CodEstado="{ item }">
          {{ getTextEstado(item.CodEstado) }}
        </template>
       
   
        <template v-slot:item.FechaUltObs="{ item }">
          {{ formatFecha(item.FechaUltObs) }}
        </template>

        <template v-slot:item.FechaLead="{ item }">
          {{ formatFecha(item.FechaLead) }}
        </template>

        <template v-slot:item.VerDato="{ item }">
          <v-btn text @click="getDato(item)">
            <v-icon left>mdi-text-search</v-icon>Ver Dato
          </v-btn>
        </template>

        
        <template v-slot:top>
          <v-expansion-panels focusable>
            <v-expansion-panel>
              <v-expansion-panel-header> Filtros </v-expansion-panel-header>
              <v-expansion-panel-content>
                <v-container fluid>
                  <v-row>

                    <v-col cols="3">
                      <v-row class="pa-6">
                        <v-text-field
                          type="date"
                          dense
                          class="fillable"
                          :min="getMin"
                          :max="getToday"
                          label="Fecha Lead Desde"
                          placeholder="Fecha Lead Desde"
                          v-model="fecha_desde"
                        ></v-text-field>
                      </v-row>
                    </v-col>
                    <v-col cols="3">
                      <v-row class="pa-6">
                        <v-text-field
                          type="date"
                          dense
                          class="fillable"
                          :min="getMin"
                          :max="getToday"
                          label="Fecha Lead Hasta"
                          placeholder="Fecha Lead Hasta"
                          v-model="fecha_hasta"
                        ></v-text-field>
                      </v-row>
                    </v-col>
                  
                  </v-row>
                </v-container>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
        </template>
    
      </v-data-table>

      <v-card-actions v-show="exportable">
        <v-btn
          cclass="ma-2"
          color="primary"
          outlined
          text
          
          @click="showModalWeb"
        >
          <v-icon left>mdi-folder-plus-outline</v-icon>Nuevo Dato
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn
          cclass="ma-2"
          color="success"
          outlined
          text
          @click="exportExcel"
        >
          <v-icon left>mdi-file-excel-outline</v-icon>Excel
        </v-btn>
      </v-card-actions>
    </v-card>

     <template>
        <v-dialog
          v-model="dialogAlta"
          :scrollable="false"
          :hide-overlay="false"
          max-width="700px"
          ma-0
        >
          <FormAltaDatoWeb
            @hide="dialogAlta = false"
            @refresh="this.getDatos()"
          ></FormAltaDatoWeb>
        </v-dialog>
      </template>
  </div>
</template>
<script>
import { mapState, mapActions } from "vuex";
import XLSX from "xlsx";
import moment from "moment";
import FormAltaDatoWeb from "@/components/propios/FormAltaDatoWeb.vue";

export default {
  name: "formaltadatoweb",
  components: {
    FormAltaDatoWeb,
  },
  props: {
    pars: {
      type: Object,
      required: true,
    },

    search: {
      type: String,
      required: true,
    },
    grid: {
      type: String,
      required: true,
    },
    items: {
      type: Array,
      default: []
    },
  },

  data() {
    return {
      // Filter models.
      oficialFilterValue: "",
      stateFilterValue: null,
      sliderAvance: 45,
      sliderHaberNeto: 15000,
      dialogAlta: false,
      fecha_desde: null,
      fecha_hasta: null,

      statesList: [
        { text: "Todos", value: 0 },
        { text: "Sin Gestionar", value: "null" },
        { text: "Telefono Mal", value: "Telefono Mal" },
        { text: "Deje Mensaje", value: "Deje Mensaje" },
        { text: "Entrevista Pendiente", value: "Entrevista Pendiente" },
        { text: "No le interesa", value: "No le interesa" },
        { text: "Vende Plan", value: "Vende el Plan" },
        { text: "En Gesti??n", value: "En Gestion" },
        { text: "Descartados", value: "Descartados" },
      ],

      //search: "",
      showTooltip: false,
      showBotones: null,
      //loading: true,
      cantItems: 15,

      headers_component: [
                {
                  text: '',
                  value: 'Star',
                  align: 'center',

                },
                {
                  text: 'Marca',
                  value: 'MarcaPlan',
                  align: 'center',

                },
                
                
                {
                  text: 'Grupo',
                  value: 'Grupo',
                  align: 'center',

                  width: '1%'
                },
                {
                  text: 'Orden',
                  value: 'Orden',
                  align: 'center',

                  width: '1%'
                },
                
                {
                  text: 'Apellido y Nombre',
                  value: 'FullName',
                  align: 'left',

                },

                {
                  text: 'Telefono',
                  value: 'Telefono',
                  align: 'center',

                },
                
                {
                  text: 'Cuotas Lead',
                  value: 'CantidadCuotas',
                  align: 'center',
                  width: '2%'
                },

                {
                  text: 'Estado Lead',
                  value: 'EstadoPlan',
                  align: 'center',
                  width: '2%',

                },
               
                {
                  text: 'Estado',
                  value: 'CodEstado',
                  align: 'center',
      
                },
               
        
                {
                  text: 'Fecha Lead',
                  value: 'FechaLead',
                  align: 'center',
                  filterable: true,
                  filter: this.dateFilter,
                },

                {
                  text: 'Fecha Ult Obs',
                  value: 'FechaUltObs',
                  align: 'center',

                },
                
                { text: '', value: 'VerDato', align: 'center', width: '1%' },
              ],

    };
  },

  created() {
    //this.getData();
   // this.getDatos();
  },
  
  mounted() {
   // 
  },

  computed: {
    ...mapState("gestiondatosweb", [
      "items_filtered",
      "showItemsFiltered",
      "loadingDatos",
      "askData",
    ]),

    ...mapState("auth", [
      "login",
      "user",
      "esConcesionario",
      "esVinculo",
      "codigoConcesionario",
    ]),

    getToday() {
      return moment().format("DD/MM/YYYY");
    },

    getMin() {
      return "2021-03-11";
    },


    exportable() {
      if (typeof this.pars.exportable !== "undefined") {
        return this.pars.exportable;
      } else {
        return true;
      }
    },
  },

  methods: {

    showModalWeb() {
      this.dialogAlta = true;
    },


    getTooltipData() {
      return "Cambi?? el Reconocimiento";
      this.showToolTip = true;
    },

    stateFilter(value) {
      if (!this.stateFilterValue || this.stateFilterValue == 0) {
        return true;
      }

      if (this.stateFilterValue === "null") {
        return value === null;
      }

      return value === this.stateFilterValue;
    },

    avanceFilter(value) {
      if (!this.sliderAvance) {
        return true;
      }
      return parseInt(value) >= this.sliderAvance;
    },

    dateFilter(value) {

      let fecha = moment(value).format('YYYY-MM-DD');

      if ((!this.fecha_desde) && (!this.fecha_hasta)) {
        return true;
      }

      if ((this.fecha_desde) && (!this.fecha_hasta)) {
        return fecha >= this.fecha_desde;
      }

      if ((!this.fecha_desde) && (this.fecha_hasta)) {
        return fecha <= this.fecha_hasta;
      }

      if ((this.fecha_desde) && (this.fecha_hasta)) {
        return (fecha >= this.fecha_desde) && (fecha <= this.fecha_hasta);
      }

    },

    exportExcel: function () {
      let data = XLSX.utils.json_to_sheet(this.items);
      const workbook = XLSX.utils.book_new();
      const filename = "datos_web";
      XLSX.utils.book_append_sheet(workbook, data, filename);
      XLSX.writeFile(workbook, `${filename}.xlsx`);
    },

    getDatos() {
      let pars = {};
      if (this.grid == "Pendientes"){
         pars = {DatoVerificado: 0};
      }else{
         pars = {DatoVerificado: 1};
      }
      
      this.getData(pars);
    },

    setClass(item) {

      if (item.EsDatoNuevo == "1") {
        return "classDatoNuevo"; //NARANJA
      } else {
        if (
          (item.CodEstado == "0" || item.CodEstado == null) &&
          parseInt(item.AvanceAutomatico) >= 75 &&
          parseInt(item.AvanceAutomatico) <= 83
        ) {
          if (parseInt(item.AvanceAutomatico) == 83) {
            return "classDatoLimite"; //VERDE OSCURO
          } else {
            return "classDatoEntreLimites"; //VERDE CLARO
          }
        } else {
          return "";
        }
      }
    },


    

    getDato(item) {

      this.$router.push({
        name: "detalledatoweb",
        params: {
          id: item.ID,
          modulo: "gestiondatosweb",
        },
      });
    },

    formatFecha(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM/YYYY");
      }
    },

    getTextEstado(estado) {
      if (estado != null) {
        switch(estado){
          case "1":
            return "En Gesti??n";
          case "2":
            return "Avance Bajo";
          case "3":
            return "Cuotas Insuficientes";
          case "4":
            return "Llamar Mas Adelante";
          case "5":
            return "Pasar A Asignaci??n";
          case "6":
            return "Deje Mensaje";
          case "7":
            return "Descartados";  
          default:
            return estado;
        }
      } else {
        return "Sin Gestionar";
      }
    },

    ...mapActions({
      mostrarDato: "gestiondatosweb/mostrarDato",
      getData: "gestiondatosweb/getData",
      setData: "gestiondatosweb/setData",
      filterData: "gestiondatosweb/filterData",
    }),
  },


};
</script>
<style scoped>

.fullw {
  width: 100%;
}

.v-data-table >>> th {
  font-size: 14px !important;
  padding-left: 2px !important;
  padding-right: 2px !important;
  text-align: center;
}

.v-data-table >>> td {
  font-size: 12px !important;
  white-space: nowrap;
  padding-left: 2px !important;
  padding-right: 2px !important;
}

.v-data-table >>> .classDatoNuevo {
  background: #f3be69;
}

.v-data-table >>> .classDatoLimite {
  background: #57b479;
}

.v-data-table >>> .classDatoEntreLimites {
  background: #bae5ca;
}

.classDatoNuevo {
  background: #f3be69;
}

.classDatoLimite {
  background: #57b479;
}

.classDatoEntreLimites {
  background: #bae5ca;
}

.padded {
  padding-left: 10px;
  padding-right: 10px;
}
</style>
