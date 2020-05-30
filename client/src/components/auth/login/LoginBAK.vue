<template>
  <form @submit.prevent="loggin">
    <va-input
      v-model="username"
      type="text"
      :label="$t('auth.username')"
      :error="!!emailErrors.length"
      :error-messages="emailErrors"
    />

    <va-input
      v-model="password"
      type="password"
      :label="$t('auth.password')"
      :error="!!passwordErrors.length"
      :error-messages="passwordErrors"
    />

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

    <div class="d-flex justify--center mt-3">
      <va-button type="submit" class="my-0">{{ $t("auth.login") }}</va-button>
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
      emailErrors: [],
      passwordErrors: []
    };
  },
  computed: {
    formReady() {
      return !this.emailErrors.length && !this.passwordErrors.length;
    }
  },
  methods: {
    ...mapActions({
      signIn: "auth/signIn"
    }),

    onsubmit() {
      this.emailErrors = this.username ? [] : ["Username is required"];
      this.passwordErrors = this.password ? [] : ["Password is required"];
      if (!this.formReady) {
        return;
      }
      this.$router.push({ name: "dashboard" });
    },

    loggin() {
      let login = this.username;
      let password = this.password;

      this.signIn({ login, password })
        .then(() => this.$router.push({ name: "dashboard" }))
        .catch(err => {
          console.log(err);
          this.emailErrors = ["Usuario o Clave incorrecto."];
          this.snackbar = true;
        });
      /*
      this.$store
        .dispatch("signIn", { login, password })
        .then(() => this.$router.push({ name: "dashboard" }))
        .catch(err => {
          console.log(err);
          this.emailErrors = ["Usuario o Clave incorrecto."];
          this.snackbar = true;
        });
        */
    }
  }
};
</script>

<style lang="scss"></style>
