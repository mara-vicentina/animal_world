<template>
    <div>
        <v-dialog v-model="dialog" max-width="600">
            <v-card class="text-center">
                <template v-slot:title>
                    <span class="title">Edição do Agendamento</span>
                </template>
                <v-card-text class="py-0 px-4">
                    <v-row dense>
                        <v-col cols="12" md="12" sm="12">
                            <v-select
                                :items="clients"
                                label="Tutor*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                item-title="name"
                                item-value="id"
                                v-model="schedule.client_id"
                                disabled
                                required
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="12" sm="12">
                            <v-select
                                :items="filteredAnimals"
                                label="Animal*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                item-title="name"
                                item-value="id"
                                v-model="schedule.animal_id"
                                disabled
                                @update:modelValue="animalChange"
                                required
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="12" sm="12">
                            <v-text-field
                                label="Título*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="schedule.only_title"
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="12" sm="12">
                            <v-select
                                :items="eventColors"
                                label="Motivo da Consulta*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                item-title="text"
                                item-value="value"
                                v-model="schedule.color"
                                required
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="6" sm="6">
                            <v-text-field
                                label="Data de Início*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                type="date"
                                v-model="schedule.start_date"
                                required
                                ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6" sm="6">
                            <v-text-field
                                label="Hora de Início*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                type="time"
                                v-model="schedule.start_time"
                                required
                                ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6" sm="6">
                            <v-text-field
                                label="Data de Término*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                type="date"
                                v-model="schedule.end_date"
                                required
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6" sm="6">
                            <v-text-field
                                label="Hora de Término*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                type="time"
                                v-model="schedule.end_time"
                                required
                            ></v-text-field>
                        </v-col>

                    </v-row>
                </v-card-text>
        
                <v-card-actions class="pt-0 pb-4 px-4">
                    <v-spacer></v-spacer>
                    <v-btn
                        text="Deletar Agendamento"
                        color="red"
                        variant="flat"
                        @click="deleteSchedule()"
                    ></v-btn>
                    <v-btn
                        text="Salvar  Dados"
                        color="600"
                        variant="flat"
                        @click="editSchedule()"
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
            animals: Array,
            getEvents: Function,
        },
        data() {
            return {
                dialog: false,
                schedule: this.$inertia.form({}),
                eventColors: [{ text: 'Vacinação', value: 'vaccine' }, { text: 'Rotina', value: 'routine' }, { text: 'Análise Clínica', value: 'warning' }, { text: 'Cirurgia', value: 'danger' }]
            }
        },
        computed: {
            filteredAnimals() {
                return this.animals.filter(animal => animal.client_id === this.schedule.client_id);
            }
        },
        methods: {
            openModal(event) {
                this.schedule = useForm(event);
                this.dialog = true;
            },
            editSchedule() {
                this.schedule.put('/schedule', {
                    onSuccess: () => {
                        this.dialog = false;
                        this.schedule.reset();
                        this.getEvents();
                    }
                });
            },
            deleteSchedule() {
                this.schedule.delete('/schedule', {
                    onSuccess: () => {
                        this.dialog = false;
                        this.getEvents();
                    }
                });
            },
            animalChange() {
                let client = this.clients.filter(client => client.id === this.schedule.client_id)[0];
                let animal = this.animals.filter(animal => animal.id === this.schedule.animal_id)[0];
                this.schedule.title = `Consulta ${animal.name} (Tutor: ${client.name})`;
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