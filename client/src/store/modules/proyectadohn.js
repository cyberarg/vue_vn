import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  detalle_proyec_mes: [],
  detalle_proyec_mes_Giama: [],
  detalle_proyec_mes_CE: [],
  detalle_proyec_renta_mes: [],
  detalle_proyec_renta_mes_Giama: [],
  detalle_proyec_renta_mes_CE: [],
  detalle_proyec_anios: [],
  detalle_proyec_anios_Giama: [],
  detalle_proyec_anios_CE: [],
  detalle_proyec_renta_anios: [],
  detalle_proyec_renta_anios_Giama: [],
  detalle_proyec_renta_anios_CE: [],
  loadingdetalle_proyec_mes: false,
  loadingdetalle_proyec_renta_mes: false,
  loadingdetalle_proyec_anios: false,
  loadingdetalle_proyec_renta_anios: false
};

export const mutations = {
  CLEAR_PROOYECTADO_HN(state) {
    state.dataStatus = "";
    state.detalle_proyec_mes = [];
    state.detalle_proyec_renta_mes = [];
    state.detalle_proyec_anios = [];
    state.detalle_proyec_renta_anios = [];
  },

  GET_DPM_STATUS(state) {
    state.dataStatus = "loading";

    state.detalle_proyec_mes = [];
    state.detalle_proyec_renta_mes = [];
    state.detalle_proyec_anios = [];
    state.detalle_proyec_renta_anios = [];

    state.detalle_proyec_mes_Giama = [];
    state.detalle_proyec_renta_mes_Giama = [];
    state.detalle_proyec_anios_Giama = [];
    state.detalle_proyec_renta_anios_Giama = [];

    state.detalle_proyec_mes_CE = [];
    state.detalle_proyec_renta_mes_CE = [];
    state.detalle_proyec_anios_CE = [];
    state.detalle_proyec_renta_anios_CE = [];

    state.loadingdetalle_proyec_mes = true;
    state.loadingdetalle_proyec_renta_mes = true;
    state.loadingdetalle_proyec_anios = true;
    state.loadingdetalle_proyec_renta_anios = true;
  },

  DPM_SUCCESS(state, datos) {
    state.detalle_proyec_mes = datos["Meses"];
    state.detalle_proyec_mes_Giama = datos["Meses_Giama"];
    state.detalle_proyec_mes_CE = datos["Meses_CE"];
    state.loadingdetalle_proyec_mes = false;

    state.detalle_proyec_renta_mes = datos["RentabilidadMeses"];
    state.detalle_proyec_renta_mes_Giama = datos["RentabilidadMeses_Giama"];
    state.detalle_proyec_renta_mes_CE = datos["RentabilidadMeses_CE"];
    state.loadingdetalle_proyec_renta_mes = false;

    state.detalle_proyec_anios = datos["Anios"];
    state.detalle_proyec_anios_Giama = datos["Anios_Giama"];
    state.detalle_proyec_anios_CE = datos["Anios_CE"];
    state.loadingdetalle_proyec_anios = false;

    state.detalle_proyec_renta_anios = datos["RentabilidadAnios"];
    state.detalle_proyec_renta_anios_Giama = datos["RentabilidadAnios_Giama"];
    state.detalle_proyec_renta_anios_CE = datos["RentabilidadAnios_CE"];
    state.loadingdetalle_proyec_renta_anios = false;

    state.dataStatus = "success";
  },

  PROYECT_ERROR(state) {
    state.dataStatus = "Error";
    state.loadingdetalle_proyec_mes = false;
    state.loadingdetalle_proyec_renta_mes = false;
    state.loadingdetalle_proyec_anios = false;
    state.loadingdetalle_proyec_renta_anios = false;
  }
};

export const getters = {
  //
};

export const actions = {
  getHNProyectados({ commit }, params) {
    commit("GET_DPM_STATUS");
    console.log(params);
    var endpoint = "";
    /*
    if (params.Marca == 2 ) {
      endpoint = "/hnproyectados";
    } else {
      endpoint = "/hnproyectadosce";
    }
    */
    endpoint = 'hnproyectados_select';
    return axios
      .post(endpoint, params)
      .then(response => {
        console.log(response.data);
        commit("DPM_SUCCESS", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("PROYECT_ERROR");
      });
  }
};
