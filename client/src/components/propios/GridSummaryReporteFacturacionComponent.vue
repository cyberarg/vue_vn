<template>
<div>
    <div>
      <v-card color="grey lighten-4">
        <v-data-table
          dense
          fixed-header
          locale="es"
          :headers="headers"
          :items="items"
          :items-per-page="-1"
          :hide-default-footer="true"
          :hide-default-header="true"
          item-key="pars.itemkey"
          class="elevation-1"
          :loading="loading"
          loading-text="Cargando Datos... Aguarde"
          no-data-text="No hay datos disponibles."
        >
          <template v-slot:header="{ props }">
            <thead class="v-data-table-header">
              <tr>
                <th />
                <th colspan="6" class="text-center lineH1">
                  RB
                </th>
                <th colspan="1" class="text-center lineH1">
                 Total RB
                </th>
                <th colspan="1" class="text-center lineH1L">
                  Car Group
                </th>
                <th colspan="1" class="text-center lineH1L">
                  Sauma
                </th>
                <th colspan="1" class="text-center lineH1L">
                  Iruña
                </th>
                <th colspan="1" class="text-center lineH1L">
                  TOTAL
                </th>
              </tr>
              <tr>
                <th class="text-center child-header">AutoNet</th>
                <th class="text-center child-header">Iruña</th>
                <th class="text-center child-header">Luxcar</th>
                <th class="text-center child-header">Amendola</th>
                <th class="text-center child-header">Sauma</th>
                <th class="text-center child-header">Car Group</th>
              </tr>
            </thead>
          </template>
        
            <!--
          <template v-slot:item.Comision_0="{ item }">{{
            showValorComision(item, 0, true)
          }}</template>
          <template v-slot:item.Comision_1="{ item }">{{
            showValorComision(item, 1, true)
          }}</template>
          <template v-slot:item.Comision_2="{ item }">{{
            showValorComision(item, 2, true)
          }}</template>
            -->

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            cclass="ma-2"
            color="success"
            outlined
            text
            @click="exportExcel"
            v-show="showBotones"
          >
            <v-icon left>mdi-file-excel-outline</v-icon>Excel
          </v-btn>
        </v-card-actions>
      </v-card>
    </div>
  </div>
</template>

<script>
    export default {
        props: {
            headers: {
            type: Array,
            required: true,
            },
            mesBase: {
            type: Number,
            required: true,
            },
        },

        data() {
            return {
            //
            };
        },
    }
</script>

<style lang="sass" scoped>

</style>