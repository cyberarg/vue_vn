import axios from "axios";
import axiosMail from "@/app/main.js";

export const namespaced = true;

export const state = {
  dataStatus: "",
  datos: [],
  loadingDatos: false,
  cbuStatus: "loading",
  loadingCBU: false,
  cbuStatusMsgInsert: "",
  loadingFechas: false,
  emailStatus: "",
  emailResponse: "",
  loadingEmail: false,
  loadingTitularCompra: false,
  obsGestionStatus: "success",
  obsGestionStatusInsert: "",
  loadingObsGestion: false
};

export const mutations = {
  GET_DATA_STATUS(state) {
    state.datos = [];
    state.dataStatus = "loading";
    state.loadingDatos = true;
  },

  DATOS_SUCCESS(state, datos) {
    console.log(datos);
    state.datos = datos;
    state.loadingDatos = false;
    state.dataStatus = "success";
  },

  DATOS_ERROR(state) {
    state.dataStatus = "error";
    state.loadingDatos = false;
  },

  GET_FECHAS_STATUS(state) {
    state.dataStatus = "loading";
    state.loadingFechas = true;
  },

  FECHAS_SUCCESS(state, datos) {
    state.loadingFechas = false;
    state.dataStatus = "success";
  },

  FECHAS_ERROR(state) {
    state.dataStatus = "error";
    state.loadingFechas = false;
  },

  SAVE_CBU_STATUS(state) {
    state.cbuStatus = "loading";
    state.loadingCBU = true;
  },

  CBU_SUCCESS(state, datos) {
    state.loadingCBU = false;
    state.cbuStatus = "success";
    state.cbuStatusMsgInsert = "Datos bancarios grabados exitosamente";
  },

  CBU_ERROR(state) {
    state.cbuStatus = "error";
    state.loadingCBU = false;
    state.cbuStatusMsgInsert =
      "Ocurrió un error al intentar grabar los datos bancarios";
  },

  SAVE_TITULAR_COMPRA_STATUS(state) {
    state.loadingTitularCompra = true;

    state.dataStatus = "loading";
  },

  SAVE_TITULAR_COMPRA_SUCCESS(state, respuesta) {
    state.loadingTitularCompra = false;
    state.dataStatus = "success";
  },

  SAVE_TITULAR_COMPRA_ERROR(state) {
    state.loadingTitularCompra = false;
    state.dataStatus = "error";
  },

  SEND_EMAIL_STATUS(state) {
    state.emailStatus = "loading";
    state.loadingEmail = true;
  },

  EMAIL_SUCCESS(state, respuesta) {
    console.log(respuesta);
    state.loadingEmail = false;

    if (respuesta.success) {
      state.emailResponse = "Email enviado exitosamente";
      state.emailStatus = "success";
    } else {
      state.emailResponse = "Ocurrió un error al intentar enviar el Email";
      state.emailStatus = "error";
    }
  },

  EMAIL_ERROR(state) {
    state.emailStatus = "error";
    state.loadingEmail = false;
    state.emailResponse = "Ocurrió un error al intentar enviar el Email";
  },

  SAVE_OBS_STATUS(state) {
    state.obsGestionStatus = "loading";
    state.loadingObsGestion = true;
  },
  SAVE_OBS_SUCCESS(state, respuesta) {
    console.log(respuesta);
    state.obsGestionStatus = "success";
    state.obsGestionStatusInsert = "Observación ingresada exitosamente";
    state.loadingObsGestion = false;
  },
  SAVE_OBS_ERROR(state) {
    state.obsGestionStatus = "error";
    state.obsGestionStatusInsert =
      "Ocurrió un error al intentar guardar la observación";
    state.loadingObsGestion = false;
  }
};

export const getters = {
  //
};

export const actions = {
  getDatosComprados({ commit }, params) {
    console.log(params);
    var user = JSON.parse(localStorage.getItem("user"));
    var oficial = 29; // 29 Es el codigo de oficial Gral para la base GF

    //if (user.HNConcesionario == null) {
    if (parseInt(user.HN_PerfilUsuario) > 2) {
      oficial = user.CodigoOficialHN;
    }

    params.oficial = oficial;

    commit("GET_DATA_STATUS");
    return axios
      .post("/gestioncompras", params)
      .then(response => {
        commit("DATOS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  setFechaDato({ commit }, params) {
    console.log(params);
    commit("GET_FECHAS_STATUS");
    return axios
      .post("/setfechascontrol", params)
      .then(response => {
        commit("FECHAS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("FECHAS_ERROR");
      });
  },

  generarHNVigente({ commit }, params) {
    commit("NEW_HN_STATUS");
    console.log(params);
    return axios
      .post("/pasarhnvigente", params)
      .then(response => {
        //console.log(response.data);
        commit("NEW_HN_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("NEW_HN_ERROR");
      });
  },

  sendEmailTransferencia({ commit, dispatch }, params) {
    console.log(params);

    const axiosMail = axios.create({
      baseURL: "https://api.elasticemail.com/v2/"
    });

    commit("SEND_EMAIL_STATUS");
    return axiosMail
      .post("email/send?" + params)
      .then(response => {
        commit("EMAIL_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("EMAIL_ERROR");
      });
  },

  saveObs({ commit }, pars) {
    commit("SAVE_OBS_STATUS");
    return axios
      .post("/saveobsgestion", pars)
      .then(response => {
        commit("SAVE_OBS_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("SAVE_OBS_ERROR");
      });
  },

  saveTitularCompra({ commit }, pars) {
    console.log(pars);
    commit("SAVE_TITULAR_COMPRA_STATUS");
    return axios
      .post("/savetitularcompra", pars)
      .then(response => {
        commit("SAVE_TITULAR_COMPRA_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("SAVE_TITULAR_COMPRA_ERROR");
      });
  },

  saveCBU({ commit }, pars) {
    console.log(pars);
    commit("SAVE_CBU_STATUS");
    return axios
      .post("/savecbu", pars)
      .then(response => {
        commit("CBU_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("CBU_ERROR");
      });
  }
};
