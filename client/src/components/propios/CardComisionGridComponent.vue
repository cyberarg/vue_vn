<template>
    <div>
        <v-card
            class="mx-auto"
            outlined
            :loading="this.loadingData"
        >
            <v-card-title>
            {{concesionario}}
            <v-spacer></v-spacer>
            <template v-if="this.esGridPagos" >
                {{this.totalComisiones | numFormat('$0,0')}}
            </template>
            <template v-else>
                Total Facturar: {{total() * 0.05 | numFormat('$0,0')}}
                <v-spacer></v-spacer> 
                Total HN: {{total() | numFormat('$0,0')}}
            </template>
           
            <template v-if="casos() > 0">     
            <v-spacer></v-spacer>
                Casos: {{casos() | numFormat}}
            </template>
          
        </v-card-title>

            <v-expansion-panels v-model="panel">
                <v-expansion-panel>
                <v-expansion-panel-header>
                    {{subtitle}}
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                    <v-data-table
                        dense
                        :headers="headers"
                        :items="datos"
                        :hide-default-footer="true"
                        :loading="this.loadingData"
                        loading-text="Cargando Datos... Aguarde"
                        >
                        

                        <template v-slot:item.NomConcesionario="{ item }">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-layout justify-start v-on="on" class="rowclass" @click="handleClick(item)">{{item.NomConcesionario}}</v-layout> 
                                </template>
                                <span>{{ getTooltipData(item) }}</span>
                            </v-tooltip>
                        </template>
            
                        <template v-slot:item.TotalHN="{ item }">{{
                            item.TotalHN | numFormat('$0,0')
                        }}</template>


                         <template v-slot:item.Total="{ item }">{{
                            item.Total | numFormat('$0,0')
                        }}</template>

                        <template v-slot:footer >
                            <div class="d-flex justify-end mt-2"> 
                                <v-btn
                                    outlined
                                    color="success"
                                    text
                                    small
                                    @click="emitDetalleExcelCE()"
                                >
                                    <v-icon left>mdi-file-excel</v-icon>Detalle
                                </v-btn>
                            </div>
                        </template>
                    </v-data-table>
                </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </v-card>
    </div>
</template>

<script>
import { mapActions, mapState } from 'vuex';
    export default {
        props:{

            loadingData:{
                esGridPagos:Boolean,
                required:true
            },

             codConcesionario:{
                type:String
            },
            concesionario:{
                type:String,
                required:true
            },
            subtitle:{
                type:String,
                required:true,
            },
           
            headers:{
                type:Array,
                required:true
            },
            datos:{
                type:Array,
                required:true
            },
             esGridPagos:{
                esGridPagos:Boolean,
                required:true
            },
            
            totalComisiones:{
                type:Number,
            },
           
        },
         data() {
            return {
                showToolTip: false,
                panel:[],
                expanded:false
            }
        },

        computed: {
            ...mapState("reportefacturacion", ["loading_filter","items_filtrados"])
        
        },

        watch:
        {
            panel(newValue, oldValue)
            {
                if (typeof this.panel !== "undefined"){
                    this.expanded = true;
                }else{
                    this.expanded = false;
                }
                this.emitExpand();
            }
        },

        methods: {

            ...mapActions({ showFiltradosRB: "reportefacturacion/getDetalleRB", showFiltradosRB_CE: "reportefacturacion/getDetalleRB_CE"}),
       
            emitDetalleExcelCE(){
                console.log(this.codConcesionario);
                this.$emit('detalleExcelCE', this.codConcesionario);
            },

            casos() {
                let casos = [];

                Object.entries(this.datos).forEach(([key, val]) => {
                    casos.push(parseInt(val.CantHN)) // the value of the current key.
                });

                return casos.reduce(function(casos, num){ return casos + num }, 0);
            },

            total() {
                let total = [];

                Object.entries(this.datos).forEach(([key, val]) => {
                    total.push(parseInt(val.TotalHN)) // the value of the current key.
                });

                return total.reduce(function(total, num){ return total + num }, 0);
            },

            getTooltipData(item) {
                if (item.CantHN > 0) {
                    this.showToolTip = true;
                    return "Haga click para ver el detalle de " + item.NomConcesionario;
                } else {
                    this.showToolTip = false;
                }
            },

            emitExpand(){
                console.log(this.expanded);
                this.$emit("setExpand", this.expanded); 
            },

           async handleClick(item){

               this.$emit("setNombreConc", item.NomConcesionario); 
               switch (parseInt(item.Concesionario)){

                    case 4:
                        await this.showFiltradosRB("6");
                    break;
                    case 5:
                        await this.showFiltradosRB("3");
                    break;
                    case 6:
                        await this.showFiltradosRB("8");
                    break;
                    default:    
                        await this.showFiltradosRB_CE(item.Concesionario);
                    break;
              
               }
               
                
            }

        },


    }

   
</script>

<style lang="scss" scoped>

.rowclass {
  padding: 0;
}

</style>