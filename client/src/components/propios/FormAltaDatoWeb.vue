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
        <v-form class="formulario">
          <v-form-base :model="Model" :schema="Schema" @input="handleInput"/>
          <v-btn
              small
              outlined
              :disabled="disabledAceptar"
              color="success"
              @click="grabarDato"
            >
              <v-icon left>mdi-content-save-outline</v-icon>Aceptar
            </v-btn>
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
import VFormBase from 'vuetify-form-base'

export default {
  components:{ VFormBase },
  props: {
    dato: {
      type: Object,
      required: false,
    },
  },
  data() {
    return {
      Model: {
          form:{
          fullname: '',
          nrodocumento: '',
          telefono: '',
          email: '',
          marca: '',
          modelo: '',
          cuotas: '',
          estdo: '',
          grupo: '',
          orden: '',
          avance: '',
          obs: '',
        }
      },

      Schema: {
        form:{
          fullname: { type:'text', label:'Nombre y Apellido', class:'pr-2', col: { cols:12, sm:12, md:6, lg:6, xs:12 }},
          nrodocumento: { type:'number', label:'Documento', col: { cols:12, sm:12, md:6, lg:6, xs:12 } },
          telefono: { type:'text', label:'Teléfono', class:'pr-2', col: { cols:12, sm:12, md:6, lg:6, xs:12 } },
          email: { type:'email', label:'Email', col: { cols:12, sm:12, md:6, lg:6, xs:12 } },
          marca: { type: 'combobox', label: 'MarcaC', returnObject: false, itemText: 'codigo', itemValue: 'nombre', class:'pr-2', items: this.listMarcas, col: { cols:12, sm:12, md:6, lg:6, xs:12 }},    
          modelo: { type:'text', label:'Modelo', col: { cols:12, sm:12, md:6, lg:6, xs:12 } },    
          cuotas: { type:'number', label:'Cantidad Cuotas', class:'pr-2', col: { cols:12, sm:12, md:6, lg:6, xs:12 } },
          estado: { type: 'combobox', label: 'Estado Plan',  items: this.listMarcas, col: { cols:12, sm:12, md:6, lg:6, xs:12 }},  
          grupo: { type:'number', label:'Grupo', class:'pr-2', col: { cols:12, sm:12, md:4, lg:4, xs:12 } },
          orden: { type:'number', label:'Orden', class:'pr-2', col: { cols:12, sm:12, md:4, lg:4, xs:12 } },
          avance: { type:'number', label:'Avance',  col: { cols:12, sm:12, md:4, lg:4, xs:12 } },
          obs: { type:'textarea', label:'Observaciones', col: { cols:12, sm:12, md:12, lg:12, xs:12 } },
        }

      },  

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
        { codigo: 2, nombre: "Fiat" },
        { codigo: 5, nombre: "Volkswagen" },
        { codigo: 9, nombre: "Ford" },
        { codigo: 3, nombre: "Peugeot" },
        { codigo: 7, nombre: "Jeep" },
        { codigo: 10, nombre: "Citroen" },
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

    handleInput( ev ){
      console.log( ev ) 
    },

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

      console.log(this.Model);

/*
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
        */

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

.formulario {
  padding: 20px;
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
