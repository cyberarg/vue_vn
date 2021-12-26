import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  dataStatusMsg: "",
  items_totales: [],
  items: [],
  itemsPendientes: [],
  itemsVerificados: [],
  items_filtered: [],
  motivos: [],
  motivosCaida: [],
  estados: [],
  item: {},
  obsStatus: "",
  observaciones: [],
  valores: [],
  loadingDatos: false,
  loadingObs: true,
  loadingSearch:false,
  showMsg: false,
  askData: false,
  showItemsFiltered: false,
  hn_FCA: 0,
  loadingHN:false,
  loadingStatusInsert: false,
  dataStatusInsert: "",
  dataStatusMsgInsert: "",

};

export const mutations = {
  CLEAR_STATE(state) {
    state.dataStatus = "";
    state.dataStatusMsg = "";
    state.items_totales = [];
    state.items = [];
    state.items_filtered = [];
    state.observaciones = [];
    state.itemsPendientes = [];
    state.itemsVerificados = [];
  },

  GET_FILTERED_DATA_STATUS(state) {
    state.dataStatus = "loading";
    state.showMsg = false;
    state.loadingDatos = true;
  },

  FILTERED_SUCCESS(state, datos) {
    //console.log(datos);
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
    state.itemsPendientes = [];
    state.itemsVerificados = [];
    state.askData = true;
    state.loadingDatos = true;
    state.showMsg = false;
  },

  DATOS_SUCCESS(state, datos) {
    //console.log(datos);
    state.items = datos;
    state.loadingDatos = false;
    state.dataStatus = "success";

    state.showMsg = false;
  },

  DATOS_SUCCESS_P(state, datos) {
    //console.log(datos);
    state.itemsPendientes = datos;
    state.loadingDatos = false;
    state.dataStatus = "success";

    state.showMsg = false;
  },

  DATOS_SUCCESS_V(state, datos) {
    //console.log(datos);
    state.itemsVerificados = datos;
    state.loadingDatos = false;
    state.dataStatus = "success";

    if (state.itemsPendientes == []){
      state.items = datos;
    }else{
      state.items = state.itemsPendientes.concat(datos);
    }

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

  GET_ALTA_STATUS(state) {
    state.loadingStatusInsert = true;
    state.dataStatusInsert = "loading"
  },

  ALTA_SUCCESS(state, respuesta) {
    state.loadingStatusInsert = false;
    state.dataStatusInsert = "success";
    state.dataStatusMsgInsert = "El nuevo dato web se agregó exitosamente";
    //console.log(respuesta);
  },

  ALTA_ERROR(state) {
    state.loadingStatusInsert = false;
    state.dataStatusInsert = "error";
    state.dataStatusMsgInsert = "Ocurrió un error al intentar grabar el nuevo dato web";

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
    //console.log(dato);
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
    state.itemsPendientes = [];
    state.itemsVerificados = [];
    
  },

  DATOS_ERROR_P(state) {
    state.dataStatus = "error";
    state.dataStatusMsg = "Ocurrió un error al intentar guardar los datos pendientes.";
    state.showMsg = true;
    state.loadingDatos = false;
    state.itemsPendientes = [];
    
  },

  DATOS_ERROR_V(state) {
    state.dataStatus = "error";
    state.dataStatusMsg = "Ocurrió un error al intentar guardar los datos verificados.";
    state.showMsg = true;
    state.loadingDatos = false;
    state.itemsVerificados = [];
    
  },

  

  GET_HN_FCA_STATUS(state) {
    state.dataStatus = "loading";
    state.hn_FCA = 0;
    state.loadingHN = true;
  },

  HN_FCA_SUCCESS(state, datos) {
    //console.log(datos);
    state.hn_FCA = datos;
    state.dataStatus = "success";
    state.loadingHN = false;

  },

  HN_FCA_ERROR(state) {
    state.dataStatus = "error";
    state.hn_FCA = 0;
    state.loadingHN = false;
  },

  GET_GRUPO_STATUS(state) {
    state.dataStatus = "loading";
    state.valores = [];
    state.loadingSearch = true;
  },

  GRUPO_SUCCESS(state, datos) {
    //console.log(datos);
    state.valores = datos;
    state.dataStatus = "success";
    state.loadingSearch = false;

  },

  GRUPO_ERROR(state) {
    state.dataStatus = "error";
    state.valores = [];
    state.loadingSearch = false;
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
    //console.log(respuesta);
    state.motivosCaida = respuesta;
  },

  ESTADOS_SUCCESS(state, respuesta) {
    //console.log(respuesta);
    state.estados = respuesta;
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
  saveDato({ commit }, req) {
    //console.log(req);
    commit("SAVING_DATA");
    return axios
      .post("/updatedatoweb", req)
      .then(response => {
        commit("SAVE_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  setColection({ commit }, items) {
   // console.log(items);
    commit("SET_COLECTION_ASIG", items);
  },

  filterData({ commit, getters }, conc) {
    commit("GET_FILTERED_DATA_STATUS");
    //console.log(conc);
    var filtrado = getters.filterItemsByConcesionario(conc);
    //console.log(filtrado);
    commit("FILTERED_SUCCESS", filtrado);
  },

  getData({ commit }, params) {
    commit("GET_DATA_STATUS");
    var user = JSON.parse(localStorage.getItem("user"));
    var oficial = 29; // 29 Es el codigo de oficial Gral para la base GF

    if (user.HNConcesionario == null && user.HN_PerfilUsuario !== "2") {
      oficial = user.CodigoOficialHN;
    }
    //console.log(user);
    let pars = {};
    pars.oficial = oficial;
    //pars.DatoVerificado = params.DatoVerificado;
    //console.log(oficial);
    //(pars);
    return axios
      .post("/getdatosweb", pars)
      .then(response => {
          commit("DATOS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  getDatosPendientes({ commit }, params) {
    commit("GET_DATA_STATUS");
    var user = JSON.parse(localStorage.getItem("user"));
    var oficial = 29; // 29 Es el codigo de oficial Gral para la base GF

    if (user.HNConcesionario == null && user.HN_PerfilUsuario !== "2") {
      oficial = user.CodigoOficialHN;
    }
    //console.log(user);
    let pars = {};
    pars.oficial = oficial;
    //console.log(oficial);
    //console.log(pars);
    return axios
      .post("/getdatosweb_pend", pars)
      .then(response => {
          commit("DATOS_SUCCESS_P", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR_P");
      });
  },

  getDatosVerificados({ commit }, params) {
    var user = JSON.parse(localStorage.getItem("user"));
    var oficial = 29; // 29 Es el codigo de oficial Gral para la base GF

    if (user.HNConcesionario == null && user.HN_PerfilUsuario !== "2") {
      oficial = user.CodigoOficialHN;
    }
    //console.log(user);
    let pars = {};
    pars.oficial = oficial;
    //console.log(oficial);
   // console.log(pars);
    return axios
      .post("/getdatosweb_verif", pars)
      .then(response => {
          commit("DATOS_SUCCESS_V", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR_V");
      });
  },

  getHN_FCA({ commit }, pars) {
    commit("GET_HN_FCA_STATUS");
   
    return axios
      .post("/hn_fca", pars)
      .then(response => {
        commit("HN_FCA_SUCCESS", response.data);
      })
      .catch(err => {
        commit("HN_FCA_ERROR");
      });
  },


  
  searchValuesByGroup({ commit }, pars) {
    commit("GET_GRUPO_STATUS");
   
    return axios
      .post("/search_grupo", pars)
      .then(response => {
        commit("GRUPO_SUCCESS", response.data);
      })
      .catch(err => {
        commit("GRUPO_ERROR");
      });
  },

  grabarDatoWeb({ commit }, pars) {
    commit("GET_ALTA_STATUS");
   
    return axios
      .post("/altadatoweb", pars)
      .then(response => {
        commit("ALTA_SUCCESS", response.data);
      })
      .catch(err => {
        commit("ALTA_ERROR");
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
    //console.log(dato);

    if (dato) {
      commit("SET_DATO", dato);
      dispatch("getObservacionesDato", params);
      dispatch("loadCombosMotivoEstado");
    } else {
      return axios
        .post("/showdatoweb", params)
        .then(response => {
          console.log('Response showdatoweb');
          console.log(response.data);
          commit("SET_DATO", response.data);
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
      .get("/getobservacionesweb/" + id)
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
    //console.log(params);
    return axios
      .post("/getobservacionesweb", params)
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
    //console.log(params);
    return axios
      .post("/observacionesweb", params)
      .then(response => {
        commit("ADD_OBS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  loadCombosMotivoEstado({ commit }) {

    return axios
      .get("/combobox/estadosweb")
      .then(response => {
        commit("ESTADOS_SUCCESS", response.data);
      })
      .catch(err => {
        commit("DATOS_ERROR");
      });

  //async loadCombosMotivoEstado({ commit }) {
    /*
    await axios
      .all([axios.get(`/combobox/estados`), 
      axios.get(`/combobow/motivos`),
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
    */
  }
};
