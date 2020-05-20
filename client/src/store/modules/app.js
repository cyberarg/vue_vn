export const namespaced = true;

export const state = {
  sidebar: {
    opened: false
  },
  config: {
    palette: {
      primary: "#4ae387",
      danger: "#e34a4a",
      info: "#4ab2e3",
      success: "#db76df",
      warning: "#f7cc36",
      white: "#fff",
      black: "#000",
      fontColor: "#34495e",
      transparent: "transparent",
      lighterGray: "#ddd"
    }
  },
  isLoading: true
};

export const mutations = {
  SET_LOADING(state, isLoading) {
    state.isLoading = isLoading;
  }
};

export const actions = {};

export const getters = {
  config: () => {
    return state.app.config;
  },
  palette: () => {
    return state.app.config.palette;
  },
  isLoading: () => {
    return state.app.isLoading;
  }
};
