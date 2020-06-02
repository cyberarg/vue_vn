import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  obsStatus: "",
  observaciones: [],
  datos: [],
  items_modelos: [],
  items_planes: [],
  loadingCombos: false,
  loadingDatos: true,
  loadingObs: true,
  loadingPlanes: true,
  loadingModelos: true,
  loadingCalculadora: false,
  hnreal: 0,
  hnformula: 0
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

  GET_CALCULADORAHN_STATUS(state) {
    state.dataStatus = "loading";
    state.loadingCalculadora = true;
    state.hnreal = 0;
    state.hnformula = 0;
  },

  CALCULADORAHN_SUCCESS(state, datos) {
    state.loadingCalculadora = false;
    state.hnreal = datos.HNReal;
    state.hnformula = datos.HNFormula;
    console.log(datos);
  },

  GET_MODELOS_STATUS(state) {
    state.loadingCombos = true;
    state.loadingPlanes = true;
    state.hnreal = 0;
    state.hnformula = 0;
  },

  MODELOS_SUCCESS(state, data) {
    state.loadingModelos = false;
    state.loadingCombos = false;
    state.items_modelos = data;
    state.hnreal = 0;
    state.hnformula = 0;
  },

  GET_PLANES_STATUS(state) {
    state.loadingCombos = true;
    state.hnreal = 0;
    state.hnformula = 0;
  },

  PLANES_SUCCESS(state, data) {
    state.loadingPlanes = false;
    state.loadingCombos = false;
    state.items_planes = data;
    state.hnreal = 0;
    state.hnformula = 0;
  },

  HN_ERROR(state) {
    state.dataStatus = "Error";
    state.hnreal = 0;
    state.hnformula = 0;
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

  getModelos({ commit }, marca) {
    commit("GET_MODELOS_STATUS");
    return axios
      .get("/getmodeloshn/?marca=" + marca)
      .then(response => {
        console.log(response.data);
        commit("MODELOS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("HN_ERROR");
      });
  },

  getPlanes({ commit }, p) {
    commit("GET_PLANES_STATUS");
    console.log(p);
    return axios
      .get("/getplaneshn/?marca=" + p.marca + "&modelo=" + p.modelo)
      .then(response => {
        console.log(response.data);
        commit("PLANES_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("HN_ERROR");
      });
  },

  getCalculoHN({ commit }, params) {
    commit("GET_CALCULADORAHN_STATUS");
    console.log(params);
    return axios
      .post("/calculadorahn", params)
      .then(response => {
        console.log(response.data);
        commit("CALCULADORAHN_SUCCESS", response.data[0]);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("HN_ERROR");
      });
  },

  setValoresDolar(datos) {}
};
