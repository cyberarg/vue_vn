import axios from "axios";

export const namespaced = true;

export const state = {
  authStatus: "",
  authStatusMsg: "",
  token: localStorage.getItem("token") || "",
  user: {},
  userAuthenticated: false,
  login: "",
  esConcesionario: null,
  esVinculo: null,
  codigoConcesionario: null,
  loading: false,
  perfilUsuario: null,
  verCalculadora: null
};

export const mutations = {
  AUTH_REQUEST(state) {
    state.authStatus = "loading";
  },

  AUTH_SUCCESS(state, { token }) {
    state.authStatus = "success";
    state.token = token;
    state.userAuthenticated = true;
  },

  SET_USER(state, user) {
    state.user = user;

    if (user.HNConcesionario !== null) {
      state.esConcesionario = true;
      state.codigoConcesionario = user.HNConcesionario;
    } else {
      state.esConcesionario = false;
      state.codigoConcesionario = null;
    }

    if (user.HN_VerCalculadora == 1) {
      state.verCalculadora = true;
    } else {
      state.verCalculadora = false;
    }

    state.perfilUsuario = user.HN_PerfilUsuario;

    if (user.HN_PerfilUsuario == 2) {
      state.esVinculo = true;
    } else {
      state.esVinculo = false;
    }
  },

  SET_LOGIN(state, login) {
    state.login = login;
  },

  AUTH_ERROR(state) {
    state.authStatus = "error";
  },
  LOGOUT(state) {
    state.authStatus = "";
    state.token = "";
    localStorage.removeItem("token");
    localStorage.removeItem("user");
    localStorage.clear();
    state.userAuthenticated = false;
  },
  SET_CHANGE_PASS(state) {
    state.authStatus = "loading";
    state.loading = true;
  },

  CHANGE_PASS_SUCCESS(state, data) {
    state.authStatus = data["Status"];
    state.authStatusMsg = data["Msg"];
    state.loading = false;
  },

  CHANGE_PASS_ERROR(state, data) {
    state.authStatus = "error";
    state.authStatusMsg = data["Msg"];
    state.loading = false;
  }
};

export const actions = {
  async attempt(_, token) {
    console.log(token);
  },

  changePassword({ commit }, pars) {
    commit("SET_CHANGE_PASS");

    return axios
      .post("/changepassword", pars)
      .then(response => {
        commit("CHANGE_PASS_SUCCESS", response.data);
      })
      .catch(err => {
        commit("CHANGE_PASS_ERROR", response.data);
      });
  },

  logOut({ commit }) {
    commit("LOGOUT");
  },

  async signIn({ commit }, userData) {
    return new Promise((resolve, reject) => {
      commit("AUTH_REQUEST");
      axios
        .post("/auth/signin", {
          login: userData.login,
          password: userData.password
        })
        .then(response => {
          const token = response.data.access_token;
          const user = response.data.user;
          //console.log(response);
          // storing jwt in localStorage. https cookie is safer place to store

          localStorage.setItem("token", token);
          localStorage.setItem("user", JSON.stringify(user));
          localStorage.setItem("userName", user.Nombre);
          localStorage.setItem("login", userData.login);

          if (user.HNConcesionario !== null) {
            localStorage.setItem("esConcesionario", true);
            localStorage.setItem("codigoConcesionario", user.HNConcesionario);
            localStorage.setItem("perfilUsuario", user.HN_PerfilUsuario);
          } else {
            localStorage.setItem("esConcesionario", false);
          }

          if (user.HN_PerfilUsuario === 2) {
            localStorage.setItem("esVinculo", true);
          } else {
            localStorage.setItem("esVinculo", false);
          }

          //console.log(localStorage.getItem("esConcesionario"));

          //axios.defaults.headers.common["Authorization"] = "Bearer " + token;
          // mutation to change state properties to the values passed along
          commit("AUTH_SUCCESS", { token });
          commit("SET_USER", user);
          commit("SET_LOGIN", userData.login);

          this.userAuthenticated = true;

          resolve(response);
        })
        .catch(err => {
          console.log("login error");
          commit("AUTH_ERROR");
          localStorage.removeItem("token");
          reject(err);
        });
    });
  },

  checkAuth() {
    var jwt = localStorage.getItem("token");
    if (jwt) {
      this.userAuthenticated = true;
    } else {
      this.userAuthenticated = false;
    }
  }
};
