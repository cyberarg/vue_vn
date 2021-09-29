<template>
  <div>
    <div>
      <h3></h3>
      <v-card color="grey lighten-4">
        <v-card-title>
          {{ pars.titleform }}
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
          <v-btn class="ma-2" color="primary" outlined text @click="getDatos">
            <v-icon left>mdi-refresh</v-icon>Actualizar
          </v-btn>
        </v-card-title>

        <v-data-table
          dense
          fixed-header
          height="58vh"
          locale="es"
          :headers="headers"
          :items="items"
          :items-per-page="-1"
          :search="search"
          item-key="pars.itemkey"
          class="elevation-1"
          :loading="loading"
          group-by="NomOrigen"
          loading-text="Cargando Datos... Aguarde"
          no-data-text="No hay datos disponibles."
        >
          <template v-slot:item="{ item }">
            <template v-if="expanded">
              <tr>
                <td>{{ item.NomOficial }}</td>
                <td
                  @click="handleClick(item, 'Asignados', item.Asignados, '-1')"
                >
                  <v-layout justify-center class="rowclass">
                    {{ item.Asignados }}
                  </v-layout>
                </td>
                <td
                  @click="
                    handleClick(item, 'Sin Gestionar', item.SinGestionar, '0')
                  "
                >
                  <v-layout justify-center class="rowclass">
                    {{ item.SinGestionar }}
                  </v-layout>
                </td>
                <td
                  @click="
                    handleClick(item, 'Telefono Mal', item.TelefonoMal, '1')
                  "
                >
                  <v-layout justify-center class="rowclass">
                    {{ item.TelefonoMal }}
                  </v-layout>
                </td>
                <td
                  @click="
                    handleClick(item, 'Deje Mensaje', item.DejeMensaje, '2')
                  "
                >
                  <v-layout justify-center class="rowclass">
                    {{ item.DejeMensaje }}
                  </v-layout>
                </td>
                <td
                  @click="
                    handleClick(item, 'No le interesa', item.NoCompra, '4')
                  "
                >
                  <v-layout justify-center class="rowclass">
                    {{ item.NoCompra }}
                  </v-layout>
                </td>
                <td
                  @click="handleClick(item, 'En Gestiòn', item.EnGestion, '7')"
                >
                  <v-layout justify-center class="rowclass">
                    {{ item.EnGestion }}
                  </v-layout>
                </td>
                <td
                  @click="
                    handleClick(
                      item,
                      'Entrevista Pendiente',
                      item.EntrevistaPendiente,
                      '3'
                    )
                  "
                >
                  <v-layout justify-center class="rowclass">{{
                    item.EntrevistaPendiente
                  }}</v-layout>
                </td>

                <td
                  @click="handleClick(item, 'Vende Plan', item.VendePlan, '5')"
                >
                  <v-layout justify-center class="rowclass">
                    {{ item.VendePlan }}
                  </v-layout>
                </td>
                <td
                  @click="
                    handleClick(item, 'Pasar a Venta', item.PasarAVenta, '8')
                  "
                >
                  <v-layout justify-center class="rowclass">
                    {{ item.PasarAVenta }}
                  </v-layout>
                </td>
                <td @click="handleClick(item, 'Compro', item.Compro, '6')">
                  <v-layout justify-center class="rowclass">
                    {{ item.Compro }}
                  </v-layout>
                </td>
              </tr>
            </template>
            <template></template>
          </template>
          <template v-slot:group.header="{ items }">
            <td
              :colspan="headers.length"
              default="true"
              @click="expandRows"
              class="rowclassGroup"
            >
              <strong>{{ items[0].NomOrigen }}</strong>
            </td>
          </template>
          <template v-slot:group.summary="{ headers, items }">
            <template>
              <td v-for="(column, index) in headers" default="true">
                <v-layout justify-center class="rowclassSub">
                  <strong>{{ totalS(column, items) }}</strong>
                </v-layout>
              </td>
            </template>
          </template>

          <template v-slot:body.append="{ headers }">
            <td v-for="(column, index) in headers" default="true">
              <v-layout justify-center class="rowclassSub">
                <strong>{{ total(column) }}</strong>
              </v-layout>
            </td>
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
    </div>
  </div>
</template>
<script>
import { mapState, mapActions } from "vuex";
import XLSX from "xlsx";

