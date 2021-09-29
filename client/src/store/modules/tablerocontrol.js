import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  items: [],
  datos: [],
  datos_cartera: [],
  items_cartera: [],
  items_filtrados: [],
  loading: false,
  loading_cartera: false,
  loading_items_detalle_cartera:false,
  getting_items_detalle_cartera:false,
  items_detalle_cartera:[],
  items_detalle_compras:[],
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
    state.loading_items_detalle_cartera = true;
  },

  GET_DETALLE_EVOL_STATUS(state) {
    
    state.dataStatus = "loading";
    state.items_detalle_compras = [];
    state.loading_detalle_evolucion = true;
  },

  DETALLE_EVOL_SUCCESS(state, datos) {
    //console.log(datos);
    state.items_detalle_compras = datos.Reporte;
    state.loading_detalle_evolucion = false;

    state.dataStatus = "success";
  },

  DETALLE_EVOL_ERROR(state) {
    state.dataStatus = "Error";
    state.loading_detalle_evolucion = false;
    state.items_detalle_compras = [];
  },

  GET_CARTERA_STATUS(state) {
    state.dataStatus = "loading";
    state.datos_cartera = [];
    state.items_cartera = [];
    state.loading_cartera = true;
    state.loading_items_detalle_cartera = true;
  },

  CARTERA_SUCCESS(state, datos) {
    //console.log(datos);
    state.datos_cartera = datos.Reporte;
    state.items_cartera = datos.Reporte;
    state.loading_cartera = false;
    state.loading_items_detalle_cartera = false;
    state.dataStatus = "success";
  },

  CARTERA_ERROR(state) {
    state.dataStatus = "Error";
    state.loading_cartera = false;
    state.loading_items_detalle_cartera = true;
  },

  GET_DETALLE_CARTERA_STATUS(state) {
    state.dataStatus = "loading";
    state.items_detalle_cartera = [];
    state.loading_items_detalle_cartera = true;
    state.getting_items_detalle_cartera = true;
  },

  CARTERA_DETALLE_SUCCESS(state, datos) {
    //console.log(datos);
    state.items_detalle_cartera = datos.Detalle;
    state.loading_items_detalle_cartera = false;
    state.getting_items_detalle_cartera = false;
    state.dataStatus = "success";
  },

  CARTERA_DETALLE_ERROR(state) {
    state.dataStatus = "Error";
    state.items_detalle_cartera = [];
    state.loading_items_detalle_cartera = true;
    state.getting_items_detalle_cartera = false;
  },

  GETTING_FILTRO(state) {
    state.dataStatus = "loading";
  },

  DATA_FILTERED(state, datos) {
    state.items_filtrados = datos;
  }
};

export const getters = {
  getFiltrados: state => codConcesionario => {
    console.log(state.datos);

    return state.datos.filter(function(item) {
      return item.Concesionario === codConcesionario;
    });
  }
};

export const actions = {
  getDatos({ commit }, pars) {
    //console.log(pars.periodo);
    commit("GET_DATA_STATUS");
    return axios
      .post("/tablerocontrol", pars)
      .then(response => {
        console.log(response.data);
        commit("DATOS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  getCarteraGral({ commit }, pars) {
    //console.log(pars.periodo);
    commit("GET_CARTERA_STATUS");
    return axios
      .post("/reportecarteradashboard", pars)
      .then(response => {
        console.log(response.data);
        commit("CARTERA_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("CARTERA_ERROR");
      });
  },

  getDetallePendientesCarteraGral({ commit }) {
    //console.log(pars.periodo);
    commit("GET_DETALLE_CARTERA_STATUS");
    return axios
      .post("/reportecarteradetalledashboard")
      .then(response => {
        //console.log(response.data);
        commit("CARTERA_DETALLE_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("CARTERA_DETALLE_ERROR");
      });
  },

  getDetalle({ commit }) {
    //console.log(pars.periodo);
    commit("GET_DETALLE_EVOL_STATUS");
    return axios
      .post("/detalle_evol_compras")
      .then(response => {
        console.log(response.data);
        commit("DETALLE_EVOL_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DETALLE_EVOL_ERROR");
      });
  },
  
  

  showFiltrados({ commit, getters }, parametros) {
    //console.log(parametros);
    commit("GETTING_FILTRO");
    var datos = getters.getFiltrados(parametros.codConcesionario);

    //console.log(datos);

    commit("DATA_FILTERED", datos);
  }
};
