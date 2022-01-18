// https://vuex.vuejs.org/en/actions.html
import axios from "axios";

// The login action passes vuex commit helper that we will use to trigger mutations.
export default {
  getdata({ commit }, api) {
    return new Promise((resolve, reject) => {
      commit("GET_DATA_STATUS");

      axios
        .get("/" + api)
        .then(response => {
          const items = response.data;
          //console.log(items);
          commit("DATOS_SUCCESS", { items });
          resolve(response);
        })
        .catch(err => {
          //console.log("get datos error");
          commit("DATOS_ERROR");
          reject(err);
        });
    });
  },

  newdata({ commit }, parameters) {
    console.log(parameters);
    return new Promise((resolve, reject) => {
      commit("NEW_DATA");

      axios
        .post("/" + parameters.api, parameters.item)
        .then(response => {
          console.log(response);
          const items = response.data.result;
          console.log(items);
          commit("DATOS_SUCCESS", { items });
          resolve(response);
        })
        .catch(err => {
          console.log("get datos error");
          commit("DATOS_ERROR");
          reject(err);
        });
    });
  },

  updatedata({ commit }, parameters) {
    return new Promise((resolve, reject) => {
      commit("UPDATE_DATA");

      axios
        .put("/" + parameters.api + "/" + parameters.item.Codigo, {
          item: parameters.item
        })
        .then(response => {
          console.log(response);
          const items = response.data;
          console.log(items);
          commit("DATOS_SUCCESS", { items });
          resolve(response);
        })
        .catch(err => {
          console.log("get datos error");
          commit("DATOS_ERROR");
          reject(err);
        });
    });
  }
};
