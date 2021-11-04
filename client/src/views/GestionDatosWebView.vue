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
          <v-btn class="ma-2" color="primary" outlined text @click="this.getDatos()">
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
                :items="this.itemsPendientes"
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
                :items="this.itemsVerificados"
              ></GridFormWebComponent>
            </v-card>
          </v-tab-item>
          <v-tab-item>
            <v-card class="mt-5">
              <GridFormComponent
              :pars="{
                titleform: 'Gestión de Datos',
                routeapi: 'gestiondatos',
                itemkey: 'Codigo',
                module: 'gestiondatos',
                //items: this.items,
                origen: 'gestiondatos',
                showCombo: true,
                loading: 'loadingDatos',
              }"
              :headers="headers_datos"
              :show_title="false"
              :from_leads="true"
              :search_str="this.search"
            ></GridFormComponent>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-card>
    </div>
  </v-app>
</template>

<script>
import GridFormWebComponent from "@/components/propios/GridFormWebComponent.vue";
import GridFormComponent from "@/components/propios/GridFormComponent.vue";
import { mapState, mapActions } from "vuex";

export default {
  name: "gestiondatosweb",
  components: {
    GridFormWebComponent,
    GridFormComponent
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
        
        {
          Codigo: 3,
          Nombre: "Datos en Gestión",
        },
        
      ],

      headers_datos: [
                {
                  text: '',
                  value: 'Star',
                  align: 'center',
                  sorteable: true,
                },
                {
                  text: 'Grupo Orden',
                  value: 'Grupo',
                  align: 'center',
                  sorteable: true,
                  filterable: true,
                },

                {
                  text: 'Concesionario',
                  value: 'Concesionario',
                  align: 'center',
                  filterable: false,
                },

                {
                  text: 'Haber Neto',
                  value: 'HaberNeto',
                  align: 'center',
                  filterable: false,
                },
                {
                  text: 'Apellido y Nombre',
                  value: 'ApeNom',
                  align: 'left',
                  sorteable: true,
                  filterable: true,
                },

                {
                  text: 'Avance',
                  value: 'Avance',
                  align: 'center',
                  filterable: false,
                },

                {
                  text: 'Estado',
                  value: 'NomEstado',
                  align: 'left',
                  filterable: false,
                },
                {
                  text: 'Motivo',
                  value: 'Motivo',
                  align: 'left',
                  filterable: false,
                },
                {
                  text: 'Fecha Compra',
                  value: 'FechaCompra',
                  align: 'center',
                  filterable: false,
                },
                {
                  text: 'Precio Compra',
                  value: 'PrecioCompra',
                  align: 'center',
                  filterable: false,
                },
                {
                  text: 'Precio Max Compra',
                  value: 'PrecioMaximoCompra',
                  align: 'center',
                  filterable: false,
                },
                {
                  text: 'Fecha Asignación',
                  value: 'FechaUltimaAsignacion',
                  align: 'center',
                  filterable: false,
                },
                {
                  text: 'Fecha Ult. Obs',
                  value: 'FechaUltObs',
                  align: 'center',
                  filterable: false,
                },
                { text: '', value: 'VerDatos', align: 'center', width: '1%' },
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
    ...mapState("gestiondatosweb", ["itemsPendientes", "itemsVerificados"]),
    ...mapState("gestiondatos", ["items"]),
  },

  created() {
    //this.getData();
    this.getDatosPendientes();
    this.getDatosVerificados();
    this.getDatosLeads();
    //this.getData();
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
      getDatosPendientes: "gestiondatosweb/getDatosPendientes",
      getDatosVerificados: "gestiondatosweb/getDatosVerificados",
      getDatosLeads: "gestiondatos/getDatosLeads",
    }),

    getDatos(){
      //this.getData();
      this.getDatosPendientes();
      this.getDatosVerificados();
      this.getDatosLeads();
    }
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
