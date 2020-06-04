import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  dataStatusMsg: "",
  items: [],
  motivos: [],
  estados: [],
  item: {},
  obsStatus: "",
  observaciones: [],
  loading: true,
  loadingObs: true,
  showMsg: false
};

export const mutations = {
  GET_DATA_STATUS(state) {
    state.dataStatus = "loading";
    state.showMsg = false;
  },

  DATOS_SUCCESS(state, datos) {
    state.items = datos;
    state.loading = false;
    state.dataStatus = "success";
    state.showMsg = false;
  },

  SET_DATA_STATUS(state, colection) {
    state.items = colection;
    state.loading = false;
    state.dataStatus = "success";
    state.showMsg = false;
  },

  SAVING_DATA(state) {
    state.loading = true;
    state.dataStatus = "loading";
    state.showMsg = false;
  },

  SAVE_SUCCESS(state, dato) {
    state.item = dato;
    state.dataStatus = "success";
    state.dataStatusMsg = "Los datos se grabaron correctamente.";
    state.showMsg = true;
    state.loading = false;
  },

  OBS_SUCCESS(state, datos) {
    state.observaciones = datos;
    state.loadingObs = false;
    state.obsStatus = "success";
    state.showMsg = false;
  },
  OBS_ERROR(state) {
    state.obsStatus = "error";
    state.showMsg = false;
  },

  ADD_OBS(state, obs) {
    state.obsStatus = "Added";
    state.observaciones.push(obs);
    state.showMsg = false;
  },

  RESET_OBS(state) {
    state.observaciones = [];
    state.loadingObs = true;
  },
  RESET_ITEM(state) {
    state.item = {};
  },

  DATOS_ERROR(state) {
    state.dataStatus = "error";
    state.dataStatusMsg = "Ocurrió un error al intentar guardar los datos.";
    state.showMsg = true;
  },

  SET_DATO(state, dato) {
    state.item = dato;
    state.showMsg = false;
  },

  MOTIVOS_SUCCESS(state, respuesta) {
    state.motivos = respuesta;
  },

  ESTADOS_SUCCESS(state, respuesta) {
    state.estados = respuesta;
  }
};

export const getters = {
  getDatoById: state => ID => {
    return state.items.find(item => item.ID === ID);
  }
};

export const actions = {
  saveDato({ commit }, req) {
    console.log(req);
    commit("SAVING_DATA");
    return axios
      .put("/gestiondatos/" + req.ID, req)
      .then(response => {
        commit("SAVE_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

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

  setData({ commit }, coleccion) {
    commit("SET_DATA_STATUS", coleccion);
  },

  mostrarDato({ commit, dispatch, getters }, id) {
    //console.log(id);
    commit("RESET_ITEM");
    commit("RESET_OBS");
    var dato = getters.getDatoById(id);
    //console.log(dato);

    if (dato) {
      commit("SET_DATO", dato);
      dispatch("getObservaciones", id);
      dispatch("loadCombosMotivoEstado");
    } else {
      return axios
        .get("/gestiondatos/" + id)
        .then(response => {
          //console.log(response.data);
          commit("SET_DATO", response.data);
          dispatch("getObservaciones", id);
          dispatch("loadCombosMotivoEstado");
        })
        .catch(err => {
          //console.log("get datos error");
          commit("DATOS_ERROR");
        });
    }
  },

  getObservaciones({ commit }, id) {
    return axios
      .get("/observaciones/" + id)
      .then(response => {
        //console.log(response.data);
        commit("OBS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("OBS_ERROR");
      });
  },

  newObs({ commit }, params) {
    console.log(params);
    return axios
      .post("/observaciones", params)
      .then(response => {
        commit("ADD_OBS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  async loadCombosMotivoEstado({ commit }) {
    await axios
      .all([axios.get(`/combobox/estados`), axios.get(`/combobox/motivos`)])
      .then(
        axios.spread((estados, motivos) => {
          commit("ESTADOS_SUCCESS", estados.data);
          commit("MOTIVOS_SUCCESS", motivos.data);
        })
      )
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  }
};
