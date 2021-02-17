<template>
  <v-app class="contenedor">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <GridDialogComponent
              :pars="{
                titleform: 'Oficiales',
                routeapi: 'oficiales',
                itemkey: 'Codigo',
                module: 'oficiales'
              }"
              :headers="[
                {
                  text: 'Codigo',
                  align: 'start',
                  value: 'Codigo'
                },
                { text: 'Nombre', value: 'Nombre' },
                { text: 'Usuario', value: 'login' },
                { text: 'Supervisor', value: 'NomSup' },
                { text: 'Acciones', value: 'actions', sortable: false }
              ]"
            ></GridDialogComponent>
          </div>
        </div>
      </div>
    </div>
  </v-app>
</template>

<script>
//import GridFormCrud from "@/components/propios/GridFormCrud.vue";
import GridDialogComponent from "@/components/propios/GridDialogComponent.vue";

export default {
  name: "oficiales",
  components: {
    GridDialogComponent
    //GridFormCrud
  }
};
</script>

<style scoped>
.contenedor {
  width: 100%;
  background-color: #eeeeee;
}
</style>
