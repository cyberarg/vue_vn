<template>
  <div class="contenedor">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <FormCalculadoraHN
              :pars="{
                titleform: 'Calculadora Haberes Netos',
                routeapi: 'haberneto',
                itemkey: 'Codigo',
                module: 'haberneto'
              }"
            ></FormCalculadoraHN>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FormCalculadoraHN from "@/components/propios/FormCalculadoraHN.vue";

export default {
  name: "calculadorahn",
  components: {
    FormCalculadoraHN
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
