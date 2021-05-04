<template>
  <v-app class="contenedor">
    <div class="dashboard">
      <v-row justify="center">

        <v-col cols="12" sm="6" lg="4">
          <router-link to="datosweb">
            <v-card hover elevation-2 color="blue-grey lighten-4">
              <v-list-item three-line>
                <v-list-item-content>
                  <v-list-item-title class="headline mb-1">Gestión de Datos Web</v-list-item-title>
                  <v-list-item-subtitle></v-list-item-subtitle>
                </v-list-item-content>

                <v-icon :size="sizeicon" color="blue-grey darken-1">mdi-file-document-edit-outline</v-icon>
              </v-list-item>
            </v-card>
          </router-link>
        </v-col>
      </v-row>
      <!--
      <v-row justify="center">
        <v-col cols="12" sm="6" lg="4">
          <router-link to="estadogestion">
            <v-card hover elevation-2 color="blue-grey lighten-3">
              <v-list-item three-line>
                <v-list-item-content>
                  <v-list-item-title class="headline mb-1">Estado de la Gestión</v-list-item-title>
                  <v-list-item-subtitle></v-list-item-subtitle>
                </v-list-item-content>
                <v-icon :size="sizeicon" color="blue-grey darken-1">mdi-file-table-outline</v-icon>
              </v-list-item>
            </v-card>
          </router-link>
        </v-col>
      </v-row>
      <v-row justify="center">
        <v-col cols="12" sm="6" lg="4">
          <router-link to="gestioncompras">
            <v-card hover elevation-2 color="blue-grey lighten-2">
              <v-list-item three-line>
                <v-list-item-content>
                  <v-list-item-title class="headline mb-1">Gestión Compras</v-list-item-title>
                  <v-list-item-subtitle></v-list-item-subtitle>
                </v-list-item-content>

                <v-icon
                  :size="sizeicon"
                  color="blue-grey darken-3"
                >vuestic-iconset vuestic-iconset-forms</v-icon>
              </v-list-item>
            </v-card>
          </router-link>
        </v-col>

      </v-row>
      --->

    </div>
  </v-app>
</template>

<script>
import GridControlComponent from "@/components/propios/GridControlComponent";
//import BaseMaterialStatsCard from "@/components/base/MaterialCard";

export default {
  name: "dashboardoficialweb",
  data() {
    return {
      sizeicon: 64,
    };
  },
  components: {
    GridControlComponent,
  },
};
</script>

<style lang="scss">
.contenedor {
  width: 100%;
  //background-color: #eeeeee;
}

.row-equal {
  margin-top: 10px;
  padding-bottom: 5px;
}

.rounded-card {
  border-radius: 100px;
}

.row-equal .flex {
  .va-card {
    height: 100%;
  }
}

.dashboard {
  //background-color: #eeeeee;
  margin: 50px 20px;
}
</style>
