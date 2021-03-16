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
                  sorteable: true,
                },
                {
                  text: 'Marca',
                  value: 'MarcaPlan',
                  align: 'center',
                  sorteable: true,
                  filterable: true,
                },
                {
                  text: 'Grupo/Orden',
                  value: 'GrupoOrden',
                  align: 'center',
                  sorteable: true,
                  filterable: true,
                },

                {
                  text: 'Apellido y Nombre',
                  value: 'FullName',
                  align: 'left',
                  sorteable: true,
                  filterable: true,
                },
                
                {
                  text: 'Cuotas Lead',
                  value: 'CantidadCuotas',
                  align: 'center',
                  width: '1%'
                },

                {
                  text: 'Estado Lead',
                  value: 'EstadoPlan',
                  align: 'center',
                  width: '1%'
                },
                /*
                {
                  text: 'Cutas AD',
                  value: 'CAD',
                  align: 'center',
                  width: '1%'
                },
                */
               
                {
                  text: 'Estado',
                  value: 'NomEstado',
                  align: 'left',
                  filterable: false,
                },
               
        
                {
                  text: 'Fecha Lead',
                  value: 'FechaLead',
                  align: 'center',
                  filterable: false,
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
