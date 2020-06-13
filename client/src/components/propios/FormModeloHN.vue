<template>
  <v-app class="fullw">
    <v-card>
      <v-card-title>
        {{pars.titleform}}
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
      </v-card-title>

      <v-container>
        <v-form v-model="valid">
          <v-row>
            <v-col cols="3">
              <v-container class="cont-row">
                <v-row class="cont-row">
                  <v-col cols="12">
                    <v-text-field
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
                      v-model="indicemultiplicador"
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
                      v-model="cantidadhncomprados"
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
                <v-divider class="mx-4" inset horizontal></v-divider>
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
                <v-divider class="mx-4" inset horizontal></v-divider>
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
                    <v-text-field
                      dense
                      type="number"
                      hide-details="true"
                      label="Compramos HN por"
                      placeholder="Compramos HN por"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12">
                    <v-text-field
                      dense
                      type="number"
                      hide-details="true"
                      label="Valor Unitario de Planes"
                      placeholder="Valor Unitario de Planes"
                      :value="valorUnitarioPlanes"
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
                      v-model="totalplanes"
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
                      label="Totales"
                      placeholder="Totales"
                      :value="totalplanes * valorUnitarioPlanes"
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
                      :value="(totalplanes * valorUnitarioPlanes) / invinicial"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-col>
            <v-col cols="9">
              <v-row>
                <v-col>
                  <v-simple-table dense>
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
                          >USD {{getCalculoValor(invinicial, 1)}}</td>
                          <td
                            :v-model="campo2"
                          >USD {{getCalculoValorConResta(invinicial, campo1, 2)}}</td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td
                            class="bg-gray"
                            :v-model="campo3"
                          >USD {{getCalculoValorConSupuesto(campo1, 3)}}</td>
                          <td :v-model="campo4">USD {{getCalculoValorConResta(campo1, campo3, 4)}}</td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td :v-model="campo5">USD {{getCalculoValorConSupuesto(campo3, 5)}}</td>
                          <td :v-model="campo6">USD {{getCalculoValorConResta(campo3,campo5,6)}}</td>
                          <td></td>
                        </tr>
                        <tr>
                          <td>Saldo de Caja</td>
                          <td></td>
                          <td></td>
                          <td>USD {{getTotalesCampos(2, campo2, 0, 0)}}</td>
                          <td>USD {{getTotalesCampos(3, campo4, campo5, 0)}}</td>
                          <td>USD {{getTotalesCampos(4, campo6, 0, 0)}}</td>
                          <td>USD {{getTotalesPeriodos()}}</td>
                        </tr>
                        <tr>
                          <td>Cantidad HN Comprados</td>
                          <td>{{getCantidadPlanes(0, cantidadhncomprados)}}</td>
                          <td>{{getCantidadPlanes(1, campo1)}}</td>
                          <td>{{getCantidadPlanes(2, campo3)}}</td>
                          <td>{{getCantidadPlanes(3, 0)}}</td>
                          <td>{{getCantidadPlanes(4, 0)}}</td>
                          <td>{{getCantidadTotalPlanes()}}</td>
                        </tr>
                        <tr>
                          <td colspan="5"></td>
                          <td>TIR Compuesta</td>
                          <td>{{tirCompuesta}}%</td>
                        </tr>
                      </tbody>
                    </template>
                  </v-simple-table>
                </v-col>
              </v-row>
              <v-row>
                <v-col>
                  <v-simple-table dense>
                    <template v-slot:default>
                      <thead>
                        <tr>
                          <th class="text-center" colspan="5">Control</th>
                        </tr>
                      </thead>
                      <tbody text-align="center">
                        <tr>
                          <td>Cantidad de Planes</td>
                          <td>{{totalplanes}}</td>
                          <td></td>
                          <td>Cobranza Total</td>
                          <td>USD {{totPeriodos}}</td>
                        </tr>
                        <tr>
                          <td>Utilidad Marg. X HN</td>
                          <td>USD {{utilidadMarginalPorHN}}</td>
                          <td></td>
                          <td>Inversión Incial</td>
                          <td>USD {{invinicial}}</td>
                        </tr>
                        <tr>
                          <td>TOTAL</td>
                          <td>USD {{ utilidadMarginalPorHN * totalplanes}}</td>
                          <td></td>
                          <td>Resultado Neto</td>
                          <td>USD {{ totPeriodos - invinicial}}</td>
                        </tr>
                        <tr>
                          <td>Verificación</td>
                          <td>USD {{(utilidadMarginalPorHN * totalplanes) - (totPeriodos - invinicial)}}</td>
                        </tr>
                      </tbody>
                    </template>
                  </v-simple-table>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </v-form>
      </v-container>
    </v-card>
  </v-app>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  props: {
    pars: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      valid: true,
      filled: true,
      invinicial: 100000,
      multiinvinicial: 0,

      campo1: 0,
      campo2: 0,
      campo3: 0,
      campo4: 0,
      campo5: 0,
      campo6: 0,

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
      hnpromedio: 200000,
      //cantidadhncomprados: 0,
      tipocambio: 100,
      costototal: 50,
      totalplanes: 0,

      headersproy: [
        { text: "", value: "", align: "left" },
        { text: "2020", value: "Valor", align: "left" },
        { text: "2021", value: "Valor", align: "center" },
        { text: "2022", value: "Valor", align: "center" },
        { text: "2023", value: "Valor", align: "center" },
        { text: "2024", value: "Valor", align: "center" },
        { text: "Total", value: "Total", align: "center" }
      ],

      itemsproy: [
        { Nombre: "Inversion Incial", Valor: "USD 100,000" },
        { Nombre: "Multiplico Inversion Inicial", Valor: "USD 200,000" },
        { Nombre: "Primera Reinversion", Valor: "USD 100,000" },
        { Nombre: "Segunda Reinversion", Valor: "USD 100,000" }
      ]
    };
  },

  created() {
    //
  },

  methods: {
    ...mapActions({}),

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

    getCantidadTotalPlanes() {
      this.totalplanes = Math.round(
        this.planesAnio0 +
          this.planesAnio1 +
          this.planesAnio2 +
          this.planesAnio3 +
          this.planesAnio4
      );
      return this.totalplanes;
    },

    getCalculoValor(inversion, nroCampo) {
      var valor = Math.round(
        inversion * this.indicemultiplicador * (this.segundosupuesto / 100)
      );
      switch (nroCampo) {
        case 1:
          this.campo1 = valor;
          this.primerreinv = valor;
          break;
        case 2:
          this.campo2 = valor;
          break;
        case 3:
          this.campo3 = valor;
          this.segundareinv = valor;
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
      var valorCResta = Math.round(
        inversion * this.indicemultiplicador - resta
      );
      switch (nroCampo) {
        case 1:
          this.campo1 = valorCResta;
          break;
        case 2:
          this.campo2 = valorCResta;
          break;
        case 3:
          this.campo3 = valorCResta;
          this.segundareinv = valor;
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
      var valorCSupuesto = Math.round(
        inversion * this.indicemultiplicador * (this.segundosupuesto / 100)
      );
      switch (nroCampo) {
        case 1:
          this.campo1 = valorCSupuesto;
          break;
        case 2:
          this.campo2 = valorCSupuesto;
        case 3:
          this.campo3 = valorCSupuesto;
          this.segundareinv = valorCSupuesto;
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
    }
  },

  computed: {
    ...mapState("haberneto", ["marcas"]),

    tirCompuesta() {
      if (this.invinicial > 0) {
        return Math.round(((this.totPeriodos / this.invinicial - 1) / 5) * 100);
      }
      return 0;
    },

    valorUnitarioPlanes() {
      if (this.tipocambio > 0) {
        return Math.round(this.hnpromedio / this.tipocambio);
      }
      return 0;
    },

    utilidadMarginalPorHN() {
      if (this.tipocambio > 0) {
        return Math.round(
          (this.hnpromedio * (this.costototal / 100)) / this.tipocambio
        );
      }
      return 0;
    },

    cantidadhncomprados() {
      var canthnc = 0;
      var numerador = this.invinicial * this.tipocambio;
      if (this.hnpromedio > 0 && this.costototal > 0) {
        var denominador = this.hnpromedio * (this.costototal / 100);
        canthnc = numerador / denominador;
      }

      return Math.round(canthnc);
    },

    indicemultiplicador() {
      var num = 100 / this.costototal;
      return num.toFixed(2);
    },

    multInverInicial() {
      return Math.round(
        parseInt(this.invinicial) * this.indicemultiplicador,
        0
      );
    },

    tercerSupuestoCobro() {
      return Math.round(100 - this.segundosupuesto, 0);
    }
  }
};
</script>

<style  scoped>
.fullw {
  width: 100%;
}

.centered {
  margin: 50px 20px;
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