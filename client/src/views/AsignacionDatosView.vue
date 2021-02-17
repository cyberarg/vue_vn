<template>
  <v-app class="contenedor">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <GridFormAsignacionComponent
              :pars="{
                titleform: 'Asignación de Datos',
                routeapi: 'asignaciondatos',
                itemkey: 'ID',
                module: 'asignaciondatos',
              }"
              :headers="[
                {
                  text: 'Supervisor',
                  value: 'NomSup',
                  align: 'left',
                  filterable: false,
                },
                {
                  text: 'Grupo Orden',
                  value: 'GrupoOrden',
                  align: 'center',
                  filterable: true,
                },
                {
                  text: 'Solicitud',
                  value: 'Solicitud',
                  align: 'center',
                  width: '1%',
                  filterable: false,
                },
                {
                  text: 'Haber Neto',
                  value: 'HaberNeto',
                  align: 'center',
                  width: '1%',
                  filterable: false,
                },
                {
                  text: 'Nombre y Apellido',
                  value: 'ApeNom',
                  align: 'left',
                  filterable: false,
                },
                { text: 'ID', value: 'ID', width: '1%' },

                {
                  text: 'Fecha Vto Cuota 2',
                  value: 'FechaVtoCuota2',
                  align: 'center',
                  filterable: false,
                },
                {
                  text: 'Avance',
                  value: 'Avance',
                  align: 'center',
                  width: '1%',
                  filterable: false,
                },
                {
                  text: 'Avance Calculado',
                  value: 'AvanceCalculado',
                  align: 'center',
                  width: '1%',
                  filterable: false,
                },
                {
                  text: 'Oficial',
                  value: 'NomOficial',
                  align: 'center',
                  filterable: true,
                },
                {
                  text: 'Estado',
                  value: 'NomEstado',
                  align: 'left',
                  filterable: false,
                },
                {
                  text: 'Precio Max Compra',
                  value: 'PrecioMaximoCompra',
                  align: 'center',
                  width: '1%',
                  filterable: false,
                },
                {
                  text: 'Fecha Ult. Obs',
                  value: 'FechaUltObs',
                  align: 'center',
                  width: '1%',
                  filterable: false,
                },
                { text: '', value: 'VerDatos', align: 'center', width: '1%' },
              ]"
            ></GridFormAsignacionComponent>
          </div>
        </div>
      </div>
    </div>
  </v-app>
</template>

<script>
import GridFormAsignacionComponent from "@/components/propios/GridFormAsignacionComponent.vue";

export default {
  name: "asignaciondatos",
  components: {
    GridFormAsignacionComponent,
    //GridFormCrud
  },
};
</script>
<style scoped>
.contenedor {
  width: 100%;
}
</style>
