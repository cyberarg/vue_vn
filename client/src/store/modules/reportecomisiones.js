import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  items: [],
  datos: [],
  items_filtrados: [],
  loading: false,

  dataStatusAnual: "",
  itemsAnual: [],
  datosAnual: [],
  items_filtradosAnual: [],
  loadingAnual: false
};

export const mutations = {
  GET_DATA_STATUS(state) {
    state.dataStatus = "loading";
    state.items = [];
    state.loading = true;
  },

  DATOS_SUCCESS(state, datos) {
    //console.log(datos);
    state.items = datos.Reporte;
    state.datos = datos.Datos;
    state.loading = false;
    state.dataStatus = "success";
  },

  DATOS_ERROR(state) {
    state.dataStatus = "Error";
    state.loading = false;
  },

  GET_DATA_STATUS_ANUAL(state) {
    state.dataStatusAnual = "loading";
    state.itemsAnual = [];
    state.loadingAnual = true;
  },

  DATOS_SUCCESS_ANUAL(state, datos) {
    //console.log(datos);
    state.itemsAnual = datos.Reporte;
    state.datosAnual = datos.Datos;
    state.loadingAnual = false;
    state.dataStatusAnual = "success";
  },

  DATOS_ERROR_ANUAL(state) {
    state.dataStatusAnual = "Error";
    state.loadingAnual = false;
  },

  GETTING_FILTRO(state) {
    state.dataStatus = "loading";
  },

  DATA_FILTERED(state, datos) {
    state.items_filtrados = datos;
  }
};

export const getters = {
  getFiltrados: state => codOficial => {
    console.log(state.datos);

    return state.datos.filter(function(item) {
      return item.CodOficial === codOficial;
    });
  }
};

export const actions = {
  getReporte({ commit }, pars) {
    //console.log(pars.periodo);
    commit("GET_DATA_STATUS");
    return axios
      .post("/reportecomisiones", pars)
      .then(response => {
        console.log(response.data);
        commit("DATOS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  getReporteAnual({ commit }, pars) {
    //console.log(pars.periodo);
    commit("GET_DATA_STATUS_ANUAL");
    return axios
      .post("/reportecomisionesanual", pars)
      .then(response => {
        console.log(response.data);
        commit("DATOS_SUCCESS_ANUAL", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR_ANUAL");
      });
  },

  getReporteDetalle({ commit }, pars) {
    console.log(pars);
    commit("GET_DATA_STATUS_DETALLE");
    return axios
      .post("/reportecomisionesdetalle", pars)
      .then(response => {
        console.log(response.data);
        commit("DATOS_SUCCESS_DETALLE", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR_DETALLE");
      });
  },

  showFiltrados({ commit, getters }, parametros) {
    //console.log(parametros);
    commit("GETTING_FILTRO");
    var datos = getters.getFiltrados(parametros.codOficial);

    //console.log(datos);

    commit("DATA_FILTERED", datos);
  }
};
