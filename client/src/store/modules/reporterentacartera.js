import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  detalle_proyectado: [],
  detalle_historico: [],

  loading_renta: false,

};

export const mutations = {
  CLEAR_STATE(state) {
    state.detalle_proyectado = [];
    state.detalle_historico = [];
    state.loading_renta = false;
  },

  GET_RENTA_STATUS(state) {
    state.dataStatus = "loading";

    state.detalle_proyectado = [];
    state.detalle_historico = [];

    state.loading_renta = true;
  },

  RENTA_SUCCESS(state, datos) {

    console.log(datos);
    state.detalle_proyectado = datos["Datos_Proyectado"];
    state.detalle_historico = datos["Datos_Historico"];

    state.loading_renta = false;

    state.dataStatus = "success";
  },

  RENTA_ERROR(state) {
    state.dataStatus = "Error";
    state.detalle_proyectado = false;
    state.detalle_historico = false;
  },

};

export const getters = {
  //
};

export const actions = {
  getReporteRentaCartera({ commit }, params) {
    commit("GET_RENTA_STATUS");

    return axios
      .post("/reporterentacartera", params)
      .then(response => {
        commit("RENTA_SUCCESS", response.data);
      })
      .catch(err => {
        commit("RENTA_ERROR");
      });
  },

};
