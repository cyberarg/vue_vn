<template>
  <v-app class="fullw">
    <v-card color="grey lighten-4">
      <v-card-title>
        {{pars.titleform}}
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
      </v-card-title>

      <v-container class="centered">
        <v-form v-model="valid">
          <v-row>
            <v-col cols="2" md="2"></v-col>
            <v-col cols="8" md="8">
              <v-container>
                <v-row>
                  <v-col cols="4" md="4">
                    <v-select
                      dense
                      :items="marcas"
                      item-text="Nombre"
                      item-value="Codigo"
                      label="Marca"
                      v-model="marcaSelect"
                      disabled
                    ></v-select>
                  </v-col>
                  <v-col cols="4" md="4">
                    <v-select
                      dense
                      :items="items_modelos"
                      item-text="Nombre"
                      item-value="Codigo"
                      label="Modelo"
                      :disabled="loadingModelos"
                      v-model="modeloSelect"
                      @change="loadPlanes"
                    ></v-select>
                  </v-col>
                  <v-col cols="4" md="4">
                    <v-select
                      dense
                      :items="items_planes"
                      item-text="Nombre"
                      item-value="Codigo"
                      label="Plan"
                      :disabled="loadingPlanes"
                      v-model="planSelect"
                    ></v-select>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="4" md="4">
                    <v-text-field
                      dense
                      label="Cuotas Pagas"
                      placeholder="Cuotas Pagas"
                      v-model="cpg"
                      @change="verificarCuotas('CPG')"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="4" md="4">
                    <v-text-field
                      dense
                      label="Cuotas Adelantadas"
                      placeholder="Cuotas Adelantadas"
                      v-model="cad"
                      @change="verificarCuotas('CAD')"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="4" md="4">
                    <v-btn cclass="ma-2" outlined @click="calcularHN">
                      <v-icon left>mdi-calculator</v-icon>Calcular
                    </v-btn>
                  </v-col>
                </v-row>

                <v-divider class="mx-1" inset horizontal></v-divider>

                <v-row>
                  <v-col cols="4" md="4"></v-col>
                  <v-col cols="4" md="4">
                    <p class="hn">{{netoCalculado}}</p>
                  </v-col>
                  <v-col cols="4" md="4"></v-col>
                </v-row>
              </v-container>
            </v-col>
            <v-col cols="2" md="2"></v-col>
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
      filled: true,
      cpg: 0,
      cad: 0,
      marcaSelect: 5,
      modeloSelect: null,
      planSelect: null,
      esperandoCalculo: false,
      marcas: [
        { Codigo: 2, Nombre: "Fiat" },
        { Codigo: 5, Nombre: "Volkswagen" }
      ]
      /*
      modelos: [
        { Codigo: 1, Nombre: "Gol" },
        { Codigo: 2, Nombre: "Fox" },
        { Codigo: 3, Nombre: "Suran" }
      ],
      planes: [
        { Codigo: "1A", Nombre: "1A" },
        { Codigo: "Y9", Nombre: "Y9" },
        { Codigo: "1Q", Nombre: "1Q" }
      ]
      */
    };
  },

  created() {
    this.loadModelos();
  },

  methods: {
    ...mapActions({
      getMarcas: "haberneto/getMarcas",
      getModelos: "haberneto/getModelos",
      getPlanes: "haberneto/getPlanes",
      getCalculoHN: "haberneto/getCalculoHN"
    }),

    async loadModelos() {
      this.esperandoCalculo = false;
      //console.log(this.marcaSelect);
      await this.getModelos(this.marcaSelect);
    },

    async loadPlanes() {
      this.esperandoCalculo = false;
      var p = {
        marca: this.marcaSelect,
        modelo: this.modeloSelect
      };

      await this.getPlanes(p);
    },

    verificarCuotas(tipo) {
      this.esperandoCalculo = false;
      if (parseInt(this.cpg) > 0 && parseInt(this.cad) > 0) {
        var suma = parseInt(this.cpg) + parseInt(this.cad);
        if (suma > 84) {
          switch (tipo) {
            case "CAD":
              this.cpg -= parseInt(suma) - 84;
              break;
            case "CPG":
              this.cad -= parseInt(suma) - 84;
              break;
          }
        }
      }
    },

    calcularHN() {
      this.esperandoCalculo = true;
      var params = {
        marca: this.marcaSelect,
        plan: this.planSelect,
        cpagas: this.cpg,
        cadel: this.cad
      };

      //console.log(params);
      this.getCalculoHN(params);
    }
  },

  computed: {
    ...mapState("haberneto", [
      "marcas",
      "items_modelos",
      "items_planes",
      "hnreal",
      "hnformula",
      "loading",
      "loadingModelos",
      "loadingPlanes"
    ]),

    netoCalculado() {
      if (!this.esperandoCalculo) {
        return "";
      } else {
        if (this.loadingCalculadora) {
          return "Obteniendo Haber Neto...";
        } else {
          if (this.hnreal > 0) {
            return (
              "Haber Neto: $" + this.$options.filters.numFormat(this.hnreal)
            );
          }
        }
      }
    }
  }
};
</script>

<style scoped>
.fullw {
  width: 100%;
}

.centered {
  margin: 50px 20px;
}

.hn {
  font-size: 26px;
  font-weight: bolder;
}
</style>