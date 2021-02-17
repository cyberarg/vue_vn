<template>
  <v-app class="contenedor">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <UploadExcel></UploadExcel>
          </div>
        </div>
      </div>
    </div>
  </v-app>
</template>

<script>
import UploadExcel from "@/components/propios/UploadExcel.vue";

export default {
  name: "ImportarDatos",
  components: {
    UploadExcel
  }
};
</script>

<style scoped>
.contenedor {
  width: 100%;
}
</style>
