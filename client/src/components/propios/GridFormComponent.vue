<template>
  <div>
    <v-card color="grey lighten-4">
      <v-card-title v-if="this.show_title">
        {{ pars.titleform }}
        <v-divider class="mx-4" inset vertical></v-divider>

        <v-spacer></v-spacer>
        <template v-if="!esConcesionario">
          <v-row class="padded">
            <v-col cols="3">
              <v-combobox
                v-show="mostrarCombo"
                item-text="Nombre"
                item-value="Codigo"
                :items="listMarcas"
                label="Marca"
                v-model="codMarcaSelected"
                @change="filterListConcesionaria"
                class="padded"
              ></v-combobox>
            </v-col>
            <v-col cols="3">
              <v-combobox
                v-show="mostrarCombo"
                item-text="Nombre"
                item-value="Codigo"
                :items="listC"
                label="Concesionario"
                v-model="codConcesSelected"
                @change="filterConcesionaria"
              ></v-combobox>
            </v-col>
            <v-col cols="3">
              <v-text-field
                v-show="mostrarbuscar"
                v-model="search_box"
                append-icon="mdi-magnify"
                label="Buscar"
                single-line
                hide-details
              ></v-text-field>
            </v-col>
          </v-row>
        </template>
        <template v-else>
          <v-text-field
            v-show="mostrarbuscar"
            v-model="search_box"
            append-icon="mdi-magnify"
            label="Buscar"
            single-line
            hide-details
          ></v-text-field>
        </template>
        <v-btn class="ma-2" color="primary" outlined text @click="getDatos()">
          <v-icon left>mdi-refresh</v-icon>Actualizar
        </v-btn>
      </v-card-title>

      <v-data-table
        dense
        fixed-header
        height="58vh"
        :headers="computedHeaders"
        :items="items"
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
        <template v-slot:item.Grupo="{ item }"
          >{{ item.Grupo }}-{{ item.Orden }}</template
        >
        <template v-slot:item.Concesionario="{ item }">
          {{ getTextConc(item.Concesionario) }}
        </template>
        <template v-slot:item.HaberNeto="{ item }">
          {{ item.HaberNeto | numFormat("$0,0") }}
        </template>
        <template v-slot:item.ApeNom="{ item }">{{ item.ApeNom }}</template>
        <template v-slot:item.Avance="{ item }">{{ item.Avance }}</template>
        <template v-slot:item.AvanceCalculado="{ item }">{{ item.AvanceCalculado }}</template>
        <template v-slot:item.NomEstado="{ item }">
          {{ getTextEstado(item.NomEstado) }}
        </template>
        <template v-slot:item.Motivo="{ item }">
          {{ getTextMotivo(item.Motivo) }}
        </template>
        <template v-slot:item.FechaCompra="{ item }">
          {{ item.FechaCompra }}
        </template>
        <template v-slot:item.PrecioCompra="{ item }">
          {{ item.PrecioCompra | numFormat("$0,0") }}
        </template>
        <template v-slot:item.PrecioMaximoCompra="{ item }">
          {{ item.PrecioMaximoCompra | numFormat("$0,0") }}
        </template>
        <template v-slot:item.FechaUltimaAsignacion="{ item }">
          {{ formatFecha(item.FechaUltimaAsignacion) }}
        </template>
        <template v-slot:item.FechaUltObs="{ item }">
          {{ formatFecha(item.FechaUltObs) }}
        </template>

        <template v-slot:item.VerDatos="{ item }">
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
                        <v-select
                          :items="statesList"
                          v-model="stateFilterValue"
                          label="Estado"
                        ></v-select>
                      </v-row>
                    </v-col>
                    <v-col cols="3">
                      <v-row class="pa-10">
                        <v-slider
                          label="Avance"
                          v-model="sliderAvance"
                          thumb-label="always"
                          max="83"
                          :min="minByBrand"
                        ></v-slider>
                      </v-row>
                    </v-col>
                    <v-col cols="2">
                      <v-row class="pa-10">
                        <v-switch
                          label="Solo Circ. Fiat"
                          v-model="solodatocircular"
                          class="mt-0 pt-0"
                        ></v-switch>
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
        <v-spacer></v-spacer>
        <v-btn
          cclass="ma-2"
          color="success"
          outlined
          text
          @click="exportExcel"
          v-show="showBotones"
        >
          <v-icon left>mdi-file-excel-outline</v-icon>Excel
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>
<script>
import { mapState, mapActions } from "vuex";
import XLSX from "xlsx";
import moment from "moment";

