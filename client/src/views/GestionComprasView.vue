<template>
  <v-app class="contenedor">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <GestionComprasComponent :titleForm="titleForm" :headers="headers"></GestionComprasComponent>
          </div>
        </div>
      </div>
    </div>
  </v-app>
</template>

<script>
import GestionComprasComponent from "@/components/propios/GestionComprasComponent";

export default {
  name: "gestioncompras",
  components: {
    GestionComprasComponent,
  },
  data() {
    return {
      titleForm: "Gestión de Datos Comprados",
      headers: [
        { text: "Concesionario", value: "Concesionario", align: "start", filterable: false },
        { text: "Fecha Compra", value: "FechaCompra", align: "center", filterable: false },
        { text: "Grupo-Orden", value: "GrupoOrden", align: "center" },
        { text: "Titular Plan", value: "ApeNom", align: "start" },
        { text: "Monto HN", value: "MontoHNCompra", align: "center", filterable: false },
        // { text: "Fecha Vende Plan", value: "FechaCompra", align: "center" },
        { text: "Avance", value: "Avance", align: "center", filterable: false},
        { text: "Monto Compra", value: "MontoCompraDato", align: "center", filterable: false },
        { text: "% De Compra", value: "PorcentajeCompra", align: "center", filterable: false },
        { text: "En Gestion", value: "EnGestion", align: "center", filterable: false },
        { text: "Firma Cliente", value: "FechaFirmaCliente", align: "center", filterable: false },
        { text: "Titular Compra", value: "TitularCompra", align: "center", filterable: false },
        {
          text: "Firma Nvo. Titular",
          value: "FechaFirmaNvoTitular",
          align: "center",
          filterable: false
        },
        {
          text: "Enviada Terminal",
          value: "FechaEnviadaTerminal",
          align: "center", filterable: false
        },
        { text: "Ok", value: "FechaOk", align: "center", filterable: false },
        { text: "CBU Cargado", value: "FechaCBUCargado", align: "center", filterable: false },
        { text: "Fecha Envio Mail", value: "FechaEnvioMail", align: "center", filterable: false },
        {
          text: "Observaciones",
          value: "Observaciones",
          align: "center",
          width: "50px", filterable: false
        },
        { text: "Acciones", value: "Acciones", align: "center", width: "40px", filterable: false },
      ],
    };
  },
};
</script>

<style lang="scss" scoped>
.contenedor {
  width: 100%;
}
</style>
