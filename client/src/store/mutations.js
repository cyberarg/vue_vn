// https://vuex.vuejs.org/en/mutations.html

export default {
  GET_DATA_STATUS(state) {
    state.dataStatus = "loading";
  },

  DATOS_SUCCESS(state, { items }) {
    state.items = items;
    state.dataStatus = "success";
  },

  DATOS_ERROR(state) {
    state.dataStatus = "Error";
  },

  NEW_DATA(state) {
    state.dataStatus = "adding";
  },

  UPDATE_DATA(state) {
    state.dataStatus = "loading";
  }
};
