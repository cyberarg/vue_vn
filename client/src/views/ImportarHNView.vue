<template>
  <v-app class="contenedor">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <UploadExcelHN></UploadExcelHN>
          </div>
        </div>
      </div>
    </div>
  </v-app>
</template>

<script>
import UploadExcelHN from "@/components/propios/UploadExcelHN.vue";

export default {
  name: "ImportarHN",
  components: {
    UploadExcelHN
  }
};
</script>

<style scoped>
.contenedor {
  width: 100%;
}
</style>
