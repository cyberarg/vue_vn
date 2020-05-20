import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  dataMesStatus: "",
  dataUnivStatus: "",
  items: [],
  items_totales: [],
  items_resumen: [],
  items_casos_mes: [],
  items_universo: [],
  datos: [],
  empresa: {},
  loading: true,
  loadingtextresumen: "Cargando datos del Resumen... Aguarde por favor",
  loadingmes: true,
  loadingtextmes:
    "Cargando datos de Nuevos Casos del Mes Actual... Aguarde por favor",
  loadinguniv: true,
  loadingtextuniv: "Cargando datos de Universo de Compra... Aguarde por favor",
  okResponse: false,
  showTable: false,
  items_filtrados: [],
  mostrarInfo: false
};

export const mutations = {
  GET_RESUMEN_STATUS(state) {
    state.dataStatus = "loading";
    state.items_resumen = [];
    state.loading = true;
  },

  DATOS_SUCCESS(state, datos) {
    state.items_totales = datos;
  },

  RESUMEN_SUCCESS(state, datos) {
    state.items_resumen = datos;
    state.loading = false;
    state.dataStatus = "success";
  },

  GET_MESACTUAL_STATUS(state) {
    state.dataMesStatus = "loading";
    state.items_casos_mes = [];
    state.loadingmes = true;
  },

  MESACTUAL_SUCCESS(state, datos) {
    state.items_casos_mes = datos;
    state.loadingmes = false;
    state.dataMesStatus = "success";
  },

  UNIVERSO_SUCCESS(state, datos) {
    state.items_universo = datos;
    state.loadinguniv = false;
    state.dataUnivStatus = "success";
  },

  DATOS_ERROR(state) {
    state.dataStatus = "Error";
    state.loading = false;
  },

  GETTING_RESUMEN(state) {
    state.dataStatus = "loading";
  },

  RESUMEN_PROPIOS(state, datos) {
    state.items_filtrados = datos;
    state.mostrarInfo = true;
  }
};

export const actions = {
  getResumen({ commit }, periodo) {
    // console.log(periodo);
    commit("GET_RESUMEN_STATUS");
    return axios
      .get("/reportecomprasresumen?periodo=" + periodo)
      .then(response => {
        console.log(response.data.Datos);
        commit("DATOS_SUCCESS", response.data.Datos);
        commit("RESUMEN_SUCCESS", response.data.Resumen);
        commit("MESACTUAL_SUCCESS", response.data.MesActual);
        commit("UNIVERSO_SUCCESS", response.data.Universo);
        //dispatch("getMesActual", { list: response.data.MesActual });
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  showResumenPropios({ commit, getters }, propiedad) {
    commit("GETTING_RESUMEN");
    switch (propiedad) {
      case "EsPropio":
        var datos = getters.getPropiosFromResumen;
        break;
      case "EsOtrasSociedades":
        var datos = getters.getSociedadesFromResumen;
        break;
      case "EsUniverso":
        var datos = getters.getUniversoFromResumen;
        break;
      case "EsSGA":
        var datos = getters.getSGAFromResumen;
        break;
    }

    commit("RESUMEN_PROPIOS", datos);
  }
};

export const getters = {
  getPropiosFromResumen: state => {
    return state.items_totales.filter(item => item.EsPropio === 1);
  },
  getSociedadesFromResumen: state => {
    return state.items_totales.filter(item => item.EsOtrasSociedades === 1);
  },
  getUniversoFromResumen: state => {
    return state.items_totales.filter(item => item.EsUniverso === 1);
  },
  getSGAFromResumen: state => {
    return state.items_totales;
  }
};
