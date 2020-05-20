import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  items: [],
  datos: [],
  empresa: {},
  loading: true,
  okResponse: false
};

export const mutations = {
  GET_DATA_STATUS(state) {
    state.dataStatus = "loading";
  },

  DATOS_SUCCESS(state, datos) {
    state.items = datos;
    state.datos = datos;
    state.empresa = datos.Empresa;
    state.loading = false;
    state.dataStatus = "success";
  },

  DATOS_ERROR(state) {
    state.dataStatus = "Error";
  }
};

export const getters = {
  //
};

export const actions = {
  getData({ commit }, api) {
    commit("GET_DATA_STATUS");
    return axios
      .get("/" + api)
      .then(response => {
        //console.log(response.data);
        commit("DATOS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  }
};
