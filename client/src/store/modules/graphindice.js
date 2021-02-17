import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  seriesDB: [],
  periodos:[],
  maxValue: 0,
  minValue: 0,
  loadingDatos: true
};

export const mutations = {
  GET_SERIES_STATUS(state) {
    state.dataStatus = "loading";
  },

  SERIES_SUCCESS(state, datos) {
    state.seriesDB = datos['Series'];
    state.periodos = datos['Periodos'];
    state.maxValue = datos['MaxValue'];
    state.minValue = datos['MinValue'];
    state.loadingDatos = false;
    state.dataStatus = "success";
  },

  SERIES_ERROR(state) {
    state.seriesDB = null;
    state.periodos = null;
    state.maxValue = null;
    state.minValue = null;
    state.loadingDatos = false;
    state.dataStatus = "error";
  }
};

export const getters = {
  //
};

export const actions = {
  getSeries({ commit }, pars) {
    commit("GET_SERIES_STATUS");
    return axios
      .post("/reporteacara", pars)
      .then(response => {
        console.log(response.data);
        commit("SERIES_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("SERIES_ERROR");
      });
  }
};
