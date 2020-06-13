<template>
  <div class="contenedor">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <FormModeloHN
              :pars="{
                titleform: 'Modelo Haberes Netos',
                routeapi: 'modelohn',
                itemkey: 'Codigo',
                module: 'modelohn'
              }"
            ></FormModeloHN>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FormModeloHN from "@/components/propios/FormModeloHN.vue";

export default {
  name: "modelohn",
  components: {
    FormModeloHN
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
