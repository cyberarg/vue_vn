import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  cotizaciones: [],
  cotizaciones_filtradas: [],
  loading: true
};

export const mutations = {
  GET_COTIZACION_STATUS(state) {
    state.dataStatus = "loading";
    state.items = [];
    state.loading = true;
    state.showTable = true;
  },

  COTIZACION_SUCCESS(state, datos) {
    state.cotizaciones = datos;
    localStorage.setItem("cotizacionDolar", JSON.stringify(datos));
    state.loading = false;
    state.dataStatus = "success";
  },

  COTIZACION_ERROR(state) {
    state.dataStatus = "Error";
    state.loading = false;
  },

  GETTING_FILTRO(state) {
    state.dataStatus = "loading";
    state.loading = true;
  },

  COTIZACION_FILTERED(state, datos) {
    state.cotizaciones_filtradas = datos;
    state.loading = false;
  }
};

export const getters = {
  //
};

export const actions = {
  getCotizaciones({ commit }) {
    commit("GET_COTIZACION_STATUS");
    return axios
      .get("/cotizaciondolar")
      .then(response => {
        //console.log(response.data);
        commit("COTIZACION_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("COTIZACION_ERROR");
      });
  },

  getCotizacion() {
    var cotizaciones = JSON.parse(localStorage.getItem("cotizacionDolar"));
    var fecha = "2010-02-09";
    if (cotizaciones) {
      console.log(cotizaciones.filter(item => item.Fecha === fecha));
    }

    return 0;
  }
};
