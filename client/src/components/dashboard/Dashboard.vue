<template>
  <v-app class="contenedor">
    <div class="dashboard">
      <v-row>
        <v-col cols="12" sm="6" lg="4">
          <router-link to="oficiales">
            <base-material-stats-card
              color="#13599E"
              icon="mdi-account-group"
              title="Oficiales"
              value
              sub-icon
              sub-text
            />
          </router-link>
        </v-col>

        <v-col cols="12" sm="6" lg="4">
          <router-link to="estadogestion">
            <base-material-stats-card
              color="#13599E"
              icon="mdi-file-table-outline"
              title="Estado de la Gestión"
              value
              sub-icon
              sub-text
            />
          </router-link>
        </v-col>

        <v-col cols="12" sm="6" lg="4">
          <router-link to="gestiondatos">
            <base-material-stats-card
              color="#13599E"
              icon="mdi-file-document-edit-outline"
              title="Gestión de Datos"
              value
              sub-icon
              sub-text
            />
          </router-link>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" sm="6" lg="4">
          <router-link to="asignaciondatos">
            <base-material-stats-card
              color="#1667B8"
              icon="mdi-clipboard-arrow-down-outline"
              title="Asignación de Datos"
              value
              sub-icon
              sub-text
            />
          </router-link>
        </v-col>

        <v-col cols="12" sm="6" lg="4">
          <router-link to="importardatos">
            <base-material-stats-card
              color="#1667B8"
              icon="mdi-application-import"
              title="Importación de Datos"
              value="importardatos"
              sub-icon
              sub-text
            />
          </router-link>
        </v-col>
        <v-col cols="12" sm="6" lg="4">
          <router-link to="importarhn">
            <base-material-stats-card
              color="#1667B8"
              icon="mdi-file-import-outline"
              title="Importación de HN"
              value
              sub-icon
              sub-text
            />
          </router-link>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" sm="6" lg="6">
          <router-link to="reportecompras">
            <base-material-stats-card
              color="#1976D2"
              icon="mdi-file-chart"
              title="Reporte de Compras"
              value
              sub-icon
              sub-text
            />
          </router-link>
        </v-col>
        <v-col cols="12" sm="6" lg="6">
          <router-link to="reporteasignaciones">
            <base-material-stats-card
              color="#1976D2"
              icon="mdi-printer-check"
              title="Reporte de Asignaciones por Período"
              value
              sub-icon
              sub-text
            />
          </router-link>
        </v-col>
      </v-row>
      <!--
      
      <dashboard-charts />

      -->
      <router-link to="prueba">
        <dashboard-info-block />
      </router-link>
      <!--    
      <div class="row">
        <div class="flex xs12">
          <dashboard-table />
        </div>
      </div>
    
      <div class="row row-equal">
        <div class="flex xs12 lg6">
          <dashboard-tabs @submit="addAddressToMap" />
        </div>
        <div class="flex xs12 lg6">
          <dashboard-map ref="dashboardMap" />
        </div>
      </div>
      -->
    </div>
  </v-app>
</template>

<script>
//import DashboardCharts from "./DashboardCharts";
import DashboardInfoBlock from "./DashboardInfoBlock";
//import DashboardTable from "./DashboardTable";
//import DashboardTabs from "./DashboardTabs";
//import DashboardMap from "./DashboardMap";

import BaseMaterialStatsCard from "@/components/base/MaterialCard";

export default {
  name: "dashboard",
  components: {
    // DashboardCharts,
    DashboardInfoBlock,
    //DashboardTable,
    //DashboardTabs,
    //DashboardMap,
    BaseMaterialStatsCard
  },
  methods: {
    /*
    addAddressToMap({ city, country }) {
      this.$refs.dashboardMap.addAddress({ city: city.text, country });
    }
    */
  }
};
</script>

<style lang="scss">
.contenedor {
  width: 100%;
}

.row-equal {
  margin-top: 10px;
  padding-bottom: 5px;
}

.row-equal .flex {
  .va-card {
    height: 100%;
  }
}

.dashboard {
  .va-card {
    margin-bottom: 0 !important;
  }
}
</style>
