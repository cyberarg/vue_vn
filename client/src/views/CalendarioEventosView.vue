<template>
  <v-app class="fullw">
    <div class="contenedor">
    <v-card hover elevation-2 color="grey lighten-4">
        <v-card-title>
          Calendario Eventos
        <v-spacer></v-spacer>
        <div v-if="$refs.calendar">
          {{ $refs.calendar.title }}
        </div>
        </v-card-title>
        <div>
          <v-sheet
            tile
            height="54"
            class="d-flex"
          >
            <v-btn
              icon
              class="ma-2"
              @click="$refs.calendar.prev()"
            >
              <v-icon>mdi-chevron-left</v-icon>
            </v-btn>
            <v-select
              v-model="type"
              :items="types"
              dense
              outlined
              hide-details
              class="ma-2"
              label="Ver Calendario"
            ></v-select>
            <v-select
              :items="sectores"
              v-model="sector"
              dense
              outlined
              hide-details
              item-text="Nombre"
              item-value="ID"
              label="Sector"
              class="ma-2"
              @change="filterPersonal"
            ></v-select>
            <v-select
              :items="list_Responsables"
              v-model="person"
              dense
              outlined
              hide-details
              item-text="Nombre"
              item-value="ID"
              label="Responsable"
              class="ma-2"
            ></v-select>
            <v-spacer></v-spacer>
            <v-btn
              class="ma-2"
              outlined
              color="blue darken-1"
              text
              @click="getEventosFiltered"
            >
              <v-icon left>mdi-refresh</v-icon>Actualizar
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn
              icon
              class="ma-2"
              @click="$refs.calendar.next()"
            >
              <v-icon>mdi-chevron-right</v-icon>
            </v-btn>
            
          </v-sheet>
          <v-sheet height="600">
            <v-calendar
              ref="calendar"
              v-model="value"
              :weekdays="weekday"
              locale="es"
              :type="type"
              :events="events"
              :event-overlap-mode="mode"
              :event-overlap-threshold="30"
              :event-color="getEventColor"
              :first-interval="8"
              @change="getEvents"
            ></v-calendar>
          </v-sheet>
        </div>
   
      <div class="aclaracion">Nota: El Porcentaje de Trabajados </div>
     </v-card>
  </div>
  </v-app>
</template>



<script>

import moment from "moment";
import { mapState, mapActions } from "vuex";

export default {
  name: "calendarioeventos",
  components: {
    //
  
  },
  data() {
    return {
      type: 'week',
      sector: {},
      person: {},
      types: [
        { text: 'Mes', value: ['month'] },
        { text: 'Semana', value: ['week'] },
        { text: 'Día', value: ['day'] },
        { text: '4day', value: ['4day'] },
      ],
      mode: 'stack',
      modes: ['Datos Web', 'Oficiales', 'Administracion', 'Escribanias', 'Pagos'],
      weekday: [1, 2, 3, 4, 5],
      weekdays: [
        { text: 'Facundo Quiroz', value: [0, 1, 2, 3, 4, 5, 6] },
        { text: 'Andrea Roman', value: [1, 2, 3, 4, 5, 6, 0] },
        { text: 'Melisa Martinez', value: [1, 2, 3, 4, 5] },
        { text: 'Candela Georges', value: [1, 3, 5] },
        { text: 'Sofia Di Nanno', value: [1, 3, 5] },
      ],
      value: '',
      events: [],
      colors: ['blue', 'indigo', 'orange', 'green'],
      names: ['Llamado Inicial', 'Llamado Seguimiento', 'Firma', 'Pedido Pago'],
      list_Responsables: [{ID: 0, Nombre: 'Todos los Responsables', Sector: '', CodOficial: 0}]
    };
  },

  created() {
    //
  },

  computed: {
    ...mapState("calendario", ["items_sectores", "items_personal", "items_tipo_eventos"]),

    sectores(){
      let sect = []

      sect.push({ID: 0, Nombre: 'Todos los Sectores'})
      this.items_sectores.map((item) => sect.push(item))

      return sect
    },

    responsables(){
      let resp = []

      resp.push({ID: 0, Nombre: 'Todos los Responsables', Sector: '', CodOficial: 0})
      this.items_personal.map((item) => resp.push(item))

      return resp
    },
  },

  methods: {
    ...mapActions({
      getEventos: "calendario/getEventos",
    }),

    filterPersonal(value){
      console.log(value)
      console.log(this.items_personal)
      let lstFiltered = []
      this.list_Responsables = []
      this.list_Responsables = [{ID: 0, Nombre: 'Todos los Responsables', Sector: '', CodOficial: 0}]
      lstFiltered = this.items_personal.filter((item) => parseInt(item.Sector) === value)
      lstFiltered.map((item) => this.list_Responsables.push(item))
      console.log(this.list_Responsables)
     // this.person = this.items_personal.filter((item) => parseInt(item.Sector) === value)
      
    },

    getEventosFiltered() {
      let pars = {
        Sector: this.sector,
        Personal: this.person
      }
      console.log(pars)
      this.getEventos(pars)
    },

    getEvents ({ start, end }) {
        const events = []

        const min = new Date(`${start.date}T09:00:00`)
        const max = new Date(`${end.date}T17:29:59`)
        const days = (max.getTime() - min.getTime()) / 86400000
        const eventCount = 10

        for (let i = 0; i < eventCount; i++) {
          const allDay = 0
          const firstTimestamp = this.rnd(min.getTime(), max.getTime())
          const first = new Date(firstTimestamp - (firstTimestamp % 900000))
          const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000
          // const second = new Date(first.getTime() + (0 * 900000)) // 0 min
           const second = new Date(first.getTime() + (1 * 900000)) // 15 min
          // const second = new Date(first.getTime() + (2 * 900000)) // 30 min
          // const second = new Date(first.getTime() + (4 * 900000)) // 60 min
          // const second = new Date(first.getTime() + (8 * 900000)) // 120 min

          events.push({
            name: this.names[this.rnd(0, this.names.length - 1)],
            start: first,
            end: second,
            color: this.colors[this.rnd(0, this.colors.length - 1)],
            timed: true,
          })
        }

        this.events = events
      },
      getEventColor (event) {
        return event.color
      },
      rnd (a, b) {
        return Math.floor((b - a + 1) * Math.random()) + a
      },
  
  },
};
</script>

<style scoped>
.contenedor {
  width: 100%;
}

.padded {
  padding-left: 20px;
  padding-right: 20px;
}

.fullw {
  width: 100%;
  height: 100%;
}
</style>
