import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  items_sectores: [],
  items_personal:[],
  items_tipo_eventos: [],
  eventos: [],
  loading: false,
  item: {}
};

export const mutations = {
  GET_DATA_STATUS(state) {
    state.dataStatus = "loading";
    state.loading = true;
  },

  DATOS_SUCCESS(state, items) {
    state.items = items;
    state.loading = false;
    state.dataStatus = "success";
  },

  DATOS_ERROR(state) {
    state.dataStatus = "Error";
    state.loading = false;
  }, 

  GET_COMBOS_STATUS(state) {
    state.dataStatus = "loading";
    state.items_sectores = [];
    state.items_personal = [];
    state.items_tipo_eventos = [];
    state.loading = true;
  },

  COMBOS_SUCCESS(state, datos) {
    state.items_sectores = datos['Sectores'];
    state.items_personal = datos['Personal'];
    state.items_tipo_eventos = datos['TipoEventos'];
    state.loading = false;
    state.dataStatus = "success";
  },

  COMBOS_ERROR(state) {
    state.items_sectores = [];
    state.items_personal = [];
    state.items_tipo_eventos = [];
    state.dataStatus = "Error";
    state.loading = false;
  }
};

export const getters = {
  getItems: () => {
    return state.items;
  }
};

export const actions = {


  getEventos({ commit }, pars) {
    return axios
      .post("/eventos_calendario", pars)
      .then(response => {
        console.log(response.data);
        commit("DATOS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  getDatosCombos({ commit }) {
    console.log('Llego al getDatosCombo de calendario')
    return axios
      .get("/calendario_combos")
      .then(response => {
        commit("COMBOS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("COMBOS_ERROR");
      });
  }
};
