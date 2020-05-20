<template>
  <v-app>
    <v-data-table
      :dense="dense"
      :headers="headers"
      :items="items"
      :search="search"
      item-key="itemkey"
      class="elevation-1"
      :loading="loading"
      :loading-text="loadingtext"
    ></v-data-table>
  </v-app>
</template>

<script>
export default {
  name: "gridcomponent",
  props: {
    headers: {
      type: Array,
      default: []
    },
    items: {
      type: Array,
      default: []
    },
    itemkey: {
      type: String,
      default: ""
    },
    search: {
      type: String,
      default: ""
    },
    loading: {
      type: Boolean,
      default: false
    },
    loadingtext: {
      type: String,
      default: "Cargando Datos... Aguarde..."
    },
    dense: {
      type: Boolean,
      default: false
    }
  }
};
</script>

<style lang="scss" scoped>
</style>