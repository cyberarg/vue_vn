<template>
  <v-app class="fullw">
    <v-card>
      <v-card-title>
        {{ pars.titleform }} - {{ item.FullName }}
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
      </v-card-title>
      <v-container>
        <v-form ref="form" v-model="validForm">
          <v-row>
            <v-col cols="6" md="6" sm="12" xs="12">
              <v-container>
                <v-row>
                  <v-col cols="12" md="12">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Nombre y Apellido"
                      placeholder="Nombre y Apellido"
                      v-model="item.FullName"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" md="12">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Teléfono"
                      placeholder="Teléfono"
                      v-model="item.Telefono"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" md="12">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Email"
                      placeholder="Email"
                      v-model="item.Email"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row> 
                  <v-col cols="12" md="12" class="margintop">
                    <v-divider class="mx-1" horizontal></v-divider>
                  </v-col>
                </v-row>

                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Marca Plan"
                      placeholder="Marca Plan"
                      :readonly="disabled"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="item.MarcaPlan"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Modelo Plan"
                      placeholder="Modelo Plan"
                      :readonly="disabled"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="item.ModeloAhorro"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Cantidad Cuotas"
                      placeholder="Cantidad Cuotas"
                      :readonly="disabled"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="item.CantidadCuotas"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Estado Plan"
                      placeholder="Estado Plan"
                      :readonly="disabled"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="item.EstadoPlan"
                    ></v-text-field>
                  </v-col>
                </v-row>
                
                
              </v-container>
            </v-col>
            <v-col cols="6" md="6" sm="12" xs="12">
              <v-container>
                <v-row>
                  <v-col cols="8" md="8">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Nombre y Apellido"
                      placeholder="Nombre y Apellido"
                      v-model="item.FullName"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="4" md="4">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Documento"
                      placeholder="Documento"
                      v-model="item.NroDoc"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Teléfono"
                      placeholder="Teléfono"
                      v-model="item.Telefono"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Teléfono 2"
                      placeholder="Teléfono 2"
                      v-model="item.Telefono2"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Email"
                      placeholder="Email"
                      v-model="item.Email"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Email 2"
                      placeholder="Email 2"
                      v-model="item.Email2"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row> 
                  <v-col cols="12" md="12">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Domicilio"
                      placeholder="Domicilio"
                      v-model="item.Domicilio"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-select
                        dense
                        class="fillable"
                        :items="listMarcas"
                        item-text="Nombre"
                        item-value="Codigo"
                        label="Marca"
                        :value="codMarca"
                        @input="setMarca"
                        @change="changeMarca"
                      ></v-select>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Plan"
                      placeholder="Plan"
                      :readonly="disabled"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="item.ModeloAhorro"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Cuotas Pagas"
                      placeholder="Cuotas Pagas"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="item.CPG"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Cuotas Adelantadas"
                      placeholder="Cuotas Adelantadas"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="item.CAD"
                    ></v-text-field>
                  </v-col>
                </v-row>
                
                <v-row>
                  <v-col cols="3" md="3">
                    <v-text-field
                      dense
                      class="importantDisabled"
                      label="Grupo"
                      placeholder="Grupo"
                      :filled="filled"
                      v-model="item.Grupo"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="3" md="3">
                    <v-text-field
                      dense
                      class="importantDisabled"
                      label="Orden"
                      placeholder="Orden"
                      :filled="filled"
                      v-model="item.Orden"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="4" md="4" class="check_activo">
                    <v-checkbox
                      v-model="item.PlanActivo"
                      false-value="0"
                      true-value="1"
                      label="Plan Activo"
                     
                    ></v-checkbox>
                  </v-col>
                  
                </v-row>
                <v-row>
                   <v-col cols="4" md="4" sm="12" xs="12" class="d-flex align-items-start check_activo px-3">
                    <v-btn

                      small
                      :loading="loadingSearch"
                      :disabled="loadingSearch"
                      color="secondary"
                      @click="getDatosPlan"
                    >
                      Datos del Grupo
                      <v-icon right>mdi-magnify</v-icon>
                    </v-btn>
                  </v-col>
                  <v-col cols="8" md="8" sm="12" xs="12" class="check_activo px-3">
                    <v-text-field
                        dense
                        :color="this.color_datos_grupo"
                        :loading="this.loading_datos_grupo"
                        :value="this.detail_datos_grupo"
                        readonly
                    ></v-text-field>
                  </v-col>
                </v-row>

                <v-row> 
                  <v-col cols="12" md="12" class="margintopdivider">
                    <v-divider class="mx-1" horizontal></v-divider>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="2" md="2">
                    <v-text-field
                      dense
                      class="importantDisabled"
                      label="Avance"
                      placeholder="Avance"
                      :filled="filled"
                      v-model="item.Avance"
                    ></v-text-field>
                  </v-col>
                  
                  <v-col cols="8" md="8">
                    <template v-if="this.exactMatch == null" >
                      <v-text-field
                        dense
                        label="Modelo"
                        placeholder="Modelo"
                        class="importantDisabled"
                        :filled="filled"
                        v-model="item.Modelo"
                      >
                      </v-text-field>
                    </template>
                    <template v-else-if="this.exactMatch == 1">
                        <v-text-field
                        dense
                        label="Modelo"
                        placeholder="Modelo"
                        class="importantDisabled"
                        :filled="filled"
                        v-model="item.Modelo"
                      >
                      
                        <template v-slot:append>  
                          <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                              <v-icon :color="colorIconMatchGrupo"
                              v-bind="attrs"
                              v-on="on">
                              {{ iconMatchGrupo }}
                              </v-icon> 
                            </template>
                            <span>{{ toolTipBusqueda }}</span>
                          </v-tooltip>
                        </template>
                      </v-text-field>
                    </template>
                    <template v-else>
                      <v-select
                        dense
                        class="fillable"
                        :items="modelos_marca"
                        item-text="Nombre"
                        item-value="Codigo"
                        label="Modelo"
                        :value="ObjModelo"
                        @input="setModelo"
                        @change="changeModelo"
                      ></v-select>
                    </template>
                  </v-col>
                  <v-col cols="2" md="2">
                    <template v-if="this.exactMatch == null" >
                      <v-text-field
                        dense
                        label="Plan Real"
                        placeholder="Plan Real"
                        class="importantDisabled"
                        :filled="filled"
                        v-model="item.Plan"
                      ></v-text-field>
                    </template>
                    <template v-else-if="this.exactMatch == 1">
                        <v-text-field
                        dense
                        label="Plan Real"
                        placeholder="Plan Real"
                        class="importantDisabled"
                        :filled="filled"
                        v-model="item.Plan"
                      >
                    </v-text-field>
                    </template>
                    <template v-else>
                      <v-combobox
                        dense
                        class="fillable"
                        :items="planes_modelo"
                        item-text="Plan"
                        item-value="Plan"
                        label="Plan Real"
                        :value="ObjPlan"
                        @input="setPlan"
                        @change="changePlan"
              
                      ></v-combobox>
                    </template>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="3" md="3">
                     <v-text-field
                      dense
                      suffix="%"
                      v-mask="'##.##'"
                      :label="this.labelPorcentaje"
                      :placeholder="this.labelPorcentaje"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="item.PorcentajeValorHN"
                      :disabled="this.codMarca != 2 && this.codMarca != 7 && this.codMarca != 3"
                    ></v-text-field>
                  </v-col>
                   <v-col cols="1" md="1">
                     <v-btn
                      icon
                      :disabled="((this.codMarca != 2 && this.codMarca != 7 && this.codMarca != 3) || item.PorcentajeValorHN == '' || loadingHN)"
                      @click="calculateHaberNeto"
                      :loading="loadingHN"
   
                      >
                        <v-icon>mdi-calculator</v-icon>
                    </v-btn>
                  </v-col>
                  <v-col cols="4" md="4">
                    <v-text-field
                      dense
                      prefix="$"
                      type="number"
                      label="Haber Neto"
                      placeholder="Haber Neto"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="item.HaberNeto"
                    ></v-text-field>
                  </v-col>
                   <v-col cols="4" md="4">
                     <v-select
                        dense
                        class="fillable"
                        :items="estados"
                        item-text="Nombre"
                        item-value="Codigo"
                        label="Estado"
                        :value="codEstado"
                        @input="setEstado"
                        @change="changeEstado"
                      ></v-select>
                  </v-col>
    
                </v-row>    
                <v-row>
                  <v-col class="d-flex justify-end check_verif">
                    <v-checkbox
                      :disabled="disableChKVerificado"
                      v-model="item.DatoVerificado"
                      false-value="0"
                      true-value="1"
                      label="Dato Verificado"
                      class="check_verif"
                      @change="check_AsignarA($event)"
                    ></v-checkbox>
                  </v-col>
                  <template v-if="item.DatoVerificado == 1">
                    <v-col class="d-flex justify-end check_verif">
                      <v-select
                        dense
                        :disabled="this.disableSelectOficial"
                        class="fillable"
                        :items="items_dw"
                        item-text="Nombre"
                        item-value="Codigo"
                        label="Asignar A"
                        :value="codOficial"
                        @input="setOficial"
                        @change="changeOficial"
                  
                      ></v-select>
                    </v-col>
                  </template>
                </v-row> 

                <v-row>
                 
                </v-row>            
              </v-container>
            </v-col>
        
          </v-row>
        </v-form>
      </v-container>
      <template>
        <v-dialog v-model="dialog" v-show="dialog" max-width="650px">
          <v-card>
            <v-progress-linear v-if="loading" indeterminate color="primary darken-1"></v-progress-linear>
            <v-card-title>
              <span class="headline">Nueva Observación</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12" sm="12" md="12">
                    <v-textarea
                      name="text-obs"
                      label="Ingrese una nueva observación"
                      v-model="observacion"
                    ></v-textarea>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-btn cclass="ma-2 primary" @click="saveObs">
                <v-icon left>mdi-content-save-outline</v-icon>Guardar
              </v-btn>
              <v-spacer></v-spacer>
              <v-btn cclass="ma-2 danger" @click="close">
                <v-icon left>mdi-cancel</v-icon>Cancelar
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </template>

      <v-data-table
        dense
        :headers="headersobs"
        :items="observaciones"
        item-key="pars.itemkey"
        class="elevation-1"
        :loading="loadingObs"
        loading-text="Cargando Observaciones... Aguarde"
        no-data-text="No hay observaciones ingresadas."
      >
        <template v-slot:item="{ item }">
            <tr>
              <td>{{ item.login }}</td>
              <td>{{ timestamp(item.Fecha) }}</td>
              <td>{{ item.Obs }}</td>
            </tr>
        </template>
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Observaciones</v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>
        </template>
      </v-data-table>
      <v-card-actions v-show="showBotones">
        <v-spacer></v-spacer>
        <v-btn class="ma-2 primary" @click="nuevaObs">
          <v-icon left>mdi-comment-plus-outline</v-icon>Nueva
        </v-btn>
      </v-card-actions>

      <v-card-actions>
        <v-btn class="ma-2 primary" :disabled="!validForm" @click="submit" v-show="showBotones">
          <v-icon left>mdi-content-save-outline</v-icon>Aceptar
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn class="ma-2 primary" @click="volver">
          <v-icon left>mdi-arrow-left</v-icon>Volver
        </v-btn>
      </v-card-actions>
    </v-card>

    <v-dialog v-model="loadingDatos" persistent max-width="350px">
      <v-progress-linear indeterminate height="10" color="primary darken-1"></v-progress-linear>
    </v-dialog>

   
  </v-app>
