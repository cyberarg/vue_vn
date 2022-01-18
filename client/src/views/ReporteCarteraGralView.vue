<template>
  <v-app class="fullw">
    <GridCarteraGral :detalle_ces="true"></GridCarteraGral>
  </v-app>
</template>



<script>
import GridCarteraGral from "@/components/propios/GridCarteraGral";

import moment from "moment";
import { mapState, mapActions } from "vuex";

export default {
  name: "reportecarteragral",
  components: {
    GridCarteraGral,
  
  },
  data() {
    return {

 
    };
  },

  created() {
    this.getReport()
  },

  computed: {
    //...mapState("tablerocontrol", ["items", "loading", "datos"]),
  },

  methods: {
    ...mapActions({
      getReporte: "tablerocontrol/getCarteraGral",
    }),

    getReport() {
        let pars = {
            detalleCEs: 1
        };
        this.getReporte(pars);
    },

  
  },
};
</script>

<style scoped>
.contenedor {
  width: 100%;
}

.padded {
  padding-left: 20px;
  padding-right: 20px;
}

.fullw {
  width: 100%;
  height: 100%;
}
</style>
