<template>
  <v-app class="fullw">
    <v-card>
      <v-card-title>
        {{ pars.titleform }} - {{ item.Apellido }}, {{ item.Nombres }}
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
      </v-card-title>
      <v-container>
        <v-form ref="form" v-model="valid">
          <v-row>
            <v-col cols="5" md="5">
              <v-container>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Grupo"
                      placeholder="Grupo"
                      :disabled="disabled"
                      :filled="filled"
                      v-model="item.Grupo"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Orden"
                      placeholder="Orden"
                      :disabled="disabled"
                      :filled="filled"
                      v-model="item.Orden"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" md="12">
                    <v-text-field
                      dense
                      label="Solicitud"
                      placeholder="Solicitud"
                      :disabled="disabled"
                      :filled="filled"
                      v-model="item.Solicitud"
                    ></v-text-field>
                  </v-col>
                </v-row>

                <v-divider class="mx-1" inset horizontal></v-divider>

                <v-row>
                  <v-col cols="12" md="12">
                    <v-text-field
                      dense
                      label="Haber Neto"
                      placeholder="Haber Neto"
                      :disabled="disabled"
                      :filled="filled"
                      v-model="valorHaberNetoFormat"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" md="12">
                    <v-text-field
                      dense
                      label="Avance"
                      placeholder="Avance"
                      :disabled="disabled"
                      :filled="filled"
                      v-model="item.Avance"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" md="6">
                    <v-text-field
                      dense
                      label="Cuotas Pagas"
                      placeholder="Cuotas Pagas"
                      :disabled="disabled"
                      :filled="filled"
                      v-model="item.CPG"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field
                      dense
                      label="Cuotas Adelantadas"
                      placeholder="Cuotas Adelantadas"
                      :disabled="disabled"
                      :filled="filled"
                      v-model="item.CAD"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-col>
            <v-col cols="7" md="7">
              <v-container>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Apellido"
                      placeholder="Apellido"
                      :disabled="disabled"
                      :filled="filled"
                      v-model="item.Apellido"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Nombres"
                      placeholder="Nombres"
                      :disabled="disabled"
                      :filled="filled"
                      v-model="item.Nombres"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Documento"
                      placeholder="Documento"
                      :disabled="disabled"
                      :filled="filled"
                      v-model="item.NroDoc"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Email 1"
                      placeholder="Email 1"
                      v-model="item.Email1"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Domicilio"
                      placeholder="Domicilio"
                      v-model="item.Domicilio"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Teléfono 1"
                      placeholder="Teléfono 1"
                      v-model="item.Telefono1"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Teléfono 2"
                      placeholder="Teléfono 2"
                      v-model="item.Telefono2"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Teléfono 3"
                      placeholder="Teléfono 3"
                      v-model="item.Telefono3"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Teléfono 4"
                      placeholder="Teléfono 4"
                      v-model="item.Telefono4"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Oficial"
                      placeholder="Oficial"
                      :disabled="disabled"
                      :filled="filled"
                      v-model="item.NomOficial"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-select
                      dense
                      class="fillable"
                      :items="estados"
                      item-text="Nombre"
                      item-value="Codigo"
                      label="Estado"
                      :value="codEstado"
                      @input="setEstado"
                    ></v-select>
                  </v-col>
                  <!--
                  <v-col cols="6" md="2">
                    <v-checkbox :label="`Vendido`" dense></v-checkbox>
                  </v-col>
                  -->
                </v-row>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Fecha Compra"
                      placeholder="Fecha Compra"
                      :disabled="checkEstado"
                      :filled="filled"
                      v-model="item.FechaCompra"
                    ></v-text-field>
                    <!--
                    <v-menu
                      v-model="menu2"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      transition="scale-transition"
                      offset-y
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on }">
                        <v-text-field v-model="date" label="Fecha Compra" readonly v-on="on"></v-text-field>
                      </template>
                      <v-date-picker v-model="date" @input="menu2 = false"></v-date-picker>
                    </v-menu>
                    -->
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-select
                      dense
                      class="fillable"
                      :items="motivos"
                      item-text="Nombre"
                      item-value="Codigo"
                      label="Motivo"
                      :disabled="checkMotivo"
                      :value="codMotivo"
                      @input="setMotivo"
                      :rules="rules"
                    ></v-select>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      label="Precio Máximo de Compra"
                      placeholder="Precio Máximo de Compra"
                      :disabled="disabled"
                      :filled="filled"
                      v-model="valorPrecioMaxCompraFormat"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6" md="6">
                    <v-text-field
                      dense
                      class="fillable"
                      label="Precio Compra"
                      placeholder="Precio Compra"
                      :disabled="checkEstado"
                      :filled="filled"
                      v-model="item.PrecioCompra"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-col>
          </v-row>
        </v-form>
      </v-container>
      <template>
        <v-dialog v-model="dialog" v-show="dialog" max-width="650px">
          <v-card>
            <v-progress-linear v-if="loading" indeterminate color="primary darken-1"></v-progress-linear>
            <v-card-title>
              <span class="headline">Nueva Observación</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12" sm="12" md="12">
                    <v-textarea
                      name="text-obs"
                      label="Ingrese una nueva observación"
                      v-model="observacion"
                    ></v-textarea>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-btn cclass="ma-2 primary" @click="saveObs">
                <v-icon left>mdi-content-save-outline</v-icon>Guardar
              </v-btn>
              <v-spacer></v-spacer>
              <v-btn cclass="ma-2 danger" @click="close">
                <v-icon left>mdi-cancel</v-icon>Cancelar
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </template>

      <v-data-table
        dense
        :headers="headersobs"
        :items="observaciones"
        item-key="pars.itemkey"
        class="elevation-1"
        :loading="loadingObs"
        loading-text="Cargando Observaciones... Aguarde"
      >
        <template v-slot:item="{ item }">
          <template v-if="item.Automatica == 1 && showAutObs">
            <tr v-show="showAutObs" :class="typeObs(item.CambioReconocimiento)">
              <td>{{ item.login }}</td>
              <td>{{ timestamp(item.Fecha) }}</td>
              <td>{{ item.Obs }}</td>
              <td>
                <v-badge color="green" content="AUT" v-if="item.Automatica == 1"></v-badge>
              </td>
            </tr>
          </template>
          <template v-else>
            <tr v-show="item.Automatica != 1">
              <td>{{ item.login }}</td>
              <td>{{ timestamp(item.Fecha) }}</td>
              <td>{{ item.Obs }}</td>
              <td></td>
            </tr>
          </template>
        </template>
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Observaciones</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-switch v-model="showAutObs" label="Incluir Observaciones Automáticas" class="mt-2"></v-switch>
          </v-toolbar>
        </template>
      </v-data-table>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn class="ma-2 primary" @click="nuevaObs">
          <v-icon left>mdi-comment-plus-outline</v-icon>Nueva
        </v-btn>
      </v-card-actions>

      <v-card-actions>
        <v-btn class="ma-2 primary" :disabled="disabledAceptar" @click="submit">
          <v-icon left>mdi-content-save-outline</v-icon>Aceptar
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn class="ma-2 primary" @click="volver">
          <v-icon left>mdi-arrow-left</v-icon>Volver
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-app>
</template>

