import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  items: [],
  items_filtrados: [],
  datos: [],
  empresa: {},
  loading: true,
  okResponse: false
};

export const mutations = {
  GET_DATA_STATUS(state) {
    state.dataStatus = "loading";
  },

  DATOS_SUCCESS(state, datos) {
    state.items = datos;
    state.datos = datos;
    state.empresa = datos.Empresa;
    state.loading = false;
    state.dataStatus = "success";
  },

  DATOS_ERROR(state) {
    state.dataStatus = "Error";
  },

  GETTING_FILTRO(state) {
    state.dataStatus = "loading";
  },

  DATA_FILTERED(state, datos) {
    state.items_filtrados = datos;
  }
};

export const getters = {
  getFiltrados: state => (codOficial, codEstado) => {
    console.log(state.datos);
    if (codEstado == "-1") {
      return state.datos.filter(function(item) {
        return item.CodOficial === codOficial;
      });
    } else {
      return state.datos.filter(function(item) {
        return item.CodOficial === codOficial && item.CodEstado === codEstado;
      });
    }
  }
};

export const actions = {
  getData({ commit }, api) {
    commit("GET_DATA_STATUS");
    return axios
      .get("/" + api)
      .then(response => {
        //console.log(response.data);
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
    var datos = getters.getFiltrados(
      parametros.codOficial,
      parametros.codEstado
    );

    console.log(datos);

    commit("DATA_FILTERED", datos);
  }
};
