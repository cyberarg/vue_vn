import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  datos_ce: [],
  datos_rb: [],
  detalle_rb:[],
  detalle_rb_ce:[],
  detalle_conces:[],

  table_rb: [],
  table_gral:[],
  
  detalle_gral:[],
  filtrados: [],

  detalle_comisionistas:[],
  totalComisiones: 0,

  periodo_selected: '',
  concesionarios_facturados: '',

  items_filtrados: [],
  loading: false,
  loadingdetalle: false,
  loading_filter: false,
  show_filtrados:false,
  loadingdetalle_ce: false,
  loadingdetalle_gral: false,
  
  disableButtonResumenPDF:false,
  disableButtonDetalleGralXLS: false,
  
};

export const mutations = {
  GET_DATA_STATUS(state, periodo) {
    state.dataStatus = "loading";
    state.datos_ce = [];
    state.datos_rb = [];
    state.detalle_rb = [];
    state.detalle_rb_ce = [];
    state.periodo_selected = periodo;
    state.loading = true;
    state.show_filtrados = false;
  },

  DATOS_SUCCESS(state, datos) {
    console.log(datos);
    state.datos_ce = datos.Reporte_CE;
    state.datos_rb = datos.Reporte_RB;

    //state.detalle_rb = datos.Detalle_RB_RB;
    //state.detalle_rb_ce = datos.Detalle_RB_CE;
    state.loading = false;
    state.dataStatus = "success";
  },

  DATOS_ERROR(state) {
    state.dataStatus = "Error";
    state.loading = false;
  },

  
  GET_DETALLE_STATUS(state) {
    state.dataStatus = "loading";
    state.detalle_rb = [];
    state.detalle_rb_ce = [];

    state.table_gral = [];
    state.table_rb = [];

    state.detalle_comisionistas = [];
    state.totalComisiones = 0;
    state.loadingdetalle = true;


  },

  DETALLE_SUCCESS(state, datos) {
    console.log(datos);
    state.detalle_rb = datos.Detalle_RB_RB;
    state.detalle_rb_ce = datos.Detalle_RB_CE;

    state.detalle_comisionistas = datos.ComisionesTerceros;
    state.totalComisiones = datos.TotalComisiones;


    state.table_rb = datos.Tabla_RB;
    state.table_gral = datos.Tabla_Gral;

    state.loadingdetalle = false;
    state.dataStatus = "success";
  },

  DETALLE_ERROR(state) {
    state.dataStatus = "Error";
    state.loadingdetalle = false;
    state.table_gral = [];
    state.table_rb = [];

  },

  GET_DETALLE_CE_STATUS(state) {
    state.dataStatus = "loading";
    state.detalle_conces =  [];

    state.loadingdetalle_ce = true;
  },

  DETALLE_CE_SUCCESS(state, datos) {
    state.detalle_conces =  datos.Detalle_CE;

    state.loadingdetalle_ce = false;
    state.dataStatus = "success";
  },

  DETALLE_CE_ERROR(state) {
    state.dataStatus = "Error";
    state.loadingdetalle_ce = false;
  },

  GET_DETALLE_GRAL_STATUS(state) {
    state.dataStatus = "loading";
    state.detalle_gral =  [];

    state.disableButtonResumenPDF = true;
    state.disableButtonDetalleGralXLS = true;

    state.loadingdetalle_gral = true;
  },

  DETALLE_GRAL_SUCCESS(state, datos) {
    state.detalle_gral =  datos.Detalle_Gral;

    state.disableButtonResumenPDF = false;
    state.disableButtonDetalleGralXLS = false;

    state.loadingdetalle_gral = false;
    state.dataStatus = "success";
  },

  DETALLE_GRAL_ERROR(state) {
    state.dataStatus = "Error";
    state.loadingdetalle_gral = false;

    state.disableButtonResumenPDF = true;
    state.disableButtonDetalleGralXLS = true;
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

  /*
  getFiltradosPor_CE: state => codConcesionario => {
    return state.detalle_conces.filter(function(item) {
      return item.Concesionario === codConcesionario;
    });
  },
  */

  getFiltradosRB: state => codEmpresaOrigenGyO => {
    return state.detalle_rb.filter(function(item) {
      return item.EmpresaOrigenGyO === codEmpresaOrigenGyO;
    });
  }
};

export const actions = {
  getReporte({ commit }, pars) {
    //console.log(pars.periodo);
    commit("GET_DATA_STATUS", pars.periodo);
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

  getDetalleReporte({ commit }, pars) {
    //console.log(pars.periodo);
    commit("GET_DETALLE_STATUS");
    return axios
      .post("/detallefacturacion", pars)
      .then(response => {
        console.log(response.data);
        commit("DETALLE_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DETALLE_ERROR");
      });
  },

  getDetalleConcesionario({ commit }, pars){
    commit("GET_DETALLE_CE_STATUS");
    return axios
      .post("/detalleporconces", pars)
      .then(response => {
        console.log(response.data);
        commit("DETALLE_CE_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DETALLE_CE_ERROR");
      });
    
  },

  getDetalleGeneral({ commit }, pars){
    commit("GET_DETALLE_GRAL_STATUS");
    return axios
      .post("/detallegeneral", pars)
      .then(response => {
        console.log(response.data);
        commit("DETALLE_GRAL_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DETALLE_GRAL_ERROR");
      });
    
  },

  

  getDetalleRB({commit, getters}, codConcesionario){
    console.log(codConcesionario);
    commit("GETTING_FILTRO");
    let datos = getters.getFiltradosRB(codConcesionario);
    commit("DATA_FILTERED", datos);
    
  },

  getDetallePor_CE({commit, getters}, codConcesionario){
    console.log(codConcesionario);
    commit("GETTING_FILTRO");
    let datos = getters.getFiltradosPor_CE(codConcesionario);
    commit("DATA_FILTERED", datos);
    
  },

  getDetalleRB_CE({commit, getters}, codEmpresaOrigenGyO){
    //console.log(codEmpresaOrigenGyO);
    commit("GETTING_FILTRO");
    let datos = getters.getFiltradosRB_CE(codEmpresaOrigenGyO);
    commit("DATA_FILTERED", datos);
    
  },

  

};
