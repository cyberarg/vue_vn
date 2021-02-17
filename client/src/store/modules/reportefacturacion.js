import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  datos_ce: [],
  datos_rb: [],
  detalle_rb:[],
  detalle_rb_ce:[],
  items_filtrados: [],
  loading: false,
  loading_filter: false,
  show_filtrados:false,
};

export const mutations = {
  GET_DATA_STATUS(state) {
    state.dataStatus = "loading";
    state.datos_ce = [];
    state.datos_rb = [];
    state.detalle_rb = [];
    state.detalle_rb_ce = [];
    state.loading = true;
    state.show_filtrados = false;
  },

  DATOS_SUCCESS(state, datos) {
    console.log(datos);
    state.datos_ce = datos.Reporte_CE;
    state.datos_rb = datos.Reporte_RB;
    state.detalle_rb = datos.Detalle_RB_RB;
    state.detalle_rb_ce = datos.Detalle_RB_CE;
    state.loading = false;
    state.dataStatus = "success";
  },

  DATOS_ERROR(state) {
    state.dataStatus = "Error";
    state.loading = false;
  },

  GETTING_FILTRO(state) {
    state.dataStatus = "loading";
    state.loading_filter = true;
    state.show_filtrados = false;
  },

  DATA_FILTERED(state, datos) {
    console.log(datos);
    state.items_filtrados = datos;
    state.loading_filter = false;
    state.show_filtrados = true;
  }

};

export const getters = {
  getFiltradosRB_CE: state => codConcesionario => {
    return state.detalle_rb_ce.filter(function(item) {
      return item.Concesionario === codConcesionario;
    });
  },

  getFiltradosRB: state => codEmpresaOrigenGyO => {
    return state.detalle_rb.filter(function(item) {
      return item.EmpresaOrigenGyO === codEmpresaOrigenGyO;
    });
  }
};

export const actions = {
  getReporte({ commit }, pars) {
    //console.log(pars.periodo);
    commit("GET_DATA_STATUS");
    return axios
      .post("/reportefacturacion", pars)
      .then(response => {
        console.log(response.data);
        commit("DATOS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  getDetalleRB({commit, getters}, codConcesionario){
    console.log(codConcesionario);
    commit("GETTING_FILTRO");
    let datos = getters.getFiltradosRB(codConcesionario);
    commit("DATA_FILTERED", datos);
    
  },

  getDetalleRB_CE({commit, getters}, codEmpresaOrigenGyO){
    //console.log(codEmpresaOrigenGyO);
    commit("GETTING_FILTRO");
    let datos = getters.getFiltradosRB_CE(codEmpresaOrigenGyO);
    commit("DATA_FILTERED", datos);
    
  },

  

};
