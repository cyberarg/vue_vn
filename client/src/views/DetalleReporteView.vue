<template>
  <div class="contenedor" tabindex="0" @keyup.esc="goBack">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <GridFormComponentDetalle
              :pars="{
                titleform: this.title,
                routeapi: 'detalle',
                items: this.items_f,
                itemkey: 'Codigo',
                module: 'gestiondatos',
                origen: 'detalle'
              }"
              :headers="[
                {
                  text: 'ID',
                  align: 'center',
                  value: 'ID',
                  width: '1%'
                },
                {
                  text: 'Grupo Orden',
                  value: 'GrupoOrden',
                  align: 'center'
                },
                {
                  text: 'Solicitud',
                  value: 'Solicitud',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Haber Neto',
                  value: 'HaberNeto',
                  align: 'center'
                },
                {
                  text: 'Nombre y Apellido',
                  value: 'ApeNom',
                  align: 'start'
                },
                {
                  text: 'Supervisor',
                  value: 'NomSup',
                  align: 'center'
                },
                /*
                {
                  text: 'Domicilio',
                  value: 'Domicilio',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Teléfono 1',
                  value: 'Telefono1',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Teléfono 2',
                  value: 'Telefono2',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Email 1',
                  value: 'Email1',
                  align: 'center',
                  width: '1%'
                },
                */
                {
                  text: 'Cuotas PG',
                  value: 'CPG',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Cutas AD',
                  value: 'CAD',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Avance',
                  value: 'Avance',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Estado',
                  value: 'NomEstado',
                  align: 'center'
                },
                {
                  text: 'Fecha Compra',
                  value: 'FechaCompra',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Precio Compra',
                  value: 'PrecioCompra',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Precio Max Compra',
                  value: 'PrecioMaximoCompra',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Motivo',
                  value: 'Motivo',
                  align: 'center',
                  width: '1%'
                },
                {
                  text: 'Fecha Ult. Obs',
                  value: 'FechaUltObs',
                  align: 'center',
                  width: '1%'
                },
                { text: '', value: 'VerDatos', align: 'center', width: '1%' }
              ]"
            ></GridFormComponentDetalle>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import GridFormComponentDetalle from "@/components/propios/GridFormComponentDetalle.vue";
import moment from "moment";
import { mapState } from "vuex";
import closeMixin from "@/assets/js/close.js";

export default {
  name: "detallereporte",
  components: {
    GridFormComponentDetalle
    //GridFormCrud
  },

  data() {
    return {
      items: []
    };
  },

  mixins: [closeMixin],

  props: ["title", "volverARuta", "items_f"],

  created() {
    console.log("items_f:");
    console.log(this.items_f);
  },

  computed: {
    ...mapState("reporteasignacion", ["items_filtrados"])
  },
  methods: {
    goBack() {
      console.log(this.volverARuta);
      if (typeof this.volverARuta !== "undefined") {
        this.$router.push(this.volverARuta);
      }
      this.volverARuta = undefined;
    }
  }
};
</script>
<style scoped>
.contenedor {
  width: 100%;
}
</style>
