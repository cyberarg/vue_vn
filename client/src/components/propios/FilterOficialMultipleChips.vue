<template>
    <v-container fluid>
        <v-row>
            <v-col cols="9">
                <v-combobox
                v-model="selectedOficiales"
                :items="listaOficiales"
                item-text="Nombre"
                item-value="Codigo"
                label="Oficial"
                disable-lookup
                multiple
                chips
                >
                <template v-slot:prepend-item>
                    <v-list-item
                    ripple
                    @click="toggle"
                    >
                    <v-list-item-action>
                        <v-icon :color="selectedOficiales.length > 0 ? 'indigo darken-4' : ''">
                        {{ icon }}
                        </v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>
                        Seleccionar Todos
                        </v-list-item-title>
                    </v-list-item-content>
                    </v-list-item>
                    <v-divider class="mt-2"></v-divider>
                </template>
                </v-combobox>
            </v-col>
            <v-col cols="3">
                <v-btn
                    class="ma-2"
                    outlined
                    color="blue darken-1"
                    text
                    @click="emitFiltros()"
                >
                    <v-icon left>mdi-refresh</v-icon>Actualizar
                </v-btn>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import { mapState, mapActions } from "vuex";

    export default {
        
         props: {
            listaOficiales: {
                type: Array,
                required: true
            },
        },
        
        data() {
            return {
                selectedOficiales: [],
            }
        },

       

        computed: {
            ...mapState("parametros", [
                "loadingOficiales",
                "dataStatusMsg",
                "dataStatus",
                "oficiales"
                
            ]),

            likesAllFruit () {
                return this.selectedOficiales.length === this.listaOficiales.length
            },
            likesSomeFruit () {
                return this.selectedOficiales.length > 0 && !this.likesAllFruit
            },
            icon () {
                if (this.likesAllFruit) return 'mdi-close-box'
                if (this.likesSomeFruit) return 'mdi-minus-box'
                return 'mdi-checkbox-blank-outline'
            },
        },

        methods: {
            

            emitFiltros(){
                console.log(this.selectedOficiales)
                this.$emit('clickedFilters', this.selectedOficiales)
            },

            toggle () {
                this.$nextTick(() => {
                if (this.likesAllFruit) {
                    this.selectedOficiales = []
                } else {
                    this.selectedOficiales = this.listaOficiales.slice()
                }
                })
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>