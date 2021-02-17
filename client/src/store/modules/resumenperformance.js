import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  detalle_resumen: [],
  detalle_resumen_CE: [],
  detalle_saldo: [],
  detalle_saldo_CE: [],
  detalle_saldo_RB: [], 
  loading_resumen: false
};

export const mutations = {
  CLEAR_PROOYECTADO_HN(state) {
    state.dataStatus = "";
    state.detalle_resumen = [];
    state.detalle_resumen_CE = [];
    state.detalle_saldo = [];
    state.detalle_saldo_CE = [];
    state.detalle_saldo_RB = [];
  },

  GET_RESPER_STATUS(state) {
    state.dataStatus = "loading";
    state.detalle_resumen = [];
    state.detalle_resumen_CE = [];
    state.detalle_saldo = [];
    state.detalle_saldo_CE = [];
    state.detalle_saldo_RB = [];
    state.loading_resumen = true;

  },

  RESPER_SUCCESS(state, datos) {
    state.detalle_resumen = datos["Grid1"];
    state.detalle_resumen_CE = datos["Grid1_CE"];
    state.detalle_saldo = datos["Grid2"];
    state.detalle_saldo_CE = datos["Grid2_CE"];
    state.detalle_saldo_RB = datos["Grid2_RB"];
    state.loading_resumen = false;

    state.dataStatus = "success";
  },

  RESPER_ERROR(state) {
    state.dataStatus = "Error";
    state.loading_resumen = false;

  }
};

export const getters = {
  //
};

export const actions = {
    getHNResumenPerformance({ commit }, params) {
    commit("GET_RESPER_STATUS");
    console.log(params);

    return axios
      .post('/hnresumenperformance', params)
      .then(response => {
        console.log(response.data);
        commit("RESPER_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("RESPER_ERROR");
      });
  }
};
