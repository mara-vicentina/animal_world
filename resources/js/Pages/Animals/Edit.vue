<template>
    <div>
        <v-dialog max-width="800" v-model="dialog">
            <v-card class="text-center">
                <template v-slot:title>
                    <span class="title">Edição do Animal</span>
                </template>
                <v-card-text class="py-0 px-4">
                    <v-row dense>
                        <v-col cols="12" md="4" sm="6">
                            <v-text-field
                                label="Nome Completo*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="animal.name"
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" >
                            <v-text-field
                                hint="example of helper text only on focus"
                                label="Espécie*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="animal.species"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6">
                            <v-text-field
                                label="Raça*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="animal.race"
                                persistent-hint
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6">
                            <v-select
                                :items="[{ text: 'Fêmea', value: 1 }, { text: 'Macho', value: 0 }]"
                                label="Sexo*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                item-title="text"
                                item-value="value"
                                v-model="animal.sex"
                                required
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="4" sm="6">
                            <v-select
                                :items="[{ text: 'Sim', value: 1 }, { text: 'Não', value: 0 }]"
                                label="Animal Castrado?*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                item-title="text"
                                item-value="value"
                                v-model="animal.castrated_animal"
                                required
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="4" sm="6">
                            <v-text-field
                                label="Data de Nascimento*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="animal.birth_date"
                                type="date"
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6">
                            <v-select
                                :items="clients"
                                label="Tutor*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                item-title="name"
                                item-value="id"
                                v-model="animal.client_id"
                                required
                            ></v-select>
                        </v-col>
                    </v-row>
                </v-card-text>
        
                <v-card-actions class="pt-0 pb-4 px-4">
                    <v-spacer></v-spacer>
            
                    <v-btn
                        text="Salvar  Dados"
                        color="600"
                        variant="flat"
                        @click="editAnimal()"
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
        props: {
            clients: Array,
        },
        data() {
            return {
                dialog: false,
                animal: this.$inertia.form({}),
            }
        },
        methods: {
            editAnimal() {
                this.animal.put('/animals', {
                    onSuccess: () => {
                        this.dialog = false
                    }
                });
            },
            openModal(animal) {
                this.animal = useForm(animal);
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