<template>
  <div class="contenedor">
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
                items: this.items,
                origen: 'gestiondatos',
                showCombo: true
              }"
              :headers="[
                {
                  text: 'Grupo Orden',
                  value: 'GrupoOrden',
                  align: 'center',
                  width: '5%'
                },

                {
                  text: 'Concesionario',
                  value: 'Concesionario',
                  align: 'left',
                  width: '5%'
                },

                {
                  text: 'Haber Neto',
                  value: 'HaberNeto',
                  align: 'center'
                },
                {
                  text:  'Apellido y Nombre',
                  value: 'ApeNom',
                  align: 'left'
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
                  align: 'center'
                },
                {
                  text: 'Estado',
                  value: 'NomEstado',
                  align: 'left'
                },
                {
                  text: 'Motivo',
                  value: 'Motivo',
                  align: 'left'
                },
                {
                  text: 'Fecha Compra',
                  value: 'FechaCompra',
                  align: 'center'
                },
                {
                  text: 'Precio Compra',
                  value: 'PrecioCompra',
                  align: 'center'
                },
                {
                  text: 'Precio Max Compra',
                  value: 'PrecioMaximoCompra',
                  align: 'center'
                },
                {
                  text: 'Fecha Asignación',
                  value: 'FechaUltimaAsignacion',
                  align: 'center'
                },
                {
                  text: 'Fecha Ult. Obs',
                  value: 'FechaUltObs',
                  align: 'center'
                },
                { text: '', value: 'VerDatos', align: 'center', width: '1%' }
              ]"
            ></GridFormComponent>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import GridFormComponent from "@/components/propios/GridFormComponent.vue";
import { mapState, mapActions } from "vuex";

export default {
  name: "gestiondatos",
  components: {
    GridFormComponent
    //GridFormCrud
  },

  data() {
    return {};
  },

  computed: {
    ...mapState("gestiondatos", ["items"])
  },

  created() {
    this.getData("gestiondatos");
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
      getData: "gestiondatos/getData"
    })
  }
};
</script>
<style scoped>
.contenedor {
  width: 100%;
}
</style>
