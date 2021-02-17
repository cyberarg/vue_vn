<template>
  <div>
    <v-card color="grey lighten-4" ma-0>
      <v-card-title
        class="headline grey lighten-2"
        justify="center"
        primary-title
      >
        Carga de Datos Bancarios
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
      </v-card-title>
      <div class="container">
        <v-form v-model="valid" ref="form">
          <v-row justify="center">
            <v-col lg="10" md="10" sm="10" xs="10">
              <div class="contenedor">
                <v-row>
                  <v-col lg="6" md="6" sm="6" xs="12">
                    <v-text-field
                      dense
                      readonly
                      label="Grupo"
                      placeholder="Grupo"
                      v-model="item.Grupo"
                      min="4"
                      max="6"
                      :rules="ruleRequired"
                      required
                    ></v-text-field>
                  </v-col>
                  <v-col lg="6" md="6" sm="6" xs="12">
                    <v-text-field
                      dense
                      readonly
                      label="Orden"
                      placeholder="Orden"
                      v-model="item.Orden"
                      type="number"
                      min="1"
                      max="3"
                      required
                      :rules="ruleRequired"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col lg="6" md="6" sm="6" xs="12">
                    <v-text-field
                      dense
                      label="Importe a Transferir"
                      placeholder="Importe a Transferir"
                      v-model="item.PrecioCompra"
                      type="number"
                      min="0"
                      step="any"
                      :rules="ruleRequired"
                      required
                    ></v-text-field>
                  </v-col>
                  <v-col lg="6" md="6" sm="6" xs="12">
                    <v-text-field
                      dense
                      label="Valor de Compra"
                      placeholder="Valor de Compra"
                      :value="getValorCompra"
                      type="number"
                      min="0"
                      step="any"
                      :rules="ruleRequired"
                      required
                    ></v-text-field>
                  </v-col>
                </v-row>

                <v-row>
                  <v-col lg="6" md="6" sm="6" xs="12">
                    <v-text-field
                      dense
                      label="Titular de la Cuenta"
                      placeholder="Titular de la Cuenta"
                      type="text"
                      v-model="item.TitularCuenta"
                      :rules="ruleRequired"
                    ></v-text-field>
                  </v-col>
                  <v-col lg="6" md="6" sm="6" xs="12">
                    <v-text-field
                      dense
                      label="CUIT/CUIL"
                      placeholder="CUIT/CUIL"
                      v-model="item.CUIT"
                      type="text"
                      :rules="ruleRequired"
                    ></v-text-field>
                  </v-col>
                </v-row>

                <v-row>
                  <v-col lg="6" md="6" sm="6" xs="12">
                    <v-text-field
                      dense
                      label="Banco"
                      placeholder="Banco"
                      v-model="item.NombreBanco"
                      type="text"
                      :rules="ruleRequired"
                    ></v-text-field>
                  </v-col>
                  <v-col lg="6" md="6" sm="6" xs="12">
                    <v-text-field
                      dense
                      label="Nro. de Cuenta"
                      placeholder="Nro. de Cuenta"
                      v-model="item.NroCuenta"
                      type="text"
                      :rules="ruleRequired"
                    ></v-text-field>
                  </v-col>
                </v-row>

                <v-row>
                  <v-col lg="6" md="6" sm="6" xs="12">
                    <v-text-field
                      dense
                      label="CBU"
                      placeholder="CBU"
                      v-model="item.CBU"
                      type="text"
                      :rules="ruleRequired"
                    ></v-text-field>
                  </v-col>
                  <v-col lg="6" md="6" sm="6" xs="12">
                    <v-text-field
                      dense
                      label="Alias CBU"
                      placeholder="Alias CBU"
                      v-model="item.AliasCBU"
                      type="text"
                    ></v-text-field>
                  </v-col>
                </v-row>

                <v-row>
                  <v-col lg="9" md="9" sm="9" xs="12">
                    <v-text-field
                      dense
                      readonly
                      label="Haber Neto"
                      placeholder="Haber Neto"
                      v-model="item.HaberNeto"
                      type="number"
                      min="0"
                      step="any"
                      :rules="ruleRequired"
                      required
                    ></v-text-field>
                  </v-col>
                  <v-col lg="3" md="3" sm="3" xs="12">
                    <v-text-field
                      dense
                      readonly
                      label="Avance"
                      placeholder="Avance"
                      v-model="item.Avance"
                      type="number"
                      min="0"
                      step="any"
                      :rules="ruleRequired"
                      required
                    ></v-text-field>
                  </v-col>
                </v-row>

                <v-divider horizontal></v-divider>
                <v-row>
                  <v-col>
                    <v-btn
                      small
                      outlined
                      :disabled="disabledAceptar"
                      color="success"
                      @click="grabarCBU"
                    >
                      <v-icon left>mdi-content-save-outline</v-icon>Aceptar
                    </v-btn>
                  </v-col>
                  <v-spacer></v-spacer>
                  <v-spacer></v-spacer>
                  <v-spacer></v-spacer>
                  <v-spacer></v-spacer>
                  <v-spacer></v-spacer>
                  <v-col>
                    <v-btn small outlined color="error" @click="volver">
                      <v-icon left>mdi-close-circle</v-icon>Cancelar
                    </v-btn>
                  </v-col>
                </v-row>
              </div>
            </v-col>
          </v-row>
        </v-form>
      </div>
    </v-card>

    <v-dialog v-model="loadingData" persistent max-width="350px">
      <v-progress-linear
        indeterminate
        height="10"
        color="primary darken-1"
      ></v-progress-linear>
    </v-dialog>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import moment from "moment";
