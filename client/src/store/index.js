import Vue from "vue";
import Vuex from "vuex";
import VuexI18n from "vuex-i18n"; // load vuex i18n module
import * as app from "./modules/app.js";
import * as auth from "./modules/auth.js";
import * as oficiales from "./modules/oficiales.js";
import * as estadogestion from "./modules/estadogestion.js";
import * as gestiondatos from "./modules/gestiondatos.js";
import * as asignaciondatos from "./modules/asignaciondatos.js";
import * as importardatos from "./modules/importardatos.js";
import * as importarhn from "./modules/importarhn.js";
import * as reporteasignacion from "./modules/reporteasignacion.js";
import * as reporteacompras from "./modules/reporteacompras.js";

Vue.use(Vuex);

export const namespaced = true;

const store = new Vuex.Store({
  //strict: true, // process.env.NODE_ENV !== 'production',
  modules: {
    app,
    auth,
    oficiales,
    estadogestion,
    gestiondatos,
    asignaciondatos,
    importardatos,
    importarhn,
    reporteasignacion,
    reporteacompras
  },
  state: {
    tableList: [],
    user: {}
  }
});

Vue.use(VuexI18n.plugin, store);

export default store;
