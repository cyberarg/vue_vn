import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  detalle_grid1_compras: [],
  detalle_grid1_cobros: [],
  detalle_grid1_compras_Giama: [],
  detalle_grid1_compras_TotalGiama: [],
  detalle_grid1_cobros_Giama: [],
  detalle_grid1_cobros_TotalGiama: [],
  detalle_grid1_compras_CE: [],
  detalle_grid1_cobros_CE: [],

  detalle_grid2_compras: [],
  detalle_grid2_cobros: [],
  detalle_grid2_compras_Giama: [],
  detalle_grid2_compras_TotalGiama: [],
  detalle_grid2_cobros_Giama: [],
  detalle_grid2_cobros_TotalGiama: [],
  detalle_grid2_compras_CE: [],
  detalle_grid2_cobros_CE: [],

  loadingdetalle_grid1_compras: false,
  loadingdetalle_grid1_cobros: false,

  loadingdetalle_grid2_compras: false,
  loadingdetalle_grid2_cobros: false
};

export const mutations = {
  CLEAR_COMPRAS_HN(state) {
    state.detalle_grid1_compras = [];
    state.detalle_grid2_compras = [];
    state.detalle_grid1_compras_Giama = [];
    state.detalle_grid2_compras_Giama = [];
    state.detalle_grid1_compras_TotalGiama = [];
    state.detalle_grid2_compras_TotalGiama = [];
    state.detalle_grid1_compras_CE = [];
    state.detalle_grid2_compras_CE = [];
  },

  GET_COMPRAS_STATUS(state) {
    state.dataStatus = "loading";

    state.detalle_grid1_compras = [];
    state.detalle_grid2_compras = [];
    state.detalle_grid1_compras_Giama = [];
    state.detalle_grid2_compras_Giama = [];
    state.detalle_grid1_compras_TotalGiama = [];
    state.detalle_grid2_compras_TotalGiama = [];
    state.detalle_grid1_compras_CE = [];
    state.detalle_grid2_compras_CE = [];

    state.loadingdetalle_grid1_compras = true;
    state.loadingdetalle_grid2_compras = true;
  },

  COMPRAS_SUCCESS(state, datos) {
    console.log('COMPRAS:');
    console.log(datos);
    state.detalle_grid1_compras = datos["Grid1_Comprados"];
    state.detalle_grid1_compras_Giama = datos["Grid1_Comprados_Giama"];
    state.detalle_grid1_compras_TotalGiama = datos["Grid1_Comprados_TotalGiama"];
    state.detalle_grid1_compras_CE = datos["Grid1_Comprados_CE"];
    state.loadingdetalle_grid1_compras = false;

    state.detalle_grid2_compras = datos["Grid2_Comprados"];
    state.detalle_grid2_compras_Giama = datos["Grid2_Comprados_Giama"];
    state.detalle_grid2_compras_TotalGiama = datos["Grid2_Comprados_TotalGiama"];
    state.detalle_grid2_compras_CE = datos["Grid2_Comprados_CE"];
    state.loadingdetalle_grid2_compras = false;

    state.dataStatus = "success";
  },

  COMPRAS_ERROR(state) {
    state.dataStatus = "Error";
    state.loadingdetalle_grid1_compras = false;
    state.loadingdetalle_grid2_compras = false;
  },

  CLEAR_COBROS_HN(state) {
    state.detalle_grid1_cobros = [];
    state.detalle_grid2_cobros = [];
    state.detalle_grid1_cobros_Giama = [];
    state.detalle_grid2_cobros_Giama = [];
    state.detalle_grid1_cobros_TotalGiama = [];
    state.detalle_grid2_cobros_TotalGiama = [];
    state.detalle_grid1_cobros_CE = [];
    state.detalle_grid2_cobros_CE = [];
  },

  GET_COBROS_STATUS(state) {
    state.dataStatus = "loading";

    state.detalle_grid1_cobros = [];
    state.detalle_grid2_cobros = [];

    state.detalle_grid1_cobros_Giama = [];
    state.detalle_grid2_cobros_Giama = [];

    state.detalle_grid1_cobros_TotalGiama = [];
    state.detalle_grid2_cobros_TotalGiama = [];
    
    state.detalle_grid1_cobros_CE = [];
    state.detalle_grid2_cobros_CE = [];

    state.loadingdetalle_grid1_cobros = true;
    state.loadingdetalle_grid2_cobros = true;
  },

  COBROS_SUCCESS(state, datos) {
    console.log('COBROS:');
    console.log(datos);
    state.detalle_grid1_cobros = datos["Grid1_Cobrados"];
    state.detalle_grid1_cobros_Giama = datos["Grid1_Cobrados_Giama"];
    state.detalle_grid1_cobros_TotalGiama = datos["Grid1_Cobrados_TotalGiama"];
    state.detalle_grid1_cobros_CE = datos["Grid1_Cobrados_CE"];
    state.loadingdetalle_grid1_cobros = false;

    state.detalle_grid2_cobros = datos["Grid2_Cobrados"];
    state.detalle_grid2_cobros_Giama = datos["Grid2_Cobrados_Giama"];
    state.detalle_grid2_cobros_TotalGiama = datos["Grid2_Cobrados_TotalGiama"];
    state.detalle_grid2_cobros_CE = datos["Grid2_Cobrados_CE"];
    state.loadingdetalle_grid2_cobros = false;

    state.dataStatus = "success";
  },

  COBROS_ERROR(state) {
    state.dataStatus = "Error";
    state.loadingdetalle_grid1_cobros = false;
    state.loadingdetalle_grid2_cobros = false;
  }
};

export const getters = {
  //
};

export const actions = {
  getHNResumenCompras({ commit }, params) {
    commit("GET_COMPRAS_STATUS");
    console.log(params);

    return axios
      //.post("/hnresumencompras", params)
      .post("/hnresumencompras_select", params)
      .then(response => {
        console.log(response.data);
        commit("COMPRAS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("COMPRAS_ERROR");
      });
  },

  getHNResumenCobros({ commit }, params) {
    commit("GET_COBROS_STATUS");
    console.log(params);

    return axios
      //.post("/hnresumencobros", params)
      .post("/hnresumencobros_select", params)
      .then(response => {
        //console.log(response.data);
        commit("COBROS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("COBROS_ERROR");
      });
  }
};
