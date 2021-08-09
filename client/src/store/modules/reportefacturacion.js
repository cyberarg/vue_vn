import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  datos_ce: [],
  datos_rb: [],
  datos_gb: [],
  detalle_rb:[],
  detalle_rb_ce:[],
  detalle_gb:[],
  detalle_gb_ce:[],
  detalle_conces:[],
  detalle_comi:[],

  table_rb: [],
  table_gb: [],
  table_gral:[],
  
  detalle_gral:[],
  filtrados: [],

  acumulados_rb: [],
  cantAcum_RB: 0,
  acumulados_gb: [],
  cantAcum_GB: 0,
  acumulados_ce: [],
  cantAcum_CE: 0,
  acumulados_tot: [],
  cantAcum_TOT: 0,

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
  loadingdetalle_comi: false,
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
    state.datos_gb = [];
    state.detalle_gb = [];
    state.detalle_gb_ce = [];
    state.periodo_selected = periodo;
    state.loading = true;
    state.show_filtrados = false;
  },

  DATOS_SUCCESS(state, datos) {
    console.log(datos);
    state.datos_ce = datos.Reporte_CE;
    state.datos_rb = datos.Reporte_RB;
    state.datos_gb = datos.Reporte_GB;

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
    state.detalle_gb = [];
    state.detalle_gb_ce = [];

    state.table_gral = [];
    state.table_rb = [];
    state.table_gb = [];

    state.detalle_comisionistas = [];
    state.totalComisiones = 0;
    state.loadingdetalle = true;

    state.acumulados_rb = [];
    state.cantAcum_RB = 0;
    state.acumulados_gb = [];
    state.cantAcum_GB = 0;
    state.acumulados_ce = [];
    state.cantAcum_CE = 0;

    state.acumulados_tot = [];
    state.cantAcum_TOT = 0;
  },

  DETALLE_SUCCESS(state, datos) {
    console.log(datos);
    state.detalle_rb = datos.Detalle_RB_RB;
    state.detalle_rb_ce = datos.Detalle_RB_CE;

    state.detalle_gb = datos.Detalle_GB_GB;
    state.detalle_gb_ce = datos.Detalle_GB_CE;

    state.detalle_comisionistas = datos.ComisionesTerceros;
    state.totalComisiones = datos.TotalComisiones;

    state.acumulados_rb =  datos.Acumulados_RB;
    state.cantAcum_RB = datos.CantAcumulados_RB;

    state.acumulados_gb =  datos.Acumulados_GB;
    state.cantAcum_GB = datos.CantAcumulados_GB;

    state.acumulados_ce = datos.Acumulados_CE;
    state.cantAcum_CE = datos.CantAcumulados_CE;

    state.acumulados_tot =  datos.Acumulados_TOT;
    state.cantAcum_TOT = datos.CantAcumulados_TOT;

    state.table_rb = datos.Tabla_RB;
    state.table_gb = datos.Tabla_GB;
    state.table_gral = datos.Tabla_Gral;

    state.loadingdetalle = false;
    state.dataStatus = "success";
  },

  DETALLE_ERROR(state) {
    state.dataStatus = "Error";
    state.loadingdetalle = false;
    state.table_gral = [];
    state.table_rb = [];
    state.table_gb = [];
    state.acumulados_rb = [];
    state.cantAcum_RB = 0;
    state.acumulados_gb = [];
    state.cantAcum_GB = 0;
    state.acumulados_ce = [];
    state.cantAcum_CE = 0;
    state.acumulados_tot = [];
    state.cantAcum_TOT = 0;

  },


  GET_DETALLE_COMI_STATUS(state) {
    state.dataStatus = "loading";
    state.detalle_comi =  [];

    state.loadingdetalle_comi = true;
  },

  DETALLE_COMI_SUCCESS(state, datos) {
    state.detalle_comi =  datos.Detalle_Comisionistas;

    state.loadingdetalle_comi = false;
    state.dataStatus = "success";
  },

  DETALLE_COMI_ERROR(state) {
    state.dataStatus = "Error";
    state.loadingdetalle_comi = false;
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

  getDetalleComisionista({ commit }, pars){
    commit("GET_DETALLE_COMI_STATUS");
    return axios
      .post("/detallecomisionista", pars)
      .then(response => {
        console.log(response.data);
        commit("DETALLE_COMI_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DETALLE_COMI_ERROR");
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
