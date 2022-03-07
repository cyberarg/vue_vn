<template>
  <div>
    <v-card color="grey lighten-4">
      <v-card-title>
        {{ titleForm }}
        <v-divider class="mx-4" inset vertical></v-divider>

        <v-spacer></v-spacer>
        <template v-if="!esConcesionario">
          <v-row class="padded">
            <v-col cols="3">
              <v-combobox
                item-text="Nombre"
                item-value="Codigo"
                :items="listMarcas"
                label="Marca"
                :value="codMarcaSelected"
                @change="filterListConcesionaria"
                class="padded"
              ></v-combobox>
            </v-col>
            <v-col cols="3">
              <v-combobox
                item-text="Nombre"
                item-value="Codigo"
                :items="listC"
                label="Concesionario"
                v-model="codConcesSelected"
                @change="filterConcesionaria"
              ></v-combobox>
            </v-col>
            <v-col cols="3">
              <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Buscar"
                single-line
                hide-details
              ></v-text-field>
            </v-col>
          </v-row>
        </template>
        <template v-else>
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Buscar"
            single-line
            hide-details
          ></v-text-field>
        </template>
        <v-btn class="ma-2" color="primary" outlined text @click="getDatos()">
          <v-icon left>mdi-refresh</v-icon>Actualizar
        </v-btn>
      </v-card-title>

      <v-data-table
        dense
        fixed-header
        height="58vh"
        :headers="headers"
        :items="datos"
        :item-class="setClass"
        :search="search"
        item-key="pars.itemkey"
        :items-per-page="-1"
        :hide-default-footer="true"
        class="elevation-1"
        :loading="loadingDatos"
        loading-text="Cargando Datos... Aguarde"
        no-data-text="No hay datos disponibles"
      >
        <template v-slot:item.GrupoOrden="{ item }"
          >{{ item.Grupo }}-{{ item.Orden }}</template
        >
        <template v-slot:item.ApeNom="{ item }"
          >{{ item.Apellido }} {{ item.Nombres }}</template
        >
        <template v-slot:item.Concesionario="{ item }">
          {{ getNameConcesionario(item.Concesionario) }}
        </template>

        <template v-slot:item.FechaCompra="{ item }">
          {{ formatFechaLarga(item.FechaCompra) }}
        </template>
        <template v-slot:item.MontoHNCompra="{ item }">
          {{ item.MontoHNCompra | numFormat("$0,0") }}
        </template>
        <template v-slot:item.MontoCompraDato="{ item }">
          {{ item.MontoCompraDato | numFormat("$0,0") }}
        </template>
        <template v-slot:item.PorcentajeCompra="{ item }">
          {{ getPorcentaje(item) }}
        </template>

        <template v-slot:item.EnGestion="{ item }">
          <v-checkbox dense v-model="chkEnGestion" disabled></v-checkbox>
        </template>

        <template v-slot:item.FechaFirmaCliente="{ item }">
          <template v-if="item.FechaFirmaCliente == null">
            <template v-if="mostrarChecks">
              <v-checkbox
                dense
                id="chk_firmaCliente"
                @click="handleClick(item, 'Firma Cliente', 1)"
              ></v-checkbox>
            </template>
          </template>
          <template v-else>{{ formatFecha(item.FechaFirmaCliente) }}</template>
        </template>

        <template v-slot:item.FechaFirmaNvoTitular="{ item }">
          <template v-if="item.FechaFirmaNvoTitular == null">
            <template v-if="mostrarChecks">
              <v-checkbox
                dense
                id="chk_firmaNvoTitular"
                :disabled="item.FechaFirmaCliente == null"
                @click="handleClick(item, 'Firma Nuevo Titular', 2)"
              ></v-checkbox>
            </template>
          </template>
          <template v-else>
            {{ formatFecha(item.FechaFirmaNvoTitular) }}
          </template>
        </template>

        <template v-slot:item.TitularCompra="{ item }">
          <template v-if="item.TitularCompra == null">
            <template v-if="mostrarChecks">
              <v-combobox
                dense
                item-text="Nombre"
                item-value="Codigo"
                :items="getListTitularesCompra(item.Concesionario)"
                v-model="codTitularSelected"
                @change="handleChangeTitular(item, 'Titular Compra')"
              ></v-combobox>
            </template>
          </template>
          <template v-else>
            {{ setNameTitularCompra(item.TitularCompra) }}
          </template>
        </template>

        <template v-slot:item.FechaEnviadaTerminal="{ item }">
          <template v-if="item.FechaEnviadaTerminal == null">
            <template v-if="mostrarChecks">
              <v-checkbox
                dense
                id="chk_enviadaTerminal"
                :disabled="item.FechaFirmaNvoTitular == null"
                @change="handleClick(item, 'Enviada a Terminal', 3)"
              ></v-checkbox>
            </template>
          </template>
          <template v-else>
            {{ formatFecha(item.FechaEnviadaTerminal) }}
          </template>
        </template>

        <template v-slot:item.FechaOk="{ item }">
          <template v-if="item.FechaOk == null">
            <template v-if="mostrarChecks">
              <v-checkbox
                dense
                id="chk_fechaOk"
                :disabled="item.FechaEnviadaTerminal == null"
                @click="handleClick(item, 'Ok', 4)"
              ></v-checkbox>
            </template>
          </template>
          <template v-else>{{ formatFecha(item.FechaOk) }}</template>
        </template>

        <template v-slot:item.FechaCBUCargado="{ item }">
          <template v-if="item.FechaCBUCargado == null">
            <template v-if="mostrarChecks">
              <v-checkbox
                dense
                id="chk_fechaCBUCargado"
                @click="loadModalCBU(item, false)"
              ></v-checkbox>
            </template>
          </template>
          <template v-else>
            <template v-if="mostrarChecks">
              <v-hover v-slot:default="{ hover }">
                <div>
                  <v-expand-transition>
                    <div
                      v-if="hover"
                      class="d-flex transition-fast-in-fast-out orange darken-2 v-card--reveal display-3 white--text"
                    >
                      <v-btn text @click="loadModalCBU(item, true)"
                        >Editar</v-btn
                      >
                    </div>
                    <div v-else>{{ formatFecha(item.FechaCBUCargado) }}</div>
                  </v-expand-transition>
                </div>
              </v-hover>
            </template>
            <template v-else>{{ formatFecha(item.FechaCBUCargado) }}</template>
          </template>
        </template>

        <template v-slot:item.FechaEnvioMail="{ item }">
          <template v-if="item.FechaEnvioMail == null">
            <template v-if="mostrarChecks">
              <v-checkbox
                dense
                id="chk_fechaEnvioMail"
                :disabled="item.FechaOk == null || item.FechaCBUCargado == null"
                @change="handleClickMail(item, 'Envío Mail', 6)"
              ></v-checkbox>
            </template>
          </template>
          <template v-else>{{ formatFecha(item.FechaEnvioMail) }}</template>
        </template>

        <template v-slot:item.Observaciones="{ item }">
          <template v-if="item.Observaciones.length == 0">
            <!--<v-btn text @click="loadModalObs(item)">Nueva</v-btn>-->
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-icon medium v-on="on" @click="loadModalObs(item)"
                  >mdi-comment-plus-outline</v-icon
                >
              </template>
              <span>Nueva Obs.</span>
            </v-tooltip>
          </template>
          <template v-else>
            <v-hover v-slot:default="{ hover }">
              <div>
                <v-expand-transition>
                  <div
                    v-if="hover"
                    class="d-flex transition-fast-in-fast-out orange darken-2 v-card--reveal display-3 white--text"
                  >
                    <!--<v-btn text @click="loadModalObs(item)">Nueva</v-btn>-->
                    <v-tooltip bottom>
                      <template v-slot:activator="{ on }">
                        <v-icon medium v-on="on" @click="loadModalObs(item)"
                          >mdi-comment-plus-outline</v-icon
                        >
                      </template>
                      <span>Ver / Agregar Obs.</span>
                    </v-tooltip>
                  </div>
                  <div v-else>{{ item.Observaciones[0].Obs }}</div>
                </v-expand-transition>
              </div>
            </v-hover>
          </template>
        </template>

        <template v-slot:item.Acciones="{ item }">
          <template v-if="mostrarChecks">
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-icon medium v-on="on" @click="getDato(item)"
                  >mdi-text-search</v-icon
                >
              </template>
              <span>Ver Dato</span>
            </v-tooltip>

            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-icon medium v-on="on" @click="pasarVigente(item)"
                  >mdi-currency-usd</v-icon
                >
              </template>
              <span>Pasar a HN Vigentes</span>
            </v-tooltip>
          </template>
        </template>

        <template slot="body.append">
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th class="title">
              {{ sumField("MontoHNCompra") | numFormat("$0,0") }}
            </th>
            <th></th>
            <th class="title">
              {{ sumField("MontoCompraDato") | numFormat("$0,0") }}
            </th>
          </tr>
        </template>
      </v-data-table>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          cclass="ma-2"
          color="success"
          outlined
          text
          @click="exportExcel"
          v-show="showBotones"
        >
          <v-icon left>mdi-file-excel-outline</v-icon>Excel
        </v-btn>
      </v-card-actions>
    </v-card>
    <template>
      <v-dialog
        v-model="dialogCBU"
        :scrollable="false"
        :hide-overlay="false"
        max-width="700px"
        ma-0
      >
        <FormCargaCBU
          :item="itemSelected"
          :key="itemSelected.ID"
          :esEdicion="cbuEditable"
          @hide="dialogCBU = false"
          @refresh="getDatos()"
        ></FormCargaCBU>
      </v-dialog>
    </template>

    <template>
      <v-dialog
        v-model="dialogOBS"
        :scrollable="false"
        :hide-overlay="false"
        max-width="700px"
        ma-0
      >
        <FormObsGestionCompra
          :obs="obsGestion"
          :key="idDatos"
          :id_dato="idDatos"
          :puedeGenerarObs="this.mostrarChecks"
          @hide="dialogOBS = false"
        ></FormObsGestionCompra>
      </v-dialog>
    </template>

    <template>
      <v-dialog
        v-model="dialogCompra"
        :scrollable="false"
        :hide-overlay="false"
        max-width="700px"
        ma-0
        v-on:close="onClose"
      >
        <FormCompraHN
          :dato="operacion"
          :key="operacion.ID"
          @hide="hideDialogCompra()"
          @refresh="refreshGrid()"
        ></FormCompraHN>
      </v-dialog>
    </template>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import FormCargaCBU from "@/components/propios/FormCargaCBU.vue";
