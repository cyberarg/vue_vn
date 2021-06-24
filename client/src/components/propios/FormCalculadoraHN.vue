<template>
  <div class="fullw">
    <v-card color="grey lighten-4">
      <v-card-title>
        {{ pars.titleform }}
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
      </v-card-title>


        <v-form v-model="valid">
          <v-row justify="center">
            <v-col lg="6" md="6" sm="8" xs="12">
              <v-container>
                <v-row>
                   <v-col lg="3" md="3" sm="12" xs="12">
                    <v-select
                      dense
                      :items="marcas"
                      item-text="Nombre"
                      item-value="Codigo"
                      label="Marca"
                      v-model="marcaSelect"
                      @change="loadPlanesSinModelo()"
                    ></v-select>
                  </v-col>
                  <!--
                   <v-col lg="3" md="3" sm="12" xs="12">
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
                  -->
                   <v-col lg="2" md="2" sm="12" xs="12">
                    <v-select
                      dense
                      return-object
                      :items="items_planes"
                      item-text="Nombre"
                      item-value="Codigo"
                      label="Plan"
                      :disabled="loadingPlanes"
                      v-model="planSelect"
                      @change="loadListas"
                    ></v-select>
                  </v-col>
                  <v-col lg="3" md="3" sm="12" xs="12">
                    <v-text-field
                      dense
                      label="Modelo"
                      placeholder="Modelo"
                      disabled
                      v-model="nomModelo"
                    ></v-text-field>
                  </v-col>
                   <v-col lg="2" md="2" sm="12" xs="12">
                      <v-combobox
                      dense
                      item-text="Nombre"
                      item-value="Codigo"
                      :items="items_listas"
                      label="Lista Precios"
                      v-model="listaSelect"
                      @change="setPrecio()"
                    
                    ></v-combobox>
                  </v-col>
                  <v-col lg="2" md="2" sm="12" xs="12">
                    <v-text-field
                      dense
                      label="Valor"
                      placeholder="Valor"
                      v-model="valormovilsel"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col lg="3" md="3" sm="12" xs="12">
                    <v-text-field
                      dense
                      label="Cuotas Pagas"
                      placeholder="Cuotas Pagas"
                      v-model="cpg"
                      @change="verificarCuotas('CPG')"
                    ></v-text-field>
                  </v-col>
                  <v-col lg="3" md="3" sm="12" xs="12">
                    <v-text-field
                      dense
                      label="Cuotas Adelantadas"
                      placeholder="Cuotas Adelantadas"
                      v-model="cad"
                      @change="verificarCuotas('CAD')"
                    ></v-text-field>
                  </v-col>
                  <v-col lg="2" md="2" sm="12" xs="12">
                    <v-text-field
                      dense
                      label="Descuento"
                      placeholder="Descuento"
                      v-model="descuento"
                  
                    ></v-text-field>
                  </v-col>
                  
                   <v-col lg="3" md="3" sm="12" xs="12">
                     <v-spacer></v-spacer>
                    <v-btn
                      cclass="ma-2"
                      small
                      outlined
                      @click="calcularHN"
                      :disabled="esperandoCalculo"
                    >
                      <v-icon left>mdi-calculator</v-icon>Calcular
                    </v-btn>
                  </v-col>
                </v-row>

                <v-divider horizontal></v-divider>

                <v-row justify="center">
                  <v-col lg="12" md="12" sm="12" xs="12">
                    <p class="hn">{{ netoCalculado }}</p>
                  </v-col>
                </v-row>
              </v-container>
            </v-col>
          </v-row>
        </v-form>

    </v-card>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  props: {
    pars: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      valid:true,
      filled: true,
      cpg: 0,
      cad: 0,
      nomModelo:'',
      valormovilsel:0,
      descuento:0,
      marcaSelect: null,
      modeloSelect: null,
      planSelect: null,
      listaSelect:null,
      esperandoCalculo: false,
      disabledCalcular: false,
      marcas: [
        // { Codigo: 2, Nombre: "Fiat" },
        { Codigo: 5, Nombre: "Volkswagen" },
        // { Codigo: 9, Nombre: "Ford" },
        // { Codigo: 3, Nombre: "Peugeot" },
      ],
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
    //this.loadModelos();
  },

  methods: {
    ...mapActions({
      getMarcas: "haberneto/getMarcas",
      getModelos: "haberneto/getModelos",
      getPlanes: "haberneto/getPlanes",
      getPlanesSinModel: "haberneto/getPlanesSinModelo",
      getListas: "haberneto/getListas",
      //getCalculoHN: "haberneto/getCalculoHN",
      getCalculoHN: "haberneto/getCalculoHNGuido",
      
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
        modelo: this.modeloSelect,
      };

      await this.getPlanes(p);
    },

     async loadPlanesSinModelo() {
      this.esperandoCalculo = false;
      var p = {
        marca: this.marcaSelect,
        //modelo: this.modeloSelect,
      };

      await this.getPlanesSinModel(p);
    },

    async loadListas(value) {
      console.log(value);
      this.nomModelo = value.NomModelo;
      this.modeloSelect = value.CodModelo;

      this.esperandoCalculo = false;
      var p = {
        marca: this.marcaSelect,
        modelo: this.modeloSelect,
      };

      await this.getListas(p);
      
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

    setPrecio(){
      if (typeof this.listaSelect !== "undefined"){
        console.log(this.listaSelect);
        this.valormovilsel = this.listaSelect.Precio;
      }
     
    },

    calcularHN() {
      this.esperandoCalculo = true;
      var params = {
        marca: this.marcaSelect,
        plan: this.planSelect.Codigo,
        cpagas: this.cpg,
        cadel: this.cad,
        valormovil: this.valormovilsel,
        descuento: this.descuento
      };

      //console.log(params);
      this.getCalculoHN(params);
    },
  },

  computed: {
    ...mapState("haberneto", [
      //"marcas",
      "items_modelos",
      "items_planes",
      "items_listas",
      "valormovil",
      "hnreal",
      "hnformula",
      "loading",
      "loadingModelos",
      "loadingPlanes",
      "loadingListas",
      "loadingCalculadora",
    ]),

    setValorMovil(){
        //
    },

    

    netoCalculado() {
      if (!this.esperandoCalculo) {
        return "";
      } else {
        if (this.loadingCalculadora) {
          return "Obteniendo Haber Neto...";
        } else {
          this.esperandoCalculo = false;
          if (this.hnreal > 0) {
            return (
              "Haber Neto: $" + this.$options.filters.numFormat(this.hnreal)
            );
          }
        }
      }
    },
  },
};
</script>

<style scoped>
.fullw {
  width: 100%;
}

.centered {
  margin: 50px 10px;
}

.hn {
  font-size: 26px;
  font-weight: bolder;
  text-align: center;
}
</style>
