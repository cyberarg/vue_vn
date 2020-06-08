import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  dataStatusMsg: "",
  items: [],
  items_totales: [],
  listOficiales: [],
  listSupervisores: [],
  loading: true,
  unselect: false,
  repuesta: []
};

export const mutations = {
  GET_FILTERED_DATA_STATUS(state) {
    state.dataStatus = "loading";
    state.showMsg = false;
  },

  FILTERED_SUCCESS(state, datos) {
    state.items = datos;
    state.loading = false;
    state.dataStatus = "success";
    state.showMsg = false;
  },

  GET_DATA_STATUS(state) {
    state.dataStatus = "loading";
  },

  DATOS_SUCCESS(state, datos) {
    state.items = datos;
    state.items_totales = datos;
    state.loading = false;
    state.dataStatus = "success";
  },

  GET_OFICIALES_STATUS(state) {
    state.dataStatus = "loading";
  },

  GET_SUPERVISORES_STATUS(state) {
    state.dataStatus = "loading";
  },

  OFICIALES_SUCCESS(state, datos) {
    state.listOficiales = datos;
  },

  SUPERVISORES_SUCCESS(state, datos) {
    state.listSupervisores = datos;
  },

  OK_RESPONSE(state, respuesta) {
    state.repuesta = respuesta;
    state.unselect = true;
  },

  DATOS_ERROR(state) {
    state.dataStatus = "error";
  },

  SEND_SELECCION(state) {
    state.dataStatus = "loading";
    state.loading = true;
  },
  SET_TOTALES(state) {
    state.items = state.items_totales;
  }
};

export const getters = {
  getDatoById: state => ID => {
    return state.items.find(item => item.ID === ID);
  },

  filterItemsByConcesionario: state => codConc => {
    console.log(state.items_totales);
    return state.items_totales.filter(function(item) {
      return parseInt(item.Concesionario) === codConc;
    });
  }
};

export const actions = {
  getData({ commit, dispatch }, api) {
    commit("GET_DATA_STATUS");
    return axios
      .get("/" + api)
      .then(response => {
        dispatch("loadComboOficiales");
        dispatch("loadComboSupervisores");
        commit("DATOS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  filterData({ commit, getters }, conc) {
    commit("GET_FILTERED_DATA_STATUS");
    if (conc === 0) {
      commit("SET_TOTALES");
    } else {
      var filtrado = getters.filterItemsByConcesionario(conc);
      console.log(filtrado);
      commit("FILTERED_SUCCESS", filtrado);
    }
  },

  reloadItems({ commit }) {
    commit("GET_DATA_STATUS");
    return axios
      .get("/asignaciondatos")
      .then(response => {
        commit("DATOS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  asignarDatos({ commit, dispatch }, params) {
    commit("SEND_SELECCION");
    console.log(params);
    //data = params.data;
    //oficial = params.oficial;

    return axios
      .post("/asignardatos", {
        data: params.data,
        oficial: params.oficial,
        supervisor: params.supervisor,
        login: params.login
      })
      .then(response => {
        console.log(response);
        commit("OK_RESPONSE", response);
        dispatch("reloadItems");
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  pasarASinGestionar({ commit, dispatch }, params) {
    commit("SEND_SELECCION");
    console.log(params);

    return axios
      .post("/pasarsingestion", {
        data: params.data
      })
      .then(response => {
        console.log(response);
        commit("OK_RESPONSE", response);
        dispatch("reloadItems");
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  async loadComboOficiales({ commit }) {
    commit("GET_OFICIALES_STATUS");
    return axios
      .get("/oficiales")
      .then(response => {
        //console.log(response.data);
        commit("OFICIALES_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  async loadComboSupervisores({ commit }) {
    commit("GET_SUPERVISORES_STATUS");
    return axios
      .get("/supervisores")
      .then(response => {
        //console.log(response.data);
        commit("SUPERVISORES_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  }
};
