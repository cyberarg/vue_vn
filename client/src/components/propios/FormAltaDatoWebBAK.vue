<template>
  <div>
    <v-card color="grey lighten-4" ma-0>
      <v-card-title
        class="headline grey lighten-2"
        justify="center"
        primary-title
      >
        Alta Dato WEB
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
      </v-card-title>

       <div class="container">
        <v-form v-model="valid" ref="form">
          <v-row justify="center">
            <v-col lg="10" md="10" sm="10" xs="10">
              <div class="contenedor">
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Nombre y Apellido"
                      placeholder="Nombre y Apellido"
                      :value="this.item.FullName"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Documento"
                      placeholder="Documento"
                      v-model="this.item.Documento" 
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
                      v-model="this.item.Telefono" 
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Email"
                      placeholder="Email"
                      v-model="this.item.Email" 
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
                    <v-select
                        dense
                        class="fillable"
                        :items="listMarcas"
                        item-text="Nombre"
                        item-value="Codigo"
                        label="Marca"
                        v-model="this.item.Marca" 
                        @input="setMarca"
                        @change="changeMarca"
                      ></v-select>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Modelo Plan"
                      placeholder="Modelo Plan"
                      :readonly="disabled"
                      class="importantDisabled"
                      :filled="filled"
                      v-model="this.item.ModeloAhorro" 
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
                      v-model="this.item.CantidadCuotas" 
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                  <v-select
                          dense
                          class="fillable"
                          :items="estados"
                          item-text="Nombre"
                          item-value="Codigo"
                          label="Estado Plan"
                          v-model="this.item.EstadoPlan"
                          @input="setEstado"
                          @change="changeEstado"
                        ></v-select>
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
                        v-model="this.item.Grupo"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="4" md="4">
                      <v-text-field
                        dense
                        class="importantDisabled"
                        label="Orden"
                        placeholder="Orden"
                        :filled="filled"
                        v-model="this.item.Orden"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="4" md="4">
                      <v-text-field
                        dense

                        class="importantDisabled"
                        label="Avance"
                        placeholder="Avance"
                        :filled="filled"
                        v-model="this.item.Avance"
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
                          @click="grabarDato"
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

export default {
  props: {
    dato: {
      type: Object,
      required: false,
    },
  },
  data() {
    return {

      item: {
        FullName: null,
        Documento: null,
        Telefono: null,
        Email: null,
        Marca:{
          Codigo: null,
          Nombre: null,
        },
        CodMarca: null,
        NomMarca: null,
        ModeloAhorro: null,
        CantidadCuotas: null,
        Grupo: null,
        Orden: null,
        Avance: null,
        EstadoPlan: {
          Codigo: null,
          Nombre: null,
        },
      
      },

      listMarcas: [
        { Codigo: 2, Nombre: "Fiat" },
        { Codigo: 5, Nombre: "Volkswagen" },
        { Codigo: 9, Nombre: "Ford" },
        { Codigo: 3, Nombre: "Peugeot" },
        { Codigo: 7, Nombre: "Jeep" },
        { Codigo: 10, Nombre: "Citroen" },
      ],
    };
  },

  mounted() {
    //this.setDefaults();
    /*
    console.log(this.dato);
    if (this.dato.ID !== null) {
      this.setValoresProps();
    }
    */
  },

  methods: {
    ...mapActions({
  
      grabarDatoWeb: "gestiondatosweb/grabarDatoWeb",
 
    }),

     setEstado(value) {
      this.item.EstadoPlan.Codigo = value.Codigo;
      this.item.EstadoPlan.Nombre = value.Nombre;
    },


    setMarca(value){
      this.item.CodMarca = value.Codigo;
      this.item.NomMarca = value.Nombre;
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



    volver() {
      //this.$router.go(-1);
      this.clearForm();

      this.$emit("hide");
    },

    clearForm() {

      this.item.FullName = null;
      this.item.Documento = null;
      this.item.Telefono = null;
      this.item.Email = null;
      this.item.NomMarca = null;
      this.item.CodMarca = null;
      this.item.ModeloAhorro = null;
      this.item.CantidadCuotas = null;
      this.item.Grupo = null;
      this.item.Orden = null;
      this.item.Avance = null;
      this.item.EstadoPlan = null;

    },

    
    async grabarDato() {
      this.disabledAceptar = true;

        var pars = {
          FullName: this.item.FullName,
          Telefono: this.item.Telefono,
          Email: this.item.Email,
          NomMarca: this.item.NomMarca,
          ModeloAhorro: this.item.ModeloAhorro,
          CantidadCuotas: this.item.CantidadCuotas,
          EstadoPlan: this.item.EstadoPlan,

          CodMarca: this.item.CodMarca,
          Documento: this.item.Documento,
          Grupo: this.item.Grupo,
          Orden: this.item.Orden,
          Avance: this.item.Avance,
          
        };
      
        //console.log(pars);
        await this.grabarDatoWeb(pars);
        await this.showSwal();
        this.$emit("refresh");
        this.volver();

    },

    showSwal() {
      this.$swal(this.dataStatusMsgInsert, "", this.dataStatusInsert);
    },


  },

  computed: {
    ...mapState("gestiondatosweb", [
      "loadingStatusInsert",
      "dataStatusMsgInsert",
      ""
    
    ]),

    stateMsg() {
      switch (true) {
        case this.loadingStatusInsert:
          return "Guardando Nuevo Dato Web, aguarde por favor...";
          break;
      }
    },

   

    loadingData() {
      return (
        this.loadingStatusInsert
      );
    },

    ruleRequired() {
      return [(v) => !!v || "Campo Requerido."];
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
