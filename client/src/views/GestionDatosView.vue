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
                origen: 'gestiondatos'
              }"
              :headers="[
                {
                  text: 'Grupo Orden',
                  value: 'GrupoOrden',
                  align: 'center'
                },

                {
                  text: 'Haber Neto',
                  value: 'HaberNeto',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Nombre y Apellido',
                  value: 'ApeNom',
                  align: 'left'
                },

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
                {
                  text: 'Avance',
                  value: 'Avance',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Estado',
                  value: 'NomEstado',
                  align: 'left'
                },
                {
                  text: 'Fecha Compra',
                  value: 'FechaCompra',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Precio Compra',
                  value: 'PrecioCompra',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Precio Max Compra',
                  value: 'PrecioMaximoCompra',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Motivo',
                  value: 'Motivo',
                  align: 'left'
                },
                {
                  text: 'Fecha Ult. Obs',
                  value: 'FechaUltObs',
                  align: 'center',
                  width: '1%'
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
