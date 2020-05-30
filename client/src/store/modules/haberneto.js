import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  obsStatus: "",
  observaciones: [],
  datos: [],
  loadingDatos: true,
  loadingObs: true
};

export const mutations = {
  GET_HNV_STATUS(state) {
    state.dataStatus = "loading";
  },

  HNV_SUCCESS(state, datos) {
    state.datos = datos.ListHN;
    state.loadingDatos = false;
    state.dataStatus = "success";
  },

  GET_HNC_STATUS(state) {
    state.dataStatus = "loading";
  },

  HNC_SUCCESS(state, datos) {
    state.datos = datos.ListHN;
    state.loadingDatos = false;
    state.dataStatus = "success";
  },

  HN_ERROR(state) {
    state.dataStatus = "Error";
  }
};

export const getters = {
  //
};

export const actions = {
  getHNVigentes({ commit, dispatch }) {
    commit("GET_HNV_STATUS");
    return axios
      .get("/haberesnetos")
      .then(response => {
        //console.log(response.data);
        commit("HNV_SUCCESS", response.data);
        dispatch("setValoresDolar", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("HN_ERROR");
      });
  },

  getHNCobrados({ commit }) {
    commit("GET_HNC_STATUS");
    return axios
      .get("/haberesnetos")
      .then(response => {
        console.log(response.data);
        commit("HNC_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("HN_ERROR");
      });
  },

  setValoresDolar(datos) {}
};
