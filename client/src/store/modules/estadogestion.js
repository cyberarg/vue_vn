import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  items: [],
  items_filtrados: [],
  datos: [],
  datos_totales: [],
  empresa: {},
  loading: false,
  okResponse: false
};

export const mutations = {
  CLEAR_STATE(state) {
    state.dataStatus = "";
    state.items = [];
    state.items_filtrados = [];
    state.datos = [];
  },

  GET_DATA_STATUS(state) {
    state.dataStatus = "loading";
    state.loading = true;
    state.datos = [];
    state.items = [];
  },

  DATOS_SUCCESS(state, datos) {
    console.log(datos["TotalDatos"]);
    state.items = datos["Estados"];
    state.datos = datos["Estados"];
    state.datos_totales = datos["TotalDatos"];
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
  getFiltrados: state => (codOficial, codEstado, codConces) => {
    //console.log(state.datos_totales);

    switch (codEstado) {
      case "0":
        return state.datos_totales.filter(function(item) {
          return (
            item.CodOficial === codOficial &&
            item.Concesionario == codConces &&
            item.CodEstado == null
          );
        });
        break;
      case "-1":
        return state.datos_totales.filter(function(item) {
          return (
            item.CodOficial === codOficial && item.Concesionario == codConces
          );
        });
        break;
      default:
        return state.datos_totales.filter(function(item) {
          return (
            item.CodOficial === codOficial &&
            item.CodEstado === codEstado &&
            item.Concesionario == codConces
          );
        });
        break;
    }
  }
};

export const actions = {
  getData({ commit }, pars) {
    commit("GET_DATA_STATUS");
    return axios
      .post("/estadogestion", pars)
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
    var datos = getters.getFiltrados(
      parametros.codOficial,
      parametros.codEstado,
      parametros.Concesionario
    );

    console.log(datos);

    commit("DATA_FILTERED", datos);
  }
};
