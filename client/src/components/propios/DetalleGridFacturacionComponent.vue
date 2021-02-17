<template>
    <div class="maxcontent">
        <v-card
            class="mx-auto"
            outlined
        >
            <v-card-title>
            {{titulo}}
            <v-spacer></v-spacer>
            {{total() | numFormat('$0,0')}}
            <template v-if="casos() > 0">     
            <v-spacer></v-spacer>
                Casos: {{casos() | numFormat}}
            </template>

        </v-card-title>

        <v-data-table
            dense
            fixed-header
            height="250px"
            :items-per-page="-1"
            :headers="headers"
            :items="datos"
            :hide-default-footer="true"
        >
            <template v-slot:item.GrupoOrden="{item}">
                {{item.Grupo}}/{{item.Orden}}
            </template>

            <template v-slot:item.HaberNetoOriginal="{item}">
                {{parseInt(item.HaberNetoOriginal) | numFormat('$0,0')}}
            </template>
             <template v-slot:item.MontoCompra="{item}">
                {{parseInt(item.MontoCompra) | numFormat('$0,0')}}
            </template>
            

        </v-data-table>
         </v-card>
    </div>
</template>

<script>
    export default {
        props:{
            titulo:{
                type:String,
                required:true
            },

            headers:{
                type:Array,
                required:true
            },
            datos:{
                type:Array,
                required:true
            },
        
        },
         data() {
            return {
               //
            };
        },

        computed: {
            maxHeigth(){
                if (this.datos.length > 20){
                    return "50hv";
                }
                return null;
            }
        },

        methods: {
            casos() {

                return this.datos.length;
                /*
                let casos = [];
                 
                Object.entries(this.datos).forEach(([key, val]) => {
                    casos.push(parseInt(val.HaberNetoSubite)) // the value of the current key.
                });

                return casos.reduce(function(casos, num){ return casos + num }, 0);
                */
            },

            total() {
                let total = [];

                Object.entries(this.datos).forEach(([key, val]) => {
                    total.push(parseInt(val.HaberNetoOriginal)) // the value of the current key.
                });

                return total.reduce(function(total, num){ return total + num }, 0);
            },
        },

    }
</script>

<style  scoped> 
.maxcontent {
  padding-bottom: 5px;
}

</style>