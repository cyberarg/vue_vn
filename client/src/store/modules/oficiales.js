import axios from "axios";

export const namespaced = true;

export const state = {
  dataStatus: "",
  items: [],
  items_dw:[],
  listSupervisores: [],
  loading: true,
  okResponse: false,
  item: {}
};

export const mutations = {
  GET_DATA_STATUS(state) {
    state.dataStatus = "loading";
  },

  ADD_ITEM(state, item) {
    console.log(item);
    state.items.push(item);
  },

  DATOS_SUCCESS(state, items) {
    state.items = items;
    state.loading = false;
    state.dataStatus = "success";
  },

  OFICIALES_DW_STATUS(state) {
    state.items_dw = [];
    state.dataStatus = "loading";
  },

  OFICIALES_DW_SUCCESS(state, items) {
    state.items_dw = items;
    state.dataStatus = "success";
  },

  OFICIALES_DW_ERROR(state) {
    state.items_dw = [];
    state.dataStatus = "error";
  },
  
  OK_RESPONSE(state) {
    state.loading = false;
    state.okResponse = true;
  },

  SET_LOADING(state) {
    state.loading = true;
  },

  SET_LIST_SUPERVISORES(state, list) {
    state.listSupervisores = list;
  },

  DATOS_ERROR(state) {
    state.dataStatus = "Error";
  }
};

export const getters = {
  getItems: () => {
    return state.items;
  }
};

export const actions = {
  newItem({ commit }, item) {
    //console.log(item);
    return axios
      .post("/oficiales", item)
      .then(response => {
        commit("OK_RESPONSE");
        commit("ADD_ITEM", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  destroyItem({ commit }, item) {
    return axios
      .delete("/oficiales/" + item.Codigo)
      .then(response => {
        commit("OK_RESPONSE");
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  updateItem({ commit }, item) {
    commit("SET_LOADING");
    return axios
      .put("/oficiales/" + item.Codigo, item)
      .then(response => {
        commit("OK_RESPONSE");
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  getData({ commit, dispatch }, api) {
    return axios
      .get("/" + api)
      .then(response => {
        console.log(response.data);
        commit("DATOS_SUCCESS", response.data);
        dispatch("getListSupervisores");
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  },

  getOficialesDatoWeb({commit}){
    commit("OFICIALES_DW_STATUS");
    return axios
      .get("/getoficiales_dw")
      .then(response => {
        console.log(response.data);
        commit("OFICIALES_DW_SUCCESS", response.data);
      })
      .catch(err => {
        commit("OFICIALES_DW_ERROR");
      });
  },
  

  getListSupervisores({ commit }) {
    return axios
      .get("/supervisores")
      .then(response => {
        commit("SET_LIST_SUPERVISORES", response.data);
      })
      .catch(err => {
        //console.log("get datos error");
        commit("DATOS_ERROR");
      });
  }
};
