<template>
  <div>
    <v-data-table
      fixed-header
      height="60vh"
      dense
      :items="datos_items"
      :headers="headers"
      :item-class="style"
      :key="datos_items.Id"
      class="elevation-1"
      :loading="loadingDatos"
      loading-text="Cargando Datos... Aguarde"
      no-data-text="No hay datos disponibles."
      :items-per-page="-1"
      :hide-default-footer="true"
    >
      <template v-slot:item.Operacion.Empresa="{ item }">
        
        {{
          getTextEmpresaPorCE(
            item.Operacion.Concesionario,
            item.Operacion.Empresa
          )
        }}
      </template>
      <template v-slot:item.ComproGiama="{ item }">{{
        getTextTitularHN(item.TitularHN, item.Operacion.Concesionario)
      }}</template>
      <template v-slot:item.Titular="{ item }">{{
        getTextTipoTitular(item.Titular)
      }}</template>

      <template v-slot:item.FechaCompra="{ item }">{{
        formatFecha(item.FechaCompra)
      }}</template>
      <template v-slot:item.Rescindido="{ item }">
        <v-simple-checkbox
          v-model="item.Rescindido == 1"
          disabled
        ></v-simple-checkbox>
      </template>
      <template v-slot:item.Operacion.TipoPlan="{ item }">{{
        getTextTipoPlan(item.Operacion.TipoPlan)
      }}</template>
      <template v-slot:item.TipoCompra="{ item }">{{
        getTextTipoCompra(item.TipoCompra)
      }}</template>

     

      <template v-slot:item.MontoCompra="{ item }">{{
        item.MontoCompra | numFormat("$0,0")
      }}</template>

      <template v-slot:item.MontoCompraDolares="{ item }"
        >USD {{ item.MontoCompraDolares | numFormat("0,0") }}</template
      >
      <template v-slot:item.HaberNetoSubite="{ item }">{{
        item.HaberNetoSubite | numFormat("$0,0")
      }}</template>

      <template v-slot:item.MontoCobroReal="{ item }">{{
        item.MontoCobroReal | numFormat("$0,0")
      }}</template>
      <template v-slot:item.MontoCobroDolares="{ item }"
        >USD {{ item.MontoCobroDolares | numFormat("0,0") }}</template
      >
      <template v-slot:item.DifCobro="{ item }">{{
        (item.HaberNetoSubite - item.MontoCobroReal) | numFormat("$0,0")
      }}</template>
      <template v-slot:item.HaberNetoSubiteUSD="{ item }"
        >USD {{ item.HaberNetoSubiteUSD | numFormat("0,0") }}</template
      >
      <template v-slot:item.UtilidadActual="{ item }"
        >{{ item.UtilidadActual | numFormat }} %</template
      >

      <template v-slot:item.TIRActual="{ item }"
        >{{ item.TIRActual | numFormat }} %</template
      >

      <template v-slot:item.TIR="{ item }"
        >{{ item.TIR | numFormat }} %</template
      >
      <template v-slot:item.Duration="{ item }">
        {{ showDuration(item.Operacion.Marca, item.Duration) }}
      </template>

      <template v-slot:item.Utilidad="{ item }"
        >{{ item.Utilidad | numFormat }} %</template
      >
      <template v-slot:item.UtilidadUSD="{ item }"
        >{{ item.UtilidadUSD | numFormat }} %</template
      >

      <template v-slot:item.DurationActual="{ item }">{{
        showDuration(item.Operacion.Marca, item.DurationActual)
      }}</template>
      <template v-slot:item.DurationCompra="{ item }">
        {{ showDuration(item.Operacion.Marca, item.DurationCompra) }}
      </template>

      <template v-slot:item.Operacion.PrecioAutoActual="{ item }"
        >$ {{ item.Operacion.PrecioAutoActual | numFormat }}</template
      >

      <template v-slot:item.FechaCuota84="{ item }">{{
        formatFecha(item.FechaCuota84)
      }}</template>

      <template v-slot:item.FechaCobroReal="{ item }">{{
        formatFecha(item.FechaCobroReal)
      }}</template>

      <template v-slot:item.TIRSpot="{ item }">
        {{ showTIRSpot(item.TIRSpot) }}
      </template>
      <template v-slot:item.ConcesionarioPropio="{ item }">
        <v-simple-checkbox
          v-model="item.ConcesionarioPropio == 1"
          disabled
        ></v-simple-checkbox>
      </template>

      <template v-slot:item.Accion="{ item }">
          <template v-if="pars.grid == 'HNVigentes'">
            <v-btn x-small outlined color="primary" :disabled="!showButtons" @click="accionesVigentes(item)">
              <v-icon small left>mdi-currency-usd</v-icon>Cobrar
            </v-btn>
          </template>
          <template v-else>
            <v-btn x-small outlined color="primary" :disabled="!showButtons" @click="accionesCobrados(item)">
              <v-icon small left>mdi-currency-usd</v-icon>+ Cobros
            </v-btn>
          </template>
      </template>
    
    </v-data-table>
