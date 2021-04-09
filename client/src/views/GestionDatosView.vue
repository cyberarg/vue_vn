<template>
  <v-app class="contenedor">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
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
              :headers="[
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
                /*
                {
                  text: 'Cuotas PG',
                  value: 'CPG',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Cutas AD',
                  value: 'CAD',
                  align: 'center',
                  width: '1%'
                },
                */
                {
                  text: 'Avance',
                  value: 'Avance',
                  align: 'center',
                  filterable: false,
                },
                /*
                {
                  text: 'Avance Calculado',
                  value: 'AvanceCalculado',
                  align: 'center',
                  filterable: false,
                },
                */
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
              ]"
            ></GridFormComponent>
          </div>
        </div>
      </div>
    </div>
  </v-app>
</template>

<script>
import GridFormComponent from "@/components/propios/GridFormComponent.vue";
import { mapState, mapActions } from "vuex";

export default {
  name: "gestiondatos",
  components: {
    GridFormComponent,
    //GridFormCrud
  },

  data() {
    return {};
  },

  computed: {
    ...mapState("gestiondatos", ["items"]),
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
      getData: "gestiondatos/getData",
    }),
  },
};
</script>
<style scoped>
.contenedor {
  width: 100%;
}
</style>
