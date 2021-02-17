<template>
  <div class="auth-layout row align-content--center">
    <div class="flex xs12 pa-3">
      <div class="d-flex justify--center">
        <va-card class="auth-layout__card">
          <va-tabs center>
            <va-tab>Cambio de Contraseña</va-tab>
          </va-tabs>

          <va-separator />
          <form @submit.prevent="onsubmit()" v-on:keyup.enter="onsubmit">
            <v-text-field v-model="login" type="text" label="Usuario" disabled></v-text-field>
            <v-text-field v-model="user.Nombre" type="text" label="Nombre Completo" disabled></v-text-field>

            <v-text-field
              :append-icon="showpass ? 'mdi-eye' : 'mdi-eye-off'"
              :rules="[rules.required, rules.min]"
              :type="showpass ? 'text' : 'password'"
              name="input-10-0"
              label="Contraseña Actual"
              v-model="password"
              ref="password"
              class="input-group--focused"
              @click:append="showpass = !showpass"
              :error="!!passwordErrors.length"
              :error-messages="passwordErrors"
              required
            ></v-text-field>

            <v-text-field
              :append-icon="shownew ? 'mdi-eye' : 'mdi-eye-off'"
              :rules="[rules.required, rules.min]"
              :type="shownew ? 'text' : 'password'"
              name="input-10-1"
              label="Nueva Contraseña"
              v-model="newpassword"
              ref="newpassword"
              class="input-group--focused"
              @click:append="shownew = !shownew"
              :error="!!newpasswordErrors.length"
              :error-messages="newpasswordErrors"
              required
            ></v-text-field>

            <v-text-field
              :append-icon="showrenew ? 'mdi-eye' : 'mdi-eye-off'"
              :rules="[rules.required, rules.min, rules.passMatch]"
              :type="showrenew ? 'text' : 'password'"
              name="input-10-2"
              v-model="renewpassword"
              ref="renewpassword"
              label="Repita Nueva Contraseña"
              class="input-group--focused"
              @click:append="showrenew = !showrenew"
              :error="!!renewpasswordErrors.length"
              :error-messages="renewpasswordErrors"
              required
            ></v-text-field>

            <div class="d-flex justify--center mt-3">
              <v-btn outlined color="indigo" :disabled="disableButton" @click="onsubmit">Enviar</v-btn>
            </div>
          </form>
        </va-card>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  name: "changepass",
  data() {
    return {
      showpass: false,
      shownew: false,
      showrenew: false,
      password: "",
      newpassword: "",
      renewpassword: "",
      passwordErrors: [],
      newpasswordErrors: [],
      renewpasswordErrors: [],
      disableButton: false,
      rules: {
        required: value => !!value || "Este campo es requerido.",
        min: v =>
          v.length >= 4 || "La contraseña debe tener como mínimo 4 caracteres",
        passMatch: v =>
          (!!v && v) === this.newpassword ||
          "Las nuevas contraseñas no coinciden."
      }
    };
  },
  methods: {
    ...mapActions({
      changePassword: "auth/changePassword",
      logOut: "auth/logOut"
    }),

    onsubmit() {
      this.disableButton = true;
      this.passwordErrors = this.password
        ? []
        : ["El campo de contraseña actual es obligatorio"];
      this.newpasswordErrors = this.newpassword
        ? []
        : ["El campo de nueva contraseña es obligatorio"];
      this.renewpasswordErrors = this.renewpassword
        ? []
        : ["El campo de verificación de nueva contraseña es obligatorio"];

      if (!this.formReady) {
        this.disableButton = false;
        return;
      }
      //this.$router.push({ name: "login" });
      this.changePass();
    },

    changePass() {
      var pars = {
        username: this.login,
        password: this.password,
        newpassword: this.newpassword
      };
      this.changePassword(pars)
        //.then(() => this.$router.push({ name: "login" }))
        .then(() => this.showSwal())
        .catch(err => {
          console.log(err);
          this.passwordErrors = [this.authStatusMsg];
          this.disableButton = false;
        });
    },

    showSwal() {
      //this.$swal("Good job!", dataStatusMsg, dataStatus);
      this.$swal(this.authStatusMsg, "", this.authStatus);
      if (this.authStatus == "success") {
        this.logOut();
        this.$router.push({ name: "login" });
      } else {
        this.passwordErrors = [this.authStatusMsg];
        this.disableButton = false;
      }
    }
  },
  computed: {
    ...mapState("auth", ["login", "user", "authStatusMsg", "authStatus"]),

    formReady() {
      return !(
        this.passwordErrors.length ||
        this.passwordErrors.length ||
        this.newpasswordErrors.length ||
        this.renewpasswordErrors.length
      );
    }
  }
};
</script>

<style lang="scss">
</style>
