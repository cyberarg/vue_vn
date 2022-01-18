import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  items: [],
  items_valores: [],
  datos: [],
  items_filtrados: [],
  loading: false
};

export const mutations = {
  GET_DATA_STATUS(state) {
    state.dataStatus = "loading";
    state.items = [];
    state.items_valores = [];
    state.loading = true;
  },

  DATOS_SUCCESS(state, datos) {
    //console.log(datos);
    state.items = datos.Reporte;
    state.items_valores = datos.ReporteValores;
    state.datos = datos.Datos;
    state.loading = false;
    state.dataStatus = "success";
  },

  DATOS_ERROR(state) {
    state.dataStatus = "Error";
    state.loading = false;
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
    console.log(pars);
    commit("GET_DATA_STATUS");
    return axios
      .post("/reportecaidas", pars)
      .then(response => {
        console.log(response.data);
        commit("DATOS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
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
