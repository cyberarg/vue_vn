// Polyfills
import "core-js/stable";
import "regenerator-runtime/runtime";
// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from "vue";
window.Vue = Vue;

import App from "./App";
import { ColorThemePlugin } from "../services/vuestic-ui";
import router from "../router/index";
import store from "@/store";
import { VuesticPlugin } from "../services/vuestic-ui/components";
import "../i18n/index";
import moment from "moment";
import VueSwal from "vue-swal";
import numFormat from "vue-filter-number-format";
//import Chart from "v-chart-plugin";
import VueApexCharts from "vue-apexcharts";
Vue.use(VueApexCharts);


/*
import vueToPdf from 'vue-to-pdf';
import VueEasyPrinter from 'vue-easy-printer';
Vue.use(vueToPdf);
Vue.use(VueEasyPrinter);
*/

import htmlToPdf from '@/components/utils/htmlToPdf';
Vue.use(htmlToPdf)

Vue.component("apexchart", VueApexCharts);

Vue.filter("numFormat", numFormat);

import { VueMaskDirective } from 'v-mask'
Vue.directive('mask', VueMaskDirective);

import { VueFormBase } from 'vuetify-form-base'
Vue.directive('v-form-base', VueFormBase);

//Vue.use(Chart);
Vue.use(moment);
Vue.use(VueSwal);

Vue.i18n.set("es");


import YmapPlugin from "vue-yandex-maps";
import VueClipboard from "vue-clipboard2";
import vuetify from "@/plugins/vuetify"; // path to vuetify export
import "@/plugins/base";

import "../metrics";
import "../registerServiceWorker";

import axios from "axios";

Vue.prototype.$http = axios;
// Sets the default url used by all of this axios instance's requests

// //axios.defaults.baseURL = "http://192.168.14.10:8080/api/";

// //axios.defaults.baseURL = "http://testvue.test/api/";
//axios.defaults.baseURL = "http://localhost:7080/api/";

axios.defaults.baseURL = "http://api.giama.com.ar/api/";

 // //axios.defaults.baseURL = "http://52.41.224.173/api/";

axios.defaults.headers.get["Accept"] = "application/json";

const token = localStorage.getItem("token");
if (token) {
  //axios.defaults.headers.common["Authorization"] = "Bearer " + token;
}

if (process.env.VUE_APP_BUILD_VERSION) {
  // eslint-disable-next-line
  const message = `%c${"Build_information:"}\n %c${"Version"}: %c${VERSION},\n %c${"Timestamp"}: %c${TIMESTAMP},\n %c${"Commit"}: %c${COMMIT}`;
  // eslint-disable-next-line
  console.info(
    message,
    "color: blue;",
    "color: red;",
    "color: blue;",
    "color: red;",
    "color: blue;",
    "color: red;",
    "color: blue;"
  );
}

Vue.use(VuesticPlugin);
Vue.use(YmapPlugin);
Vue.use(VueClipboard);

Vue.use(ColorThemePlugin, {
  // override colors here.
});

router.beforeEach((to, from, next) => {
  //if (to.name !== "login" && !isAuthenticated) next({ name: "login" });
  
  
  if (to.name === "login") {
    localStorage.clear();
    store.commit("auth/LOGOUT");
    store.commit("gestiondatos/CLEAR_STATE");
    store.commit("estadogestion/CLEAR_STATE");
    store.commit("reporteacompras/CLEAR_STATE");
    store.commit("haberneto/CLEAR_GRIDS_HN");
    store.commit("proyectadohn/CLEAR_PROOYECTADO_HN");
    store.userAuthenticated = false;
    next();
  }

  if (to.name !== "login" && !store.userAuthenticated) {
    store.commit("auth/LOGOUT");
    store.commit("gestiondatos/CLEAR_STATE");
    store.commit("estadogestion/CLEAR_STATE");
    store.commit("reporteacompras/CLEAR_STATE");
    store.commit("haberneto/CLEAR_GRIDS_HN");
    store.commit("proyectadohn/CLEAR_PROOYECTADO_HN");
    next({ name: "login" });
  } else {
    next();
  }
  
  
  //store.commit("app/SET_LOADING", true);
  //next();
});

router.afterEach((to, from) => {
  //store.commit("app/SET_LOADING", false);
});

Vue.filter("formatDate", function(value) {
  if (value) {
    return moment(String(value)).format("DD/MM/YYYY hh:mm");
  }
});

/* eslint-disable no-new */
new Vue({
  el: "#app",
  router,
  store,
  vuetify,
  render: h => h(App)
});