import FormObsGestionCompra from "@/components/propios/FormObservacionesGestionCompras.vue";
import FormCompraHN from "@/components/propios/FormCompraHN.vue";
import moment from "moment";
import XLSX from "xlsx";

export default {
  props: {
    titleForm: {
      type: String,
      required: true,
    },
    headers: {
      type: Array,
      required: true,
    },
  },
  components: {
    FormCargaCBU,
    FormObsGestionCompra,
    FormCompraHN,
  },
  data() {
    return {
      dato: {},
      operacion: {
        Marca: null,
        Concesionario: null,
        Grupo: null,
        Orden: null,
        Titular: null,
        TipoCompra: null,
        PrecioCompra: null,
        HaberNeto: null,
        ComproGiama: null,
        ID: null,
        EmpresaGyO: null,
        Login: null,
        NroTransferencia: null,
      },
      indexItem: null,
      itemSelected: {},
      obsGestion: [],
      idDatos: null,
      dialogCBU: false,
      dialogOBS: false,
      dialogCompra: false,
      search: "",
      showTooltip: false,
      showBotones: null,
      cbuEditable: null,
      chkEnGestion: true,
      mostrarChecks: true,
      codTitularSelected: null,
      codMarcaSelected: null,
      listMarcas: [
        { Codigo: 0, Nombre: "Todas" },
        { Codigo: 2, Nombre: "Fiat" },
        { Codigo: 5, Nombre: "Volkswagen" },
        { Codigo: 6, Nombre: "Ford" },
        { Codigo: 3, Nombre: "Peugeot" },
        { Codigo: 7, Nombre: "Jeep" },
        { Codigo: 10, Nombre: "Citroen" },
      ],
      codConcesSelected: null,
      listC: [],

      listConcesionarios: [
        { Codigo: 0, Nombre: "Todos", Marca: 0 },
        { Codigo: 1, Nombre: "Sauma", Marca: 5 },
        { Codigo: 2, Nombre: "Iruña", Marca: 5 },
        { Codigo: 3, Nombre: "Amendola", Marca: 5 },
        { Codigo: 7, Nombre: "Luxcar", Marca: 5 },
        { Codigo: 4, Nombre: "AutoCervo", Marca: 2 },
        { Codigo: 5, Nombre: "AutoNet", Marca: 2 },
        { Codigo: 6, Nombre: "Car Group", Marca: 2 },
        { Codigo: 9, Nombre: "Sapac", Marca: 6 },
        { Codigo: 10, Nombre: "Alizze", Marca: 3 },
        { Codigo: 12, Nombre: "Datos Web - Peugeot", Marca: 3 },
        { Codigo: 13, Nombre: "Datos Web - Fiat", Marca: 2 },
        { Codigo: 14, Nombre: "Datos Web - Jeep", Marca: 7 },
        { Codigo: 15, Nombre: "Datos Web - Volkswagen", Marca: 5 },
        { Codigo: 16, Nombre: "Datos Web - Ford", Marca: 9 },
        { Codigo: 17, Nombre: "Datos Web - Citroen", Marca: 10 },
      ],

      concesB: [
        { Codigo: 18, Nombre: "Alra", Marca: 5 },
        { Codigo: 19, Nombre: "Autotag", Marca: 5 },
        { Codigo: 20, Nombre: "Maynar", Marca: 5 },
        { Codigo: 21, Nombre: "Sebastiani", Marca: 5 },
        { Codigo: 22, Nombre: "Yacopini", Marca: 5 },
        { Codigo: 24, Nombre: "Pussetto", Marca: 5 },
      ],

      listTitularesCompra: [
        { Codigo: 1, Concesionario: 1, ComproGiama: 0, Nombre: "Sauma" },
        { Codigo: 2, Concesionario: 0, ComproGiama: 1, Nombre: "RB" },
        { Codigo: 3, Concesionario: 2, ComproGiama: 0, Nombre: "Mirage" },
        { Codigo: 4, Concesionario: 4, ComproGiama: 0, Nombre: "AutoCervo" },
        { Codigo: 5, Concesionario: 5, ComproGiama: 0, Nombre: "AutoNet" },
        { Codigo: 6, Concesionario: 6, ComproGiama: 0, Nombre: "Car Group" },
        { Codigo: 7, Concesionario: 7, ComproGiama: 0, Nombre: "LuxCar" },
        { Codigo: 8, Concesionario: 10, ComproGiama: 0, Nombre: "Alizze" },
        { Codigo: 9, Concesionario: 0, ComproGiama: 1, Nombre: "GB" },
       
      ],
    };
  },

  mounted() {
    this.checkEsConcesionario();
    this.checkPerfilUsuario();
  },



  computed: {
    ...mapState("auth", [
      "login",
      "user",
      "puedeVerConcesionariosB",
      "esConcesionario",
      "esVinculo",
      "codigoConcesionario",
    ]),

    ...mapState("gestioncompras", [
      "loadingDatos",
      "datos",
      "dataStatus",
      "emailResponse",
      "emailStatus",
      "loadingFechas",
      "loadingTitularCompra",
    ]),
  },

  methods: {
    ...mapActions({
      getDatosComprados: "gestioncompras/getDatosComprados",
      setFechaDato: "gestioncompras/setFechaDato",
      sendEmailTransferencia: "gestioncompras/sendEmailTransferencia",
      saveTitularCompra: "gestioncompras/saveTitularCompra",
    }),

    checkPerfilUsuario(){
      if (parseInt(this.puedeVerConcesionariosB) == 1){
          Array.prototype.push.apply(this.listConcesionarios,this.concesB)
      }
    },

    onClose() {
      this.dato = null;
    },

    hideDialogCompra() {
      this.dialogCompra = false;
    },

    refreshGrid() {
      if (this.indexItem !== null) {
        this.datos.splice(this.indexItem, 1);
        this.indexItem = null;
      }
    },

    getDato(item) {
      // this.mostrarDato(item.ID);
      //console.log(item.ID);
      this.$router.push({
        name: "detalledato",
        params: {
          id: item.ID,
          Marca: item.Marca,
          Concesionario: item.Concesionario,
          modulo: "gestiondatos",
        },
      });
    },

    pasarVigente(item) {
      //console.log(item);
      this.indexItem = this.datos.indexOf(item);

      this.operacion.ID = item.ID;
      this.operacion.Marca = item.Marca;
      this.operacion.Concesionario = item.Concesionario;
      this.operacion.Grupo = item.Grupo;
      this.operacion.Orden = item.Orden;
      this.operacion.HaberNeto = item.HaberNeto;
      this.operacion.PrecioCompra = item.PrecioCompra;
      this.operacion.Titular = item.TitularCompra;
      this.operacion.ComproGiama = item.TitularCompra == 2;

      this.dialogCompra = true;
    },

    sumField(key) {
      // sum data in give key (property)
      return this.datos.reduce((a, b) => a + parseInt(b[key] || 0), 0);
    },

    loadModalCBU(item, editar) {
      this.itemSelected = item;
      this.cbuEditable = editar;
      this.dialogCBU = true;
    },

    loadModalObs(item) {
      this.obsGestion = item.Observaciones;
      this.idDatos = item.ID;
      this.dialogOBS = true;
    },

    getPorcentaje(item) {
      return this.$options.filters.numFormat(
        item.MontoCompraDato / item.MontoHNCompra,
        "%0,0"
      );
    },

    formatFecha(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM");
      }
    },

    formatFechaLarga(fecha) {
      var date = moment(fecha);
      if (date.isValid()) {
        return moment(fecha).format("DD/MM/YYYY");
      }
    },

    exportExcel: function () {
      let data = XLSX.utils.json_to_sheet(this.datos);
      const workbook = XLSX.utils.book_new();
      const filename = "GestionCompras";
      XLSX.utils.book_append_sheet(workbook, data, filename);
      XLSX.writeFile(workbook, `${filename}.xlsx`);
    },

    filterListConcesionaria(value) {
      console.log(value);
      this.codMarcaSelected = value;
      this.codConcesSelected = null;
      this.listC = [];
      if (value.Codigo == 0) {
        this.listC = this.listConcesionarios.find(function (item) {
          return item.Codigo === 0;
        });
        this.codConcesSelected = this.listC;
      } else {
        this.listC = this.listConcesionarios.filter(function (item) {
          return item.Marca === value.Codigo || item.Marca === 0;
        });
      }
    },

    filterConcesionaria(value) {
      console.log(value);
      if (value.Codigo == 0) {
      } else {
        //this.filterData(value.Codigo);
      }
    },

    checkEsConcesionario() {
      if (this.esConcesionario) {
        var codC = parseInt(this.codigoConcesionario);
        console.log(codC);
        var itemC = {};
        itemC = this.listConcesionarios.find(function (item) {
          return item.Codigo === codC;
        });
        this.codConcesSelected = itemC;
        this.codMarcaSelected = itemC.Marca;
        this.showBotones = false;
        this.mostrarChecks = false;
      } else {
        if (this.esVinculo) {
          this.listMarcas.splice(1, 1);

          this.showBotones = false;
          this.mostrarChecks = false;
        } else {
          this.showBotones = true;
          this.mostrarChecks = true;
        }
      }
    },

    async handleChangeTitular(item, nombreCombo) {
      if (confirm("¿Desea modificar " + nombreCombo + "?")) {
        var params = {
          ID_Dato: item.ID,
          Marca: item.Marca,
          Concesionario: item.Concesionario,
          TitularCompra: this.codTitularSelected.Codigo,
          ComproGiama: this.codTitularSelected.ComproGiama,
        };
        await this.saveTitularCompra(params);
        item.TitularCompra = this.codTitularSelected.Codigo;
      }
    },

    setNameTitularCompra(codTitularCompra) {
      return this.listTitularesCompra.find(function (item) {
        return item.Codigo === parseInt(codTitularCompra);
      }).Nombre;
    },

    getListTitularesCompra(codConces) {
      var arrTitulares = [];
      switch (codConces) {
        case "1":
          arrTitulares = [
            { Codigo: 1, Concesionario: 1, ComproGiama: 0, Nombre: "Sauma" },
            { Codigo: 2, Concesionario: 0, ComproGiama: 1, Nombre: "RB" },
            { Codigo: 9, Concesionario: 0, ComproGiama: 1, Nombre: "GB" },
          ];
          break;
        case "2":
          arrTitulares = [
            { Codigo: 3, Concesionario: 2, ComproGiama: 0, Nombre: "Mirage" },
            { Codigo: 2, Concesionario: 0, ComproGiama: 1, Nombre: "RB" },
            { Codigo: 9, Concesionario: 0, ComproGiama: 1, Nombre: "GB" },
          ];
          break;
        case "3":
          arrTitulares = [
            { Codigo: 2, Concesionario: 0, ComproGiama: 1, Nombre: "RB" },
            { Codigo: 9, Concesionario: 0, ComproGiama: 1, Nombre: "GB" },
          ];
          break;
        case "4":
          arrTitulares = [
            { Codigo: 2, Concesionario: 0, ComproGiama: 1, Nombre: "RB" },
            { Codigo: 9, Concesionario: 0, ComproGiama: 1, Nombre: "GB" },
            {
              Codigo: 4,
              Concesionario: 4,
              ComproGiama: 0,
              Nombre: "AutoCervo",
            },
          ];
          break;
        case "5":
          arrTitulares = [
            { Codigo: 2, Concesionario: 0, ComproGiama: 1, Nombre: "RB" },
            { Codigo: 9, Concesionario: 0, ComproGiama: 1, Nombre: "GB" },
            { Codigo: 5, Concesionario: 5, ComproGiama: 0, Nombre: "AutoNet" },
          ];
          break;
        case "6":
          arrTitulares = [
            { Codigo: 2, Concesionario: 0, ComproGiama: 1, Nombre: "RB" },
            { Codigo: 9, Concesionario: 0, ComproGiama: 1, Nombre: "GB" },
            {
              Codigo: 6,
              Concesionario: 6,
              ComproGiama: 0,
              Nombre: "Car Group",
            },
          ];
          break;
        case "7":
          arrTitulares = [
            { Codigo: 2, Concesionario: 0, ComproGiama: 1, Nombre: "RB" },
            { Codigo: 9, Concesionario: 0, ComproGiama: 1, Nombre: "GB" },
            { Codigo: 7, Concesionario: 7, ComproGiama: 1, Nombre: "LuxCar" },
          ];
          break;
        case "10":
          arrTitulares = [
            { Codigo: 2, Concesionario: 0, ComproGiama: 1, Nombre: "RB" },
            { Codigo: 9, Concesionario: 0, ComproGiama: 1, Nombre: "GB" },
            { Codigo: 8, Concesionario: 10, ComproGiama: 1, Nombre: "Alizze" },
          ];
          break;
        case 12: // Dato Web - Peugeot
        case 13: // Dato Web - Fiat
        case 14: // Dato Web - Jeep
        case 15: // Dato Web - Volkswagen
        case 16: // Dato Web - Ford
        case 17: // Dato Web - Citroen
          arrTitulares = [
            { Codigo: 2, Concesionario: 0, ComproGiama: 1, Nombre: "RB" },
          ];
          break;
      }

      return arrTitulares;
    },

    getNameConcesionario(codConces) {
      switch (codConces) {
        case "1":
          return "Sauma";
          break;
        case "2":
          return "Iruña";
          break;
        case "3":
          return "Amendola";
          break;
        case "4":
          return "AutoCervo";
          break;
        case "5":
          return "AutoNet";
          break;
        case "6":
          return "Car Group";
          break;
        case "7":
          return "Luxcar";
          break;
        case "8":
          return "RB";
          break;
        case "9":
          return "Sapac";
          break;
        case "10":
          return "Alizze";
          break;
        case "12":
          return "Dato Web - Peugeot";
          break;
        case "13":
          return "Dato Web - Fiat";
          break;
        case "14":
          return "Dato Web - Jeep";
        case "15":
          return "Dato Web - Volskwagen";
        case "16":
          return "Dato Web - Ford";
        case "17":
          return "Dato Web - Citroen";
          break;
        default:
          return "";
          break;
      }
    },

    async handleClickMail(item, nombreCheck, codigo) {
      if (confirm("¿Desea marcar el dato como " + nombreCheck + "?")) {
        var fechaAGuardar = moment().format("YYYY-MM-DD");

        var params = {
          ID: item.ID,
          Marca: item.Marca,
          Concesionario: item.Concesionario,
          TipoFecha: codigo,
          FechaAGuardar: fechaAGuardar,
        };

        var to = "";
        var cc = "ferreyrafernando@gmail.com";
        var cco = "hnweb@giama.com.ar";

        switch (parseInt(item.Concesionario)) {
          case 1: // Sauma
            if (item.TitularCompra == "2") {
              // to = "dfernandez@giama.com.ar; sdinnano@giama.com.ar";
              to = "dfernandez@giama.com.ar";
            } else {
              // to = "jorge.szkadun@saumavw.com.ar";
              to = "dfernandez@giama.com.ar";
            }

            break;
          case 2: // Iruña
            if (item.TitularCompra == "2") {
              //to = "dfernandez@giama.com.ar; sdinnano@giama.com.ar";
              to = "dfernandez@giama.com.ar";
            } else {
              //to = "ricardo.millet@iruna.com.ar; cristhian.rath@iruna.com.ar; graciela.alfonso@iruna.com.ar";
              to = "dfernandez@giama.com.ar";
            }

            break;
          case 3: //Amendola
            //to = "dfernandez@giama.com.ar; sdinnano@giama.com.ar";
            to = "dfernandez@giama.com.ar";
            break;
          case 7: //Luxcar
            //to = "dfernandez@giama.com.ar; sdinnano@giama.com.ar";
            to = "dfernandez@giama.com.ar";
            break;
        }

        var str = "apikey=" + "622a5b57-631a-4d81-b06c-5e514c8a1116";
        str +=
          "&subject=" +
          "Pedido de Pago - CE: " +
          this.getNombreConcesionario(item.Concesionario) +
          " - G/O: " +
          item.Grupo +
          "/" +
          item.Orden;
        str += "&from=" + "hnweb@giama.com.ar";
        str += "&fromName=" + "Notificaciones HN Web";
        str += "&sender=" + "hnweb@giama.com.ar";
        str += "&senderName=" + "HN Web";
        str += "&replyTo=" + "hnweb@giama.com.ar";
        str += "&msgTo=" + to;
        str += "&msgCC=" + cc;
        str += "&msgBcc=" + cco;
        str += "&bodyHtml=" + this.getBodyEmail(item);

        await this.sendEmailTransferencia(str);

        if (this.emailStatus == "success") {
          this.setFechaDato(params);
          item.FechaEnvioMail = fechaAGuardar;
        }

        await this.showSwal();
      }
    },

    async handleClick(item, nombreCheck, codigo) {
      if (confirm("¿Desea marcar el dato como " + nombreCheck + "?")) {
        var fechaAGuardar = moment().format("YYYY-MM-DD");

        var params = {
          ID: item.ID,
          Marca: item.Marca,
          Concesionario: item.Concesionario,
          TipoFecha: codigo,
          FechaAGuardar: fechaAGuardar,
        };

        this.setFechaDato(params);

        switch (codigo) {
          case 1:
            item.FechaFirmaCliente = fechaAGuardar;
            break;
          case 2:
            item.FechaFirmaNvoTitular = fechaAGuardar;
            break;
          case 3:
            item.FechaEnviadaTerminal = fechaAGuardar;
            break;
          case 4:
            item.FechaOk = fechaAGuardar;
            break;
          case 5:
            item.FechaCBUCargado = fechaAGuardar;
            break;
        }
      }
    },

    getBodyEmail(item) {
      var body = "<html>";

      body += "<h3>";
      body +=
        "Concesionario: " +
        this.getNombreConcesionario(item.Concesionario) +
        " - Grupo: " +
        item.Grupo +
        " - Orden: " +
        item.Orden;
      body += "</h3>";
      body += "<br/>";
      body += "<b>Importe a Transferir: $" + item.PrecioCompra + "</b>";
      body += "<br/>";
      body += "Titular: " + item.TitularCuenta;
      body += "<br/>";
      body += "CUIT/CUIL: " + item.CUIT;
      body += "<br/>";
      body += "Banco: " + item.NombreBanco;
      body += "<br/>";
      body += "Nro. Cuenta: " + item.NroCuenta;
      body += "<br/>";
      body += "CBU: " + item.CBU;
      body += "<br/>";
      body += " Alias CBU: " + item.AliasCBU;
      body += "<br/>";
      body += "HN: $" + item.HaberNeto + " Avance: " + item.Avance;
      body += "<br/>";

      body += "</html>";

      return body;
    },

    showSwal() {
      this.$swal(this.emailResponse, "", this.emailStatus);
    },

    getNombreConcesionario(valor) {
      switch (parseInt(valor)) {
        case 1:
          return "Sauma";
        case 2:
          return "Iruña";
        case 3:
          return "Amendola";
        case 4:
          return "AutoCervo";
        case 5:
          return "AutoNet";
        case 6:
          return "Car Group";
        case 7:
          return "Luxcar";
        case 8:
          return "RB";
      }
    },

    getDatos() {
      var pars = {
        Marca: this.codMarcaSelected.Codigo,
        Concesionario: this.codConcesSelected.Codigo,
      };

      console.log(pars);
      this.getDatosComprados(pars);
    },

    setClass(item) {
      //console.log(item);
      return "";
    },
  },
};
</script>

<style lang="scss" scoped></style>
