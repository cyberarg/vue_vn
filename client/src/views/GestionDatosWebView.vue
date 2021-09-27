<template>
  <v-app class="contenedor">
    <div>
      <v-card color="grey lighten-4" class="padded-card">
        <v-card-title>
          Gestión de Datos WEB
          <v-divider class="mx-4" inset vertical></v-divider>

          <v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Buscar"
            single-line
            hide-details
          ></v-text-field>
          <v-spacer></v-spacer>
          <v-btn class="ma-2" color="primary" outlined text >
            <v-icon left>mdi-refresh</v-icon>Actualizar
          </v-btn>
        </v-card-title>

        <v-tabs v-model="tab" class="elevation-2" grow>
          <v-tabs-slider></v-tabs-slider>

          <v-tab
            v-for="tab in tabitems"
            :key="tab.Codigo"
            :value="'tab-' + tab.Codigo"
          >
            {{ tab.Nombre }}
          </v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab" >
          <v-tab-item color="grey lighten-4">
            <v-card class="mt-5">
              <GridFormWebComponent
                :pars="{
                  titleform: 'Gestión de Datos WEB',
                }"
                :search="this.search"
                grid="Pendientes"
              ></GridFormWebComponent>
            </v-card>
          </v-tab-item>
          <v-tab-item>
            <v-card class="mt-5">
              <GridFormWebComponent
                :pars="{
                  titleform: '',
                }"
                :search="this.search"
                grid="Verificados"
              ></GridFormWebComponent>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-card>
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
    return {
      tab: null,
      search: "",
      tabitems: [
        {
          Codigo: 1,
          Nombre: "Leads Pendientes",
        },
        {
          Codigo: 2,
          Nombre: "Leads Verificados",
        },
        /*
        {
          Codigo: 3,
          Nombre: "Análisis de la Gestión",
        },
        */
      ],

      
    };
  },

  watch: {

    tab(newValue){
      console.log('Cambio de Tab:');
      console.log(newValue);

      if (newValue == 4){
        //
      }else{
        //
      }
    },
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

.padded-card {
  padding-left: 5px;
  padding-right: 5px;
}

</style>
