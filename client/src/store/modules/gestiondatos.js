import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  dataStatusMsg: "",
  items_totales: [],
  items: [],
  items_filtered: [],
  motivos: [],
  motivosCaida: [],
  estados: [],
  item: {},
  obsStatus: "",
  observaciones: [],
  loadingDatos: false,
  loadingObs: true,
  showMsg: false,
  askData: false,
  showItemsFiltered: false,
  codMarcaSelState: {},
  codConcesSelState: {}
};

export const mutations = {
  CLEAR_STATE(state) {
    state.dataStatus = "";
    state.dataStatusMsg = "";
    state.items_totales = [];
    state.items = [];
    state.items_filtered = [];
    state.observaciones = [];
    state.codMarcaSelState = {};
    state.codConcesSelState = {};
  },

  GET_FILTERED_DATA_STATUS(state) {
    state.dataStatus = "loading";
    state.showMsg = false;
    state.loadingDatos = true;
  },

  FILTERED_SUCCESS(state, datos) {
    console.log(datos);
    state.items = datos;
    state.showItemsFiltered = true;
    state.dataStatus = "success";
    state.loadingDatos = false;
    state.showMsg = false;
  },

  GET_DATA_STATUS(state) {
    state.dataStatus = "loading";
    state.items_totales = [];
    state.items = [];
    state.askData = true;
    state.loadingDatos = true;
    state.showMsg = false;
  },

  DATOS_SUCCESS(state, datos) {
    console.log(datos);
    state.items_totales = datos;
    state.items = datos;
    state.loadingDatos = false;
    state.dataStatus = "success";

    state.showMsg = false;
  },

  SET_DATA_STATUS(state, colection) {
    state.loadingDatos = true;
    state.items = colection;
    //state.items_totales = colection;
    // console.log(colection);
    state.dataStatus = "success";
    state.showMsg = false;
  },

  SET_LOADING_STATUS(state) {
    state.loadingDatos = false;
  },

  SAVING_DATA(state) {
    state.loadingDatos = true;
    state.dataStatus = "loading";
    state.showMsg = false;
  },

  SAVE_SUCCESS(state, dato) {
    console.log(dato);
    state.item = dato;
    state.dataStatus = "success";
    state.dataStatusMsg = "Los datos se grabaron correctamente.";
    state.showMsg = true;
    state.loadingDatos = false;
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
    //state.item = {};
    state.item = [];
  },

  SET_COLECTION_ASIG(items) {
    state.items = items;
  },

  DATOS_ERROR(state) {
    state.dataStatus = "error";
    state.dataStatusMsg = "Ocurrió un error al intentar guardar los datos.";
    state.showMsg = true;
    state.loadingDatos = false;
  },

  SET_DATO(state, dato) {
    state.item = dato;
    state.showMsg = false;
    state.loadingDatos = false;
  },

  SET_DATO_ARR(state, dato) {
    state.item = dato[0];
    state.showMsg = false;
  },

  MOTIVOS_SUCCESS(state, respuesta) {
    state.motivos = respuesta;
  },

  MOTIVOS_CAIDA_SUCCESS(state, respuesta) {
    console.log(respuesta);
    state.motivosCaida = respuesta;
  },

  ESTADOS_SUCCESS(state, respuesta) {
    state.estados = respuesta;
  }, 

  SET_COMBOS_MARCA_CONCES(state, valor){
    console.log(valor);
    state.codMarcaSelState = valor.Marca;
    state.codConcesSelState = valor.Concesionario;
  }
};

export const getters = {
  getDatoById: state => ID => {
    return state.items.find(item => item.ID === ID);
  },

  filterItemsByConcesionario: state => codConc => {
    return state.items_totales.filter(function(item) {
      return parseInt(item.Concesionario) === codConc;
    });
  }
};

export const actions = {

  setMarcaConcesionario({commit}, params){
    commit("SET_COMBOS_MARCA_CONCES", params);
  },

  saveDato({ commit }, req) {
    console.log(req);
    commit("SAVING_DATA");
    return axios
      .post("/updatedato", req)
      .then(response => {
        commit("SAVE_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  setColection({ commit }, items) {
    console.log(items);
    commit("SET_COLECTION_ASIG", items);
  },

  filterData({ commit, getters }, conc) {
    commit("GET_FILTERED_DATA_STATUS");
    console.log(conc);
    var filtrado = getters.filterItemsByConcesionario(conc);
    //console.log(filtrado);
    commit("FILTERED_SUCCESS", filtrado);
  },

  getData({ commit }, pars) {
    commit("GET_DATA_STATUS");
    var user = JSON.parse(localStorage.getItem("user"));
    var oficial = 29; // 29 Es el codigo de oficial Gral para la base GF

    if (user.HNConcesionario == null && user.HN_PerfilUsuario !== "2") {
      oficial = user.CodigoOficialHN;
    }
    //console.log(user);
    pars.oficial = oficial;
    //console.log(oficial);
    console.log(pars);
    return axios
      .post("/getdatos", pars)
      .then(response => {
        commit("DATOS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  getDatosLeads({commit}){
    commit("GET_DATA_STATUS");
    let user = JSON.parse(localStorage.getItem("user"));
    let oficial = 29; // 29 Es el codigo de oficial Gral para la base GF
    let pars = {};
   
    pars.oficial = oficial;
    return axios
      .post("/getdatosleads", pars)
      .then(response => {
        commit("DATOS_SUCCESS", response.data);
      })
      .catch(err => {
        commit("DATOS_ERROR");
      });
  },

  setData({ commit }, coleccion) {
    commit("SET_DATA_STATUS", coleccion);
  },

  setLoader({ commit }) {
    commit("SET_LOADING_STATUS");
  },

  mostrarDato({ commit, dispatch, getters }, params) {
    //console.log(id);
    commit("RESET_ITEM");
    commit("RESET_OBS");
    var dato = getters.getDatoById(params.id);
    console.log(dato);

    if (dato) {
      commit("SET_DATO", dato);
      dispatch("getObservacionesDato", params);
      dispatch("loadCombosMotivoEstado");
    } else {
      return axios
        .post("/showdato", params)
        .then(response => {
          //console.log(response.data);
          commit("SET_DATO", response.data[0]);
          dispatch("getObservacionesDato", params);
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

  getObservacionesDato({ commit }, params) {
    console.log(params);
    return axios
      .post("/getobservaciones", params)
      .then(response => {
        console.log(response.data);
        commit("OBS_SUCCESS", response.data);
      })
      .catch(err => {
        console.log("get datos error");
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
      .all([axios.get(`/combobox/estados`), 
      axios.get(`/combobox/motivos`),
      axios.get(`/combobox/motivos_caida`)])
      .then(
        axios.spread((estados, motivos, motivos_caida) => {
          commit("ESTADOS_SUCCESS", estados.data);
          commit("MOTIVOS_SUCCESS", motivos.data);
          commit("MOTIVOS_CAIDA_SUCCESS", motivos_caida.data);
        })
      )
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  }
};
