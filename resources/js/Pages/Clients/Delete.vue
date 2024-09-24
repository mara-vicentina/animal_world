<template>
    <div>
        <v-dialog max-width="500" v-model="isActive">
                <v-card class="text-center">
                    <template v-slot:title>
                        <span class="title">Deleção do Cliente</span>
                    </template>
                    <v-card-text class="py-0 px-4">
                        <div class="text-medium-emphasis mb-4">
                            Tem certeza de que deseja excluir esta informação?
                            <br/>
                            Esta ação é irreversível e a informação não poderá ser recuperada.
                        </div>
                    </v-card-text>
            
                    <v-card-actions class="pt-0 pb-4 px-4">
                        <v-spacer></v-spacer>
                
                        <v-btn
                            text="Deletar Dados"
                            color="red"
                            variant="flat"
                            @click="deleteClient()"
                        ></v-btn>
                        <v-btn
                            text="Cancelar"
                            color="red"
                            variant="outlined"
                            @click="isActive = false"
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
                isActive: false,
                client: this.$inertia.form({ id: null }),
            }
        },
        methods: {
            deleteClient() {
                this.client.delete('/clients');
                this.isActive = false;
            },
            openModal(clientId) {
                this.client.id = clientId;
                this.isActive = true;
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