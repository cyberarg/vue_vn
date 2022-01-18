<template>
  <div class="contenedor">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <FormCompraHN
              :pars="{
                titleform: 'Compra Haber Neto',
                routeapi: 'haberneto',
                itemkey: 'Codigo',
                module: 'haberneto'
              }"
            ></FormCompraHN>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FormCompraHN from "@/components/propios/FormCompraHN.vue";

export default {
  name: "comprahn",
  components: {
    FormCompraHN
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
