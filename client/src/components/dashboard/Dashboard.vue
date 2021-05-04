<template>
  <v-app class="contenedor">
    <div class="dashboard">
      <template v-if="!esConcesionario && !esVinculo">
        <template v-if="perfilUsuario == 3">
          <DashboardOficiales></DashboardOficiales>
        </template>
        <template v-else-if="perfilUsuario == 4">
          <DashboardSupervisor></DashboardSupervisor>
        </template>
        <template v-else-if="perfilUsuario == 5">
          <DashboardAdmin></DashboardAdmin>
        </template>
        <template v-else-if="perfilUsuario == 8">
          <CalculadoraHNView></CalculadoraHNView>
        </template>
        <template v-else-if="perfilUsuario == 9">
          <DashboardOficialesWeb></DashboardOficialesWeb>
        </template>
        <template v-else>
          <DashboardFull></DashboardFull>
        </template>
      </template>
      <template v-else>
        <template v-if="perfilUsuario == 7">
          <GridComponentHNCobradores></GridComponentHNCobradores>
        </template>
        <template v-else>
          <DashboardConcesionario></DashboardConcesionario>
        </template>
      </template>
    </div>
  </v-app>
</template>

<script>
import DashboardAdmin from "@/components/propios/DashboardAdmin";
import DashboardFull from "@/components/propios/DashboardFull";
import DashboardSupervisor from "@/components/propios/DashboardSupervisor";
import DashboardOficiales from "@/components/propios/DashboardOficiales";
import DashboardOficialesWeb from "@/components/propios/DashboardOficialesWeb";
import DashboardConcesionario from "@/components/propios/DashboardConcesionario";
import GridComponentHNCobradores from "@/views/GridComponentHNCobradores";
import CalculadoraHNView from "@/views/CalculadoraHNView";
import { mapActions, mapState } from "vuex";

export default {
  name: "dashboard",
  data() {
    return {
      sizeicon: 64,
    };
  },
  components: {
    DashboardAdmin,
    DashboardFull,
    DashboardSupervisor,
    DashboardOficiales,
    DashboardOficialesWeb,
    DashboardConcesionario,
    GridComponentHNCobradores,
    CalculadoraHNView
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
      "perfilUsuario",
    ]),
  },

  methods: {
    /*
    ...mapActions({ getCotizacionesDolar: "cotizaciondolar/getCotizaciones" }),
    
    addAddressToMap({ city, country }) {
      this.$refs.dashboardMap.addAddress({ city: city.text, country });
    }
    */
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
