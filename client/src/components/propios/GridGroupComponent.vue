<template>
  <v-app id="inspire">
    <v-data-table
      dense
      :headers="headers"
      :items="desserts"
      item-key="name"
      group-by="category"
      class="elevation-1"
      show-group-by
      show-expand
    >
      <template v-slot:item="{ item }">
        <template v-if="expanded">
          <tr>
            <td>{{item.name}}</td>
            <td>{{item.january}}</td>
            <td>{{item.february}}</td>
            <td>{{item.march}}</td>
          </tr>
        </template>
        <template></template>
      </template>
      <template v-slot:group.header="{ items }">
        <td class="text-xs-right" :colspan="headers.length" default="true" @click="expandRows">
          <strong>{{ items[0].category }}</strong>
        </td>
      </template>
    </v-data-table>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      expanded: true,
      headers: [
        {
          text: "Dessert (100g serving)",
          align: "left",
          value: "name",
          sortable: false
        },
        { text: "January", value: "january", sortable: false },
        { text: "February", value: "february", sortable: false },
        { text: "March", value: "march", sortable: false },
        { text: "", value: "data-table-expand" }
      ],
      desserts: [
        {
          name: "Frozen Yogurt",
          category: "Ice cream",
          january: 1300,
          february: 1400,
          march: 1500
        },
        {
          name: "Ice cream sandwich",
          category: "Ice cream",
          january: 1300,
          february: 1400,
          march: 1500
        },
        {
          name: "Eclair",
          category: "Cookie",
          january: 1300,
          february: 1400,
          march: 1500
        },
        {
          name: "Cupcake",
          category: "Pastry",
          january: 1300,
          february: 1400,
          march: 1500
        },
        {
          name: "Gingerbread",
          category: "Cookie",
          january: 1300,
          february: 1400,
          march: 1500
        },
        {
          name: "Jelly bean",
          category: "Candy",
          january: 1300,
          february: 1400,
          march: 1500,
          hide: true
        },
        {
          name: "Lollipop",
          category: "Candy",
          january: 1300,
          february: 1400,
          march: 1500,
          hide: true
        },
        {
          name: "Honeycomb",
          category: "Toffee",
          january: 1300,
          february: 1400,
          march: 1500
        },
        {
          name: "Donut",
          category: "Pastry",
          january: 1300,
          february: 1400,
          march: 1500
        },
        {
          name: "KitKat",
          category: "Candy",
          january: 1300,
          february: 1400,
          march: 1500,
          hide: true
        }
      ]
    };
  },
  methods: {
    expandRows() {
      this.expanded = !this.expanded;
    }
  }
};
</script>
