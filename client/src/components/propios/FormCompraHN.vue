<template>
  <div>
    <v-card color="grey lighten-4" ma-0>
      <v-card-title
        class="headline grey lighten-2"
        justify="center"
        primary-title
      >
        Compra Haber Neto
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
                    <v-combobox
                      dense
                      label="Marca"
                      item-text="Nombre"
                      item-value="Codigo"
                      :items="listMarcas"
                      v-model="codMarcaSelected"
                      @change="filterListConcesionaria"
                      :rules="ruleRequired"
                      required
                      :disabled="operationFound"
                    ></v-combobox>
                  </v-col>
                  <v-col lg="6" md="6" sm="6" xs="12">
                    <v-combobox
                      dense
                      item-text="Nombre"
                      item-value="Codigo"
                      :items="listC"
                      label="Concesionario"
                      v-model="codConcesSelected"
                      :rules="ruleRequired"
                      :disabled="operationFound"
                      @change="setTitularesCompra"
                      required
                    ></v-combobox>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col lg="12" md="12" sm="12" xs="12">
                    <v-combobox
                      dense
                      label="Titular Compra HN"
                      item-text="Nombre"
                      item-value="Codigo"
                      :items="listTitularesCompra"
                      v-model="codTitularCSelected"
                    ></v-combobox>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col lg="3" md="3" sm="3" xs="12">
                    <v-text-field
                      dense
                      label="Grupo"
                      placeholder="Grupo"
                      v-model="searchGrupo"
                      min="4"
                      max="6"
                      :rules="ruleRequired"
                      :disabled="operationFound"
                      required
                    ></v-text-field>
                  </v-col>
                  <v-col lg="3" md="3" sm="3" xs="12">
                    <v-text-field
                      dense
                      label="Orden"
                      placeholder="Orden"
                      type="number"
                      min="1"
                      max="3"
                      required
                      :rules="ruleRequired"
                      :disabled="operationFound"
                      v-model="searchOrden"
                    ></v-text-field>
                  </v-col>
                  <v-col lg="3" md="3" sm="3" xs="12">
                    <v-text-field
                      dense
                      label="Solicitud"
                      placeholder="Solicitud"
                      type="number"
                      :disabled="operationFound"
                      v-model="searchSolicitud"
                    ></v-text-field>
                  </v-col>
                  <v-col lg="3" md="3" sm="3" xs="12">
                    <v-btn
                      cclass="ma-2"
                      small
                      outlined
                      color="primary"
                      :disabled="disabledBuscarOp"
                      @click="buscarOperacion"
                    >
                      <v-icon left>mdi-magnify</v-icon>Buscar Op.
                    </v-btn>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col lg="6" md="6" sm="6" xs="12">
                    <v-combobox
                      dense
                      label="Titular"
                      item-text="Nombre"
                      item-value="Codigo"
                      :items="itemstt"
                      :value="tipoTitular"
                      :rules="ruleRequired"
                      disabled
                    ></v-combobox>
                  </v-col>
                  <v-col lg="6" md="6" sm="6" xs="12">
                    <v-text-field
                      dense
                      label="Haber Neto Original"
                      placeholder="Haber Neto Original"
                      type="number"
                      min="0"
                      step="any"
                      v-model="operacion.HaberNeto"
                      :rules="ruleRequired"
                      required
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col lg="3" md="3" sm="3" xs="12">
                    <v-combobox
                      dense
                      label="Tipo Compra"
                      item-text="Nombre"
                      item-value="Codigo"
                      :items="itemstp"
                      :value="tipoCompra"
                      :rules="ruleRequired"
                      required
                    ></v-combobox>
                  </v-col>
                  <v-col lg="3" md="3" sm="3" xs="12">
                    <v-text-field
                      dense
                      label="Grupo"
                      placeholder="Grupo"
                      disabled
                    ></v-text-field>
                  </v-col>
                  <v-col lg="3" md="3" sm="3" xs="12">
                    <v-text-field
                      dense
                      label="Orden"
                      placeholder="Orden"
                      disabled
                    ></v-text-field>
                  </v-col>

                  <v-col lg="3" md="3" sm="3" xs="12">
                    <v-btn
                      cclass="ma-2"
                      small
                      outlined
                      color="primary"
                      disabled
                    >
                      <v-icon left>mdi-magnify</v-icon>Buscar Op.
                    </v-btn>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col lg="6" md="6" sm="6" xs="12">
                    <v-text-field
                      dense
                      label="Monto de Compra"
                      placeholder="Monto de Compra"
                      type="number"
                      min="0"
                      step="any"
                      v-model="operacion.PrecioCompra"
                      :rules="ruleRequired"
                      required
                    ></v-text-field>
                  </v-col>
                  <v-col lg="6" md="6" sm="6" xs="12">
                    <v-text-field
                      dense
                      label="Monto de Cobro Estimado"
                      placeholder="Monto de Cobro Estimado"
                      type="number"
                      min="0"
                      step="any"
                      v-model="operacion.HaberNeto"
                      :rules="ruleRequired"
                      required
                    ></v-text-field>
                  </v-col>
                  <!--
                  <v-col lg="4" md="4" sm="4" xs="12">
                    <v-btn cclass="ma-2" small outlined disabled>
                      <v-icon left>mdi-calculator</v-icon>Cálculo Autom.
                    </v-btn>
                  </v-col>
                  -->
                </v-row>
                <v-row>
                  <v-col lg="6" md="6" sm="6" xs="12">
                    <v-text-field
                      dense
                      label="Nro de Transferencia"
                      placeholder="Nro de Transferencia"
                      type="number"
                      :disabled="disabledTransferencia"
                      @blur="verificarTransferencia"
                      v-model="nroTransferencia"
                      :rules="ruleTransferRequired"
                      :required="disabledTransferencia"
                    ></v-text-field>
                  </v-col>
                  <v-col lg="6" md="6" sm="6" xs="12">
                    <v-combobox
                      dense
                      label="Concesionario Propio"
                      item-text="Nombre"
                      item-value="Codigo"
                      :items="itemstc"
                      :value="tipoConcesionario"
                      disabled
                    ></v-combobox>
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
                      @click="grabarHN"
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
    dato: {
      type: Object,
      required: false,
    },
  },
  data() {
    return {
      valid: true,
      searchGrupo: null,
      searchOrden: null,
      searchSolicitud: null,
      codMarcaSelected: null,
      operacion: {
        ID: null,
        HaberNeto: null,
        PrecioCompra: null,
        NroTransferencia: null,
        EmpresaGyO: null,
      },
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
        { Codigo: 2, Nombre: "Iruña", Marca: 5 },
        { Codigo: 3, Nombre: "Amendola", Marca: 5 },
        { Codigo: 7, Nombre: "Luxcar", Marca: 5 },
        // { Codigo: 4, Nombre: "AutoCervo", Marca: 2 },
        { Codigo: 5, Nombre: "AutoNet", Marca: 2 },
        { Codigo: 6, Nombre: "Car Group", Marca: 2 },
        { Codigo: 8, Nombre: "RB", Marca: 2 },
        { Codigo: 9, Nombre: "Sapac", Marca: 9 },
        { Codigo: 10, Nombre: "Alizze", Marca: 3 },
        { Codigo: 12, Nombre: "Peugeot Web", Marca: 3 },
        { Codigo: 13, Nombre: "Fiat Web", Marca: 2 },
        { Codigo: 15, Nombre: "Volkswagen Web", Marca: 5 },
        { Codigo: 18, Nombre: "Alra", Marca: 5 },
        { Codigo: 19, Nombre: "Autotag", Marca: 5 },
        { Codigo: 20, Nombre: "Maynar", Marca: 5 },
        { Codigo: 21, Nombre: "Sebastiani", Marca: 5 },
        { Codigo: 22, Nombre: "Yacopini", Marca: 5 },
        { Codigo: 23, Nombre: "Detroit", Marca: 7 },
        
      ],
      listTitularesCompra: [
        { Codigo: 0, Nombre: "Concesionario", ComproGiama: 0 },
        { Codigo: 1, Nombre: "Giama (RB)", ComproGiama: 1 },
        { Codigo: 2, Nombre: "GB", ComproGiama: 1 },
      ],
      codTitularCSelected: null,
      tipoCompra: { Codigo: 1, Nombre: "Finanzas", Marca: 5 },
      itemstp: [
        { Codigo: 1, Nombre: "Finanzas", Marca: 5 },
        { Codigo: 2, Nombre: "Corrección de Mora", Marca: 5 },
        { Codigo: 3, Nombre: "Corrección de Vta.", Marca: 5 },
        { Codigo: 4, Nombre: "Convencional", Marca: 2 },
        { Codigo: 5, Nombre: "Conflictos", Marca: 2 },
        { Codigo: 6, Nombre: "Toma Plan", Marca: 2 },
        { Codigo: 7, Nombre: "Para Venta de Adjudicados", Marca: 2 },
        { Codigo: 8, Nombre: "Compra Planes para Autos", Marca: 2 },
      ],
      tipoTitular: { Codigo: 1, Nombre: "Tercero" },
      itemstt: [
        { Codigo: 1, Nombre: "Tercero" },
        { Codigo: 2, Nombre: "Propio" },
      ],
      tipoConcesionario: { Codigo: 0, Nombre: "No" },
      itemstc: [
        { Codigo: 1, Nombre: "Si" },
        { Codigo: 0, Nombre: "No" },
      ],
      nroTransferencia: null,
      cargoOperacion: false,
      disabledAceptar: true,
    };
  },

  mounted() {
    //this.setDefaults();
    console.log(this.dato);
    if (this.dato.ID !== null) {
      this.setValoresProps();
    }
  },

  methods: {
    ...mapActions({
      getOperacion: "haberneto/getOperacion",
      grabarHaberNeto: "haberneto/grabarHaberNeto",
      clearCompra: "haberneto/clearCompra",
      getCotizacionesUSD: "cotizaciondolar/getCotizacionesLocal",
      checkTransferFiat: "haberneto/checkTransferFiat",
    }),

    setValoresProps() {
      console.log(this.dato);
      //this.operacion.ID = item.ID;
      // this.codMarcaSelected = this.dato.Marca;
      // this.codConcesSelected = this.dato.Concesionario;
      this.searchGrupo = this.dato.Grupo;
      this.searchOrden = this.dato.Orden;
      this.setMarcaSelected(this.dato.Marca);
      this.setConcesionarioSelected(this.dato.Concesionario);
      this.setTitularCompraSelected(this.dato.ComproGiama);
      //this.codTitularCSelected = this.dato.ComproGiama;
      this.operacion.ID = this.dato.ID;
      this.operacion.HaberNeto = this.dato.HaberNeto;
      this.operacion.PrecioCompra = this.dato.PrecioCompra;
      this.operacion.Titular = this.dato.TitularCompra;

      this.cargoOperacion = true;
    },

    volver() {
      //this.$router.go(-1);
      this.clearForm();

      this.$emit("hide");
    },

    clearForm() {
      this.clearCompra();
      this.codConcesSelected = null;
      this.codMarcaSelected = null;
      this.codTitularCSelected = null;
      this.searchGrupo = null;
      this.searchOrden = null;
      this.searchSolicitud = null;
      this.cargoOperacion = false;
      this.disabledAceptar = false;
      this.nroTransferencia = null;
      this.$refs.form.resetValidation();
    },

    filterListConcesionaria(value) {
      this.codConcesSelected = null;
      this.listC = [];
      this.listC = this.listConcesionarios.filter(function (item) {
        return item.Marca === value.Codigo;
      });
    },

    setMarcaSelected(value) {
      this.codMarcaSelected = this.listMarcas.find(function (item) {
        return item.Codigo === parseInt(value);
      });

      console.log(this.codMarcaSelected);
    },

    setConcesionarioSelected(value) {
      this.codConcesSelected = this.listConcesionarios.find(function (item) {
        return item.Codigo === parseInt(value);
      });
    },

    setTitularCompraSelected(value) {
      var codigo = 0;
      if (value) {
        codigo = 1;
      }
      this.codTitularCSelected = this.listTitularesCompra.find(function (item) {
        return item.Codigo === codigo;
      });
    },

    setTitularesCompra(value) {
      console.log(value);

      if (value.Marca == 2 || value.Marca == 7) {
        this.codTitularCSelected = { Codigo: 1, Nombre: "Giama (RB)", ComproGiama: 1 };
      }
    },

    verificarTransferencia() {
      if (this.codConcesSelected != null && this.nroTransferencia != null) {
        //Busco transfer en FIAT que no sean de RB
        if (
          this.codConcesSelected.Marca == 2 &&
          this.codConcesSelected.Codigo != 8
        ) {
          this.checkNroTransferencia(
            this.nroTransferencia,
            this.codConcesSelected.Marca,
            this.codConcesSelected.Codigo
          );
        } else {
          this.disabledAceptar = false;
        }
      } else {
        this.disabledAceptar = false;
      }
    },

    async checkNroTransferencia(nroTransfer, marca, concesionario) {
      //
      var params = {
        Marca: marca,
        Concesionario: concesionario,
        Transferencia: nroTransfer,
      };
      await this.checkTransferFiat(params);
      var type = this.dataStatusTransfer;
      if (this.dataStatusMsgTransfer != "") {
        if (this.dataStatusTransfer == "success") {
          if (this.transferUtilizada) {
            this.nroTransferencia = "";
            //xtNroTransf.Focus()
          }
          type = "warning";
        }
        this.showSwalTransferencia(type);
      } else {
        this.disabledAceptar = false;
      }
    },

    async buscarOperacion() {
      var pars = {
        Marca: this.codConcesSelected.Marca,
        Concesionario: this.codConcesSelected.Codigo,
        Grupo: this.searchGrupo,
        Orden: this.searchOrden,
        Solicitud: this.searchSolicitud,
      };
      //console.log(pars);
      await this.getOperacion(pars);
      if (!this.encontroOp) {
        this.showSwalBusqueda();
      } else {
        this.operacion = this.operacionBuscada;
        this.cargoOperacion = true;
      }
    },

    async grabarHN() {
      this.disabledAceptar = true;
      if (!this.$refs.form.validate()) {
        this.disabledAceptar = false;
        return;
      }
      console.log(this.codTitularCSelected);

      if (typeof this.operacion !== "undefined") {
        var pars = {
          Marca: this.codConcesSelected.Marca,
          Concesionario: this.codConcesSelected.Codigo,
          Grupo: this.searchGrupo,
          Orden: this.searchOrden,
          Titular: this.tipoTitular.Codigo,
          TipoCompra: this.tipoCompra.Codigo,
          MontoCompra: this.operacion.PrecioCompra,
          MontoCobroEstimado: this.operacion.HaberNeto,
          HaberNetoSubite: this.operacion.HaberNeto,
          HaberNetoOriginal: this.operacion.HaberNeto,
          TitularCompraHN: this.codTitularCSelected.Codigo,
          ComproGiama: this.codTitularCSelected.ComproGiama,
          ID_Dato: this.operacion.ID,
          EmpresaGyO: this.operacion.EmpresaGyO,
          Login: this.login,
          NroTransferencia: this.nroTransferencia,
          TIRSpot: 0,
        };
      
        //console.log(pars);
        await this.grabarHaberNeto(pars);
        await this.showSwal();
        this.$emit("refresh");
        this.volver();
    
      }
    },

    showSwal() {
      //this.$swal("Good job!", dataStatusMsg, dataStatus);
      this.$swal(this.dataStatusMsgInsert, "", this.dataStatusInsert);
      this.clearCompra();
    },

    showSwalBusqueda() {
      //this.$swal("Good job!", dataStatusMsg, dataStatus);
      this.$swal(this.dataStatusMsgBusqueda, "", this.dataStatusBusqueda);
    },

    showSwalTransferencia(type) {
      //this.$swal("Good job!", dataStatusMsg, dataStatus);
      this.$swal(this.dataStatusMsgTransfer, "", type);
    },
  },

  computed: {
    ...mapState("haberneto", [
      "operacionBuscada",
      "dataStatusMsgOperacion",
      "dataStatusInsert",
      "dataStatusMsgInsert",
      "dataStatusBusqueda",
      "dataStatusMsgBusqueda",
      "encontroOp",
      "loadingBusqueda",
      "dataStatusTransfer",
      "dataStatusMsgTransfer",
      "transferUtilizada",
      "loadingTransfer",
      "loadingStatusInsert",
    ]),

    ...mapState("auth", ["login", "user"]),
    ...mapState("cotizaciondolar", ["cotizacionesLocal"]),

    stateMsg() {
      switch (true) {
        case this.loadingBusqueda:
          return "Obteniendo datos, aguarde por favor...";
          break;
        case this.loadingTransfer:
          return "Verificando Número de Transferencia, aguarde por favor...";
          break;
        case this.loadingStatusInsert:
          return "Guardando compra de Haber Neto, aguarde por favor...";
          break;
      }
    },

    ruleTransferRequired() {
      if (!this.disabledTransferencia) {
        return [(v) => !!v || "Campo Requerido."];
      }
    },

    loadingData() {
      return (
        this.loadingBusqueda || this.loadingTransfer || this.loadingStatusInsert
      );
    },

    ruleRequired() {
      return [(v) => !!v || "Campo Requerido."];
    },

    operationFound() {
      return this.cargoOperacion;
    },

    disabledBuscarOp() {
      return (
        this.cargoOperacion ||
        (this.codConcesSelected == null &&
          ((this.searchGrupo == null && this.searchOrden == null) ||
            this.searchSolicitud == null))
      );
    },

    disabledTransferencia() {
      if (this.codConcesSelected != null) {
        var retorno = false;
        retorno = this.codConcesSelected.Marca != 2;
        this.disabledAceptar = !retorno;

        return retorno;
      }
      return true;
    },
  },
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
