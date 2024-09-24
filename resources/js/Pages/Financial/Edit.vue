<template>
    <div>
        <v-dialog max-width="500" v-model="dialog">
            <v-card class="text-center">
                <template v-slot:title>
                    <span class="title">Edição do Registro</span>
                </template>
                <v-card-text class="py-0 px-4">
                    <v-row dense>
                        <v-col cols="12" md="6" sm="6">
                            <v-text-field
                                label="Nome"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="financial.name"
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6" sm="6" >
                            <v-text-field
                                hint="example of helper text only on focus"
                                label="Data*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                type="date"
                                v-model="financial.date"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6" sm="6">
                            <v-select
                                :items="[{ text: 'Entrada', value: 1 }, { text: 'Saída', value: 0 }]"
                                label="Tipo*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                item-title="text"
                                item-value="value"
                                v-model="financial.type"
                                required
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="6" sm="6">
                            <v-text-field
                                label="Valor*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="financial.value"
                                required
                            ></v-text-field>
                        </v-col>

                    </v-row>
                </v-card-text>
        
                <v-card-actions class="pt-0 pb-4 px-4">
                    <v-spacer></v-spacer>
            
                    <v-btn
                        text="Salvar  Dados"
                        color="600"
                        variant="flat"
                        @click="editFinancial()"
                    ></v-btn>
                    <v-btn
                        text="Cancelar"
                        color="red"
                        variant="outlined"
                        @click="dialog = false"
                    ></v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import { useForm } from '@inertiajs/vue3'

    export default {
        data() {
            return {
                dialog: false,
                financial: this.$inertia.form({}),
            }
        },
        methods: {
            editFinancial() {
                this.financial.put('/financial', {
                    onSuccess: () => {
                        this.dialog = false;
                    }
                });
            },
            openModal(financial) {
                this.financial = useForm(financial);
                this.dialog = true;
            }
        },
    }
</script>
  
<style lang="scss" scoped>
    .title {
        color: #344C92;
        font-size: 1.5rem;
    }
</style>