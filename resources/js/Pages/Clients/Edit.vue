<template>
    <div>
        <v-dialog max-width="800" v-model="dialog">
                <v-card class="text-center">
                    <template v-slot:title>
                        <span class="title">Edição do Cliente</span>
                    </template>
                    <v-card-text class="py-0 px-4">
                        <v-row dense>
                            <v-col cols="12" md="4" sm="6">
                                <v-text-field
                                    label="Nome Completo*"
                                    v-model="client.name"
                                    variant="outlined"
                                    persistent-placeholder
                                    color="800"
                                    required
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" md="4" sm="6" >
                                <v-text-field
                                    hint="example of helper text only on focus"
                                    label="E-mail*"
                                    variant="outlined"
                                    persistent-placeholder
                                    color="800"
                                    v-model="client.email"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" md="4" sm="6">
                                <v-text-field
                                    hint="example of persistent helper text"
                                    label="CPF*"
                                    variant="outlined"
                                    persistent-placeholder
                                    color="800"
                                    v-model="client.cpf"
                                    persistent-hint
                                    required
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" md="4" sm="6">
                                <v-text-field
                                    label="Telefone*"
                                    variant="outlined"
                                    persistent-placeholder
                                    color="800"
                                    v-model="client.phone"
                                    required
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" md="4" sm="6">
                                <v-text-field
                                    label="CEP*"
                                    variant="outlined"
                                    persistent-placeholder
                                    color="800"
                                    v-model="client.cep"
                                    required
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" md="4" sm="6">
                                <v-text-field
                                    label="Rua*"
                                    variant="outlined"
                                    persistent-placeholder
                                    color="800"
                                    v-model="client.street"
                                    required
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" md="4" sm="6">
                                <v-text-field
                                    label="Número*"
                                    variant="outlined"
                                    persistent-placeholder
                                    color="800"
                                    v-model="client.number"
                                    required
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" md="4" sm="6">
                                <v-text-field
                                    label="Bairro*"
                                    variant="outlined"
                                    persistent-placeholder
                                    color="800"
                                    v-model="client.neighborhood"
                                    required
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" md="4" sm="6">
                                <v-text-field
                                    label="Complemento*"
                                    variant="outlined"
                                    persistent-placeholder
                                    color="800"
                                    v-model="client.complement"
                                    required
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" md="4" sm="6">
                                <v-text-field
                                    label="Cidade*"
                                    variant="outlined"
                                    persistent-placeholder
                                    color="800"
                                    v-model="client.city"
                                    required
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" md="4" sm="6">
                                <v-text-field
                                    label="Estado*"
                                    variant="outlined"
                                    persistent-placeholder
                                    color="800"
                                    v-model="client.state"
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
                            @click="editClient()"
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
                client: this.$inertia.form({}),
            }
        },
        methods: {
            editClient() {
                this.client.put('/clients',  {
                    onSuccess: () => {
                        this.dialog = false
                    }
                });
            },
            openModal(client) {
                this.client = useForm(client);
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