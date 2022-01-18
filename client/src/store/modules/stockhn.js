import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  detalle_grid1: [],
  detalle_grid2: [],
  detalle_grid3: [],
  detalle_grid1_CE: [],
  detalle_grid2_CE: [],
  detalle_grid3_CE: [],

  loadingdetalle_grid1: false,
  loadingdetalle_grid2: false,
  loadingdetalle_grid3: false
};

export const mutations = {
  CLEAR_STOCK_HN(state) {
    state.detalle_grid1 = [];
    state.detalle_grid2 = [];
    state.detalle_grid3 = [];
    state.detalle_grid1_CE = [];
    state.detalle_grid2_CE = [];
    state.detalle_grid3_CE = [];
  },

  GET_STOCK_STATUS(state) {
    state.dataStatus = "loading";

    state.detalle_grid1 = [];
    state.detalle_grid2 = [];
    state.detalle_grid3 = [];
    state.detalle_grid1_CE = [];
    state.detalle_grid2_CE = [];
    state.detalle_grid3_CE = [];

    state.loadingdetalle_grid1 = true;
    state.loadingdetalle_grid2 = true;
    state.loadingdetalle_grid3 = true;
  },

  STOCK_SUCCESS(state, datos) {
    state.detalle_grid1 = datos["Grid1"];
    //state.detalle_grid1_CE = datos["Grid1_CE"];
    state.loadingdetalle_grid1 = false;

    state.detalle_grid2 = datos["Grid2"];
    state.detalle_grid2_CE = datos["Grid2_CE"];
    state.loadingdetalle_grid2 = false;

    state.detalle_grid3 = datos["Grid3"];
    state.detalle_grid3_CE = datos["Grid3_CE"];
    state.loadingdetalle_grid3 = false;

    state.dataStatus = "success";
  },

  STOCK_ERROR(state) {
    state.dataStatus = "Error";
    state.loadingdetalle_grid1 = false;
    state.loadingdetalle_grid2 = false;
    state.loadingdetalle_grid3 = false;
  }
};

export const getters = {
  //
};

export const actions = {
  getHNStock({ commit }, params) {
    commit("GET_STOCK_STATUS");
    console.log(params);

    return axios
      .post("/hnstock", params)
      .then(response => {
        console.log(response.data);
        commit("STOCK_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("STOCK_ERROR");
      });
  }
};
