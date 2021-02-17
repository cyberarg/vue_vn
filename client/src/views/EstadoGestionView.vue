<template>
  <v-app class="contenedor">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <GridSummaryFormComponent
              :pars="{
                titleform: 'Estado de la Gestión',
                routeapi: 'estadogestion',
                itemkey: 'Codigo',
                module: 'estadogestion'
              }"
              :headers="[
                {
                  text: 'Oficial',
                  align: 'center',
                  value: 'NomOficial',
                  width:'25%'
                },
                { text: 'Asignados', value: 'Asignados', align: 'center'},
                {
                  text: 'Sin Gestionar',
                  value: 'SinGestionar',
                  align: 'center'
                },
                { text: 'Telefono Mal', value: 'TelefonoMal', align: 'center'},
                { text: 'Deje Mensaje', value: 'DejeMensaje', align: 'center'},
                { text: 'No le Interesa', value: 'NoCompra', align: 'center' },
                { text: 'En Gestión', value: 'EnGestion', align: 'center' },
                {
                  text: 'Entrevista Pendiente',
                  value: 'EntrevistaPendiente',
                  align: 'center'
                },
                { text: 'Vende Plan', value: 'VendePlan', align: 'center'},
                { text: 'Pasar a Venta', value: 'PasarAVenta', align: 'center'},
                { text: 'Plan Subite', value: 'Compro', align: 'center' }
              ]"
            ></GridSummaryFormComponent>
          </div>
        </div>
      </div>
    </div>
  </v-app>
</template>

<script>
import GridSummaryFormComponent from "@/components/propios/GridSummaryFormComponent.vue";

export default {
  name: "estadogestion",
  components: {
    GridSummaryFormComponent
    //GridFormCrud
  }
};
</script>

<style scoped>
.contenedor {
  width: 100%;
}
</style>
