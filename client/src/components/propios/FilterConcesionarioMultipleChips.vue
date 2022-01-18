<template>
    <v-container fluid>
        <v-row>
            <v-col cols="9">
                <v-combobox
                v-model="selectedCEs"
                :items="listaCE"
                item-text="Nombre"
                item-value="ID"
                label="Concesionario"
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
                        <v-icon :color="selectedCEs.length > 0 ? 'indigo darken-4' : ''">
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
            listaCE: {
                type: Array,
                required: true
            },
        },

        data() {
            return {
                selectedCEs: [],
            }
        },

        

        computed: {
            ...mapState("parametros", [
                "loadingConcesionarios",
                "dataStatusMsg",
                "dataStatus",
                "concesionarios"
                
            ]),

            likesAllFruit () {
                return this.selectedCEs.length === this.listaCE.length
            },
            likesSomeFruit () {
                return this.selectedCEs.length > 0 && !this.likesAllFruit
            },
            icon () {
                if (this.likesAllFruit) return 'mdi-close-box'
                if (this.likesSomeFruit) return 'mdi-minus-box'
                return 'mdi-checkbox-blank-outline'
            },
        },

        methods: {
            ...mapActions({
                getDatosComboCE: "parametros/getConcesionarios",
            }),

            emitFiltros(){
                console.log(this.selectedCEs)
                this.$emit('clickedFilters', this.selectedCEs)
            },

            toggle () {
                this.$nextTick(() => {
                if (this.likesAllFruit) {
                    this.selectedCEs = []
                } else {
                    this.selectedCEs = this.listaCE.slice()
                }
                })
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>