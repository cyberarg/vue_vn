import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  datos: [],
  datos_modelo: [],
  loading: false,
  generarModelo: true,
  codMarca: null,
  codConcesionario: null
};

export const mutations = {
  GET_MODELO_STATUS(state, pars) {
    state.generarModelo = true;
    state.dataStatus = "loading";
    state.datos = [];
    state.datos_modelo = [];
    state.loading = true;
    state.codMarca = pars.Marca;
    state.codConcesionario = pars.Concesionario;
  },

  MODELO_SUCCESS(state, respuesta) {
    console.log(respuesta);
    state.dataStatus = "success";

    if (respuesta.length == 0) {
      state.generarModelo = true;
      state.datos = [];
      state.datos_modelo = [];
    } else {
      state.generarModelo = false;
      state.datos = respuesta[0];
      state.datos_modelo =  respuesta[0];
      console.log(state.datos);
    }

    state.loading = false;
  },

  MODELO_ERROR(state) {
    state.dataStatus = "Error";
    state.loading = false;
  },

  GRABAR_MODELO_STATUS(state, pars) {
    state.generarModelo = false;
    state.dataStatus = "loading";
    state.datos = [];
    state.loading = true;
    //state.codMarca = pars.Marca;
    //state.codConcesionario = pars.Concesionario;
  },

  GRABAR_MODELO_SUCCESS(state, respuesta) {
    console.log(respuesta);
    state.dataStatus = "success";

    if (respuesta.length > 0) {
      state.generarModelo = false;
      state.datos = respuesta[0];
      console.log(state.datos);
    }

    state.loading = false;
  },

  GRABAR_MODELO_ERROR(state) {
    state.dataStatus = "Error";
    state.loading = false;
  }
};

export const getters = {
  //
};

export const actions = {
  getModeloControl({ commit }, pars) {
    commit("GET_MODELO_STATUS", pars);
    return axios
      .post("/getmodelocontrol", pars)
      .then(response => {
        //console.log(response.data);
        commit("MODELO_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("MODELO_ERROR");
      });
  },

  grabarModelo({ commit, dispatch }, pars) {
    commit("GRABAR_MODELO_STATUS", pars);
    return axios
      .post("/grabarmodelo", pars)
      .then(response => {
        //console.log(response.data);
        commit("GRABAR_MODELO_SUCCESS", response.data);
        var parsMC = {
          Marca: pars.Marca,
          Concesionario: pars.Concesionario
        };
        dispatch("getModeloControl", parsMC);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("GRABAR_MODELO_ERROR");
      });
  }
};
