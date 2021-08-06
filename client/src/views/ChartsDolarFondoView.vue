<template>
   <div class="padded">
      <v-row>
        <v-col cols="6" sm="12" lg="6" md="6">
            <ChartDolarFondoComponent></ChartDolarFondoComponent>
        </v-col>
        <v-col cols="6" sm="12" lg="6" md="6">
            <ChartDolarFechasFondoComponent :semestre="1"></ChartDolarFechasFondoComponent>
        </v-col>
      </v-row>
  
    </div>
</template>

<script>
import ChartDolarFondoComponent from "@/components/propios/ChartDolarFondoComponent";
import ChartDolarFechasFondoComponent from "@/components/propios/ChartDolarFechasFondoComponent";
import { mapState, mapActions } from "vuex";
import moment from "moment";

export default {

  name: "chartsdolarfondo",
  components: {
    ChartDolarFondoComponent,
    ChartDolarFechasFondoComponent
  },

  data() {
    return {
      selectAnio: moment().format('YYYY'),
    
    };
  },

  watch: {
   //
  },

  computed: {
  //
  },

  mounted() {
    //
  },
  
  methods: {
    //
  },
};
</script>

<style scoped>
.padded {
  padding-left: 20px;
  padding-right: 20px;
  padding-bottom: 10px;
}

</style>