export default {
  props: {
    pars: {
      type: Object,
      required: true,
    },
    headers: {
      type: Array,
      required: true,
    },
    show_title:{
      type:Boolean,
      required:false,
      default:true
    },
    from_leads:{
      type:Boolean,
      required:false,
      default:false
    },
    search_str:{
      type:String,
      required:false,
      default:""
    }
  },

  data() {
    return {
      // Filter models.
      oficialFilterValue: "",
      stateFilterValue: null,
      solodatocircular:false,
      sliderAvance: 45,
      minByBrand: 45,
      sliderHaberNeto: 15000,

      statesList: [
        { text: "Todos", value: 0 },
        { text: "Sin Gestionar", value: "null" },
        { text: "Telefono Mal", value: "Telefono Mal" },
        { text: "Deje Mensaje", value: "Deje Mensaje" },
        { text: "Entrevista Pendiente", value: "Entrevista Pendiente" },
        { text: "No le interesa", value: "No le interesa" },
        { text: "Vende Plan", value: "Vende el Plan" },
        { text: "Venta Caida", value: "Venta Caida" },	
        { text: "En Gesti??n", value: "En Gestion" },
        { text: "Descartados", value: "Descartados" },
      ],

      search_box: "",
      showTooltip: false,
      showBotones: null,
      //loading: true,
      cantItems: 15,
      codMarcaSelected: null,
      listMarcas: [
        { Codigo: 2, Nombre: "Fiat" },
        { Codigo: 5, Nombre: "Volkswagen" },
        { Codigo: 9, Nombre: "Ford" },
        { Codigo: 3, Nombre: "Peugeot" },
        { Codigo: 7, Nombre: "Jeep" },
        { Codigo: 10, Nombre: "Citroen" },

      ],
      codConcesSelected: null,
      listC: [],
      listConcesionarios: [
        { Codigo: 0, Nombre: "Todos" },
        { Codigo: 1, Nombre: "Sauma", Marca: 5 },
        { Codigo: 2, Nombre: "Iru??a", Marca: 5 },
        { Codigo: 3, Nombre: "Amendola", Marca: 5 },
        { Codigo: 7, Nombre: "Luxcar", Marca: 5 },
        { Codigo: 4, Nombre: "AutoCervo", Marca: 2 },
        { Codigo: 5, Nombre: "AutoNet", Marca: 2 },
        { Codigo: 6, Nombre: "Car Group", Marca: 2 },
        { Codigo: 9, Nombre: "Sapac", Marca: 9 },
        { Codigo: 10, Nombre: "Alizze", Marca: 3 },
        { Codigo: 12, Nombre: "Datos Web - Peugeot", Marca: 3 },
        { Codigo: 13, Nombre: "Datos Web - Fiat", Marca: 2 },
        { Codigo: 14, Nombre: "Datos Web - Jeep", Marca: 7 },
        { Codigo: 15, Nombre: "Datos Web - Volkswagen", Marca: 5 },
        { Codigo: 16, Nombre: "Datos Web - Ford", Marca: 9 },
        { Codigo: 17, Nombre: "Datos Web - Citroen", Marca: 10 },
        { Codigo: 23, Nombre: "Detroit", Marca: 7 },
      ],

      concesB: [
        { Codigo: 18, Nombre: "Alra", Marca: 5 },
        { Codigo: 19, Nombre: "Autotag", Marca: 5 },
        { Codigo: 20, Nombre: "Maynar", Marca: 5 },
        { Codigo: 21, Nombre: "Sebastiani", Marca: 5 },
        { Codigo: 22, Nombre: "Yacopini", Marca: 5 },
        { Codigo: 24, Nombre: "Pussetto", Marca: 5 },
      ],
    };
  },


  created() {
    //this.$store.dispatch(this.module + "/getData", this.api);
    if (this.from_leads){
      this.setMinimosFiltrosLeads()
    }
    
  },
  mounted() {
    this.checkEsConcesionario();
    this.checkCombosSelected();
    this.checkPerfilUsuario();
  },


  computed: {
    ...mapState("gestiondatos", [
      "items",
      "items_filtered",
      "showItemsFiltered",
      "loadingDatos",
      "askData",
      "codMarcaSelState",
      "codConcesSelState"
    ]),

    ...mapState("auth", [
      "login",
      "user",
      "puedeVerConcesionariosB",
      "esConcesionario",
      "esVinculo",
      "codigoConcesionario",
    ]),

    search(){
      if (this.search_str == ""){
        return this.search_box;
      }
      return this.search_str;
    },

    getMinByBrand(){
      if (this.codMarcaSelected == 3){
        return 1
      }else{
        return 45
      }
    },

    headers_2() {
      return [
        {
          text: "",
          value: "Star",
          align: "center",
          sorteable: true,
        },
        {
          text: "Grupo Orden",
          value: "Grupo",
          align: "center",
          sorteable: true,
          filterable: true,
        },

        {
          text: "Concesionario",
          value: "Concesionario",
          align: "center",
          filterable: false,
        },

        {
          text: "Haber Neto",
          value: "HaberNeto",
          align: "center",
          filterable: false,
        },
        {
          text: "Apellido y Nombre",
          value: "ApeNom",
          align: "left",
          sorteable: true,
          filterable: true,
        },
        /*
                {
                  text: 'Cuotas PG',
                  value: 'CPG',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Cutas AD',
                  value: 'CAD',
                  align: 'center',
                  width: '1%'
                },
                */
        {
          text: "Avance",
          value: "Avance",
          align: "center",
          filterable: true,
          filter: this.avanceFilter,
        },
        /*
        {
          text: "Avance Calculado",
          value: "AvanceCalculado",
          align: "center",
          filterable: false,

        },
        */
        {
          text: "Estado",
          value: "NomEstado",
          align: "left",
          filterable: true,
          filter: this.stateFilter,
        },        
        {
          text: "Motivo",
          value: "Motivo",
          align: "left",
          filterable: false,
        },
        {
          text: "Fecha Compra",
          value: "FechaCompra",
          align: "center",
          filterable: false,
        },
        {
          text: "Precio Compra",
          value: "PrecioCompra",
          align: "center",
          filterable: false,
        },
        {
          text: "Precio Max Compra",
          value: "PrecioMaximoCompra",
          align: "center",
          filterable: false,
        },
        {
          text: "Fecha Asignaci??n",
          value: "FechaUltimaAsignacion",
          align: "center",
          filterable: false,
        },
        {
          text: "Fecha Ult. Obs",
          value: "FechaUltObs",
          align: "center",
          filterable: false,
        },
        { text: "", value: "VerDatos", align: "center", width: "1%" },
        {
          text: "",
          value: "EsCircularFiat",
          align: "center",
          filterable: true,
          filter: this.filterSoloDatoCircularFiat,
          width:"0%",
          sortable: false
        },
      ];
    },

    computedHeaders() {
      return this.headers_2.filter((headers_2) => headers_2.ocultar !== true);
    },

    api() {
      return this.pars.routeapi;
    },
    module() {
      return this.pars.module;
    },

    mostrarCombo() {
      if (typeof this.pars.showCombo !== "undefined") {
        return this.pars.showCombo;
      } else {
        return false;
      }
    },

    mostrarbuscar() {
      if (typeof this.pars.mostrarbuscar !== "undefined") {
        return this.pars.mostrarbuscar;
      } else {
        return true;
      }
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

    checkPerfilUsuario(){
      if (parseInt(this.puedeVerConcesionariosB) == 1){
          Array.prototype.push.apply(this.listConcesionarios,this.concesB)
      }
    },

    setMarca(value){
      console.log(value);
      this.codMarcaSelected = value.Codigo;
    },

    setConcesionario(value){
      console.log(value);
      this.codConcesSelected = value.Codigo;
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


    filterSoloDatoCircularFiat(value){
      if (this.solodatocircular) {
        return value === 1;
      }
      return true;
    },

    avanceFilter(value) {
      if (!this.sliderAvance) {
        return true;
      }
      return parseInt(value) >= this.sliderAvance;
    },

    exportExcel: function () {
      let data = XLSX.utils.json_to_sheet(this.items);
      const workbook = XLSX.utils.book_new();
      const filename = "archivoexcel";
      XLSX.utils.book_append_sheet(workbook, data, filename);
      XLSX.writeFile(workbook, `${filename}.xlsx`);
    },

    setMinimosFiltrosLeads(){
      this.sliderAvance = 1;
      this.minByBrand = 1;
    },

    filterListConcesionaria(value) {
      console.log(value);
      this.codConcesSelected = null;
      this.listC = [];
      this.listC = this.listConcesionarios.filter(function (item) {
        return item.Marca === value.Codigo;
      });

      if (value.Codigo == 3){
        this.sliderAvance = 1;
        this.minByBrand = 1;
      }else{
        if (value.Codigo == 2 || value.Codigo == 7 ){
          this.sliderAvance = 30;
          this.minByBrand = 30;
        }else{
          this.sliderAvance = 45;
          this.minByBrand = 45;
        }
      }
      
    },

    setMinByBrand(marca){
      if (marca== 3){
        this.sliderAvance = 1;
        this.minByBrand = 1;
      }else{
        if (value.Codigo == 2 || value.Codigo == 7 ){
          this.sliderAvance = 30;
          this.minByBrand = 30;
        }else{
          this.sliderAvance = 45;
          this.minByBrand = 45;
        }
      }
    },

    filterConcesionaria(value) {
      console.log(value);
      if (value.Codigo == 0) {
      } //else {
        //this.filterData(value.Codigo);
      //}

    },

    checkCombosSelected(){
      if (typeof this.codMarcaSelState.Codigo !== "undefined"){
          this.codMarcaSelected = this.codMarcaSelState;
          this.codConcesSelected = this.codConcesSelState;
          this.setMinByBrand(this.codMarcaSelState.Codigo);
      }
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
        this.showBotones = false;
      } else {
        if (this.esVinculo) {
          this.listMarcas.splice(0, 1);
          this.showBotones = false;
        } else {
          this.showBotones = true;
        }
      }
    },

    getDatos() {
   
      var pars = {
        Marca: this.codConcesSelected.Marca,
        Concesionario: this.codConcesSelected.Codigo,
      };

      var params = {
        Marca: this.codMarcaSelected,
        Concesionario: this.codConcesSelected
      };
      
      this.setMarcaConcesionario(params);
      this.getData(pars);
    },

    setClass(item) {
      //console.log(item);

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

    getTextConc(conc) {
      switch (conc) {
        case "1":
          return "Sauma";
          break;
        case "2":
          return "Iru??a";
          break;
        case "3":
          return "Amendola";
          break;
        case "4":
          return "AutoCervo";
          break;
        case "5":
          return "AutoNet";
          break;
        case "6":
          return "Car Group";
          break;
        case "7":
          return "Luxcar";
          break;
        case "8":
          return "RB";
          break;
        case "9":
          return "Sapac";
          break;
        case "10":
          return "Alizze";
        break;
        case "12":
          return "Dato Web - Peugeot";
        break;
        case "13":
          return "Dato Web - Fiat";
        break;
         case "14":
          return "Dato Web - Jeep";
        break;
        case "15":
          return "Dato Web - Volkswagen";
        break;
        case "16":
          return "Dato Web - Ford";
        break;
        case "17":
          return "Dato Web - Citroen";
        break;
        default:
          return "";
          break;
      }
    },

    getTextEstado(estado) {
      if (estado != null) {
        return estado;
      } else {
        return "Sin Gestionar";
      }
    },

    getTextMotivo(codmotivo) {
      switch (codmotivo) {
        case "0":
          return "Espera el cobro";
          break;
        case "1":
          return "Conflicto";
          break;
        case "2":
          return "Llamar mas adelante";
          break;
        default:
          return "";
          break;
      }
    },

    getDato(item) {
      //console.log(item);
      //console.log(item.ID);
      //this.mostrarDato(item.ID);

      this.$router.push({
        name: "detalledato",
        params: {
          id: item.ID,
          Marca: item.Marca,
          Concesionario: item.Concesionario,
          modulo: "gestiondatos",
        },
      });
    },

    formatFecha(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM/YYYY");
      }
    },

    ...mapActions({
      mostrarDato: "gestiondatos/mostrarDato",
      getData: "gestiondatos/getData",
      setData: "gestiondatos/setData",
      setLoader: "gestiondatos/setLoader",
      filterData: "gestiondatos/filterData",
      setMarcaConcesionario: "gestiondatos/setMarcaConcesionario",
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
