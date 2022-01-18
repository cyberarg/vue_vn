<template>
    <div>
        <v-card
            class="mx-auto"
            outlined
        >
         <v-card-title class="overline mb-1">
            {{concesionario}} 
            <v-spacer></v-spacer>
            Casos: {{cantidad | numFormat}}
        </v-card-title>
            <v-list-item>
                <v-list-item-content>
                    
                    <v-list-item-title >
                    Total Facturar: {{facturar | numFormat('$0,0')}}
                    </v-list-item-title>
                    <v-list-item-subtitle class="mb-2">
                    Total HN: {{total | numFormat('$0,0')}}
                    </v-list-item-subtitle>
                    
                    <v-list-item-subtitle class="d-flex justify-end">

                        <v-btn
                            outlined
                            color="success"
                            text
                            small
                            @click="emitDetalleExcelCE()"
                        >
                            <v-icon left>mdi-file-excel</v-icon>Detalle
                        </v-btn>
                    </v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
        </v-card>
    </div>
</template>

<script>
    export default {
        props:{
            codConcesionario:{
                type:String,
                required:true
            },
            concesionario:{
                type:String,
                required:true
            },
            total:{
                type:String,
                required:true
            },
            facturar:{
                type:String,
                required:true
            },
            cantidad:{
                type:String,
                required:true
            }
        },
         data() {
            return {
            //
            };
        },

        methods: {
            
            emitDetalleExcelCE(){
                console.log(this.codConcesionario);
                this.$emit('detalleExcelCE', this.codConcesionario);
            }

        },
    }
</script>

<style lang="scss" scoped>

</style>