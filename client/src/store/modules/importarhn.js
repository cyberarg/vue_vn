import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  dataStatusMsg: "",
  items: [],
  loading: true,
  respuesta: []
};

export const mutations = {
  GET_DATA_STATUS(state) {
    state.dataStatus = "loading";
  },

  DATOS_SUCCESS(state, respuesta) {
    state.respuesta = respuesta.data;
    state.loading = false;
    state.dataStatus = "success";
  },

  OK_RESPONSE(state, respuesta) {
    state.respuesta = respuesta.data;
    state.unselect = true;
    state.loading = false;
  },

  OK_RESPONSE_IMPORTACION(state, respuesta) {
    state.obtuvoRespuesta = true;
    state.unselect = true;
    state.loading = false;
    state.dataStatusMsg = "La planilla se ha procesado correctamente";
    state.dataStatus = "success";
  },

  DATOS_ERROR(state) {
    state.dataStatus = "error";
  },

  SEND_IMPORTACION(state) {
    state.dataStatus = "loading";
    state.loading = true;
  }
};

export const getters = {
  getDatoById: state => ID => {
    return state.items.find(item => item.ID === ID);
  }
};

export const actions = {
  importarDatos({ commit }, params) {
    commit("SEND_IMPORTACION");
    return axios
      .post("/importarhn", {
        data: params
      })
      .then(response => {
        //console.log(response);
        commit("OK_RESPONSE", response);
        //dispatch("reloadItems");
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  procesarRegistros({ commit }, params) {
    commit("SEND_IMPORTACION");

    return axios
      .post("/procesarhn", {
        data: params.data,
        login: params.login
      })
      .then(response => {
        console.log(response);
        commit("OK_RESPONSE_IMPORTACION", response);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  }
};
