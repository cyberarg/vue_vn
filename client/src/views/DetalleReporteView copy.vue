<template>
  <div class="contenedor" tabindex="0" @keyup.esc="goBack">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <GridFormComponent
              :pars="{
                titleform: this.title,
                routeapi: 'gestiondatos',
                items: this.items_resumen_filtrado,
                itemkey: 'Codigo',
                module: 'gestiondatos'
              }"
              :headers="[
                {
                  text: 'ID',
                  align: 'center',
                  value: 'ID'
                },
                { text: 'Grupo Orden', value: 'GrupoOrden', align: 'center' },
                { text: 'Solicitud', value: 'Solicitud', align: 'center' },
                { text: 'Haber Neto', value: 'HaberNeto', align: 'center' },
                {
                  text: 'Nombre y Apellido',
                  value: 'ApeNom',
                  align: 'center'
                },
                { text: 'Supervisor', value: 'NomSup', align: 'center' },
                { text: 'Domicilio', value: 'Domicilio', align: 'center' },
                { text: 'Teléfono 1', value: 'Telefono1', align: 'center' },
                { text: 'Teléfono 2', value: 'Telefono2', align: 'center' },
                { text: 'Email 1', value: 'Email1', align: 'center' },
                { text: 'Cuotas PG', value: 'CPG', align: 'center' },
                { text: 'Cutas AD', value: 'CAD', align: 'center' },
                { text: 'Avance', value: 'Avance', align: 'center' },
                { text: 'Estado', value: 'NomEstado', align: 'center' },
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
                { text: 'Motivo', value: 'Motivo', align: 'center' },
                {
                  text: 'Fecha Ult. Obs',
                  value: 'FechaUltObs',
                  align: 'center'
                },
                { text: '', value: 'VerDatos', align: 'center' }
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
import moment from "moment";
import { mapState } from "vuex";
import closeMixin from "@/assets/js/close.js";

export default {
  name: "detallereporte",
  components: {
    GridFormComponent
    //GridFormCrud
  },

  mixins: [closeMixin],

  props: ["title", "volverARuta"],

  computed: {
    ...mapState("reporteacompras", ["items_resumen_filtrado"])
  },
  methods: {
    goBack() {
      console.log(this.volverARuta);
      if (typeof this.volverARuta !== "undefined") {
        this.$router.push(this.volverARuta);
      }
      this.volverARuta = undefined;
    }
  }
};
</script>
<style scoped>
.contenedor {
  width: 100%;
}
</style>
