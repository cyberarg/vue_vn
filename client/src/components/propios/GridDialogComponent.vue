<template>
  <v-app class="contenedor">
    <v-data-table
      :headers="headers"
      :items="items"
      :search="search"
      item-key="pars.itemkey"
      class="elevation-1"
      :loading="loading"
      loading-text="Cargando Datos... Aguarde"
    >
      <template v-slot:top>
        <v-toolbar flat color="grey lighten-4">
          <v-toolbar-title>Oficiales</v-toolbar-title>
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
          <v-dialog v-model="dialog" max-width="650px">
            <template v-slot:activator="{ on }">
              <v-btn cclass="ma-2" outlined text v-on="on"
                ><v-icon left>mdi-account-plus-outline</v-icon>Nuevo</v-btn
              >
            </template>
            <v-card>
              <v-progress-linear
                v-if="loading"
                indeterminate
                color="primary darken-1"
              ></v-progress-linear>
              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
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
                        v-model="editedItem.login"
                        label="Usuario"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-combobox
                        v-model="editedItem.NomSup"
                        item-text="Nombre"
                        item-value="Codigo"
                        :items="listSupervisores"
                        label="Supervisor"
                      ></v-combobox>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions v-if="!loading">
                <v-btn cclass="ma-2" outlined @click="save"
                  ><v-icon left>mdi-content-save-outline</v-icon>Guardar</v-btn
                >
                <v-spacer></v-spacer>
                <v-btn cclass="ma-2" outlined @click="close"
                  ><v-icon left>mdi-cancel</v-icon>Cancelar</v-btn
                >
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
        <v-btn color="primary" @click="initialize">Resetear</v-btn>
      </template>
    </v-data-table>
  </v-app>
</template>

<script>
import { mapState, mapActions } from "vuex";

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
    select: null,
    editedIndex: -1,
    supervisorObj: {},
    editedItem: {
      Nombre: "",
      Login: "",
      NomSup: "",
      supervisorObj: {}
    },
    deletedItem: {
      Nombre: "",
      Login: "",
      NomSup: "",
      supervisorObj: {}
    },
    defaultItem: {
      Nombre: "",
      Login: "",
      NomSup: "",
      supervisorObj: {}
    }
  }),

  computed: {
    formTitle() {
      return (this.editedIndex === -1 ? "Nuevo" : "Editar") + " Oficial";
    },
    api() {
      return this.pars.routeapi;
    },
    module() {
      return this.pars.module;
    },
    grupoorden() {
      return "32323466";
    },
    ...mapState("oficiales", ["items", "loading", "listSupervisores"])
  },

  watch: {
    dialog(val) {
      val || this.close();
    }
  },

  created() {
    this.$store.dispatch(this.module + "/getData", this.api);
  },

  methods: {
    ...mapActions({
      newItem: "oficiales/newItem",
      updateItem: "oficiales/updateItem",
      destroyItem: "oficiales/destroyItem"
    }),

    editItem(item) {
      this.editedIndex = this.items.indexOf(item);
      this.editedItem = Object.assign({}, item);
      //console.log();
      this.dialog = true;
    },

    deleteItem(item) {
      const index = this.items.indexOf(item);
      this.deletedItem = Object.assign({}, item);
      confirm("Are you sure you want to delete this item?") &&
        this.items.splice(index, 1);

      this.destroyItem(this.deletedItem);
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    async save() {
      if (this.editedIndex > -1) {
        await this.updateItem(this.editedItem);
        this.editedItem.NomSup = this.editedItem.NomSup.Nombre;
        this.editedItem.CodSup = this.editedItem.NomSup.Codigo;
        Object.assign(this.items[this.editedIndex], this.editedItem);
      } else {
        this.newItem(this.editedItem);
      }
      this.close();
    }
  }
};
</script>

<style scoped>
.contenedor {
  width: 100%;
  background-color: #eeeeee;
}
</style>