<script>
import { mapState, mapActions } from "vuex";
import moment from "moment";

export default {
  name: "detalledato",

  props: ["id"],

  data() {
    return {
      date: new Date().toISOString().substr(0, 10),
      menu2: false,
      valid: true,
      disabledAceptar: false,
      estadoReact: null,
      motivoErrors: [],
      showAutObs: true,
      color: "",
      mode: "",
      timeout: 2000,
      x: null,
      y: "top",
      observacion: "",
      obsAut: false,
      items: [],
      dialog: false,
      headersobs: [
        {
          text: "Usuario",
          align: "start",
          value: "login"
        },
        { text: "Fecha", value: "Fecha" },
        { text: "Observacion", value: "Obs" },
        { text: "", value: "Automatica" }
      ],

      disabled: true,
      filled: false,
      pars: {
        routeapi: "",
        id: 0,
        module: "",
        titleform: "Detalle Dato"
      },

      firstname: "",
      lastname: "",
      nameRules: [
        v => !!v || "Name is required",
        v => v.length <= 10 || "Name must be less than 10 characters"
      ],
      email: ""
    };
  },

  created() {
    this.$store.dispatch("gestiondatos/mostrarDato", this.id);
    //this.setEstado();
  },

  watch: {
    dialog(val) {
      val || this.close();
    }
  },

  methods: {
    ...mapActions({
      updateDato: "gestiondatos/updateDato",
      newObs: "gestiondatos/newObs",
      saveDato: "gestiondatos/saveDato"
    }),

    async submit() {
      this.disabledAceptar = true;
      if (!this.$refs.form.validate()) {
        this.disabledAceptar = false;
        return;
      }

      await this.saveDato(this.item);
      await this.showSwal();
      this.volver();
    },

    saveObs() {
      this.newObs({
        id: this.id,
        Obs: this.observacion,
        Automatica: this.obsAut,
        login: this.login
      });
      this.dialog = false;
    },

    volver() {
      this.$router.go(-1);
    },
    nuevaObs() {
      this.dialog = true;
    },
    close() {
      this.dialog = false;
    },
    timestamp(fecha) {
      return moment(fecha).format("DD/MM/YYYY");
    },
    typeObs(cambioRecon) {
      if (cambioRecon == 1) {
        return "cambioReconocimiento";
      }
    },
    showSwal() {
      //this.$swal("Good job!", dataStatusMsg, dataStatus);
      this.$swal(this.dataStatusMsg, "", this.dataStatus);
    },

    setEstado(value) {
      this.item.CodEstado = value;
    },

    setMotivo(value) {
      console.log(value);
      if (this.item.CodEstado == 4) {
        this.item.Motivo = value;
      }
    },

    setFecha(value) {
      console.log(value);
      if (this.item.CodEstado == 5) {
        this.item.FechaCompra = value;
      }
    }
  },

  computed: {
    api() {
      return "gestiondatos";
      //return this.pars.routeapi;
    },

    fechaCompra() {
      return this.timestamp(this.item.FechaCompra);
    },

    codEstado() {
      return parseInt(this.item.CodEstado);
    },

    codMotivo() {
      return parseInt(this.item.Motivo);
    },

    checkEstado() {
      if (this.item.CodEstado != 5) {
        return true;
      }
    },

    checkMotivo() {
      if (this.item.CodEstado != 4) {
        return true;
      }
    },

    rules() {
      const rules = [];

      if (this.item.CodEstado == 4 && this.item.Motivo != 0) {
        const rule = v => !!v || "Motivo es Requerido.";

        rules.push(rule);
      }

      return rules;
    },

    valorHaberNetoFormat() {
      return "$" + this.$options.filters.numFormat(this.item.HaberNeto);
    },

    valorPrecioMaxCompraFormat() {
      return (
        "$" + this.$options.filters.numFormat(this.item.PrecioMaximoCompra)
      );
    },

    module() {
      return "gestiondatos";
      //return this.pars.module;
    },
    ...mapState("auth", ["login", "user"]),
    ...mapState("gestiondatos", [
      "item",
      "observaciones",
      "loading",
      "loadingObs",
      "dataStatus",
      "dataStatusMsg",
      "showMsg",
      "estados",
      "motivos"
    ])
  }
};
</script>

<style scoped>
.v-container {
  margin: 0 auto;
  max-width: 100%;
}

.fullw {
  width: 100%;
}

.v-row {
  height: 12px;
}

.v-select {
  height: 10px;
  font-size: 14px;
}

.v-checkbox {
  height: 10px;
  font-size: 8px;
  padding-bottom: 15px;
}

.v-checkbox label {
  height: 10px;
  font-size: 8px;
  padding-bottom: 15px;
}

.v-text-field {
  height: 10px;
  padding-bottom: 15px;
  font-size: 14px;
}

.v-text-field .disabled {
  height: 10px;
}

.v-text-field .filled {
  height: 10px;
}

.cambioReconocimiento {
  background: #eb952d;
}

.fillable {
  font-weight: bold;
}
</style>
