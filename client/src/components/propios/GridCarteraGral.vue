<template>
  <div class="contenedor">
    <v-data-table
      dense
      :headers="headers"
      :hide-default-header="true"
      :items="datos_cartera"
      :search="search"
      item-key="itemkey"
      class="elevation-1"
      :loading="loading_cartera"
      :loading-text="loadingtext"
      no-data-text="No hay datos disponibles"
      :hide-default-footer="true"
    >

      <template v-slot:header="{ props }">
        <thead class="v-data-table-header">
          <tr>
              <th class="text-center child-header"></th>
              <th class="text-center child-header lineH2LB" colspan="2">Avance Menor a 45</th>
              <th class="text-center child-header lineH2LB" colspan="3">Avance Entre 45 y 60</th>
              <th class="text-center child-header lineH2LB" colspan="3">Avance Mayor a 60</th>
              <th class="text-center child-header lineH2L"></th>
          </tr>
          <tr>
            <th class="text-center child-header">Marca</th>

            <th class="text-center lineH2">Cantidad</th>
            <th class="text-center lineH2">% HN Bajo</th>

            <th class="text-center lineH2">Cantidad</th>
            <th class="text-center lineH2">% HN Bajo</th>
            <th class="text-center lineH2">% Trabajados</th>


            <th class="text-center lineH2">Cantidad</th>
            <th class="text-center lineH2">% HN Bajo</th>
            <th class="text-center lineH2">% Trabajados</th>


            <th class="text-center lineH2">Total</th>
          </tr>
        </thead>
      </template>

              

      <template v-slot:item.NomMarca="{ item }">
          {{ item.NomMarca }}
      </template>

      <template v-slot:item.Menor45.Cantidad="{ item }">
          {{ item.Menor45.Cantidad }}
      </template>

      <template v-slot:item.Menor45.CantHNBajo="{ item }">
          {{ getPorcentaje(item.Menor45.Cantidad, item.Menor45.CantHNBajo) }}
      </template>

      <template v-slot:item.Entre45y60.Cantidad="{ item }">
          {{ item.Entre45y60.Cantidad }}
      </template>

      <template v-slot:item.Entre45y60.CantHNBajo="{ item }">
          {{ getPorcentaje(item.Entre45y60.Cantidad, item.Entre45y60.CantHNBajo) }}
      </template>

      <template v-slot:item.Entre45y60.CantTrabajados="{ item }">
          {{ getPorcentaje(item.Entre45y60.Cantidad, item.Entre45y60.CantTrabajados) }}
      </template>

      <template v-slot:item.Mayor60.Cantidad="{ item }">
          {{ item.Mayor60.Cantidad }}
      </template>

      <template v-slot:item.Mayor60.CantHNBajo="{ item }">
          {{ getPorcentaje(item.Mayor60.Cantidad, item.Mayor60.CantHNBajo) }}
      </template>

      <template v-slot:item.Mayor60.CantTrabajados="{ item }">
          {{ getPorcentaje(item.Mayor60.Cantidad, item.Mayor60.CantTrabajados) }}
      </template>

      <template v-slot:item.CantDatos="{ item }">
          {{ item.CantDatos }}
      </template>

      <template slot="body.append">
        <tr>
          <td class="total">Totales</td>
          <td class="totales">{{ sumFieldCantidad("Menor45") | numFormat("0,0") }}</td>
          <td></td>
          <td class="totales">{{ sumFieldCantidad("Entre45y60") | numFormat("0,0") }}</td>
          <td></td>
          <td></td>
          <td class="totales">{{ sumFieldCantidad("Mayor60") | numFormat("0,0") }}</td>
          <td></td>
          <td></td>
          <td class="totales">{{ sumFieldCantidadTotal("CantDatos") | numFormat("0,0") }}</td>
        </tr>
      </template>

      
    </v-data-table>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  name: "gridcarteragralcomponent",
  props: {
    itemkey: {
      type: String,
      default: "",
    },
    search: {
      type: String,
      default: "",
    },
    loadingtext: {
      type: String,
      default: "Cargando Datos Cartera... Aguarde...",
    },
    dense: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      headers: [
        {
          text: "Marca",
          align: "start",
          value: "NomMarca",
        },
        { 
          text: "Menor a 45", 
          value: "Menor45.Cantidad", 
          align: "center" 
        },
        { 
          text: "Porc. HN Bajo", 
          value: "Menor45.CantHNBajo",  
          align: "center" 
        },
        {
          text: "Entre 45 y 60",
          value: "Entre45y60.Cantidad",
          align: "center",
        },
        { 
          text: "Porc. HN Bajo", 
          value: "Entre45y60.CantHNBajo", 
          align: "center" 
        },
        { 
          text: "Porc. Trabajados", 
          value: "Entre45y60.CantTrabajados", 
          align: "center" 
        },
        {
          text: "Mayor a 60",
          value: "Mayor60.Cantidad",
          align: "center",
        },

        { 
          text: "Porc. HN Bajo", 
          value: "Mayor60.CantHNBajo", 
          align: "center" 
        },
        { 
          text: "Porc. Trabajados", 
          value: "Mayor60.CantTrabajados", 
          align: "center" 
        },
        
        {
          text: "Total",
          value: "CantDatos",
          align: "center",
        },
      ],
    };
  },

  created() {
    this.getDatosCartera();
  },

  computed: {
    ...mapState("tablerocontrol", [
      "dataStatus",
      "datos_cartera",
      "items_cartera",
      "loading_cartera",
    ]),
  },

  methods: {
    ...mapActions({ getDatosCartera: "tablerocontrol/getCarteraGral" }),


    getPorcentaje(total, parte){
      if (parte == 0){
        return "-";
      }
      if (total > 0){
        return this.$options.filters.numFormat((parte / total), "%0,0"); 
      }
    },

    sumField(key) {
      // sum data in give key (property)
      return this.items_cartera.reduce((a, b) => a + parseInt(b[key] || 0), 0);
    },

    sumFields(key1, key2, key3) {
      // sum data in give key (property)
      console.log(this.items_cartera);
      console.log(key1);
      console.log(key2);
      console.log(key3);
      var tot1 = this.items_cartera.reduce((a, b) => a + parseInt(b[key1.Cantidad] || 0), 0);
      var tot2 = this.items_cartera.reduce((a, b) => a + parseInt(b[key2.Cantidad] || 0), 0);
      var tot3 = this.items_cartera.reduce((a, b) => a + parseInt(b[key3.Cantidad] || 0), 0);

      return parseInt(tot1) + parseInt(tot2) + parseInt(tot3) ;
    },

    sumFieldCantidad(key) {

      let tot = 0;
      this.items_cartera.forEach(element => {
          tot += element[key]['Cantidad'];
      });

      return tot;

    },

    sumFieldCantidadTotal(key) {

      let tot = 0;
      this.items_cartera.forEach(element => {
          tot += element[key];
      });

      return tot;

    },

    sumFieldsCantidad(key1, key2, key3) {
 
      let tot = 0;
      this.items_cartera.forEach(element => {
          tot += element[key1]['Cantidad'];
      });

      return tot;

    },
    /*
    getTotal(item) {
      var tot = 0;
      tot = parseInt(item.ComprasAyer) + parseInt(item.ComprasHoy);
      return this.$options.filters.numFormat(tot, "0,0");
    },
    */
  },
};
</script>

<style lang="scss" scoped>
.contenedor {
  height: 15px;
}

.lineH1 {
  font-weight: bold;
  border-left: 1px solid #000000;
  border-right: 1px solid #000000;
}

.lineH1L {
  font-weight: bold;
  border-left: 1px solid #000000;
}

.lineH2 {
  border-left: 1px solid #000000;
  border-right: 1px solid #000000;
}

.lineH2L {
  border-left: 1px solid #000000;
}

.lineH2LB {
  border-left: 1px solid #000000;
  border-bottom: 1px solid #000000;
}

.lineL {
  border-left: 1px solid #000000;
}

.lineR {
  border-right: 1px solid #000000;
}

.total {
  font-weight: bold;
}

.totales {
  font-weight: bold;
  text-align: center;
}
</style>