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
  GET_DATA_STATUS(state) {
    state.dataStatus = "loading";
  },

  DATOS_SUCCESS(state, datos) {
    state.datos = datos;
    state.loadingDatos = false;
    state.dataStatus = "success";
  },

  OBS_SUCCESS(state, datos) {
    state.observaciones = datos;
    state.loadingObs = false;
    state.obsStatus = "success";
  },

  DATOS_ERROR(state) {
    state.dataStatus = "Error";
  },
  OBS_ERROR(state) {
    state.obsStatus = "Error";
  }
};

export const getters = {
  //
};

export const actions = {
  getData({ commit, dispatch }, { params }) {
    commit("GET_DATA_STATUS");
    return axios
      .get("/" + params.api, params.ID)
      .then(response => {
        //console.log(response.data);
        commit("DATOS_SUCCESS", response.data);
        dispatch("getObservaciones", params.ID);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  getObservaciones({ commit }, ID) {
    return axios
      .get("/observaciones", ID)
      .then(response => {
        //console.log(response.data);
        commit("OBS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("OBS_ERROR");
      });
  }
};
