<template>
  <div>
    <v-card color="grey lighten-4" ma-0>
      <v-card-title class="headline grey lighten-2" justify="center" primary-title>
        Observaciones Gestión de Compras
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
      </v-card-title>
      <div class="container">
        <v-form v-model="valid" ref="form">
          <v-row justify="center">
            <v-col cols="12">
              <v-data-table
                dense
                fixed-header
                height="25vh"
                :headers="headers"
                :items="obs"
                :items-per-page="-1"
                :hide-default-footer="true"
                class="elevation-1"
                :loading="loadingObsGestion"
                loading-text="Cargando Observaciones... Aguarde"
                no-data-text="No hay observaciones disponibles"
              >
                <template v-slot:item.Fecha="{item}">{{formatFecha(item.Fecha)}}</template>
              </v-data-table>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <template v-if="puedeGenerarObs">
                <v-text-field
                  name="input-7-1"
                  label="Nueva Observación"
                  placeholder="Ingrese una nueva observación"
                  v-model="nuevaObs"
                ></v-text-field>
              </template>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <template v-if="puedeGenerarObs">
                <v-btn
                  small
                  outlined
                  :disabled="nuevaObs == '' "
                  color="success"
                  @click="grabarOBS"
                >
                  <v-icon left>mdi-content-save-outline</v-icon>Aceptar
                </v-btn>
              </template>
            </v-col>
            <v-spacer></v-spacer>
            <v-spacer></v-spacer>
            <v-spacer></v-spacer>
            <v-spacer></v-spacer>
            <v-col>
              <v-btn small outlined color="error" @click="volver">
                <v-icon left>mdi-close-circle</v-icon>Cancelar
              </v-btn>
            </v-col>
          </v-row>
        </v-form>
      </div>
    </v-card>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import moment from "moment";

export default {
  props: {
    obs: {
      type: Array,
      required: true,
    },
    id_dato: {
      type: Number,
      required: true,
    },
    puedeGenerarObs: {
      type: Boolean,
      required: true,
    },
  },

  data() {
    return {
      valid: true,
      nuevaObs: "",
      headers: [
        {
          text: "Usuario",
          align: "start",
          value: "login",
        },
        { text: "Fecha", value: "Fecha" },
        { text: "Observación", value: "Obs" },
      ],
    };
  },

  computed: {
    ...mapState("auth", [
      "login",
      "user",
      "esConcesionario",
      "esVinculo",
      "codigoConcesionario",
    ]),

    ...mapState("gestioncompras", [
      "loadingObsGestion",
      "obsGestionStatus",
      "obsGestionStatusInsert",
    ]),
  },

  methods: {
    ...mapActions({ saveObs: "gestioncompras/saveObs" }),

    async grabarOBS() {
      var pars = {
        login: this.login,
        Obs: this.nuevaObs,
        Fecha: moment().format("YYYY-MM-DD hh:mm:ss"),
        ID_Datos: this.id_dato,
      };
      await this.saveObs(pars);
      await this.showSwal();
      this.volver();
    },

    showSwal() {
      this.$swal(this.obsGestionStatusInsert, "", this.obsGestionStatus);
    },

    clearForm() {
      this.nuevaObs = "";
    },

    formatFecha(fecha) {
      return moment(fecha).format("DD/MM/YYYY hh:mm:ss");
    },

    volver() {
      //this.$router.go(-1);
      this.clearForm();
      this.$emit("hide");
    },
  },
};
</script>

<style lang="scss" scoped>
</style>