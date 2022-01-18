import Vue from "vue";
import Vuetify from "vuetify";
import VueI18n from "vue-i18n";
import "vuetify/dist/vuetify.min.css";
import "@mdi/font/css/materialdesignicons.css"; // Ensure you are using css-loader
import "@fortawesome/fontawesome-free/css/all.css";

Vue.use(Vuetify);
Vue.use(VueI18n);

const messages = {
  en: {
    $vuetify: {
      dataTable: {
        itemsPerPageText: 'Items per page:'
      },
      dataIterator: {
        pageText: '{0}-{1} of {2}'
      }
    }
  },
  es: {
    $vuetify: {
      dataTable: {
        itemsPerPageText: 'Registros por página:',
      },
      dataFooter: {
        pageText: '{0}-{1} de {2}',
        itemsPerPageAll: 'Todos'
      },
      dataIterator: {
        pageText: '{0}-{1} de {2}'
      }
    }
  }
};

const opts = {
  icons: {
    iconfont: "mdi" // default - only for display purposes
  }
};

const i18n = new VueI18n({
  locale: 'es', // set locale
  messages, // set locale messages
})

//export default new Vuetify(opts);

export default new Vuetify({
  opts: opts,
  lang: {
    t: (key, ...params) => i18n.t(key, params),
  },
})