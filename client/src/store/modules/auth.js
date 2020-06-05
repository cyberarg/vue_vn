import axios from "axios";

export const namespaced = true;

export const state = {
  authStatus: "",
  token: localStorage.getItem("token") || "",
  user: {},
  userAuthenticated: false,
  login: ""
};

export const mutations = {
  AUTH_REQUEST(state) {
    state.authStatus = "loading";
  },

  AUTH_SUCCESS(state, { token }) {
    state.authStatus = "success";
    state.token = token;
  },

  SET_USER(state, user) {
    state.user = user;
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
  }
};

export const actions = {
  async attempt(_, token) {
    console.log(token);
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
          console.log(response);
          // storing jwt in localStorage. https cookie is safer place to store
          localStorage.setItem("token", token);
          localStorage.setItem("user", JSON.stringify(user));
          localStorage.setItem("userName", user.Nombre);
          localStorage.setItem("login", userData.login);
          axios.defaults.headers.common["Authorization"] = "Bearer " + token;
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
