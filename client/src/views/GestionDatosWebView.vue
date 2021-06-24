<template>
  <v-app class="contenedor">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <GridFormWebComponent
              :pars="{
                titleform: 'Gestión de Datos WEB',
              }"
              :headers="[
                {
                  text: '',
                  value: 'Star',
                  align: 'center',

                },
                {
                  text: 'Marca',
                  value: 'MarcaPlan',
                  align: 'center',

                },
                
                
                {
                  text: 'Grupo',
                  value: 'Grupo',
                  align: 'center',

                  width: '1%'
                },
                {
                  text: 'Orden',
                  value: 'Orden',
                  align: 'center',

                  width: '1%'
                },
                
                {
                  text: 'Apellido y Nombre',
                  value: 'FullName',
                  align: 'left',

                },

                
                
                {
                  text: 'Cuotas Lead',
                  value: 'CantidadCuotas',
                  align: 'center',
                  width: '2%'
                },

                {
                  text: 'Estado Lead',
                  value: 'EstadoPlan',
                  align: 'center',
                  width: '2%',

                },
               
                {
                  text: 'Estado',
                  value: 'CodEstado',
                  align: 'center',
      
                },
               
        
                {
                  text: 'Fecha Lead',
                  value: 'FechaLead',
                  align: 'center',

                },
                
                { text: '', value: 'VerDato', align: 'center', width: '1%' },
              ]"
            ></GridFormWebComponent>
          </div>
        </div>
      </div>
    </div>
  </v-app>
</template>

<script>
import GridFormWebComponent from "@/components/propios/GridFormWebComponent.vue";
import { mapState, mapActions } from "vuex";

export default {
  name: "gestiondatosweb",
  components: {
    GridFormWebComponent,
    //GridFormCrud
  },

  data() {
    return {};
  },

  computed: {
    ...mapState("gestiondatosweb", ["items"]),
  },

  created() {
    //this.getData("gestiondatos");
  },

  methods: {
    goBack() {
      console.log(this.volverARuta);
      if (typeof this.volverARuta !== "undefined") {
        this.$router.push(this.volverARuta);
      }
      this.volverARuta = undefined;
    },
    ...mapActions({
      getData: "gestiondatosweb/getData",
    }),
  },
};
</script>
<style scoped>
.contenedor {
  width: 100%;
}
</style>
