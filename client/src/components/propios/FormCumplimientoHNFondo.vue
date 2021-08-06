<template>
  <div>
    <v-form v-model="valid">
      <v-row>
        <v-col cols="12">
          <v-row>
            <v-col>
              <v-simple-table dense>
                <template v-slot:default>
                  <thead>
                    <tr>
                      <th class="text-left">Parámetro</th>
                      <th class="text-left">Objetivo</th>
                      <th class="text-left">Real</th>
                      <th class="text-left">Concesionario</th>
                      <th class="text-left">Giama</th>
                    </tr>
                  </thead>
                  <tbody text-align="center">
                    <tr>
                      <td>Inversión Inicial</td>
                      <td>USD {{ items.item.ObjInversionInicial | numFormat }}</td>
                      <td>
                        USD
                        {{
                        getSuma(
                        items.item.RealConcInversionInicial,
                        items.item.RealGiamaInversionInicial
                        ) | numFormat
                        }}
                      </td>
                      <td>
                        USD
                        {{
                        items.item.RealConcInversionInicial | numFormat
                        }}
                      </td>
                      <td>
                        USD
                        {{
                        items.item.RealGiamaInversionInicial | numFormat
                        }}
                      </td>
                    </tr>
                    <tr>
                      <td>Cantidad de Casos</td>
                      <td>{{ items.item.ObjCantCasos | numFormat }}</td>
                      <td>
                        {{
                        getSuma(
                        items.item.RealConcCantCasos,
                        items.item.RealGiamaCantCasos
                        ) | numFormat
                        }}
                      </td>
                      <td>{{ items.item.RealConcCantCasos | numFormat }}</td>
                      <td>{{ items.item.RealGiamaCantCasos | numFormat }}</td>
                    </tr>
                    <tr>
                      <td>HN Promedio</td>
                      <td>${{ items.item.ObjHNPromedio | numFormat }}</td>
                      <td>
                        ${{
                        getPromedio(
                        items.item.RealConcHNPromedio,
                        items.item.RealGiamaHNPromedio
                        ) | numFormat
                        }}
                      </td>
                      <td>${{ items.item.RealConcHNPromedio | numFormat }}</td>
                      <td>${{ items.item.RealGiamaHNPromedio | numFormat }}</td>
                    </tr>
                    <tr>
                      <td colspan="5"></td>
                    </tr>
                    <tr>
                      <td>Costo Total</td>
                      <td>{{ items.item.ObjCostoTotal | numFormat() }}%</td>
                      <td>
                        {{
                        getPromedioPorcentajes(
                        items.item.RealConcCosto,
                        items.item.RealGiamaCosto
                        ) | numFormat("0%")
                        }}
                      </td>
                      <td>{{ items.item.RealConcCosto | numFormat("0%") }}</td>

                      <td>{{ items.item.RealGiamaCosto | numFormat("0%") }}</td>
                    </tr>
                    <tr>
                      <td>Duration (en Meses)</td>
                      <td>{{ items.item.ObjDuration }}</td>
                      <td>
                        {{
                        getPromedio(
                        items.item.RealConcDuration,
                        items.item.RealGiamaDuration
                        ) | numFormat
                        }}
                      </td>
                      <td>{{ items.item.RealConcDuration | numFormat }}</td>
                      <td>{{ items.item.RealGiamaDuration | numFormat }}</td>
                    </tr>
                    <tr>
                      <td>TIR Compuesta</td>
                      <td>{{ items.item.ObjTIRCompuesta | numFormat }}%</td>
                      <td>
                        {{
                        getPromedioPorcentajes(
                        items.item.RealConcTIRCompuesta,
                        items.item.RealGiamaTIRCompuesta
                        ) | numFormat
                        }}%
                      </td>
                      <td>{{ items.item.RealConcTIRCompuesta | numFormat }}%</td>
                      <td>{{ items.item.RealGiamaTIRCompuesta | numFormat }}%</td>
                    </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-form>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  name: "formcumplimientohnfondo",
  props: {
    pars: {
      type: Object,
      required: true
    },
    items: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      valid: true,
      filled: true
    };
  },

  created() {
    console.log(this.items);
  },

  methods: {
    ...mapActions({}),

    getSuma(valorConc, valorGiama) {
      return parseInt(valorConc) + parseInt(valorGiama);
    },

    getPromedio(valorConc, valorGiama) {
      return (parseInt(valorConc) + parseInt(valorGiama)) / 2;
    },

    getPromedioPorcentajes(valorConc, valorGiama) {
      return (parseFloat(valorConc) + parseFloat(valorGiama)) / 2;
    }
  },

  computed: {
    //...mapState()
  }
};
</script>

<style scoped>
.fullw {
  width: 100%;
}

.centered {
  margin: 50px 20px;
}

.v-input >>> input {
  text-align: center;

  /*
  font-size: 12px;
  height: 12px;
  padding-top: 2px;
  */
}

.centered-input >>> input {
  text-align: center;
}

.bg-gray {
  background-color: lightgray;
}

.cont-row {
  padding-top: 0;
  padding-bottom: 0;
  margin-top: 0;
  margin-bottom: 0;
}

.hn {
  font-size: 26px;
  font-weight: bolder;
  text-align: center;
}
</style>
