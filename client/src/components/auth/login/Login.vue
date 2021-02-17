<template>
  <form @submit.prevent="onsubmit" v-on:keyup.enter="onsubmit">
    <va-input
      v-model="username"
      type="text"
      :label="$t('auth.username')"
      :error="!!userErrors.length"
      :error-messages="userErrors"
    />

    <va-input
      class="inputselec"
      v-model="password"
      type="password"
      :label="$t('auth.password')"
      :error="!!passwordErrors.length"
      :error-messages="passwordErrors"
    />
    <!--
    <div
      class="auth-layout__options d-flex align--center justify--space-between"
    >
      <va-checkbox
        v-model="keepLoggedIn"
        class="mb-0"
        :label="$t('auth.keep_logged_in')"
      />
      <router-link class="ml-1 link" :to="{ name: 'recover-password' }">{{
        $t("auth.recover_password")
      }}</router-link>
    </div>
    -->
    <div class="d-flex justify--center mt-3">
      <!--
      <va-button type="submit" class="my-0">{{ $t("auth.login") }}</va-button>
      -->
      <v-btn outlined color="indigo" :disabled="disableLogin" @click="onsubmit">
        {{
        $t("auth.login")
        }}
      </v-btn>
    </div>
  </form>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  name: "login",
  data() {
    return {
      username: "",
      password: "",
      keepLoggedIn: false,
      disableLogin: false,
      userErrors: [],
      passwordErrors: []
    };
  },

  computed: {
    formReady() {
      return !this.userErrors.length && !this.passwordErrors.length;
    }
  },

  mounted() {
    this.logOut();
  },

  methods: {
    ...mapActions({
      signIn: "auth/signIn",
      logOut: "auth/logOut"
    }),

    onsubmit() {
      this.disableLogin = true;
      this.userErrors = this.username ? [] : ["Usuario es un campo requerido"];
      this.passwordErrors = this.password
        ? []
        : ["Contraseña es un campo requerido"];
      if (!this.formReady) {
        this.disableLogin = false;
        return;
      }
      this.loggin();
      //this.$router.push({ name: "dashboard" });
    },

    loggin() {
      let login = this.username;
      let password = this.password;

      this.signIn({ login, password })
        .then(() => this.$router.push({ name: "dashboard" }))
        .catch(err => {
          console.log(err);
          this.userErrors = ["Usuario o Clave incorrecto."];
          this.passwordErrors = ["Usuario o Clave incorrecto."];
          this.disableLogin = false;
          this.snackbar = true;
        });
    }
  }
};
</script>

<style>
.contenedor {
  height: 280px;
}

.va-input__container__label {
  color: #7d8899;
}
</style>
