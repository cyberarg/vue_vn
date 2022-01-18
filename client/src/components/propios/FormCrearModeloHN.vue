<template>
  <div class="fullw">
    <v-card>
      <v-card-title>
        <!--
        {{pars.titleform}}
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        -->
      </v-card-title>

      <v-form v-model="valid">
        <v-row>
          <v-col cols="2">
            <v-container class="cont-row">
              <v-row class="cont-row">
                <v-col cols="12">
                  <v-text-field
                    class="centered-input"
                    dense
                    type="number"
                    hide-details="true"
                    label="Inversión Incial"
                    placeholder="Inversión Incial"
                    v-model="invinicial"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row class="cont-row">
                <v-col cols="12">
                  <v-text-field
                    class="centered-input"
                    dense
                    type="number"
                    hide-details="true"
                    label="Multiplico Inversión Incial"
                    placeholder="Multiplico Inversión Incial"
                    :value="multInverInicial"
                    disabled
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row class="cont-row">
                <v-col cols="12">
                  <v-text-field
                    dense
                    type="number"
                    hide-details="true"
                    label="Primera Reinversión"
                    placeholder="Primera Reinversión"
                    v-model="primerreinv"
                    disabled
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row class="cont-row">
                <v-col cols="12">
                  <v-text-field
                    dense
                    type="number"
                    hide-details="true"
                    label="Segunda Reinversión"
                    placeholder="Segunda Reinversión"
                    v-model="segundareinv"
                    disabled
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-divider class="mx-4" inset horizontal></v-divider>
              <v-row class="cont-row">
                <v-col cols="12">
                  <v-text-field
                    dense
                    type="number"
                    hide-details="true"
                    label="Indice Multiplicador"
                    placeholder="Indice Multiplicador"
                    v-model="indiceM"
                    disabled
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    dense
                    type="number"
                    hide-details="true"
                    label="Costo Total (Incluida Comisión Giama)"
                    placeholder="Costo Total (Incluida Comisión Giama)"
                    v-model="costototal"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    dense
                    type="number"
                    hide-details="true"
                    label="HN Promedio"
                    placeholder="HN Promedio"
                    v-model="hnpromedio"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    dense
                    type="number"
                    hide-details="true"
                    label="Cantidad HN Comprados (Anual)"
                    placeholder="Cantidad HN Comprados (Anual)"
                    :value="filtroNumeroCantHNComprados()"
                    disabled
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    dense
                    type="number"
                    hide-details="true"
                    label="Tipo de Cambio $/USD"
                    placeholder="Tipo de Cambio $/USD"
                    v-model="tipocambio"
                  ></v-text-field>
                </v-col>
              </v-row>
              <!-- <v-divider inset horizontal></v-divider>-->
              <v-row>
                <v-col cols="6">Supuesto de Cobro (Anual)</v-col>
                <v-col cols="6">
                  <v-row>
                    <v-col>
                      <v-text-field
                        dense
                        type="number"
                        hide-details="true"
                        label="2do"
                        placeholder="2do"
                        v-model="segundosupuesto"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col>
                      <v-text-field
                        dense
                        type="number"
                        hide-details="true"
                        label="3er"
                        placeholder="3er"
                        :value="tercerSupuestoCobro"
                        disabled
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>
            </v-container>
          </v-col>
          <!--<v-divider inset vertical></v-divider>-->
          <v-col cols="2">
            <v-row>
              <v-col cols="12">
                <v-text-field
                  dense
                  type="number"
                  hide-details="true"
                  label="Con Inversión Inicial"
                  placeholder="Con Inversión Inicial"
                  v-model="invinicial"
                  disabled
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <p>Compramos HN Por</p>
              </v-col>
            </v-row>
            <v-divider horizontal></v-divider>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  dense
                  type="number"
                  hide-details="true"
                  label="Valor Unitario de Planes"
                  placeholder="Valor Unitario de Planes"
                  :value="filtroNumeroValorUnitario()"
                  disabled
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  dense
                  type="number"
                  hide-details="true"
                  label="Cantidad de Planes"
                  placeholder="Cantidad de Planes"
                  v-model="totalplanesFormnat"
                  disabled
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  dense
                  type="number"
                  hide-details="true"
                  label="Totales USD"
                  placeholder="Totales USD"
                  v-model="totalesCP"
                  disabled
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  dense
                  type="number"
                  hide-details="true"
                  label="Indice de Apalancamiento"
                  placeholder="Indice de Apalancamiento"
                  :value="indiceApalancamiento"
                  disabled
                ></v-text-field>
              </v-col>
            </v-row>
          </v-col>
          <!--<v-divider inset vertical></v-divider>-->
          <v-col cols="8">
            <v-row>
              <v-col md="12">
                <v-simple-table dense class="tableP">
                  <template v-slot:default>
                    <thead>
                      <tr>
                        <th class="text-left"></th>
                        <th class="text-left">2020</th>
                        <th class="text-left">2021</th>
                        <th class="text-left">2022</th>
                        <th class="text-left">2023</th>
                        <th class="text-left">2024</th>
                        <th class="text-left">TOTAL</th>
                      </tr>
                    </thead>
                    <tbody text-align="center">
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td></td>
                        <td
                          class="bg-gray"
                          :v-model="campo1"
                        >USD {{ getCalculoValor(invinicial, 1) | numFormat }}</td>
                        <td :v-model="campo2">
                          USD
                          {{
                          getCalculoValorConResta(invinicial, campo1, 2)
                          | numFormat
                          }}
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="bg-gray" :v-model="campo3">
                          USD
                          {{
                          getCalculoValorConSupuesto(campo1, 3) | numFormat
                          }}
                        </td>
                        <td :v-model="campo4">
                          USD
                          {{
                          getCalculoValorConResta(campo1, campo3, 4)
                          | numFormat
                          }}
                        </td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td :v-model="campo5">
                          USD
                          {{
                          getCalculoValorConSupuesto(campo3, 5) | numFormat
                          }}
                        </td>
                        <td :v-model="campo6">
                          USD
                          {{
                          getCalculoValorConResta(campo3, campo5, 6)
                          | numFormat
                          }}
                        </td>
                        <td></td>
                      </tr>
                      <tr>
                        <td>Saldo de Caja</td>
                        <td></td>
                        <td></td>
                        <td>
                          USD
                          {{ getTotalesCampos(2, campo2, 0, 0) | numFormat }}
                        </td>
                        <td>
                          USD
                          {{
                          getTotalesCampos(3, campo4, campo5, 0) | numFormat
                          }}
                        </td>
                        <td>
                          USD
                          {{ getTotalesCampos(4, campo6, 0, 0) | numFormat }}
                        </td>
                        <td>USD {{ getTotalesPeriodos() | numFormat }}</td>
                      </tr>
                      <tr>
                        <td>Cantidad HN Comprados</td>
                        <td>{{ getCantidadPlanes(0, cantidadhncomprados) }}</td>
                        <td>{{ getCantidadPlanes(1, campo1) }}</td>
                        <td>{{ getCantidadPlanes(2, campo3) }}</td>
                        <td>{{ getCantidadPlanes(3, 0) }}</td>
                        <td>{{ getCantidadPlanes(4, 0) }}</td>
                        <td>{{ getCantidadTotalPlanes() | numFormat }}</td>
                      </tr>
                      <tr>
                        <td colspan="5"></td>
                        <td>TIR Compuesta</td>
                        <td>{{ Math.round(((((getTotalesPeriodos() / invinicial) - 1) / 5) * 100), 0) }} %</td>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
              </v-col>
            </v-row>
            <v-row>
              <v-col md="12">
                <v-simple-table dense class="tableP">
                  <template v-slot:default>
                    <thead>
                      <tr>
                        <th class="text-center" colspan="5">Control</th>
                      </tr>
                    </thead>
                    <tbody text-align="center">
                      <tr>
                        <td>Cantidad de Planes</td>
                        <td>{{ totalplanes | numFormat }}</td>
                        <td></td>
                        <td>Cobranza Total</td>
                        <td>USD {{ getTotalesPeriodos() | numFormat }}</td>
                      </tr>
                      <tr>
                        <td>Utilidad Marg. X HN</td>
                        <td>USD {{ utilidadMarginalPorHN | numFormat }}</td>
                        <td></td>
                        <td>Inversión Incial</td>
                        <td>USD {{ invinicial | numFormat }}</td>
                      </tr>
                      <tr>
                        <td>TOTAL</td>
                        <td>USD {{ getCantidadTotalPlanes() * utilidadMarginalPorHN | numFormat }}</td>
                        <td></td>
                        <td>Resultado Neto</td>
                        <td>USD {{ (getTotalesPeriodos() - invinicial) | numFormat }}</td>
                      </tr>
                      <tr>
                        <td>Verificación</td>
                        <td>USD {{ verificacion | numFormat }}</td>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <v-layout justify-center class="rowclass">
              <v-btn class="ma-2 primary" :disabled="disableGuardar" @click="guardarModelo">
                <v-icon left>mdi-content-save-outline</v-icon>Guardar Modelo
              </v-btn>
            </v-layout>
          </v-col>
        </v-row>
      </v-form>
    </v-card>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  name: "formmodelohn",
  props: {
    pars: {
      type: Object,
      required: true,
    },
    disableGuardar: {
      type: Boolean,
      required: false,
    },
    items: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      valid: true,
      filled: true,
      invinicial: 90000,
      //disabledAceptar: false,
      multiinvinicial: 0,
      indiceMultDec: 0,
      hnpromedio: 200000,
      campo1: 0,
      campo2: 0,
      campo3: 0,
      campo4: 0,
      campo5: 0,
      campo6: 0,
      totalplanesFormnat: 0,

      totAnio0: 0,
      totAnio1: 0,
      totAnio2: 0,
      totAnio3: 0,
      totAnio4: 0,

      planesAnio0: 0,
      planesAnio1: 0,
      planesAnio2: 0,
      planesAnio3: 0,
      planesAnio4: 0,
      totplanes: 0,

      primerreinv: 0,
      segundareinv: 0,
      segundosupuesto: 50,
      //cantidadhncomprados: 0,
      tipocambio: 100,
      costototal: 45,

      headersproy: [
        { text: "", value: "", align: "left" },
        { text: "2020", value: "Valor", align: "left" },
        { text: "2021", value: "Valor", align: "center" },
        { text: "2022", value: "Valor", align: "center" },
        { text: "2023", value: "Valor", align: "center" },
        { text: "2024", value: "Valor", align: "center" },
        { text: "Total", value: "Total", align: "center" },
      ],

      itemsproy: [
        { Nombre: "Inversion Incial", Valor: "USD 100,000" },
        { Nombre: "Multiplico Inversion Inicial", Valor: "USD 200,000" },
        { Nombre: "Primera Reinversion", Valor: "USD 100,000" },
        { Nombre: "Segunda Reinversion", Valor: "USD 100,000" },
      ],
    };
  },

  created() {
    //
  },

  methods: {
    ...mapActions({ grabarModelo: "modelohn/grabarModelo" }),

    async guardarModelo() {
      var pars = {
        Marca: this.codMarca,
        Concesionario: this.codConcesionario,

        ObjInversionInicial: parseInt(this.invinicial),
        ObjCostoTotal: parseInt(this.costototal),
        ObjHNPromedio: parseInt(this.hnpromedio),
        ObjCantCasos: parseInt(this.cantidadhncomprados),
        ObjCotizDolar: parseInt(this.tipocambio),
        ObjSupuestoCobro: parseInt(this.segundosupuesto),
        ObjTIRCompuesta: parseInt(this.tirCompuesta),
      };

      await this.grabarModelo(pars);
      console.log(pars);
    },

    getCantidadPlanes(periodo, valor) {
      var cantPeriodo = 0;
      if (this.hnpromedio > 0 && this.costototal > 0 && this.tipocambio > 0) {
        cantPeriodo =
          valor /
          ((this.hnpromedio / this.tipocambio) * (this.costototal / 100));

        switch (periodo) {
          case 0:
            this.planesAnio0 = valor;
            cantPeriodo = valor;
            break;
          case 1:
            this.planesAnio1 = cantPeriodo;
            break;
          case 2:
            this.planesAnio2 = cantPeriodo;
            break;
          case 3:
            this.planesAnio3 = cantPeriodo;
            break;
          case 4:
            this.planesAnio4 = cantPeriodo;
            break;
        }
      }
      return Math.round(cantPeriodo);
    },

    totlplanes() {
      //var cantplanestot =

      this.totalplanes =
        parseFloat(this.planesAnio0) +
        parseFloat(this.planesAnio1) +
        parseFloat(this.planesAnio2) +
        parseFloat(this.planesAnio3) +
        parseFloat(this.planesAnio4);

      //var cantplanestot = this.totalplanes;

      //this.totalplanesFormnat = Math.round(cantplanestot);
      //return this.totalplanes;
    },

    getCantidadTotalPlanes() {
      this.totalplanes =
        parseFloat(this.planesAnio0) +
        parseFloat(this.planesAnio1) +
        parseFloat(this.planesAnio2) +
        parseFloat(this.planesAnio3) +
        parseFloat(this.planesAnio4);

      this.totalplanesFormnat = Math.round(this.totalplanes);

      return this.totalplanes;
    },

    getCalculoValor(inversion, nroCampo) {
      var valor =
        inversion * this.indicemultiplicador * (this.segundosupuesto / 100);
      switch (nroCampo) {
        case 1:
          this.campo1 = valor;
          this.primerreinv = Math.round(valor);
          break;
        case 2:
          this.campo2 = valor;
          break;
        case 3:
          this.campo3 = valor;
          this.segundareinv = Math.round(valor);
          break;
        case 4:
          this.campo4 = valor;
          break;
        case 5:
          this.campo5 = valor;
          break;
        case 6:
          this.campo6 = valor;
          break;
      }

      return valor;
    },

    getCalculoValorConResta(inversion, resta, nroCampo) {
      var valorCResta = inversion * this.indicemultiplicador - resta;
      switch (nroCampo) {
        case 1:
          this.campo1 = valorCResta;
          break;
        case 2:
          this.campo2 = valorCResta;
          break;
        case 3:
          this.campo3 = valorCResta;
          this.segundareinv = Math.round(valor);
          break;
        case 4:
          this.campo4 = valorCResta;
          break;
        case 5:
          this.campo5 = valorCResta;
          break;
        case 6:
          this.campo6 = valorCResta;
          break;
      }
      return valorCResta;
    },

    getCalculoValorConSupuesto(inversion, nroCampo) {
      var valorCSupuesto =
        inversion * this.indicemultiplicador * (this.segundosupuesto / 100);
      switch (nroCampo) {
        case 1:
          this.campo1 = valorCSupuesto;
          break;
        case 2:
          this.campo2 = valorCSupuesto;
        case 3:
          this.campo3 = valorCSupuesto;
          this.segundareinv = Math.round(valorCSupuesto);
          break;
        case 4:
          this.campo4 = valorCSupuesto;
          break;
        case 5:
          this.campo5 = valorCSupuesto;
          break;
        case 6:
          this.campo6 = valorCSupuesto;
          break;
      }

      return valorCSupuesto;
    },

    getTotalesCampos(periodo, valor1, valor2, valor3) {
      var suma = valor1 + valor2 + valor3;
      switch (periodo) {
        case 0:
          this.totAnio0 = suma;
          break;
        case 1:
          this.totAnio1 = suma;
          break;
        case 2:
          this.totAnio2 = suma;
          break;
        case 3:
          this.totAnio3 = suma;
          break;
        case 4:
          this.totAnio4 = suma;
          break;
      }
      return suma;
    },

    getTotalesPeriodos() {
      this.totPeriodos = Math.round(
        this.totAnio0 +
          this.totAnio1 +
          this.totAnio2 +
          this.totAnio3 +
          this.totAnio4
      );
      return this.totPeriodos;
    },

    filtroNumeroValorUnitario() {
      return Math.round(this.valorUnitarioPlanes);
    },

    filtroNumeroCantHNComprados() {
      return this.$options.filters.numFormat(this.cantidadhncomprados);
    },
  },

  computed: {
    ...mapState("modelohn", ["codMarca", "codConcesionario"]),

    indiceApalancamiento() {
      var indice =
        (this.getCantidadTotalPlanes() * this.valorUnitarioPlanes) /
        this.invinicial;
      return indice.toFixed(1);
    },

    verificacion() {
      var verif =
        this.utilidadMarginalPorHN * this.totalplanes -
        (this.totPeriodos - this.invinicial);
      return verif;
    },

    totalPlanesUtilidad() {
      var totPlanesUtil = this.utilidadMarginalPorHN * this.totalplanes;
      //console.log(this.utilidadMarginalPorHN);
      //console.log(this.totalplanes);
      return totPlanesUtil;
    },

    totalesCP() {
      var totcp = 0;
      totcp = this.getCantidadTotalPlanes() * this.valorUnitarioPlanes;
      return Math.round(totcp);
    },

    resultadoNeto() {
      var resNeto = this.totPeriodos - this.invinicial;
      return Math.round(resNeto);
    },

    tirCompuesta() {
      if (typeof this.items.item.ObjTIRCompuesta !== "undefined") {
        return this.items.item.ObjTIRCompuesta;
      } else {
        if (this.invinicial > 0) {
          return Math.round(
            ((this.totPeriodos / this.invinicial - 1) / 5) * 100
          );
        }
        return 0;
      }
    },

    valorUnitarioPlanes() {
      var valorunitp = 0;
      if (this.tipocambio > 0) {
        valorunitp = this.hnpromedio / this.tipocambio;
      }
      return valorunitp.toFixed(2);
    },

    utilidadMarginalPorHN() {
      var utilidadm = 0;
      if (this.tipocambio > 0) {
        utilidadm =
          (this.hnpromedio * (1 - this.costototal / 100)) / this.tipocambio;
      }
      console.log(utilidadm);
      return utilidadm;
    },

    cantidadhncomprados() {
      var canthnc = 0;
      var numerador = this.invinicial * this.tipocambio;
      if (this.hnpromedio > 0 && this.costototal > 0) {
        var denominador = this.hnpromedio * (this.costototal / 100);
        canthnc = numerador / denominador;
      }

      return canthnc.toFixed(2);
    },

    indiceM() {
      var imformated = this.indicemultiplicador;
      return imformated.toFixed(2);
    },

    indicemultiplicador() {
      var num = 100 / this.costototal;
      this.indiceMultDec = 100 / this.costototal;
      return num;
    },

    multInverInicial() {
      var indiceM = 0;
      var llamada = parseFloat(this.indicemultiplicador);
      indiceM = parseFloat(this.indiceMultDec);
      var retorno = this.invinicial;
      var retorno2 = indiceM;

      var mult = 0;
      mult = retorno * retorno2;

      return parseInt(mult);
    },

    tercerSupuestoCobro() {
      return Math.round(100 - this.segundosupuesto, 0);
    },
  },
};
</script>

<style scoped>
.fullw {
  width: 100%;
  margin-bottom: 25px;
}

.centered {
  margin: 50px 20px;
}

.v-input >>> input {
  text-align: center;

  /*
  font-size: 12px;
  height: 12px;
  padding-top: 2px;
  */
}

.centered-input >>> input {
  text-align: center;
}

.v-data-table >>> td {
  white-space: nowrap;
}

.tableP >>> td {
  font-size: 14px;
  white-space: nowrap;
}

.bg-gray {
  background-color: lightgray;
}

.cont-row {
  padding-top: 0;
  padding-bottom: 0;
  margin-top: 0;
  margin-bottom: 0;
}

.hn {
  font-size: 26px;
  font-weight: bolder;
  text-align: center;
}
</style>
