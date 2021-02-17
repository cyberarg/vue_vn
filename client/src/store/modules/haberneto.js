import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  dataStatusInsert: "",
  dataStatusMsgInsert: "",
  dataStatusMsgOperacion: "",
  obsStatus: "",
  observaciones: [],
  datos: [],
  datosGiama: [],
  datosCE: [],
  datosHNCobrados: [],
  datosHNCobradosGiama: [],
  datosHNCobradosCE: [],
  items_modelos: [],
  items_planes: [],
  items_listas:[],
  loadingStatusInsert: false,
  loadingCombos: false,
  loadingDatos: false,
  loadingHNV: false,
  loadingHNC: false,
  loadingObs: true,
  loadingPlanes: true,
  loadingListas: true,
  loadingModelos: true,
  loadingCalculadora: false,
  hnreal: 0,
  hnformula: 0,
  operacionBuscada: {
    ID: null,
    HaberNeto: null,
    PrecioCompra: null,
    NroTransferencia: null,
    EmpresaGyO: null
  },
  loadingBusqueda: false,
  encontroOp: true,
  dataStatusBusqueda: "",
  dataStatusMsgBusqueda: "",
  loadingCobro: false,
  dataStatusMsgCobro: "",
  dataStatusCobro: "",
  loadingTransfer: false,
  transferUtilizada: false,
  dataStatusTransfer: "",
  dataStatusMsgTransfer: ""
};

