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
  items_filtrados: []
};

export const mutations = {
  GET_RESUMEN_STATUS(state) {
    state.dataStatus = "loading";
    state.items_resumen = [];
    state.loading = true;
    state.items_casos_mes = [];
    state.loadingmes = true;
    state.items_universo = [];
    state.loadinguniv = true;
    state.showTable = true;
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
    state.showTable = true;
  },

  GETTING_DETALLE_AVANCE(state) {
    state.dataStatus = "loading";
  },

  DETALLE_AVANCE(state, datos) {
    state.items_filtrados = datos;
    state.showTable = true;
  },

  GETTING_DETALLE_UNIVERSO(state) {
    state.dataStatus = "loading";
  },

  DETALLE_UNIVERSO(state, datos) {
    state.items_filtrados = datos;
    console.log(datos);
    state.showTable = true;
  }
};

export const actions = {
  getResumen({ commit }, params) {
    console.log(params);
    commit("GET_RESUMEN_STATUS");
    return axios
      .post("/reportecomprasresumen", params)
      .then(response => {
        console.log(response.data.Debbug);
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
      case "PropiosyOtrasSoc":
        var datos = getters.getPropiosSociedadesFromResumen;
        break;

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
  },

  showDetalleAvanceMes({ commit, getters }, tipo) {
    commit("GETTING_DETALLE_AVANCE");
    var datos = getters.getAvanceMesActual(tipo);
    //console.log(getters.getAvanceMesActual(tipo));
    commit("DETALLE_AVANCE", datos);
  },

  showDetalleUniverso({ commit, getters }, tipo) {
    commit("GETTING_DETALLE_UNIVERSO");
    var datos = getters.getUniverso(tipo);
    //console.log(getters.getAvanceMesActual(tipo));
    commit("DETALLE_UNIVERSO", datos);
  }
};

export const getters = {
  getPropiosFromResumen: state => {
    return state.items_totales.filter(item => item.EsPropio === 1);
  },
  getSociedadesFromResumen: state => {
    return state.items_totales.filter(item => item.EsOtrasSociedades === 1);
  },
  getPropiosSociedadesFromResumen: state => {
    return state.items_totales.filter(function(item) {
      return item.EsOtrasSociedades === 1 || item.EsPropio === 1;
    });
  },

  getUniversoFromResumen: state => {
    return state.items_totales.filter(item => item.EsUniverso === 1);
  },
  getSGAFromResumen: state => {
    return state.items_totales;
  },

  getUniverso: state => tipo => {
    switch (tipo) {
      case -1:
        return state.items_totales.filter(function(item) {
          return item.EsUniverso === 1;
        });
      case 0:
        return state.items_totales.filter(function(item) {
          return item.AvanceAutomatico === 0 && item.EsUniverso === 1;
        });

        break;

      case 20:
        //console.log("Entro en el case 60");
        return state.items_totales.filter(function(item) {
          return (
            item.AvanceAutomatico >= 1 &&
            item.AvanceAutomatico <= 20 &&
            item.EsUniverso === 1
          );
        });

        break;

      case 30:
        //console.log("Entro en el case 60");
        return state.items_totales.filter(function(item) {
          return (
            item.AvanceAutomatico >= 21 &&
            item.AvanceAutomatico <= 30 &&
            item.EsUniverso === 1
          );
        });

        break;

      case 40:
        //console.log("Entro en el case 60");
        return state.items_totales.filter(function(item) {
          return (
            item.AvanceAutomatico >= 31 &&
            item.AvanceAutomatico <= 40 &&
            item.EsUniverso === 1
          );
        });

        break;

      case 50:
        //console.log("Entro en el case 60");
        return state.items_totales.filter(function(item) {
          return (
            item.AvanceAutomatico >= 41 &&
            item.AvanceAutomatico <= 50 &&
            item.EsUniverso === 1
          );
        });

        break;
      case 60:
        //console.log("Entro en el case 60");
        return state.items_totales.filter(function(item) {
          return (
            item.AvanceAutomatico >= 51 &&
            item.AvanceAutomatico <= 60 &&
            item.EsUniverso === 1
          );
        });

        break;
      case 70:
        return state.items_totales.filter(function(item) {
          return (
            item.AvanceAutomatico >= 61 &&
            item.AvanceAutomatico <= 70 &&
            item.EsUniverso === 1
          );
        });

        break;
      case 80:
        return state.items_totales.filter(function(item) {
          return (
            item.AvanceAutomatico >= 71 &&
            item.AvanceAutomatico <= 80 &&
            item.EsUniverso === 1
          );
        });

        break;
      case 83:
        return state.items_totales.filter(function(item) {
          return (
            item.AvanceAutomatico >= 81 &&
            item.AvanceAutomatico <= 83 &&
            item.EsUniverso === 1
          );
        });

        break;
    }
  },

  getAvanceMesActual: state => tipo => {
    switch (tipo) {
      case -1:
        return state.items_totales.filter(function(item) {
          return item.EsUniverso === 1 && item.EsMesActual === 1;
        });
      case 0:
        return state.items_totales.filter(function(item) {
          return (
            item.AvanceAutomatico === 0 &&
            item.EsUniverso === 1 &&
            item.EsMesActual === 1
          );
        });

        break;

      case 44:
        //console.log("Entro en el case 60");
        return state.items_totales.filter(function(item) {
          return (
            item.AvanceAutomatico >= 1 &&
            item.AvanceAutomatico <= 44 &&
            item.EsUniverso === 1 &&
            item.EsMesActual === 1
          );
        });

        break;

      case 60:
        //console.log("Entro en el case 60");
        return state.items_totales.filter(function(item) {
          return (
            item.AvanceAutomatico >= 45 &&
            item.AvanceAutomatico <= 60 &&
            item.EsUniverso === 1 &&
            item.EsMesActual === 1
          );
        });

        break;
      case 70:
        return state.items_totales.filter(function(item) {
          return (
            item.AvanceAutomatico >= 61 &&
            item.AvanceAutomatico <= 70 &&
            item.EsUniverso === 1 &&
            item.EsMesActual === 1
          );
        });

        break;
      case 80:
        return state.items_totales.filter(function(item) {
          return (
            item.AvanceAutomatico >= 71 &&
            item.AvanceAutomatico <= 80 &&
            item.EsUniverso === 1 &&
            item.EsMesActual === 1
          );
        });

        break;
      case 83:
        return state.items_totales.filter(function(item) {
          return (
            item.AvanceAutomatico >= 81 &&
            item.AvanceAutomatico <= 83 &&
            item.EsUniverso === 1 &&
            item.EsMesActual === 1
          );
        });

        break;
    }
  }
};
