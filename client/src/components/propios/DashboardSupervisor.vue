<template>
  <v-app class="contenedor">
    <div class="dashboard">
      <v-row>
        <v-col cols="12" sm="6" lg="4">
          <router-link to="oficiales">
            <v-card hover elevation-2 color="grey lighten-2">
              <v-list-item three-line>
                <v-list-item-content>
                  <v-list-item-title class="headline mb-1">Oficiales</v-list-item-title>
                  <!--<v-list-item-subtitle>Hay 27 oficiales activos.</v-list-item-subtitle>-->
                </v-list-item-content>
                <v-icon :size="sizeicon" color="blue-grey darken-1">mdi-account-group</v-icon>
              </v-list-item>
            </v-card>
          </router-link>
        </v-col>
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

      <v-row>
        <v-col cols="12" sm="6" lg="4">
          <router-link to="importardatos">
            <v-card hover elevation-2 color="grey lighten-2">
              <v-list-item three-line>
                <v-list-item-content>
                  <v-list-item-title class="headline mb-1">Importación de Datos</v-list-item-title>
                  <!--
                  <v-list-item-subtitle>
                    Fecha Último Proceso: 23/05/2020
                    17:18
                  </v-list-item-subtitle>
                  -->
                </v-list-item-content>
                <v-icon :size="sizeicon" color="blue-grey darken-2">mdi-application-import</v-icon>
              </v-list-item>
            </v-card>
          </router-link>
        </v-col>
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

      <v-row>
        <v-col cols="12" sm="6" lg="4">
          <router-link to="importarhn">
            <v-card hover elevation-2 color="grey lighten-2">
              <v-list-item three-line>
                <v-list-item-content>
                  <v-list-item-title class="headline mb-1">Importación de HN</v-list-item-title>
                  <!--
                  <v-list-item-subtitle>
                    Fecha Último Proceso: 23/05/2020
                    17:32
                  </v-list-item-subtitle>
                  -->
                </v-list-item-content>

                <v-icon :size="sizeicon" color="blue-grey darken-2">mdi-file-import-outline</v-icon>
              </v-list-item>
            </v-card>
          </router-link>
        </v-col>
        <v-col cols="12" sm="6" lg="4">
          <router-link to="asignaciondatos">
            <v-card hover elevation-2 color="blue-grey lighten-4">
              <v-list-item three-line>
                <v-list-item-content>
                  <v-list-item-title class="headline mb-1">Asignación de Datos</v-list-item-title>
                  <!--<v-list-item-subtitle>Le asignaron 23 datos nuevos.</v-list-item-subtitle>-->
                </v-list-item-content>
                <v-icon :size="sizeicon" color="blue-grey darken-2">mdi-clipboard-arrow-down-outline</v-icon>
              </v-list-item>
            </v-card>
          </router-link>
        </v-col>
        <v-col cols="12" sm="6" lg="4">
          <router-link to="reporteasignaciones">
            <v-card hover elevation-2 color="blue-grey lighten-3">
              <v-list-item three-line>
                <v-list-item-content>
                  <v-list-item-title class="headline mb-1">Reporte Asig. por Período</v-list-item-title>
                  <v-list-item-subtitle></v-list-item-subtitle>
                </v-list-item-content>
                <v-icon :size="sizeicon" color="blue-grey darken-3">mdi-printer-check</v-icon>
              </v-list-item>
            </v-card>
          </router-link>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" xs="12" sm="6" lg="3" md="3">
            <GridControlComponent></GridControlComponent>
        </v-col>
        <v-col cols="12" xs="12" sm="6" lg="9" md="9">
            <GridCarteraGral></GridCarteraGral>
        </v-col>
      </v-row>
    </div>
  </v-app>
</template>

<script>
import GridControlComponent from "@/components/propios/GridControlComponent";
import GridCarteraGral from "@/components/propios/GridCarteraGral";
//import BaseMaterialStatsCard from "@/components/base/MaterialCard";

export default {
  name: "dashboardadmin",
  data() {
    return {
      sizeicon: 64,
    };
  },
  components: {
    GridControlComponent,
    GridCarteraGral
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
