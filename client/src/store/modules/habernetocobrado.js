import axios from "axios";

export const namespaced = true;

export const state = {

  loadingNuevoCobro: false,
  dataStatusMsgNuevoCobro: "",
  dataStatusNuevoCobro: "",
  loadingGridCobrosHN: false,
  itemCobrosHN:[]

};

export const mutations = {

  NEW_NUEVO_COBRO_STATUS(state) {
    state.loadingNuevoCobro = true;
    state.dataStatusCobro = "loading";
    state.dataStatusNuevoCobro =
      "Grabando Nuevo Cobro de Haber Neto, aguarde por favor...";
  },

  NEW_NUEVO_COBRO_SUCCESS(state, respuesta) {
    state.dataStatusCobro = "success";
    state.dataStatusNuevoCobro = "Nuevo Cobro de Haber Neto grabado exitosamente.";
    state.loadingNuevoCobro = false;
    console.log(respuesta);
  },

  NEW_NUEVO_COBRO_ERROR(state, respuesta) {
    state.dataStatusCobro = "error";
    state.dataStatusNuevoCobro =
      "Ocurrió un error al intentar grabar el nuevo cobro de Haber Neto.";
    state.loadingNuevoCobro = false;
    console.log(respuesta);
  },

  GET_GRID_COBRO_STATUS(state) {
    state.loadingGridCobrosHN = true;
    state.itemCobrosHN = [];

  },

  GET_GRID_COBRO_SUCCESS(state, respuesta) {
    console.log(respuesta);
    state.itemCobrosHN = respuesta;
    state.loadingGridCobrosHN = false;
    console.log(respuesta);
  },

  GET_GRID_COBRO_ERROR(state, respuesta) {
    state.itemCobrosHN = [];
    state.loadingGridCobrosHN = false;
    console.log(respuesta);
  },

  
};

export const getters = {
  //
};

export const actions = {

    getGridCobros({ commit }, params) {
        commit("GET_GRID_COBRO_STATUS");
        console.log(params);
        return axios
          .post("/getcobroshn", params)
          .then(response => {
            //console.log(response.data);
            commit("GET_GRID_COBRO_SUCCESS", response.data);
          })
          .catch(err => {
            //console.log("get datos error");
            commit("GET_GRID_COBRO_ERROR");
        });
    },

    nuevoCobroHaberNeto({ commit }, params) {
        commit("NEW_NUEVO_COBRO_STATUS");
        console.log(params);
        return axios
        .post("/nuevocobrohn", params)
        .then(response => {
            //console.log(response.data);
            commit("NEW_NUEVO_COBRO_SUCCESS", response.data);
        })
        .catch(err => {
            //console.log("get datos error");
            commit("NEW_NUEVO_COBRO_ERROR");
        });
    },


};
