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
            <v-col cols="6" md="6">
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
            <v-col cols="6" md="6">
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
                  <v-col cols="4" md="4">
                    <v-text-field
                      dense
                      class="importantDisabled"
                      label="Grupo"
                      placeholder="Grupo"
                      :filled="filled"
                      v-model="item.Grupo"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="4" md="4">
                    <v-text-field
                      dense
                      class="importantDisabled"
                      label="Orden"
                      placeholder="Orden"
                      :filled="filled"
                      v-model="item.Orden"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="4" md="4">
                    <v-text-field
                      dense
 
                      class="importantDisabled"
                      label="Avance"
                      placeholder="Avance"
                      :filled="filled"
                      v-model="item.Avance"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Haber Neto"
                      placeholder="Haber Neto"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="item.HaberNeto"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Plan"
                      placeholder="Plan"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="item.Plan"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="6" md="6">
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
                  <v-col cols="6" md="6">
                     <v-text-field
                      dense
                      suffix="%"
                      v-mask="'##.##'"
                      label="Porcentaje"
                      placeholder="Porcentaje"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="item.PorcentajeValorHN"
                    ></v-text-field>
                  </v-col>
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
    //this.setEstado();
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
  },

  mounted() {
  //  this.checkEsConcesionario();
    this.checkEsVinculo();
  },

  methods: {
    ...mapActions({
      updateDato: "gestiondatosweb/updateDato",
      newObs: "gestiondatosweb/newObs",
      saveDato: "gestiondatosweb/saveDato",
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
    },  

    changeEstado(value) {

      switch(value){
        case 5: // Pasar A Asignacion
          //
        break;
        case 4: //Llamar Mas Adelante
          //
        break;
        default:
          //
        break;

      }

    },
  },

  computed: {

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
      "motivosCaida"
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
</style>
