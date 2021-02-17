<template>
    <div>
        <v-card
            class="mx-auto"
            outlined
        >
         <v-card-title class="overline mb-4">
            {{concesionario}}
        </v-card-title>
            <v-list-item>
                <v-list-item-content>
                    <v-list-item-title class="headline mb-2">
                    {{total | numFormat('$0,0')}}
                    </v-list-item-title>
                    <v-list-item-subtitle>Casos: {{cantidad | numFormat}}</v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
        </v-card>
    </div>
</template>

<script>
    export default {
        props:{
            concesionario:{
                type:String,
                required:true
            },
            total:{
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
    }
</script>

<style lang="scss" scoped>

</style>