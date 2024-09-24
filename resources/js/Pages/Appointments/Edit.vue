<template>
    <div>
        <v-dialog max-width="800" v-model="dialog">
            <v-card class="text-center">
                <template v-slot:title>
                    <span class="title">Edição da Consulta</span>
                </template>
                <v-card-text class="pt-0 pb-2 px-4">
                    <v-row dense>
                        <v-col cols="12" md="4" sm="6">
                            <v-select
                                :items="clients"
                                label="Tutor*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                item-title="name"
                                item-value="id"
                                v-model="appointment.client_id"
                                required
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="4" sm="6">
                            <v-select
                                :items="filteredAnimals"
                                label="Animal*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                item-title="name"
                                item-value="id"
                                v-model="appointment.animal_id"
                                required
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" >
                            <v-select
                                :items="[{ text: 'Vacinação', value: 0 }, { text: 'Rotina', value: 1 }, { text: 'Análise Clínica', value: 2 }]"
                                label="Motivo da consulta?*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                item-title="text"
                                item-value="value"
                                v-model="appointment.reason"
                                required
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="4" sm="6">
                            <v-text-field
                                label="Valor da consulta*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.value"
                                required
                                disabled
                            ></v-text-field>
                        </v-col>

                        <v-col v-if="appointment.reason === 0" cols="12" md="12" sm="6" >
                            <v-textarea
                                label="Quais vacinas foram aplicadas?*"
                                :counter="300"
                                class="mb-2"
                                rows="2"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.which_vaccines"
                                persistent-counter
                            ></v-textarea>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" v-if="appointment.reason !== 0 && appointment.reason !== ''">
                            <v-select
                                :items="[{ text: 'Sim', value: 1 }, { text: 'Não', value: 0 }]"
                                label="Vacinas em dia?*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                item-title="text"
                                item-value="value"
                                v-model="appointment.up_to_date_vaccines"
                                required
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" v-if="appointment.reason !== 0 && appointment.reason !== ''">
                            <v-select
                                :items="[{ text: 'Sim', value: 1 }, { text: 'Não', value: 0 }]"
                                label="Vermifugação em dia?*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                item-title="text"
                                item-value="value"
                                v-model="appointment.up_to_date_deworming"
                                required
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" v-if="appointment.reason !== 0 && appointment.reason !== ''">
                            <v-select
                                :items="[{ text: 'Sim', value: 1 }, { text: 'Não', value: 0 }]"
                                label="Uso de antiparasitários?*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                item-title="text"
                                item-value="value"
                                v-model="appointment.use_antiparasitics"
                                required
                            ></v-select>
                        </v-col>

                        <v-col v-if="appointment.reason === 1" cols="12" md="12" sm="6" >
                            <v-textarea
                                label="Observações/Descrição*"
                                :counter="300"
                                class="mb-2"
                                rows="2"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.observations_description"
                                required
                                persistent-counter
                            ></v-textarea>
                        </v-col>

                        <v-col cols="12" md="12" sm="12" v-if="appointment.reason !== 0 && appointment.reason !== ''">
                            <p class="text-left mt-2">Histórico Médico:</p>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" class="py-0" v-if="appointment.reason !== 0 && appointment.reason !== ''">
                            <v-textarea
                                label="Cirurgias anteriores?*"
                                :counter="300"
                                rows="2"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.previous_surgeries"
                                persistent-counter
                            ></v-textarea>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" class="py-0" v-if="appointment.reason !== 0 && appointment.reason !== ''">
                            <v-textarea
                                label="Doenças ou condições crônicas?*"
                                :counter="300"
                                rows="2"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.illnesses_chronic_conditions"
                                persistent-counter
                            ></v-textarea>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" class="py-0" v-if="appointment.reason !== 0 && appointment.reason !== ''">
                            <v-textarea
                                label="Toma alguma medicação? Qual(is)?*"
                                :counter="300"
                                rows="2"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.medication"
                                persistent-counter
                            ></v-textarea>
                        </v-col>

                        <v-col cols="12" md="12" sm="12" v-if="appointment.reason === 2">
                            <p class="text-left mt-2">Queixa principal e avaliação clínica rápida:</p>
                        </v-col>
                        <v-col cols="12" md="6" sm="6" v-if="appointment.reason === 2">
                            <v-textarea
                                label="Quais os sintomas principais? Qual a duração?*"
                                :counter="300"
                                rows="2"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.main_symptoms"
                                persistent-counter
                            ></v-textarea>
                        </v-col>
                        
                        <v-col cols="12" md="6" sm="6" v-if="appointment.reason === 2">
                            <v-textarea
                                label="Houve mudança no comportamento? Qual(is)?*"
                                :counter="300"
                                rows="2"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.change_behavior"
                                persistent-counter
                            ></v-textarea>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" v-if="appointment.reason === 2">
                            <v-text-field
                                label="Temperatura Corporal*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.temperature"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" v-if="appointment.reason === 2">
                            <v-text-field
                                label="Frequência Cardíaca e Respiratória*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.heart_respiratory_rate"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" v-if="appointment.reason === 2">
                            <v-text-field
                                label="Mucosas e Hidratação*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.mucous_membranes_hydration"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" v-if="appointment.reason === 2">
                            <v-text-field
                                label="Peso*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.weight"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="12" sm="12" v-if="appointment.reason === 2">
                            <p class="text-left mt-2">Avaliação de dor e exame físico geral:</p>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" v-if="appointment.reason === 2">
                            <v-text-field
                                label="Escala de Dor*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.pain_scale"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" v-if="appointment.reason === 2">
                            <v-text-field
                                label="Pele e Pelagem*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.skin_coat"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" v-if="appointment.reason === 2">
                            <v-text-field
                                label="Olhos e Ouvidos*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.eyes_ears"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" v-if="appointment.reason === 2">
                            <v-text-field
                                label="Cavidade Oral*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.oral_cavity"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" v-if="appointment.reason === 2">
                            <v-text-field
                                label="Abdômen e Locomoção*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.abdomen_locomotion"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4" sm="6" v-if="appointment.reason === 2">
                            <v-text-field
                                label="Sistema Reprodutivo*"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.reproductive_system"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="12" sm="12" v-if="appointment.reason !== 0 && appointment.reason !== ''">
                            <p class="text-left mt-2">Sugestões de exames:</p>
                        </v-col>
                        
                        <v-col cols="12" md="12" sm="12" v-if="appointment.reason !== 0 && appointment.reason !== ''">
                            <v-textarea
                                label="Necessidade de exames laboratoriais, imagem ou outros testes diagnósticos? Qual(is)?*"
                                :counter="300"
                                rows="2"
                                variant="outlined"
                                persistent-placeholder
                                color="800"
                                v-model="appointment.exams"
                                persistent-counter
                            ></v-textarea>
                        </v-col>
                    </v-row>
                </v-card-text>
        
                <v-card-actions class="pt-0 pb-4 px-4">
                    <v-spacer></v-spacer>
            
                    <v-btn
                        text="Salvar  Dados"
                        color="600"
                        variant="flat"
                        @click="editAppointment()"
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
        },
        data() {
            return {
                dialog: false,
                appointment: this.$inertia.form({}),
            }
        },
        computed: {
            filteredAnimals() {
                return this.animals.filter(animal => animal.client_id === this.appointment.client_id);
            }
        },
        methods: {
            editAppointment() {
                this.appointment.put('/appointments', {
                    onSuccess: () => {
                        this.dialog = false;
                    }
                });
            },
            openModal(appointment) {
                this.appointment = useForm(appointment);
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
  