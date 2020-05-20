<template>
  <v-app>
    <v-data-table
      v-if="items"
      :headers="headers"
      :items="items"
      :search="search"
      sort-by="Nombre"
      class="elevation-1"
    >
      <template v-slot:top>
        <v-toolbar flat color="white">
          <v-toolbar-title>{{ pars.titleform }}</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Buscar"
            single-line
            hide-details
          ></v-text-field>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="600px">
            <template v-slot:activator="{ on }">
              <v-btn color="primary" dark class="mb-2" v-on="on">Nuevo</v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline"> {{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field
                        v-model="editedItem.Nombre"
                        label="Nombre"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field
                        v-model="editedItem.Login"
                        label="Usuario"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field
                        v-model="editedItem.NomSup"
                        label="Supervisor"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close"
                  >Cancelar</v-btn
                >
                <v-btn color="blue darken-1" text @click="save">Guardar</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon small class="mr-2" @click="editItem(item)">
          mdi-pencil
        </v-icon>
        <v-icon small @click="deleteItem(item)">
          mdi-delete
        </v-icon>
      </template>
      <template v-slot:no-data>
        <v-btn color="primary" @click="initialize">Reset</v-btn>
      </template>
    </v-data-table>
  </v-app>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  props: {
    pars: {
      type: Object,
      required: true
    },
    headers: {
      type: Array,
      required: true
    }
  },

  data: () => ({
    dialog: false,
    search: "",
    editedIndex: -1,
    editedItem: {
      Nombre: "",
      Login: "",
      NomSup: ""
    },

    defaultItem: {
      Nombre: "",
      Login: "",
      NomSup: ""
    }
  }),

  computed: {
    api() {
      return this.pars.routeapi;
    },
    formTitle() {
      return (this.editedIndex === -1 ? "Nuevo" : "Editar") + " Oficial";
    },
    ...mapState(["items"])
  },

  created() {
    this.getdata();
  },

  watch: {
    dialog(val) {
      val || this.close();
    }
  },

  methods: {
    getdata() {
      this.$store.dispatch("getdata", this.api);
    },

    editItem(item) {
      this.editedIndex = this.items.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      const index = this.items.indexOf(item);
      confirm("¿Desea eliminar?") && this.items.splice(index, 1);
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    save() {
      if (this.editedIndex > -1) {
        this.$store.dispatch("updatedata", {
          api: this.api,
          item: this.editedItem
        });
        Object.assign(this.items[this.editedIndex], this.editedItem);
      } else {
        this.$store.dispatch("newdata", {
          api: this.api,
          item: this.editedItem
        });
        this.items.push(this.editedItem);
      }
      this.close();
    }
  }
};
</script>
