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
                module: 'gestiondatos'
              }"
              :headers="[
                {
                  text: 'Grupo Orden',
                  value: 'GrupoOrden',
                  align: 'center',
                  width: '1%'
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
/*


:headers="[
                {
                  text: 'Oficial',
                  align: 'start'
                },
                { text: 'Asignados' },
                { text: 'Sin Gestionar'},
                { text: 'Telefono Mal' },
                { text: 'Deje Mensaje' },
                { text: 'Entrevista Pendiente' },
                { text: 'En Gestión'},
                { text: 'No le Interesa' },
                { text: 'Vende Plan'},
                { text: 'Plan Subite'},
                { text: 'Pasar a Venta'},
              ]"
 

              */

//import GridFormCrud from "@/components/propios/GridFormCrud.vue";
import GridFormComponent from "@/components/propios/GridFormComponent.vue";

export default {
  name: "gestiondatos",
  components: {
    GridFormComponent
    //GridFormCrud
  }
};
</script>
<style scoped>
.contenedor {
  width: 100%;
}
</style>
