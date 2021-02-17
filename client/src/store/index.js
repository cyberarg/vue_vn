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
import * as haberneto from "./modules/haberneto.js";
import * as habernetocobrado from "./modules/habernetocobrado.js";
import * as cotizaciondolar from "./modules/cotizaciondolar.js";
import * as modelohn from "./modules/modelohn.js";
import * as proyectadohn from "./modules/proyectadohn.js";
import * as resumenperformance from "./modules/resumenperformance.js";
import * as eerrhn from "./modules/eerrhn.js";
import * as resumenhn from "./modules/resumenhn.js";
import * as gestioncompras from "./modules/gestioncompras.js";
import * as reporteobservaciones from "./modules/reporteobservaciones.js";
import * as reportecomprasobjetivos from "./modules/reportecomprasobjetivos.js";
import * as reportecaidas from "./modules/reportecaidas.js";
import * as tablerocontrol from "./modules/tablerocontrol.js";
import * as reportecomisiones from "./modules/reportecomisiones.js";
import * as reportefacturacion from "./modules/reportefacturacion.js";
import * as graphindice from "./modules/graphindice.js";
import * as stockhn from "./modules/stockhn.js";
import * as parametros from "./modules/parametros.js";

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
    reporteacompras,
    haberneto,
    habernetocobrado,
    cotizaciondolar,
    modelohn,
    proyectadohn,
    resumenperformance,
    eerrhn,
    resumenhn,
    gestioncompras,
    reporteobservaciones,
    reportecomprasobjetivos,
    reportecaidas,
    tablerocontrol,
    reportecomisiones,
    reportefacturacion,
    graphindice,
    stockhn,
    parametros
  },
  state: {
    tableList: [],
    user: {}
  }
});

Vue.use(VuexI18n.plugin, store);

export default store;
