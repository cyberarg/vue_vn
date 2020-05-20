<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <grid-form-component
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
                { text: 'Usuario', value: 'Login' },
                { text: 'Tipo Haber Neto', value: 'HNMayor40' },
                { text: 'Supervisor', value: 'NomSup' }
              ]"
            ></grid-form-component>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import GridFormComponent from "@/components/propios/GridFormComponent.vue";

export default {
  name: "oficiales",
  components: {
    GridFormComponent
  }
};
</script>