export default {
  props: {
    item: {
      type: Object,
      required: true
    },
    esEdicion: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      valid: true,
      datos_cbu: {
        TitularCuenta: "",
        CUIT: "",
        NroCuenta: "",
        AliasCBU: "",
        CBU: "",
        Banco: "",
        EmailTransferencia: ""
      },
      disabledAceptar: false
    };
  },

  created() {
    //this.setDefaults();
  },

  methods: {
    ...mapActions({
      saveCBU: "gestioncompras/saveCBU",
      setFechaDato: "gestioncompras/setFechaDato"
    }),

    volver() {
      //this.$router.go(-1);
      this.clearForm();

      this.$emit("hide");
    },

    clearForm() {
      this.disabledAceptar = false;
      this.esEdicion = null;

      this.$refs.form.resetValidation();
    },

    async grabarCBU() {
      this.disabledAceptar = true;
      if (!this.$refs.form.validate()) {
        this.disabledAceptar = false;
        return;
      }

      if (typeof this.item !== "undefined") {
        var pars = {
          ID_Dato: this.item.ID,
          Marca: this.item.Marca,
          Concesionario: this.item.Concesionario,
          Grupo: this.item.Grupo,
          Orden: this.item.Orden,
          HaberNeto: this.item.HaberNeto,
          ImporteATransferir: this.item.PrecioCompra,
          TitularCuenta: this.item.TitularCuenta,
          CUIT: this.item.CUIT,
          NroCuenta: this.item.NroCuenta,
          AliasCBU: this.item.AliasCBU,
          CBU: this.item.CBU,
          NombreBanco: this.item.NombreBanco,
          EsEdicion: this.esEdicion
        };

        if (!this.esEdicion) {
          var fechaAGuardar = moment().format("YYYY-MM-DD");

          var parsFecha = {
            ID: this.item.ID,
            Marca: this.item.Marca,
            Concesionario: this.item.Concesionario,
            TipoFecha: 5,
            FechaAGuardar: fechaAGuardar
          };
          await this.setFechaDato(parsFecha);
        }

        await this.saveCBU(pars);

        await this.showSwal();
        await this.$emit("refresh");
        this.volver();
      }
    },

    showSwal() {
      this.$swal(this.cbuStatusMsgInsert, "", this.cbuStatus);
    }
  },

  computed: {
    ...mapState("gestioncompras", [
      "cbuStatus",
      "loadingCBU",
      "cbuStatusMsgInsert",
      "loadingFechas",
      "loadingTitularCompra"
    ]),

    getValorCompra() {
      return parseInt(
        parseInt(this.item.HaberNeto) * 0.08 + parseInt(this.item.PrecioCompra)
      );
    },

    getNombreConcesionario() {
      switch (parseInt(this.item.Concesionario)) {
        case 1:
          return "Sauma";
        case 2:
          return "Iruña";
        case 3:
          return "Amendola";
        case 4:
          return "AutoCervo";
        case 5:
          return "AutoNet";
        case 6:
          return "Car Group";
        case 7:
          return "Luxcar";
        case 8:
          return "RB";
        case 9:
          return "Sapac";
      }
    },

    ...mapState("auth", ["login", "user"]),

    loadingData() {
      return this.loadingFechas || this.loadingCBU || this.loadingTitularCompra;
    },

    ruleRequired() {
      //return [(v) => !!v || "Campo Requerido."];
    }
  }
};
</script>

<style scoped>
.fullw {
  width: 100%;
  margin-bottom: 0;
  padding-bottom: 0;
}

.container {
  width: 100%;
  height: 100%;
  padding-left: 10px;
  padding-right: 10px;
  padding-bottom: 20px;
  padding-top: 20px;
  margin-bottom: 0;
}

.contenedor {
  width: 100%;
  height: 100%;
}

.hn {
  font-size: 26px;
  font-weight: bolder;
  text-align: center;
}
</style>