export const mutations = {
  CLEAR_COMPRA_HN(state) {
    state.encontroOp = true;
    state.dataStatusBusqueda = "";
    state.dataStatusMsgBusqueda = "";
    state.dataStatusBusqueda = "success";
    state.encontroOp = true;
    //console.log(dato);
    state.operacionBuscada = {
      ID: null,
      HaberNeto: null,
      PrecioCompra: null
    };
  },

  CLEAR_GRIDS_HN(state) {

    state.datos = [];
    state.datosGiama = [];
    state.datosCE = [];

    state.datosHNCobrados = [];
    state.datosHNCobradosGiama = [];
    state.datosHNCobradosCE = [];

    /*
    state.datos = datos.ListHN;
    state.datosGiama = datos.ListHN_ComproGiama;
    state.datosCE = datos.ListHN_CE;
    */
  },

  GET_HNV_STATUS(state) {
   
    state.datos = [];
    state.datosGiama = [];
    state.datosCE = [];

    state.datosHNCobrados = [];
    state.datosHNCobradosGiama = [];
    state.datosHNCobradosCE = [];

    state.dataStatus = "loading";
    state.loadingHNV = true;

  },

  HNV_SUCCESS(state, datos) {
    console.log(datos.ListHN);

    state.datos = datos.ListHN;
    state.datosGiama = datos.ListHN_ComproGiama;
    state.datosCE = datos.ListHN_CE;

    state.dataStatus = "success";
    state.loadingHNV = false;
  },

  GET_HNC_STATUS(state) {
    state.dataStatus = "loading";
    state.loadingDatos = false;
    state.loadingHNC = true;

    state.datos = [];
    state.datosGiama = [];
    state.datosCE = [];

    state.datosHNCobrados = [];
    state.datosHNCobradosGiama = [];
    state.datosHNCobradosCE = [];
  },

  HNC_SUCCESS(state, datos) {
    //console.datos;
    state.datosHNCobrados = datos.ListHNCobrados;
    state.datosHNCobradosGiama = datos.ListHN_C_ComproGiama;
    state.datosHNCobradosCE = datos.ListHN_C_CE;

    state.loadingHNC = false;
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

  GET_LISTAS_STATUS(state) {
    state.loadingCombos = true;
    state.hnreal = 0;
    state.hnformula = 0;
  },

  LISTAS_SUCCESS(state, data) {
    state.loadingListas = false;
    state.loadingCombos = false;
    state.items_listas = data;
    state.hnreal = 0;
    state.hnformula = 0;
  },

  

  HN_ERROR(state) {
    state.dataStatus = "Error";
    state.datos = [];
    state.hnreal = 0;
    state.hnformula = 0;
  },

  GET_OPERACION_STATUS(state) {
    state.dataStatus = "loading";
    state.loadingBusqueda = true;
    state.dataStatusMsgOperacion = "Obteniendo datos, aguarde por favor...";
  },

  GET_OPERACION_SUCCESS(state, dato) {
    if (dato.length == 0) {
      state.encontroOp = false;
      state.dataStatusBusqueda = "error";
      state.dataStatusMsgBusqueda = "No se encontró la operación solicitada.";
    } else {
      state.dataStatusBusqueda = "success";
      state.encontroOp = true;
      //console.log(dato);
      state.operacionBuscada = dato[0];
    }
    state.loadingBusqueda = false;
  },

  NEW_HN_STATUS(state) {
    state.dataStatusInsert = "loading";
    state.dataStatusMsgInsert = "Grabando Haber Neto, aguarde por favor...";
    state.loadingStatusInsert = true;
  },

  NEW_HN_SUCCESS(state, respuesta) {
    state.dataStatusInsert = "success";
    state.dataStatusMsgInsert = "Haber Neto grabado exitosamente.";
    state.loadingStatusInsert = false;

    console.log(respuesta);
  },

  NEW_HN_ERROR(state, respuesta) {
    state.dataStatusInsert = "error";
    state.dataStatusMsgInsert =
      "Ocurrió un error al intentar grabar el Haber Neto.";
    state.loadingStatusInsert = false;

    console.log(respuesta);
  },

  NEW_COBRO_STATUS(state) {
    state.loadingCobro = true;
    state.dataStatusCobro = "loading";
    state.dataStatusMsgCobro =
      "Grabando Cobro de Haber Neto, aguarde por favor...";
  },

  NEW_COBRO_SUCCESS(state, respuesta) {
    state.dataStatusCobro = "success";
    state.dataStatusMsgCobro = "Cobro de Haber Neto grabado exitosamente.";
    state.loadingCobro = false;
    console.log(respuesta);
  },

  NEW_COBRO_ERROR(state, respuesta) {
    state.dataStatusCobro = "error";
    state.dataStatusMsgCobro =
      "Ocurrió un error al intentar grabar el cobro de Haber Neto.";
    state.loadingCobro = false;
    console.log(respuesta);
  },

  CHECK_TRANSFER_STATUS(state) {
    state.loadingTransfer = true;
    state.dataStatusTransfer = "loading";
    state.dataStatusMsgTransfer =
      "Verificando Nro. de Transferencia, aguarde por favor...";
  },

  CHECK_TRANSFER_SUCCESS(state, respuesta) {
    state.dataStatusTransfer = "success";
    state.loadingTransfer = false;
    state.dataStatusMsgTransfer = respuesta["Msg"];
    state.transferUtilizada = respuesta["EstaUtilizada"];

    console.log(respuesta);
  },

  CHECK_TRANSFER_ERROR(state, respuesta) {
    state.dataStatusTransfer = "error";
    state.loadingTransfer = false;
    state.dataStatusMsgTransfer =
      "Ocurrió un error al intentar verificar el Nro. de Transferencia.";

    console.log(respuesta);
  }
};

export const getters = {
  //
};

export const actions = {
  clearCompra({ commit }) {
    commit("CLEAR_COMPRA_HN");
  },

  getHNVigentes({ commit, dispatch }, params) {
    commit("GET_HNV_STATUS");
    return axios
      //.post("/haberesnetos", params)
      .post("/haberesnetos_select", params)
      .then(response => {
        console.log(response.data);
        commit("HNV_SUCCESS", response.data);
        dispatch("setValoresDolar", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("HN_ERROR");
      });
  },

  getHNCobrados({ commit }, params) {
    commit("GET_HNC_STATUS");
    return axios
      //.post("/haberesnetoscobrados", params)
      .post("/haberesnetoscobrados_select", params)
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
      .get("/getmodeloshn?marca=" + marca)
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
      .get("/getplaneshn?marca=" + p.marca + "&modelo=" + p.modelo)
      .then(response => {
        console.log(response.data);
        commit("PLANES_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("HN_ERROR");
      });
  },

  getListas({ commit }, p) {
    commit("GET_LISTAS_STATUS");
    console.log(p);
    return axios
      .get("/getlistashn?marca=" + p.marca + "&modelo=" + p.modelo)
      .then(response => {
        console.log(response.data);
        commit("LISTAS_SUCCESS", response.data);
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

  getCalculoHNGuido({ commit }, params) {
    commit("GET_CALCULADORAHN_STATUS");
    console.log(params);
    return axios
      .post("/calculadorahnguido", params)
      .then(response => {
        console.log(response.data);
        commit("CALCULADORAHN_SUCCESS", response.data[0]);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("HN_ERROR");
      });
  },
  

  getOperacion({ commit }, params) {
    commit("GET_OPERACION_STATUS");
    console.log(params);
    return axios
      .post("/getoperacionhn", params)
      .then(response => {
        //console.log(response.data);
        commit("GET_OPERACION_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("HN_ERROR");
      });
  },

  grabarHaberNeto({ commit }, params) {
    commit("NEW_HN_STATUS");
    console.log(params);
    return axios
      .post("/grabarhn", params)
      .then(response => {
        //console.log(response.data);
        commit("NEW_HN_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("NEW_HN_ERROR");
      });
  },

  checkTransferFiat({ commit }, params) {
    commit("CHECK_TRANSFER_STATUS");
    console.log(params);
    return axios
      .post("/checknrotransfer", params)
      .then(response => {
        //console.log(response.data);
        commit("CHECK_TRANSFER_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("CHECK_TRANSFER_ERROR");
      });
  },

  cobroHaberNeto({ commit }, params) {
    commit("NEW_COBRO_STATUS");
    console.log(params);
    return axios
      .post("/cobrarhn", params)
      .then(response => {
        //console.log(response.data);
        commit("NEW_COBRO_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("NEW_COBRO_ERROR");
      });
  },

  setValoresDolar(datos) {}
};