</template>

<script>
import { mapState, mapActions } from "vuex";
import moment from "moment";

export default {
  name: "detalledatoweb",

  props: ["id", "Marca", "Concesionario", "modulo"],

  data() {
    return {
      obtuvoBusquedaGrupo: false,
      exactMatch: null,
      grupoTabla: null,
      valorVehiculo: 0,
      modelos_marca:[],
      modelos_planes:[],

      planes_modelo:[],
      codPlanSelected:null,

      toolTipBusqueda: "",
      labelPorcentaje: "Porcentaje",
      colorIconMatchGrupo:null,
      disableSelectOficial:true,

      loading_datos_grupo: false,
      color_datos_grupo: "primary",
      detail_datos_grupo: "",

      ObjModelo:{},
      ObjPlan:null,

      listMarcas: [
        { Codigo: 2, Nombre: "Fiat" },
        { Codigo: 5, Nombre: "Volkswagen" },
        { Codigo: 9, Nombre: "Ford" },
        { Codigo: 3, Nombre: "Peugeot" },
        { Codigo: 7, Nombre: "Jeep" },
        { Codigo: 10, Nombre: "Citroen" },
      ],

      date: new Date().toISOString().substr(0, 10),
      menu2: false,
      validForm: true,
      esCaida: false,
      validarRuleNoLeInteresa: false,
      validarRuleVentaCaida:false,
      validarRuleVendePlan: false,
      disabledAceptar: false,
      disabledCboEstado: false,
      estadoReact: null,
      motivoErrors: [],
      precioCompraErrors: [],
      fechaCompraErrors: [],
      showAutObs: true,
      color: "",
      mode: "",
      timeout: 2000,
      x: null,
      y: "top",
      observacion: "",
      obsAut: false,
      items: [],
      dialog: false,
      headersobs: [
        {
          text: "Usuario",
          align: "start",
          value: "login",
        },
        { text: "Fecha", value: "Fecha" },
        { text: "Observacion", value: "Obs" },
        { text: "", value: "Automatica" },
      ],

      disabled: true,
      filled: false,
      pars: {
        routeapi: "",
        id: 0,
        module: "",
        titleform: "Detalle Dato WEB",
      },
      ocultarDatePicker: false,
      disableFechaCompra: false,
      fechaCompraFormated: "",
      showBotones: true,
      userCanChangeVenta: false,
      requiereObsCaida: false,
    };
  },

  async created() {
    var params = {
      id: this.id,
      marca: this.Marca,
      concesionario: this.Concesionario,
    };
    console.log(params);
    await this.$store.dispatch(this.modulo + "/mostrarDato", params);

    //this.checkRulesEstado(this.item.CodEstado);
    this.mostrarFechaCompraGrabada();
    this.selectAsignarA_Behavior(this.item.CodEstado);
    //this.mostrarFechaCaidaGrabada();
    //this.setEstado();
    this.getOficialesDatoWeb()
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
  },

  mounted() {
  //  this.checkEsConcesionario();
    // this.checkEsVinculo();
  },

  methods: {
    ...mapActions({
      updateDato: "gestiondatosweb/updateDato",
      newObs: "gestiondatosweb/newObs",
      saveDato: "gestiondatosweb/saveDato",
      searchValuesByGroup: "gestiondatosweb/searchValuesByGroup",
      getOficialesDatoWeb: "oficiales/getOficialesDatoWeb",
      getHN_FCA: "gestiondatosweb/getHN_FCA",
      getHN_Web: "gestiondatosweb/getHaberNeto"
    }),

    setVentaCaida() {
      this.requiereObsCaida = true;
      this.esCaida = true;
      //this.dialog = true;
    },

    setValorVehiculo(value){
      console.log(value);
      //this.valorVehiculo = 
    },

    async calculateHaberNeto(){
      if (this.valorVehiculo > 0 && this.item.PorcentajeValorHN != ""){
        if (this.item.Marca == 2){
            let pars = {
              Marca: this.item.Marca,
              ValorAuto: this.valorVehiculo,
              Porcentaje: this.item.PorcentajeValorHN,
              TipoPlan: this.ObjModelo.TipoPlan,
              CantCuotas: this.ObjModelo.CantCuotas,
              CPG: this.item.CPG
            };
            await this.getHN_FCA(pars);
            this.item.HaberNeto = this.hn_FCA;
            
        }
        console.log(this.item.PorcentajeValorHN);
      }
        
    },

    check_AsignarA: function(e) {
		  if (this.item.DatoVerificado == 1){
        this.selectAsignarA_Behavior(this.item.CodEstado);
      }
    },

    async getDatosPlan(){

      this.loading_datos_grupo = true;
      this.detail_datos_grupo = "";
      this.color_datos_grupo = "primary";

      let pars = {
        Marca: this.item.Marca, 
        Grupo: this.item.Grupo,
        CPG: this.item.CPG,
        CAD: this.item.CAD,
        Porcentaje: this.item.PorcentajeValorHN,
      }
      console.log(pars);
      await this.searchValuesByGroup(pars);

      this.loading_datos_grupo = false;

      console.log(this.valores);
      this.item.Plan = this.valores.CodigoPlan;
      this.item.Modelo = this.valores.NombreModelo;
      this.item.CodigoModelo = this.valores.CodigoModelo;
      this.exactMatch = this.valores.ExactMatch;
      this.grupoTabla = this.valores.GrupoTabla;
      this.item.HaberNeto = this.valores.HaberNeto;
      this.item.Avance = this.valores.AvanceCalculado;
      this.obtuvoBusquedaGrupo=true;
      
      if (this.item.Marca == 2){
        
        this.modelos_marca = this.valores.ModeloMarca;
        this.modelos_planes = [];
        this.item.Modelo = null;
        this.item.CodigoModelo = null;

        if (this.exactMatch == 0){
          this.color_datos_grupo = "error";
          this.detail_datos_grupo = "Coincidencia Parcial - Grupo Testigo: "+ this.valores.GrupoTabla;
        }else{
          this.color_datos_grupo = "success";
          this.detail_datos_grupo = "Coincidencia Exacta";
        }  
        this.exactMatch = 0; // Lo dejo en 0 para que se muestre el combo del Modelo
      }else{
        if (this.exactMatch == 0){
          this.modelos_marca = this.valores.ModeloMarca;
          this.modelos_planes = this.valores.ModeloPlanes;
          this.item.Modelo = null;
          this.item.Plan = null;
          this.item.CodigoModelo = null;
          this.color_datos_grupo = "error";
          this.detail_datos_grupo = "Coincidencia Parcial - Grupo Testigo: "+ this.valores.GrupoTabla;
        }else{
          this.color_datos_grupo = "success";
          this.detail_datos_grupo = "Coincidencia Exacta";
        }
      }

      

      
    },

    async submit() {
      this.disabledAceptar = true;
      if (this.ObjModelo != {}){
        this.item.CodigoModelo = this.ObjModelo.Codigo;
        this.item.Modelo = this.ObjModelo.Nombre;
      }

      if (!this.$refs.form.validate()) {
        this.disabledAceptar = false;
        return;
      }

      console.log(this.item);
      
      await this.saveDato(this.item);
      await this.showSwal();
      
      this.volver();
    },

    saveObs() {
      this.newObs({
        id: this.id,
        Obs: this.observacion,
        login: this.login,
      });
      this.dialog = false;
    },

    volver() {
      this.$refs.form.resetValidation();
      this.$router.go(-1);
      this.ocultarDatePicker = false;
    },
    nuevaObs() {
      this.dialog = true;
    },
    close() {
      this.dialog = false;
    },
    timestamp(fecha) {
      return moment(fecha).format("DD/MM/YYYY");
    },
    typeObs(cambioRecon) {
      if (cambioRecon == 1) {
        return "cambioReconocimiento";
      }
    },
    showSwal() {
      //this.$swal("Good job!", dataStatusMsg, dataStatus);
      this.$swal(this.dataStatusMsg, "", this.dataStatus);
    },

    setEstado(value) {
      this.item.CodEstado = value;
    },

    setModelo(value) {
      console.log(value);
      this.item.CodigoModelo = value;
      //this.codModelo = value;
    },

    setPlan(value){
      console.log('Plan Selected');
      console.log(value);
      this.ObjPlan = value;
    },

    setOficial(value) {
      this.item.CodOficial = value;
    },


    setMarca(value){
      this.item.Marca = value;
    },

    setFecha(value) {
      console.log(value);
      if (this.item.CodEstado == 5) {
        this.item.FechaCompra = value;
      }
    },


    getFechaCompra() {
      console.log(moment(this.item.FechaCompra).format("DD/MM/YYYY"));
      this.fechaCompraFormated = moment(this.item.FechaCompra).format(
        "DD/MM/YYYY"
      );
    },

    getFechaCaida() {
      console.log(moment(this.item.FechaCaida).format("DD/MM/YYYY"));
      this.fechaCaidaFormated = moment(this.item.FechaCaida).format(
        "DD/MM/YYYY"
      );
    },

    getPrecioMaximoCompra(avance, haberNeto) {
      console.log(avance);
      console.log(haberNeto);
      if (45 <= avance && avance <= 61) {
        return haberNeto * 0.2;
      }

      if (62 <= avance && avance <= 66) {
        return haberNeto * 0.3;
      }

      if (67 <= avance && avance <= 69) {
        return haberNeto * 0.35;
      }
      if (70 <= avance && avance <= 79) {
        return haberNeto * 0.4;
      }

      if (80 <= avance && avance <= 83) {
        return haberNeto * 0.5;
      }

      return 0;
    },

    checkEsConcesionario() {
      if (this.esConcesionario) {
        var codC = parseInt(this.codigoConcesionario);
        console.log(codC);
        var itemC = {};

        this.showBotones = false;
      } else {
        if (this.esVinculo) {
          this.showBotones = false;
        } else {
          this.showBotones = true;
        }
      }
    },

    mostrarFechaCompraGrabada() {
      console.log('FechaCompra');
      console.log(this.item.FechaCompra);
      if (this.codEstado == 5 && this.item.FechaCompra != null) {
        this.ocultarDatePicker = true;
        this.disabledCboEstado = true;
      } else {
        this.ocultarDatePicker = false;
        //return parseInt(this.item.CodOficial);
        this.disabledCboEstado = false;
      }
      if (this.user.HN_PuedeCambiarVendePlan == 1) {
        this.userCanChangeVenta = true;
      }else{
        this.userCanChangeVenta = false;
      }
      console.log('PuedeCambiarVenta');
      console.log(this.userCanChangeVenta);
    },

    changeMarca(value){
      console.log(value);
      if (this.codMarca == 2 || this.codMarca == 7){
        this.labelPorcentaje = 'Porcentaje';
      }
      if (this.codMarca == 3){
        this.labelPorcentaje = 'Ctas. Reconocidas';
      }
    },  

    async changePlan(value){
      this.item.HaberNeto = "";

      console.log(this.ObjModelo);
      //Si no es Fiat, Jeep ni Peugeot, debo hacer el llamado en el change
      if (this.codMarca != 2 && this.codMarca != 7 && this.codMarca != 3){
          let pars = {
            Marca: this.ObjModelo.Marca, 
            Modelo: this.ObjModelo.Codigo,
            Plan: this.ObjPlan.Plan,
            CPG: this.item.CPG,
            CAD: this.item.CAD,
            Avance: this.item.Avance,
            Porcentaje: this.item.PorcentajeValorHN,
          }
          console.log(pars);
          await this.getHN_Web(pars);
          this.item.HaberNeto = this.hn_Web;
      }

    },

    changeModelo(value){

     // this.item.HaberNeto = "";
      this.ObjModelo = this.modelos_marca.find(function (item) {
        return item.Codigo === value;
      });

      this.planes_modelo = [];
      if (typeof this.ObjModelo !== "undefined"){
        this.valorVehiculo = parseInt(this.ObjModelo.Precio);

        this.planes_modelo = this.modelos_planes.filter(function (item) {
          return item.CodigoModelo === value;
        });
      }

    },

    changeEstado(value) {

      this.selectAsignarA_Behavior(value);
      /*
      switch(value){
        case 5: // Pasar A Asignacion
          this.disableSelectOficial = false;
        break;
        case 4: //Llamar Mas Adelante
          this.disableSelectOficial = true;
        break;
        default:
          this.disableSelectOficial = true;
        break;

      }
     
      this.datosParaVerificarOk();
      */
    },

    selectAsignarA_Behavior(estado){
      if (this.item.DatoVerificado == 1 && estado == 5){
        this.disableSelectOficial = false;
      }else{
        this.disableSelectOficial = true;
      }
      this.datosParaVerificarOk();
    },

    changeOficial(value) {
      //
    },

     datosParaVerificarOk(){
        //console.log(this.item);
        return (this.item.Grupo != null && this.item.Orden != null &&  
        this.item.CPG != null &&
        this.item.Avance != null && this.item.HaberNeto != null && 
        this.item.CodEstado != null);
    },
  },

  computed: {

    iconMatchGrupo(){

      if (this.obtuvoBusquedaGrupo){
          if (this.exactMatch == '1'){
            this.toolTipBusqueda = "Coincidencia Exacta"
            this.colorIconMatchGrupo = "success"
            return "mdi-check-circle-outline"
          }
          this.toolTipBusqueda = "Concidencia Parcial, Grupo Comparación: " + this.grupoTabla
          this.colorIconMatchGrupo = "warning"
          return "mdi-alert-circle-outline"

      }
      return ""
    },

    disableChKVerificado(){
      //console.log(this.item.DatoVerificado);
      if (this.item.DatoVerificado == 1){
        //console.log('disableChKVerificado: True');
        return true;
      }
     // console.log('disableChKVerificado:'+ this.datosParaVerificarOk());
      return !(this.datosParaVerificarOk());

     
    },

    getToday() {
      return moment().format("DD/MM/YYYY");
    },

    getMin() {
      return moment().subtract(1, "months").format("DD/MM/YYYY");
    },

    fechaCompra() {
      return this.timestamp(this.item.FechaCompra);
    },

    codEstado() {
      return parseInt(this.item.CodEstado);
    },

    codModelo(){
      return parseInt(this.item.CodigoModelo);
    },

    codOficial() {
      return parseInt(this.item.CodOficial);
    },

    codMarca(){
      return parseInt(this.item.Marca);
    },

    checkEstado() {
      if (this.item.CodEstado != 5) {
        return true;
      }
    },

    valorHaberNetoFormat() {
      return "$" + this.$options.filters.numFormat(this.item.HaberNeto);
    },

    valorPrecioMaxCompraFormat() {
      return this.$options.filters.numFormat(
        this.getPrecioMaximoCompra(
          parseInt(this.item.Avance),
          parseInt(this.item.HaberNeto)
        ),
        "$0,0"
      );
    },

    ...mapState("auth", [
      "login",
      "user",
      "esConcesionario",
      "esVinculo",
      "codigoConcesionario",
    ]),

    ...mapState("oficiales", [
      "items_dw"
    ]),

    ...mapState("gestiondatosweb", [
      "item",
      "observaciones",
      "loading",
      "loadingDatos",
      "loadingObs",
      "dataStatus",
      "dataStatusMsg",
      "showMsg",
      "estados",
      "motivos",
      "motivosCaida",
      "valores",
      "loadingSearch",
      "hn_FCA",
      "hn_Web",
      "loadingHN"
    ]),
  },
};
</script>

<style scoped>
.v-container {
  margin: 0 0;
  max-width: 100%;
}

.fullw {
  width: 100%;
}

.v-row {
  height: 12px;
}

.v-select {
  height: 10px;
  font-size: 14px;
}

.check_activo {
  margin: 0;
  padding: 0;
}

.check_verif {
  margin-top: 0;
  padding-top: 0;
}

.v-checkbox {
  height: 10px;
  font-size: 8px;
  padding-bottom: 15px;
}

.v-checkbox label {
  height: 10px;
  font-size: 8px;
  padding-bottom: 15px;
}

.v-text-field {
  height: 10px;
  padding-bottom: 15px;
  font-size: 14px;
}

.cambioReconocimiento {
  background: #eb952d;
}

.fillable {
  font-weight: bold;
}

.resaltarDisabled {
  font-size: 16px;
  background: #fbff00;
  padding-bottom: 25px;
  font-weight: bold;
}

.importantDisabled {
  font-size: 16px;
  font-weight: bold;
}

.margintop {
  margin-top: 30px;
  margin-bottom: -12px;
}

.margintopdivider {
  margin-top: 5px;
}
</style>
