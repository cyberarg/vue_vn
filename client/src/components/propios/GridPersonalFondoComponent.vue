<template>
  <div>
    <v-data-table
      color="grey lighten-4"
      dense
      class="table-striped"
      :headers="headers"
      :items="items_personal"
      :search="search"
      :hide-default-footer="true"
      :disable-sort="true"
    >
    </v-data-table>
  </div>
</template>
<script>

export default {
  name: "GridPersonalFondo",

  data() {
    return {
      search: "",
      items_personal: [
          {
            c1: 'Guido Calvete',
            c2: 'CEO',
            c4: 'gcalvete@giama.com.ar',
          },
          {
            c1: 'Daniela Fernandez',
            c2: 'Gte. Operaciones',
            c4: 'dfernandez@giama.com.ar',
          },
          {
            c1: 'Candela Georges',
            c2: 'Escribanias',
            c4: 'cgeorges@giama.com.ar',
          },
      ]
    };
  },

  created() {
    //this.$store.dispatch(this.module + "/getData", this.api);
  },

  computed: {

    headers () {
        return [
          {
            text: 'Responsable',
            align: 'start',
            sortable: false,
            value: 'c1',
          },
          {
            text: 'Cargo',
            align: 'start',
            sortable: false,
            value: 'c2',
          },
          { text: 'E-mail', value: 'c4',  align: 'start', sortable: false, },

        ]
      },

  },

  methods: {

  }
};
</script>
<style lang="scss" scoped>
.table-header {
  thead {
    background-color: black;
  }
}

.fullw {
  width: 100%;
}

.center {
  text-align: center;
}

.bold {
  font-weight: bold;
}

.v-data-table tbody tr:nth-of-type(odd) {
  background-color: rgba(0, 0, 0, 0.06) !important;
}

</style>
