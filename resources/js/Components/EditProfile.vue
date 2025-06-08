<template>
    <div>
        <v-dialog v-model="dialog" max-width="800">
            <v-card class="text-center">
                <template v-slot:title>
                    <span class="title">Edição do Usuário</span>
                </template>
                <v-card-text class="py-0 px-4">
                    <v-row dense>
                        <v-col cols="12" md="4" sm="6">
                            <v-text-field
                                label="Nome Completo*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" >
                            <v-text-field
                                label="E-mail*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6">
                            <v-text-field
                                label="CPF*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
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
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6">
                            <v-text-field
                                label="CEP*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6">
                            <v-text-field
                                label="Rua*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6">
                            <v-text-field
                                label="Número*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6">
                            <v-text-field
                                label="Bairro*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6">
                            <v-text-field
                                label="Complemento*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6">
                            <v-text-field
                                label="Cidade*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6">
                            <v-text-field
                                label="Estado*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
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
                user: this.$inertia.form({}),
            }
        },
        methods: {
            openModal(user) {
                console.log(user, 'dados do usuario')
                // this.user = useForm(user);
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