<!--
    <v-data-table
      dense
      :items="datosTotales"
      :headers="headers"
      :item-class="styletotales"
      class="elevation-1"
      :loading="loadingDatos"
      loading-text="Calculando Totales... Aguarde"
      no-data-text="No hay datos disponibles."
      :items-per-page="-1"
      :hide-default-footer="true"
      :hide-default-header="true"
    >
 </v-data-table>
-->
    <v-dialog v-model="dialog" max-width="650px">
      <v-card>
        <v-progress-linear
          v-if="loadingCobro"
          indeterminate
          color="primary darken-1"
        ></v-progress-linear>
        <v-card-title
          class="headline grey lighten-2"
          justify="center"
          primary-title
          >Cobro Haber Neto</v-card-title
        >
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6" md="4">
                <v-text-field
                  v-model="editedItem.Grupo"
                  label="Grupo"
                  disabled
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field
                  v-model="editedItem.Orden"
                  label="Orden"
                  disabled
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field
                  v-model="editedItem.Solicitud"
                  label="Solicitud"
                  disabled
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" sm="6" md="4">
                <v-text-field
                  v-model="editedItem.HaberNetoSubite"
                  label="Monto Cobro Estimado"
                  disabled
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field
                  v-model="editedItem.Importe"
                  type="number"
                  label="Importe"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field
                  v-model="editedItem.Fecha"
                  type="date"
                  :min="getMin"
                  :max="getToday"
                  label="Fecha"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions v-if="!loadingCobro">
          <v-btn
            small
            outlined
            color="success"
            :disabled="disabledSave"
            @click="saveCobro"
          >
            <v-icon left>mdi-content-save-outline</v-icon>Aceptar
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn small outlined color="error" @click="close">
            <v-icon left>mdi-close-circle</v-icon>Cancelar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

     <v-dialog v-model="dialogcobros" max-width="650px">
      <v-card>
        <v-progress-linear
          v-if="loadingNuevoCobro"
          indeterminate
          color="primary darken-1"
        ></v-progress-linear>
        <v-card-title
          class="headline grey lighten-2"
          justify="center"
          primary-title
          >Agregar Cobros</v-card-title
        >
        <v-card-text>
          <v-container>
            <v-row>

              <v-col cols="12" sm="6" md="6">
                <v-text-field
                  v-model="editedItemNuevoCobro.MontoCobrado"
                  type="number"
                  label="Monto Cobrado"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6">
                <v-text-field
                  v-model="editedItemNuevoCobro.FechaCobrado"
                  type="date"
                  :min="getMin"
                  :max="getToday"
                  label="Fecha"
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" sm="12" md="12">
                  <v-data-table fixed-header
                    height="250px"
                    dense
                    :items="itemCobrosHN"
                    :headers="headersCobros"
                    class="elevation-1"
                    :loading="loadingGridCobrosHN"
                    loading-text="Cargando Datos... Aguarde"
                    no-data-text="No hay datos disponibles."
                    :items-per-page="-1"
                    :hide-default-footer="true">
              
                <template v-slot:top >
                   <v-row justify="center">Historial de Cobros</v-row>
                </template>

                 <template v-slot:item.FechaCobrado="{ item }">
                    {{ formatFecha(item.FechaCobrado) }}
                 </template>
                  <template v-slot:item.MontoCobrado="{ item }">
                      {{ item.MontoCobrado | numFormat('$0,0') }}
                 </template>
                  <template v-slot:item.PrimerCobro="{ item }">
                      <template v-if="item.PrimerCobro == 1">
                        <v-badge color="green" content="Primer Cobro" inline tile></v-badge>
                      </template>
                 </template>
                  </v-data-table>
              </v-col>
            </v-row>

          </v-container>
        </v-card-text>

        <v-card-actions v-if="!loadingNuevoCobro">
          <v-btn
            small
            outlined
            color="success"
            :disabled="disabledSaveNuevo"
            @click="saveNuevoCobro"
          >
            <v-icon left>mdi-content-save-outline</v-icon>Aceptar
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn small outlined color="error" @click="closeNuevoCobro">
            <v-icon left>mdi-close-circle</v-icon>Cancelar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import moment from "moment";
