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
        <v-form class="formulario" ref="form" v-model= "valid" lazy-validation>
          <v-form-base :model="Model" :schema="Schema" @input="handleInput"/>
            <v-row>
              <v-col class="d-flex justify-start">
                <v-btn small outlined color="success"
                  :disabled="!valid"
                  @click="submit"
                >
                  <v-icon left>mdi-content-save-outline</v-icon>Aceptar
                </v-btn>
              </v-col>

              <v-col class="d-flex justify-end">
                <v-btn small outlined color="error" 
                  @click="volver"
                >
                  <v-icon left>mdi-close-circle</v-icon>Cancelar
                </v-btn>
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

      valid: false,
      disabledAceptar: true,

      listMarcas: [
        { codigo: 2, nombre: "Fiat" },
        { codigo: 5, nombre: "Volkswagen" },
        { codigo: 9, nombre: "Ford" },
        { codigo: 3, nombre: "Peugeot" },
        { codigo: 7, nombre: "Jeep" },
        { codigo: 10, nombre: "Citroen" },
      ],

      listEstadosDato: [
        { codigo: 1, nombre: "En Gestion" },
        { codigo: 2, nombre: "Avance Bajo" },
        { codigo: 3, nombre: "Cuotas Insuficientes" },
        { codigo: 4, nombre: "Llamar Mas Adelante" },
        { codigo: 5, nombre: "Pasar A Asignacion" },
      ],

      listEstadosPlan: [
        { codigo: 1, nombre: "Pago al día" },
        { codigo: 2, nombre: "Rescindido / Renunciado" },
        { codigo: 3, nombre: "Más de 3 cuotas en mora" },
        { codigo: 4, nombre: "Menos de 3 cuotas en mora" },
      ],

      nameRules: [
        v => !!v || 'Este campo es requerido',
        //v => (v && v.length > 7) || 'Esta campo debe contener al menos 8 caracteres',
      ],
      phoneRules: [
        v => !!v || 'Este campo es requerido',
      ],
      emailRules: [
        //v => !!v || 'Este campo es requerido',
        //v => /.+@.+\..+/.test(v) || 'Debe ingresar un E-mail valido',
      ],
      requiredField: [
         v => !!v || 'Este campo es requerido',
      ],

      Model: {
          form:{
            fullname: '',
            nrodocumento: '',
            telefono: '',
            email: '',
            marca: '',
            modelo: '',
            cuotas: '',
            estado: '',
            grupo: '',
            orden: '',
            avance: '',
            obs: '',
        }
      },


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

     
     validate () {
       this.$refs.form.validate()
    },

    resetValidation () {
      this.$refs.form.resetValidation()
    },


    handleInput( ev ){
    //  console.log( ev ) 
    },

    ...mapActions({
  
      grabarDatoWeb: "gestiondatosweb/grabarDatoWeb",
 
    }),


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
      this.resetValidation();
      this.clearForm();

      this.$emit("hide");
    },

    clearForm() {

    /*
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
      */

    },

    
    async submit() {

      this.$nextTick(() => {
        //NOW trigger validation
        if (this.$refs.form.validate()) {
            //do work, then...
          
            //console.log(this.Model);

            var pars = {
              FullName: this.Model.form.fullname,
              Telefono: this.Model.form.telefono,
              Email: this.Model.form.email,
              
              ModeloAhorro: this.Model.form.modelo,
              CantidadCuotas: this.Model.form.cuotas,
              EstadoPlan: this.Model.form.estado,

              CodMarca: this.Model.form.marca,
              Documento: this.Model.form.nrodocumento,
              Grupo: this.Model.form.grupo, 
              Orden: this.Model.form.orden,
              Avance: this.Model.form.avance,

              Obs: this.Model.form.obs
              
            };
      
            //console.log(pars);
            this.grabarDato(pars);

        }
      })

    },

    async grabarDato(pars){
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
      "dataStatusInsert"
    
    ]),

     Schema() { 
       return {
        form:{
          fullname: { type:'text', label:'Nombre y Apellido', autofocus: true, class:'pr-2', col: { cols:12, sm:12, md:6, lg:6, xs:12 },
          rules: this.nameRules },
          nrodocumento: { type:'number', label:'Documento', col: { cols:12, sm:12, md:6, lg:6, xs:12 } },
          telefono: { type:'text', label:'Teléfono', class:'pr-2', col: { cols:12, sm:12, md:6, lg:6, xs:12 },
          rules: this.phoneRules },
          email: { type:'email', label:'Email', col: { cols:12, sm:12, md:6, lg:6, xs:12 },
          rules: this.emailRules },
          marca: { type: 'select', label: 'Marca', itemText: 'nombre', itemValue: 'codigo', class:'pr-2', items: this.listMarcas, col: { cols:12, sm:12, md:6, lg:6, xs:12 },
          rules: this.requiredField },    
          modelo: { type:'text', label:'Modelo', col: { cols:12, sm:12, md:6, lg:6, xs:12 } },    
          cuotas: { type:'number', label:'Cantidad Cuotas', class:'pr-2', col: { cols:12, sm:12, md:6, lg:6, xs:12 } },
          estado: { type: 'select', label: 'Estado Plan', itemText: 'nombre', itemValue: 'codigo', items: this.listEstadosPlan, col: { cols:12, sm:12, md:6, lg:6, xs:12 }}, 
          grupo: { type:'number', label:'Grupo', class:'pr-2', col: { cols:12, sm:12, md:4, lg:4, xs:12 } },
          orden: { type:'number', label:'Orden', class:'pr-2', col: { cols:12, sm:12, md:4, lg:4, xs:12 } },
          avance: { type:'number', label:'Avance',  col: { cols:12, sm:12, md:4, lg:4, xs:12 } },
          obs: { type:'textarea', label:'Observaciones', noResize: true, rows:'2', rowHeight:'20', col: { cols:12, sm:12, md:12, lg:12, xs:12 } },
        }

      }
     }, 

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