export default {
  props: {
    pars: {
      type: Object,
      required: true,
    },
    headers: {
      type: Array,
      required: true,
    },
  },

  data() {
    return {
      search: "",
      showBotones: null,
      expanded: true,
      totalSub: 0,
      window: {
        width: 0,
        height: 0,
      },
      codMarcaSelected: null,
      listMarcas: [
        { Codigo: 0, Nombre: "Todas" },
        { Codigo: 2, Nombre: "Fiat" },
        { Codigo: 5, Nombre: "Volkswagen" },
        { Codigo: 9, Nombre: "Ford" },
        { Codigo: 3, Nombre: "Peugeot" },
        { Codigo: 7, Nombre: "Jeep" },
        { Codigo: 10, Nombre: "Citroen" },
      ],
      codConcesSelected: null,
      listC: [],
      listConcesionarios: [
        { Codigo: 0, Nombre: "Todos" },
        { Codigo: 1, Nombre: "Sauma", Marca: 5 },
        { Codigo: 2, Nombre: "Iruña", Marca: 5 },
        { Codigo: 3, Nombre: "Amendola", Marca: 5 },
        { Codigo: 7, Nombre: "Luxcar", Marca: 5 },
        { Codigo: 4, Nombre: "AutoCervo", Marca: 2 },
        { Codigo: 5, Nombre: "AutoNet", Marca: 2 },
        { Codigo: 6, Nombre: "Car Group", Marca: 2 },
        { Codigo: 9, Nombre: "Sapac", Marca: 9 },
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
      ],
    };
  },

  created() {
    //this.$store.dispatch(this.module + "/getData", this.api);
    window.addEventListener("resize", this.handleResize);
    this.handleResize();
  },

  mounted() {
    this.checkEsConcesionario();
    this.checkPerfilUsuario();
  },

  destroyed() {
    window.removeEventListener("resize", this.handleResize);
  },

  computed: {
    api() {
      return this.pars.routeapi;
    },
    module() {
      return this.pars.module;
    },
    grupoorden() {
      return "32323466";
    },
    ...mapState("estadogestion", [
      "items",
      "loading",
      "datos",
      "empresa",
      "items_filtrados",
    ]),

    ...mapState("auth", [
      "login",
      "user",
      "esConcesionario",
      "puedeVerConcesionariosB",
      "esVinculo",
      "codigoConcesionario",
    ]),
  },

  methods: {
    ...mapActions({
      showFiltrados: "estadogestion/showFiltrados",
      getData: "estadogestion/getData",
    }),


    checkPerfilUsuario(){
      if (parseInt(this.puedeVerConcesionariosB) == 1){
          Array.prototype.push.apply(this.listConcesionarios,this.concesB)
      }
    },

    getDatos() {
      var pars = {
        api: this.api,
        Marca: this.codConcesSelected.Marca,
        Concesionario: this.codConcesSelected.Codigo,
        EsVinculo: this.esVinculo,
      };
      this.getData(pars);
      //this.$store.dispatch(this.module + "/getData", pars);
    },

    filterConcesionaria(value) {
      console.log(value);
    },

    filterListConcesionaria(value) {
      console.log(value);
      this.codConcesSelected = null;
      this.listC = [];
      if (value.Codigo == 0) {
        this.listC = this.listConcesionarios.find(function (item) {
          return item.Codigo === 0;
        });
        this.codConcesSelected = this.listC;
      } else {
        this.listC = this.listConcesionarios.filter(function (item) {
          return item.Marca === value.Codigo;
        });
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
      } else {
        if (this.esVinculo) {
          this.listMarcas.splice(1, 1);
          this.showBotones = false;
        } else {
          this.showBotones = true;
        }
      }
    },

    exportExcel: function () {
      let data = XLSX.utils.json_to_sheet(this.items);
      const workbook = XLSX.utils.book_new();
      const filename = "devschile-admins";
      XLSX.utils.book_append_sheet(workbook, data, filename);
      XLSX.writeFile(workbook, `${filename}.xlsx`);
    },
    expandRows() {
      this.expanded = !this.expanded;
    },

    handleResize() {
      this.window.width = window.innerWidth;
      this.window.height = window.innerHeight - 50;
    },

    async handleClick(item, nombre, cantidad, estado) {
      console.log(item);

      var codEstado = "";
      switch (estado) {
        case "-1":
          codEstado = "-1";
          break;
        case "-2":
          codEstado = "9";
          break;
        case "0":
          codEstado = null;
          break;
        default:
          codEstado = estado;
          break;
      }

      var pars = {};
      pars.codOficial = item.CodOficial;
      pars.codEstado = estado;
      pars.Concesionario = item.Concesionario;
      console.log(pars);
      await this.showFiltrados(pars);
      var titleForm =
        "Oficial: " +
        item.NomOficial +
        " - Estado: " +
        nombre +
        " " +
        cantidad +
        " Operaciones";

      this.$router.push({
        name: "detallereporte",
        params: {
          title: titleForm,
          items_f: this.items_filtrados,
          volverARuta: "reporteasignaciones",
          module: "reporteasignacion",
        },
      });
    },

    subTotalAsig(column) {
      return (
        function (subTotalAsig) {
          //var itemName = column.value;
          //console.log(column.Asignados);
          if (!isNaN(column.Asignados)) {
            return subTotalAsig + parseInt(column.Asignados);
          }
          return 0;
        },
        0
      );
      // console.log(column.Asignados);
    },

    totalS(column, valor) {
      return valor.reduce(function (total, item) {
        // console.log(item);
        if (isNaN(item[column.value])) {
          return "";
        }
        return total + parseInt(item[column.value]);
      }, 0);
    },

    total(column) {
      return this.items.reduce(function (total, item) {
        //var itemName = column.value;
        //console.log(itemName);
        if (isNaN(item[column.value])) {
          return "";
        }
        return total + parseInt(item[column.value]);
      }, 0);
    },
  },

  /*

Para cambiar el valor de alguna celda:
let data = XLSX.utils.json_to_sheet(
 this.dataToExport,
 {
   header: [‘transaction_date’, ‘business_name’, ‘credit’, ‘rate’]
 }
)
data[‘A1’].v = ‘Fecha’
data[‘B1’].v = ‘Empresa solicitante’
data[‘C1’].v = ‘Depósitos’
data[‘D1’].v = ‘Tasa’
const workbook = XLSX.utils.book_new()

*/
};
</script>
<style lang="scss" scoped>
.table-header {
  thead {
    background-color: black;
  }
}

.itemclass td {
  font-size: 14px;
}

.rowclass {
  padding: 0;
}

.rowclassSub {
  padding: 0;
  font-weight: bold;
}

.rowclassGroup {
  font-weight: bold;
}

.padded {
  padding-left: 10px;
  padding-right: 10px;
}
</style>
