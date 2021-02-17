import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  dataStatusMsg: "",
  concesionarios: [],
  concesionarios_filtered:[],
  loadingConcesionarios: false,
  oficiales: [],
  oficiales_filtered: [],
  loadingOficiales: false,
};

export const mutations = {
  GET_CONCESIONARIOS_STATUS(state) {
    state.dataStatus = "loading";
    state.dataStatusMsg = "loading";
    state.concesionarios = [];
    state.concesionarios_filtered = [];
    state.loadingConcesionarios = true;
  },

  CONCESIONARIOS_SUCCESS(state, datos) {
    console.log(datos);
    state.concesionarios = datos;
    state.concesionarios_filtered = datos;
    localStorage.setItem("concesionarios", JSON.stringify(datos));
    state.loadingConcesionarios = false;
    state.dataStatus = "success";
    state.dataStatusMsg = "success";
  },

  CONCESIONARIOS_ERROR(state) {
    state.dataStatus = "Error";
    state.dataStatusMsg = "Ocurrió un error al intentar recuperar la lista de Concesionarios";
    state.loadingConcesionarios = false;
  },

  CE_MARCA_SUCCESS(state, data){
    state.loadingConcesionarios = false;
    state.dataStatus = "success";
    state.dataStatusMsg = "success";
  },

  GET_OFICIALES_STATUS(state) {
    state.dataStatus = "loading";
    state.dataStatusMsg = "loading";
    state.oficiales = [];
    state.oficiales_filtered = [];
    state.loadingOficiales = true;
  },

  OFICIALES_SUCCESS(state, datos) {
    console.log(datos);
    state.oficiales = datos;
    state.oficiales_filtered = datos;
    state.loadingOficiales = false;
    state.dataStatus = "success";
    state.dataStatusMsg = "success";
  },

  OFICIALES_ERROR(state) {
    state.dataStatus = "Error";
    state.dataStatusMsg = "Ocurrió un error al intentar recuperar la lista de Oficiales";
    state.loadingOficiales = false;
  },



};

export const getters = {
    getConcesionarioByMarca: state => Marca => {
        return state.concesionarios_filtered.find(item => item.Marca === Marca);
    },
};

export const actions = {
  getConcesionarios({ commit }, pars) {
    commit("GET_CONCESIONARIOS_STATUS");
    return axios
      .post("/concesionarios", pars)
      .then(response => {
        commit("CONCESIONARIOS_SUCCESS", response.data);
      })
      .catch(err => {
        commit("CONCESIONARIOS_ERROR");
      });
  },

  getOficiales({ commit }, pars) {
    commit("GET_OFICIALES_STATUS");
    return axios
      .get("/oficiales", pars)
      .then(response => {
        commit("OFICIALES_SUCCESS", response.data);
      })
      .catch(err => {
        commit("OFICIALES_ERROR");
      });
  },

  getCEByMarca({ commit, getters }, params) {
    var ce = getters.getConcesionarioByMarca(params.idMarca);

    if (ce){
        commit("CE_MARCA_SUCCESS", ce);
    }else{
        commit("CONCESIONARIOS_ERROR");
    }
  }

};