import { mapState, mapActions } from "vuex";

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
    datos_items: {
      type: Array,
      required: true,
    },
    loadingDatos: {
      type: Boolean,
      required: true,
    },
    showButtons:{
      type: Boolean,
      required: true,
    },
    filtro_Titular: {
      type: Object,
      required: true,
    }
  },

  data() {
    return {
      dialog: false,
      dialogcobros: false,
      loading: false,
      editedIndex: -1,

      editedItem: {
        Index: "",
        ID: "",
        ID_Dato: "",
        Marca: "",
        Concesionario: "",
        Grupo: "",
        Orden: "",
        Solicitud: "",
        HaberNetoSubite: "",
        Importe: "",
        Fecha: "",
      },

       editedItemNuevoCobro: {
        Index: "",
        ID: "",
        ID_Dato: "",
        Marca: "",
        Concesionario: "",
        MontoCobrado: "",
        FechaCobrado: "",
      },

      headersCobros:[
        { text: "Fecha Cobro", value: "FechaCobrado", align: "center" },
        { text: "Monto Cobro", value: "MontoCobrado", align: "end" },
        { text: "Usuario Alta", value: "UsuarioAltaRegistro", align: "center" },
        { text: "", value: "PrimerCobro", align: "center" }
      ]
    };
  },

  watch: {
    dialog(val) {
      val || this.close();
    },

   

  },

  computed: {
    
    ...mapState("haberneto", [
      "loadingCobro",
      "dataStatusMsgCobro",
      "dataStatusCobro",
      "loadingGridCobrosHN"
      
    ]),

     ...mapState("auth", [
      "login",
      "user",
      "esConcesionario",
      "esVinculo",
      "codigoConcesionario",
    ]),
    
    /*
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
      "loadingCobro",
      "dataStatusMsgCobro",
      "dataStatusCobro",
      "loadingGridCobrosHN"
    ]),
    */
    ...mapState("habernetocobrado", [
      "loadingNuevoCobro", 
      "dataStatusNuevoCobro",
      "dataStatusMsgNuevoCobro",
      "loadingGridCobrosHN",
      "itemCobrosHN"
    ]),


    datosTotales(){
      return [
        {
           
            MontoCompra: this.totalMontoCompra(),
            MontoCompraDolares: this.totalMontoCompraDolares(),
            HaberNetoSubite: this.totalHaberNetoSubite(),
            HaberNetoSubiteUSD: this.totalHaberNetoSubiteUSD(),
           
          },
      ]
    },
   

    totalMontoCompraComputed(){
        return this.totalMontoCompra();
    },
    
    totalMontoCompraDolaresComputed(){
        return this.totalMontoCompraDolares();
    },

    totalHaberNetoSubiteComputed(){
        return this.totalHaberNetoSubite();
    },

    totalHaberNetoSubiteUSDCopmuted(){
        return this.totalHaberNetoSubiteUSD();
    },
         
          
    cotizaciones() {
      return this.pars.cotizaciones;
    },

    getToday() {
      return moment().format("YYYY-MM-DD");
    },

    getMin() {
      return "2020-01-01";
    },

    disabledSaveNuevo(){
      if (moment(this.editedItemNuevoCobro.FechaCobrado).isValid()) {
        return this.editedItemNuevoCobro.MontoCobrado == null;
      } else {
        return true;
      }
    },

    disabledSave() {
      if (moment(this.editedItem.Fecha).isValid()) {
        return this.editedItem.Importe == null;
      } else {
        return true;
      }
    },
  },

  methods: {
    ...mapActions({
      cobroHaberNeto: "haberneto/cobroHaberNeto",
      getHNCobrados: "haberneto/getHNCobrados",
    }),

    ...mapActions({
      nuevoCobroHaberNeto: "habernetocobrado/nuevoCobroHaberNeto",
      getGridCobros: "habernetocobrado/getGridCobros"
    }),

    style(item) {
      if (item.FechaCobroReal == null) {
        var fCuota84 = moment(item.FechaCuota84);
        var today = moment();

        if (
          fCuota84 < today ||
          (today.year() == fCuota84.year() &&
            (today.month() == fCuota84.month() ||
              today.month() - fCuota84.month() == 1))
        ) {
          return "vencidos";
        }
      }
    },

    styletotales(item){
//
    },

    totalMontoCompra(){

      let totalMC = [];

      Object.entries(this.datos_items).forEach(([key, val]) => {
          totalMC.push(parseInt(val.MontoCompra)) // the value of the current key.
      });


      return "$ " + this.$options.filters.numFormat(totalMC.reduce(function(totalMC, num){ return totalMC + num }, 0));
    },

    totalMontoCompraDolares(){
      let totalMCD = [];

      Object.entries(this.datos_items).forEach(([key, val]) => {
          totalMCD.push(parseInt(val.MontoCompraDolares)) // the value of the current key.
      });

      return "USD " + this.$options.filters.numFormat(totalMCD.reduce(function(totalMCD, num){ return totalMCD + num }, 0));
    },

    totalHaberNetoSubite(){
      let totalHNS = [];

      Object.entries(this.datos_items).forEach(([key, val]) => {
          totalHNS.push(parseInt(val.HaberNetoSubite)) // the value of the current key.
      });

      return "$ " + this.$options.filters.numFormat(totalHNS.reduce(function(totalHNS, num){ return totalHNS + num }, 0));
    },
  
    totalHaberNetoSubiteUSD(){
      let totalHNSD = [];

      Object.entries(this.datos_items).forEach(([key, val]) => {
          totalHNSD.push(parseInt(val.HaberNetoSubiteUSD)) // the value of the current key.
      });

      return "USD " + this.$options.filters.numFormat(totalHNSD.reduce(function(totalHNSD, num){ return totalHNSD + num }, 0));
    },

    editItem(item) {
      console.log(item);
      this.editedIndex = this.datos_items.indexOf(item);
      this.editedItem = Object.assign({}, item);
      //console.log();
      this.dialog = true;
    },

    close() {
      this.dialog = false;
    },

    closeNuevoCobro() {
      this.dialogcobros = false;
    },

    async saveNuevoCobro(){
        var pars = {
        Index: this.editedItem.Index,
        Marca: this.editedItem.Marca,
        Concesionario: this.editedItem.Concesionario,
        ID_HN: this.editedItem.ID,
        ID_Dato: this.editedItem.ID_Dato,
        MontoCobrado: this.editedItemNuevoCobro.MontoCobrado,
        FechaCobrado: moment(this.editedItemNuevoCobro.FechaCobrado).format("YYYY-MM-DD"),
        UsuarioAlta: this.login
      };

      console.log(pars);

      await this.nuevoCobroHaberNeto(pars);
      if (this.dataStatusNuevoCobro == "success") {
           this.getHistorialCobros(this.editedItem.Marca, this.editedItem.Concesionario, this.editedItem.ID);
      }
      await this.showSwal();
      this.close();
      //this.refreshHNCobrados();
    },

    getHistorialCobros(marca, concesionario, id_hn){

      let pars = {
        Marca: marca,
        Concesionario: concesionario,
        ID_HN: id_hn
      };

      this.getGridCobros(pars);

    },

    async saveCobro() {
      console.log(this.editedItem)
      var pars = {
        Index: this.editedItem.Index,
        Marca: this.editedItem.Marca,
        Concesionario: this.editedItem.Concesionario,
        ID: this.editedItem.ID,
        ID_Dato: this.editedItem.ID_Dato,
        MontoCobroReal: this.editedItem.Importe,
        FechaCobroReal: moment(this.editedItem.Fecha).format("YYYY-MM-DD"),
      };

      await this.cobroHaberNeto(pars);
      if (this.dataStatusCobro == "success") {
        this.datos_items.splice(this.editedItem.Index, 1);
      }
      await this.showSwal();
      this.close();
      //this.refreshHNCobrados();
    },

    refreshHNCobrados() {
      var today = moment();
      var pars = {
        Marca: this.editedItem.Marca,
        Concesionario: this.editedItem.Concesionario,
        Empresa: 0,
        Anio: today.year(),
        Filtros: "",
      };
      this.getHNCobrados(pars);
      this.clearCobro();
    },

    showSwal() {
      //this.$swal("Good job!", dataStatusMsg, dataStatus);
      this.$swal(this.dataStatusMsgCobro, "", this.dataStatusCobro);
    },

    clearCobro() {
      var itemClear = {
        Index: null,
        ID: null,
        ID_Dato: null,
        Grupo: null,
        Orden: null,
        Marca: null,
        Concesionario: null,
        Solicitud: null,
        HaberNetoSubite: null,
        Importe: null,
        Fecha: null,
      };

      this.editedItem = itemClear;
    },

   async accionesCobrados(item){
        this.editedIndex = this.datos_items.indexOf(item);
        console.log(item);
        var editIt = {
          Index: this.editedIndex,
          ID: item.Id,
          ID_Dato: item.ID_Dato,
          Marca: item.Operacion.Marca,
          Concesionario: item.Operacion.Concesionario,
          Importe: null,
          Fecha: null,
        };

        console.log(editIt);
        this.editedItem = editIt;
        console.log(this.editedItem)

        await this.getHistorialCobros(item.Operacion.Marca, item.Operacion.Concesionario, item.Id);
        this.editedItem = editIt;
        console.log(this.editedItem);
        this.editedItemNuevoCobro.MontoCobrado = ''; 
        this.editedItemNuevoCobro.FechaCobrado = '';
        this.dialogcobros = true;
        //console.log(item);
    },

    accionesVigentes(item) {
      this.editedIndex = this.datos_items.indexOf(item);
      var montoEstimadoFormat = 0;
      montoEstimadoFormat = this.$options.filters.numFormat(
        item.HaberNetoSubite,
        "$0,0"
      );
      var editIt = {
        Index: this.editedIndex,
        ID: item.Id,
        ID_Dato: item.ID_Dato,
        Marca: item.Operacion.Marca,
        Concesionario: item.Operacion.Concesionario,
        Grupo: item.Operacion.Grupo,
        Orden: item.Operacion.Orden,
        Solicitud: item.Operacion.NroSolicitud,
        HaberNetoSubite: montoEstimadoFormat,
        Importe: null,
        Fecha: null,
      };
      this.editedItem = editIt;
      //console.log();
      this.dialog = true;
      //console.log(item);
    },

    formatFecha(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM/YYYY");
      }
    },

    showTIRSpot(tirspot) {
      if (tirspot > 0) {
        return this.$options.filters.numFormat(tirspot, "0,0") + "%";
      } else {
        return "-";
      }
    },

    showDuration(marca, valor) {
      var duration = valor;
/*
      if (marca == 2) {
        duration = valor / 365;
      }
*/
      if (duration > 0) {
        return this.$options.filters.numFormat(duration, "0.0");
      } else {
        return "-";
      }
    },

    setDuration(valor) {
      if (valor > 0) {
        return this.$options.filters.numFormat(valor, "0.0");
      } else {
        return "-";
      }
    },

    getTextEmpresaPorCE(concesionario, valor) {
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
          break;
        case "13":
          return "FIAT-Web";
        case "14":
          return "JEEP-Web";
        case "15":
          return "VW-Web";
        case "16":
          return "FORD-Web";
        case "17":
          return "CITROEN-Web";
        case "18":
          return "ALRA";
        case "19":
          return "AUTOTAG";
        case "20":
          return "MAYNAR";
        case "21":
          return "SEBASTIANI";
        case "22":
          return "YACOPINI";
        case "23":
          return "DETROIT";
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
          case "13":
          return "FIAT-Web";
        case "14":
          return "JEEP-Web";
        case "15":
          return "VW-Web";
        case "16":
          return "FORD-Web";
        case "17":
          return "CITROEN-Web";
        case "18":
          return "ALRA";
        case "19":
          return "AUTOTAG";
        case "20":
          return "MAYNAR";
        case "21":
          return "SEBASTIANI";
        case "22":
          return "YACOPINI";
        case "23":
          return "DETROIT";
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

    getTextTitularHN(valor, codConcesionario) {
      switch (valor) {
        case "0":
          switch (codConcesionario) {
            case "1":
              return "Sauma";
              break;
            case "2":
              return "Iruña";
              break;
            case "3":
              return "Amendola";
              break;
            case "7":
              return "Luxcar";
              break;
            case "9":
              return "Sapac";
              break;
            case "10":
              return "Alizze";
              break;
            default:
              return "Conces.";
          }
          break;
        case "1":
          return "RB";
        case "2":
          return "GB";
          break;
        default:
          return "";
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
        case "4":
          return "80/20";
          break;
        case "5":
          return "50%";
          break;
        default:
          return valor;
          break;
      }
    },

    getTextTitular(valor){
      switch(valor){
        case 0:
          return "CE";
        case 1:
          return "RB";
        case 2:
          return "GB";
        default:
          return "";
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
        default:
          return valor;
          break;
      }
    },

    getValorCompraUSD(montoCompra, fechaCompra) {
      var fecha = moment(fechaCompra).format("YYYY-MM-DD");

      var arrCotiz = this.cotizaciones;
      //console.log(arrCotiz);
      var cotizUSD = this.getValorCotizAFecha(arrCotiz, fecha);
      var cCompra = 0;
      var cVenta = 0;

      if (typeof cotizUSD !== "undefined") {
        cCompra = cotizUSD.CotizacionCompra;
        cVenta = cotizUSD.CotizacionVenta;
      }

      //console.log(cCompra);
      //console.log(cVenta);
      var valorUSD = (parseInt(cCompra) + parseInt(cVenta)) / 2;
      //console.log(valorUSD);

      if (valorUSD == 0) {
        return 0;
      }
      return montoCompra / valorUSD;
    },

    getValorCotizAFecha(arrUSD, fecha) {
      var cotizfec = null;
      var fechaResta = null;
      //console.log(fecha);
      for (var i = 0; i < 5; i++) {
        fechaResta = moment(fecha).subtract(i, "days").format("YYYY-MM-DD");
        //console.log(fechaResta);
        //console.log(i);
        cotizfec = arrUSD.find((item) => item.Fecha === fechaResta);
        if (typeof cotizfec !== "undefined") {
          return cotizfec;
        }
      }
    },

    getUtilidad(montoCompra, montoCobro, fechaCompra) {
      var division = montoCobro / montoCompra;
      return (division - 1) * 100;
    },

    getDuration() {
      return "";
    },
/*
    getTIR(mCompra, mCobro, fComp, fVtoCuota) {
      var duration = this.getDurationCompra(fComp, fVtoCuota);
      var utilidad = this.getUtilidad(mCompra, mCobro, fComp);

      return utilidad / duration;
    },

    getFechaCuota84(fechaVto, formated) {
      var fVtoMoment = moment(fechaVto);
      var fCuota84 = moment(fVtoMoment).add(82, "M");
      //var futureMonthEnd = moment(futureMonth).endOf("month");
      if (formated == 1) {
        return fCuota84.format("DD/MM/YYYY");
      } else {
        return fCuota84;
      }
    },

    getDurationCompra(fechaCompra, fechaVtoCuota) {
      var fecC84 = this.getFechaCuota84(fechaVtoCuota, 0);

      var endDate = moment(fecC84, "YYYY-MM-DD");
      var startDate = moment(fechaCompra, "YYYY-MM-DD");
      var valor = endDate.diff(startDate, "days");
      //console.log(endDate);
      //console.log(startDate);
      // console.log(valor);
      //var duration = valor / 365;
      
      return duration.toFixed(1);
    },
    */
  },
};
</script>

<style scoped>
.maxHeight {
  height: 400px;
  max-height: 400px;
}

.v-data-table >>> .vencidos {
  color: red;
}

.v-data-table >>> td {
  font-size: 12px !important;
  height: 10px !important;
  white-space: nowrap;
  vertical-align: middle;
}

.v-data-table >>> th {
  font-size: 12px !important;
  height: 10px !important;
  padding-right: 4px !important;
}

.fixedCol {
  position: -webkit-sticky;
  position: absolute;
  right: 0;
}
</style>
