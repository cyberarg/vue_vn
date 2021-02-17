import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  items: [],
  datos: [],
  items_filtrados: [],
  empresa: {},
  loading: true,
  okResponse: false,
  showTable: false
};

export const mutations = {
  GET_DATA_STATUS(state) {
    state.dataStatus = "loading";
    state.items = [];
    state.loading = true;
    state.showTable = true;
  },

  DATOS_SUCCESS(state, datos) {
    console.log(datos);
    state.items = datos.Reporte;
    state.datos = datos.Datos;
    //state.empresa = datos.Empresa;
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
  getFiltrados: state => (codOficial, codEstado) => {
    switch (codEstado) {
      case "-1":
        return state.datos.filter(function(item) {
          return item.CodOficial === codOficial;
        });
        break;
      case "0":
        return state.datos.filter(function(item) {
          return item.CodOficial === codOficial && item.CodEstado === null;
        });
        break;
      default:
        return state.datos.filter(function(item) {
          return item.CodOficial === codOficial && item.CodEstado === codEstado;
        });
        break;
    }
  }
};

export const actions = {
  getData({ commit }, pars) {
    //console.log(pars.periodo);
    commit("GET_DATA_STATUS");
    return axios
      .post("/reporteasignacion", pars)
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
      parametros.codEstado
    );

    console.log(datos);

    commit("DATA_FILTERED", datos);
  }
};
