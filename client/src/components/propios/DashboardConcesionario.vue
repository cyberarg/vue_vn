<template>
  <v-app class="contenedor">
    <div class="dashboard">
      <v-row justify="center">
        <v-col cols="12" sm="6" lg="4">
          <router-link to="gestiondatos">
            <v-card hover elevation-2 color="blue-grey lighten-4">
              <v-list-item three-line>
                <v-list-item-content>
                  <v-list-item-title class="headline mb-1">Gestión de Datos</v-list-item-title>
                </v-list-item-content>

                <v-icon :size="sizeicon" color="blue-grey darken-1">mdi-file-document-edit-outline</v-icon>
              </v-list-item>
            </v-card>
          </router-link>
        </v-col>
        <v-col cols="12" sm="6" lg="4">
          <router-link to="haberesnetos">
            <v-card hover elevation-2 color="blue-grey lighten-3">
              <v-list-item three-line>
                <v-list-item-content>
                  <v-list-item-title class="headline mb-1">Haberes Netos</v-list-item-title>
                </v-list-item-content>

                <v-icon :size="sizeicon" color="blue-grey darken-3">mdi-briefcase-check</v-icon>
              </v-list-item>
            </v-card>
          </router-link>
        </v-col>
      </v-row>

      <v-row justify="center">
        <v-col cols="12" sm="6" lg="4">
          <router-link to="estadogestion">
            <v-card hover elevation-2 color="blue-grey lighten-4">
              <v-list-item three-line>
                <v-list-item-content>
                  <v-list-item-title class="headline mb-1">Estado de la Gestión</v-list-item-title>
                  <!--<v-list-item-subtitle>Asignados: 35 | Sin Gestionar: 22</v-list-item-subtitle>-->
                </v-list-item-content>
                <v-icon :size="sizeicon" color="blue-grey darken-1">mdi-file-table-outline</v-icon>
              </v-list-item>
            </v-card>
          </router-link>
        </v-col>
        <v-col cols="12" sm="6" lg="4">
          <router-link to="reportecompras">
            <v-card hover elevation-2 color="blue-grey lighten-3">
              <v-list-item three-line>
                <v-list-item-content>
                  <v-list-item-title class="headline mb-1">Reporte Cartera</v-list-item-title>
                </v-list-item-content>
                <v-icon :size="sizeicon" color="blue-grey darken-3">mdi-file-chart</v-icon>
              </v-list-item>
            </v-card>
          </router-link>
        </v-col>
      </v-row>
    </div>
  </v-app>
</template>

<script>
//import BaseMaterialStatsCard from "@/components/base/MaterialCard";
import { mapActions, mapState } from "vuex";

export default {
  name: "dashboard",
  data() {
    return {
      sizeicon: 64,
    };
  },
  components: {
    //BaseMaterialStatsCard
  },
  created() {
    //this.getCotizacionesDolar();
  },

  computed: {
    ...mapState("auth", [
      "login",
      "user",
      "esConcesionario",
      "esVinculo",
      "codigoConcesionario",
    ]),
  },

  methods: {
    ...mapActions({ getCotizacionesDolar: "cotizaciondolar/getCotizaciones" }),
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
  margin-top: 50px;
}
</style>
