<template>
  <v-app class="fullw">
    <v-card>
      <v-card-title>
        {{ pars.titleform }} - {{ item.Apellido }}, {{ item.Nombres }}
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
      </v-card-title>
      <v-container>
        <v-form ref="form" v-model="validForm">
          <v-row>
            <v-col cols="5" md="5">
              <v-container>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      :readonly="disabled"
                      class="resaltarDisabled"
                      label="Grupo"
                      placeholder="Grupo"
                      :filled="filled"
                      v-model="item.Grupo"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      :readonly="disabled"
                      class="resaltarDisabled"
                      label="Orden"
                      placeholder="Orden"
                      :filled="filled"
                      v-model="item.Orden"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" md="12">
                    <v-text-field
                      dense
                      label="Solicitud"
                      :readonly="disabled"
                      class="resaltarDisabled"
                      placeholder="Solicitud"
                      :filled="filled"
                      v-model="item.Solicitud"
                    ></v-text-field>
                  </v-col>
                </v-row>

                <v-divider class="mx-1" inset horizontal></v-divider>

                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Haber Neto"
                      placeholder="Haber Neto"
                      :readonly="disabled"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="valorHaberNetoFormat"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Plan"
                      placeholder="Plan"
                      :readonly="disabled"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="item.Plan"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" md="6">
                    <v-text-field
                      dense
                      label="Avance"
                      placeholder="Avance"
                      :readonly="disabled"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="item.Avance"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field
                      dense
                      label="Avance Calculado"
                      placeholder="Avance Calculado"
                      :readonly="disabled"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="item.AvanceCalculado"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" md="6">
                    <v-text-field
                      dense
                      label="Cuotas Pagas"
                      placeholder="Cuotas Pagas"
                      :readonly="disabled"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="item.CPG"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field
                      dense
                      label="Cuotas Adelantadas"
                      placeholder="Cuotas Adelantadas"
                      :readonly="disabled"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="item.CAD"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-col>
            <v-col cols="7" md="7">
              <v-container>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Apellido"
                      placeholder="Apellido"
                      :readonly="disabled"
                      class="resaltarDisabled"
                      :filled="filled"
                      v-model="item.Apellido"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Nombres"
                      placeholder="Nombres"
                      :readonly="disabled"
                      class="resaltarDisabled"
                      :filled="filled"
                      v-model="item.Nombres"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      :readonly="disabled"
                      class="resaltarDisabled"
                      label="Documento"
                      placeholder="Documento"
                      :filled="filled"
                      v-model="item.NroDoc"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Email 1"
                      placeholder="Email 1"
                      v-model="item.Email1"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
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
                    <v-text-field
                      dense
                      class="fillable"
                      label="Teléfono 1"
                      placeholder="Teléfono 1"
                      v-model="item.Telefono1"
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
                      label="Teléfono 3"
                      placeholder="Teléfono 3"
                      v-model="item.Telefono3"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Teléfono 4"
                      placeholder="Teléfono 4"
                      v-model="item.Telefono4"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Oficial"
                      placeholder="Oficial"
                      :readonly="disabled"
                      :filled="filled"
                      v-model="item.NomOficial"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <template v-if="userCanChangeVenta">
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
                    </template>
                    <template v-else>
                      <template v-if="ocultarDatePicker">
                        <v-select
                          dense
                          class="fillable"
                          :items="estados"
                          item-text="Nombre"
                          item-value="Codigo"
                          label="Estado"
                          :disabled="checkDisabledEstado"
                          :value="codEstado"
                          @input="setEstado"
                          @change="changeEstado"
                        ></v-select>
                      </template>
                      <template v-else>
                        <v-select
                          dense
                          class="fillable"
                          :items="estados"
                          item-text="Nombre"
                          item-value="Codigo"
                          label="Estado"
                          :disabled="checkDisabledEstado"
                          :value="codEstado"
                          @input="setEstado"
                          @change="changeEstado"
                        ></v-select>
                      </template>
                    </template>
                  </v-col>
                  <!--
                  <v-col cols="6" md="2">
                    <v-checkbox :label="`Vendido`" dense></v-checkbox>
                  </v-col>
                  -->
                </v-row>
                <v-row>
                  
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Precio Máximo de Compra"
                      placeholder="Precio Máximo de Compra"
                      :readonly="disabled"
                      class="resaltarDisabled"
                      :filled="filled"
                      v-model="valorPrecioMaxCompraFormat"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-select
                      dense
                      class="fillable"
                      :items="motivos"
                      item-text="Nombre"
                      item-value="Codigo"
                      label="Motivo"
                      :disabled="checkMotivo"
                      :value="codMotivo"
                      @input="setMotivo"
                      :rules="ruleNoLeInteresa"
                      :required="validarRuleNoLeInteresa"
                    ></v-select>
                  </v-col>
                </v-row>

                <v-row>  
                  <v-col cols="6" md="6">
                    <template v-if="userCanChangeVenta">
                      <v-text-field
                        dense
                        class="fillable"
                        label="Fecha Compra"
                        placeholder="Fecha Compra"
                        :filled="filled"
                        v-model="item.FechaCompra"
                        @change="setVentaCaida()"
                        :rules="ruleVendePlan"
                        :required="validarRuleVendePlan"
                      ></v-text-field>
                    </template>
                    <template v-else>
                      <template v-if="ocultarDatePicker">
                        <v-text-field
                          dense
                          class="fillable"
                          label="Fecha Compra"
                          placeholder="Fecha Compra"
                          disabled 
                          :filled="filled"
                          v-model="item.FechaCompra"
                          :rules="ruleVendePlan"
                          :required="validarRuleVendePlan"
                        ></v-text-field>
                      </template>
                      <template v-else>
                        <v-text-field
                          type="date"
                          dense
                          class="fillable"
                          :filled="filled"
                          :min="getMin"
                          :max="getToday"
                          label="Fecha Compra"
                          placeholder="Fecha Compra"
                          v-model="item.FechaCompra"
                          :disabled="checkEstado"
                          :rules="ruleVendePlan"
                          :required="validarRuleVendePlan"
                        ></v-text-field>
                      </template>
                    </template>

                  </v-col>

                  <v-col cols="6" md="6">
                    <template v-if="userCanChangeVenta">
                      <v-text-field
                        dense
                        type="number"
                        class="fillable"
                        label="Precio Compra"
                        placeholder="Precio Compra"
                        :filled="filled"
                        v-model="item.PrecioCompra"
                        :rules="ruleVendePlan"
                        :required="validarRuleVendePlan"
                      ></v-text-field>
                    </template>
                    <template v-else>
                      <template v-if="ocultarDatePicker">
                        <v-text-field
                          dense
                          type="number"
                          class="fillable"
                          label="Precio Compra"
                          placeholder="Precio Compra"
                          :disabled="true"
                          :filled="filled"
                          v-model="item.PrecioCompra"
                          :rules="ruleVendePlan"
                          :required="validarRuleVendePlan"
                        ></v-text-field>
                      </template>
                      <template v-else>
                        <v-text-field
                          dense
                          type="number"
                          class="fillable"
                          label="Precio Compra"
                          placeholder="Precio Compra"
                          :disabled="checkEstado"
                          :filled="filled"
                          v-model="item.PrecioCompra"
                          :rules="ruleVendePlan"
                          :required="validarRuleVendePlan"
                        ></v-text-field>
                      </template>
                    </template>
                  </v-col>
                </v-row>
                <v-row>
                  <template v-if="esCaida">
                    <v-col cols="6" md="6">
                      <v-select
                        dense
                        class="fillable"
                        :items="motivosCaida"
                        item-text="Nombre"
                        item-value="Codigo"
                        label="Motivo Caida"
                        :disabled="checkMotivoCaida"
                        :value="codMotivoCaida"
                        @input="setMotivoCaida"
                        :rules="ruleVentaCaida"
                        :required="validarRuleVentaCaida" 
                      ></v-select>
                    </v-col>
                    <v-col cols="6" md="6">
                      <v-text-field
                          type="date"
                          dense
                          class="fillable"
                          :filled="filled"
                          :min="getMin"
                          :max="getToday"
                          label="Fecha Caida"
                          placeholder="Fecha Caida"
                          v-model="item.FechaCaida"
                          :disabled="checkMotivoCaida"
                          :rules="ruleVentaCaida"
                          :required="validarRuleVentaCaida"
                        ></v-text-field>
                    </v-col>
                  </template>
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
          <template v-if="item.Automatica == 1 && showAutObs">
            <tr v-show="showAutObs" :class="typeObs(item.CambioReconocimiento)">
              <td>{{ item.login }}</td>
              <td>{{ timestamp(item.Fecha) }}</td>
              <td>{{ item.Obs }}</td>
              <td>
                <v-badge color="green" content="AUT" v-if="item.Automatica == 1"></v-badge>
              </td>
            </tr>
          </template>
          <template v-else>
            <tr v-show="item.Automatica != 1">
              <td>{{ item.login }}</td>
              <td>{{ timestamp(item.Fecha) }}</td>
              <td>{{ item.Obs }}</td>
              <td></td>
            </tr>
          </template>
        </template>
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Observaciones</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-switch v-model="showAutObs" label="Incluir Observaciones Automáticas" class="mt-2"></v-switch>
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
  name: "detalledato",

  props: ["id", "Marca", "Concesionario", "modulo"],

  data() {
    return {
      date: new Date().toISOString().substr(0, 10),
      codEstadoOriginal: null,
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
        titleform: "Detalle Dato",
      },
      ocultarDatePicker: false,
      disableFechaCompra: false,
      fechaCompraFormated: "",
      showBotones: null,
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

    this.checkRulesEstado(this.item.CodEstado);
    this.mostrarFechaCompraGrabada();
    this.mostrarFechaCaidaGrabada();
    this.codEstadoOriginal = this.item.CodEstado;
    console.log(this.codEstadoOriginal);
    //this.setEstado();
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
  },

  mounted() {
   
    this.checkEsConcesionario();
    this.checkEsVinculo();
  },

  methods: {
    ...mapActions({
      updateDato: "gestiondatos/updateDato",
      newObs: "gestiondatos/newObs",
      saveDato: "gestiondatos/saveDato",
    }),

    setVentaCaida() {
      this.requiereObsCaida = true;
      this.esCaida = true;
      //this.dialog = true;
    },

    async submit() {
      this.disabledAceptar = true;

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
        marca: this.Marca,
        concesionario: this.Concesionario,
        Obs: this.observacion,
        Automatica: this.obsAut,
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

      this.checkRulesEstado(value);
    },


    checkRulesEstado(codEstado) {
      console.log(codEstado);
      this.validarRuleNoLeInteresa = false;
      this.validarRuleVendePlan = false;
      this.validarRuleVentaCaida = false;

      switch (parseInt(codEstado)) {
        case 4:
          console.log("Es No le interesa");
          this.validarRuleNoLeInteresa = true;
          break;
        case 5:
          console.log("Es Vende Plan");
          //this.getFechaCompra();

          this.validarRuleVendePlan = true;
          break;
        case 9:
          console.log("Es Venta Caida");
          this.validarRuleVentaCaida = true;
          this.esCaida = true;
          break;
        default:
          console.log("Paso por el default");
          break;
      }
    },

    setMotivo(value) {
      console.log(value);
      if (this.item.CodEstado == 4) {
        this.item.Motivo = value;
      }
    },

    setMotivoCaida(value) {
      console.log(value);
      if (this.item.CodEstado == 9) {
        this.item.MotivoCaida = value;
      }
    },

    setFecha(value) {
      console.log(value);
      if (this.item.CodEstado == 5) {
        this.item.FechaCompra = value;
      }
    },

    setFechaCaida(value) {
      console.log(value);
      if (this.item.CodEstado == 9) {
        this.item.FechaCaida = value;
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
      /*
      console.log('FechaCompra');
      console.log(this.item.FechaCompra);
      if (this.codEstado == 5 && this.item.FechaCompra != null) {
        this.ocultarDatePicker = true;
        this.disabledCboEstado = true;
      } else {
        this.ocultarDatePicker = false;
        this.disabledCboEstado = false;
      }
      if (this.user.HN_PuedeCambiarVendePlan == 1) {
        this.userCanChangeVenta = true;
      }else{
        this.userCanChangeVenta = false;
      }
      console.log('PuedeCambiarVenta:');
      console.log(this.userCanChangeVenta);

      console.log('Cbo Estado Disabled:');
      console.log(this.disabledCboEstado);
      */
    },

    mostrarFechaCaidaGrabada() {
      if (this.codEstado == 9 && this.item.FechaCaida != null) {
        this.ocultarDatePicker = true;
        this.disabledCboEstado = true;
      } else {
        this.ocultarDatePicker = false;
        this.disabledCboEstado = false;
      }
      if (this.user.HN_PuedeCambiarVendePlan == 1) {
        this.userCanChangeVenta = true;
      }else{
        this.userCanChangeVenta = false;
      }
    },

    changeEstado(value) {

      switch(value){
        case 5: // VendePlan
          this.ocultarDatePicker = false;
          this.esCaida = false;
          if (this.codEstadoOriginal != 5){
            if (this.item.FechaCompra != null){ //Si tenia Fecha de Compra la pongo en blanco
              this.item.FechaCompra = null;
            }
          }else{
            this.ocultarDatePicker = true;
          }
          
          
        break;
        case 9: //Venta Caida
          this.esCaida = true;
          this.ocultarDatePicker = false;
          this.disableFechaCompra = false;
        break;
        default:
          console.log('Entro por el default');
          this.esCaida = false;
          this.ocultarDatePicker = true;
          this.disableFechaCompra = true;
        break;

      }

      console.log('Ocultar DatePicker');
      console.log(this.ocultarDatePicker);
      /*
      if (value == 5) {
        this.ocultarDatePicker = false;
      } else {
        this.ocultarDatePicker = true;
        this.disableFechaCompra = true;
      }
      */
    },
  },

  computed: {
    api() {
      return "gestiondatos";
      //return this.pars.routeapi;
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

    codMotivo() {
      return parseInt(this.item.Motivo);
    },

    codMotivoCaida(){
      return parseInt(this.item.MotivoCaida);
    },
    

    checkEstado() {
      if (this.item.CodEstado != 5) {
        return true;
      }
    },

    checkEstadoFechaPrecio() {
      if (this.item.CodEstado != 5) {
        return true;
      }
    },

    checkMotivo() {
      if (this.item.CodEstado != 4) {
        return true;
      }
    },

    checkOcultarDatePicker(){
      if (this.codEstado != 5){
        this.ocultarDatePicker = false;
      }
    },

    checkDisabledEstado() {
      let disabled = false;
      console.log('EstadoOriginal:');
      console.log(this.codEstadoOriginal);
      console.log('FechaCompra:');
      console.log(this.item.FechaCompra);
      console.log('PrecioCompra:');
      console.log(this.item.PrecioCompra);
      if (this.codEstadoOriginal == 5 && this.item.FechaCompra != null && this.item.PrecioCompra > 0) {
        
        this.ocultarDatePicker = true;
        this.disabledCboEstado = true;
        if (this.user.HN_PuedeCambiarVendePlan == 1) {
          disabled = false;
        }else{
          disabled = true;
        }
        
      } else {
        this.ocultarDatePicker = false;
        this.disabledCboEstado = false;
        disabled = false;
      }

     
      if (this.user.HN_PuedeCambiarVendePlan == 1) {
        this.userCanChangeVenta = true;
      }else{
        this.userCanChangeVenta = false;
      }
      console.log('PuedeCambiarVenta:');
      console.log(this.userCanChangeVenta);

      console.log('Cbo Estado Disabled:');
      console.log(this.disabledCboEstado);

      return disabled;
    },

    checkMotivoCaida() {
      if (this.item.CodEstado != 9) {
        return true;
      }
    },
    

    ruleVendePlan() {
      if (this.validarRuleVendePlan) {
        return [(v) => !!v || "Campo Requerido para Vende Plan."];
      }
    },

    ruleNoLeInteresa() {
      if (this.validarRuleNoLeInteresa) {
        if (this.item.Motivo == null) {
          return [(v) => !!v || "Campo Requerido para No Le Interesa."];
        }
      }
    },

    ruleVentaCaida() {
      if (this.validarRuleVentaCaida) {
        if (this.item.MotivoCaida == null) {
          return [(v) => !!v || "Campo Requerido para Venta Caida."];
        }
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
      /*
      if (typeof this.item.PrecioMaximoCompra !== "undefined") {
        if (this.item.PrecioMaximoCompra !== null) {
          return (
            "$" + this.$options.filters.numFormat(this.item.PrecioMaximoCompra)
          );
        } else {
          return this.$options.filters.numFormat(
            this.getPrecioMaximoCompra(
              parseInt(this.item.Avance),
              parseInt(this.item.HaberNeto)
            ),
            "$0,0"
          );
        }
      } else {
        return this.$options.filters.numFormat(
          this.getPrecioMaximoCompra(
            parseInt(this.item.Avance),
            parseInt(this.item.HaberNeto)
          ),
          "$0,0"
        );
        */
    },

    module() {
      return "gestiondatos";
      //return this.pars.module;
    },
    ...mapState("auth", [
      "login",
      "user",
      "esConcesionario",
      "esVinculo",
      "codigoConcesionario",
    ]),

    ...mapState("gestiondatos", [
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
      "motivosCaida"
    ]),
  },
};
</script>

<style scoped>
.v-container {
  margin: 0 auto;
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
</style>
