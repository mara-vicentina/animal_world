<template>
  <div>
    <v-container class="mb-7 main-content-dash">
      <Head title="Dashboard" />
      <v-row class="mb-0">
        <v-col cols="12" sm="12" md="7">
          <h1 class="text-3xl font-bold">Dashboard</h1>
        </v-col>
        <v-col cols="12" sm="2" md="2" class="d-flex align-center justify-end">
          <v-text-field
              label="Período Inicial"
              variant="outlined"
              persistent-placeholder
              color="800"
              type="date"
              v-model="periodInitial"
              required
          ></v-text-field>
        </v-col>
        <v-col cols="12" sm="2" md="2" class="d-flex align-center justify-end">
          <v-text-field
              label="Período Final"
              variant="outlined"
              persistent-placeholder
              color="800"
              type="date"
              v-model="periodFinal"
              required
          ></v-text-field>
        </v-col>
        <v-col cols="12" sm="1" md="1" class="d-flex justify-end">
          <v-btn
              color="800"
              text="Buscar"
              variant="flat"
              @click="filterByDate"
          ></v-btn>
        </v-col>
      </v-row>
      <v-row class="mt-0">
        <v-col
          cols="12"
          md="3"
          sm="12"
        >
          <Client :totalClients="totalClients"/>
        </v-col>
        <v-col
          cols="12"
          md="3"
          sm="12"
        >
          <Animal 
            :totalMalesAnimals="totalMalesAnimals"
            :totalFemalesAnimals="totalFemalesAnimals"
          />
        </v-col>
        <v-col
          cols="12"
          md="3"
          sm="12"
        >
          <Appointment
            :totalAppointmentsVaccines="totalAppointmentsVaccines"
            :totalAppointmentsRoutine="totalAppointmentsRoutine"
            :totalAppointmentsClinicalAnalysis="totalAppointmentsClinicalAnalysis"
          />
        </v-col>
        <v-col
          cols="12"
          md="3"
          sm="12"
        >
          <Financial
            :totalFinancialInflow="totalFinancialInflow"
            :totalFinancialOutflow="totalFinancialOutflow"
          />
        </v-col>
      </v-row>
      <v-row>
        <v-col
          cols="12"
          md="6"
          sm="12"
        >
          <AnimalChart
            :animalsReportChart="animalsReportChart"
          />
        </v-col>
        <v-col
          cols="12"
          md="6"
          sm="12"
        >
          <AppointmentsChart
            :appointmentsReportChart="appointmentsReportChart"
          />
        </v-col>
      </v-row>
      <v-row>
        <v-col
          cols="12"
          md="12"
          sm="12"
        >
          <FinancialChart
            :financialReportChart="financialReportChart"
          />
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Components/Layout.vue'
import Animal from '@/Pages/Dashboard/Animal.vue'
import Appointment from '@/Pages/Dashboard/Appointment.vue'
import Financial from '@/Pages/Dashboard/Financial.vue'
import Client from '@/Pages/Dashboard/Client.vue'
import AnimalChart from '@/Pages/Dashboard/AnimalChart.vue'
import AppointmentsChart from '@/Pages/Dashboard/AppointmentsChart.vue'
import FinancialChart from '@/Pages/Dashboard/FinancialChart.vue'

export default {
  components: {
    Head,
    Animal,
    Appointment,
    Financial,
    Client,
    AnimalChart,
    AppointmentsChart,
    FinancialChart,
  },
  layout: Layout,
  props: {
    totalClients: Number,
    totalMalesAnimals: Number,
    totalFemalesAnimals: Number,
    totalAppointmentsVaccines: Number,
    totalAppointmentsRoutine: Number,
    totalAppointmentsClinicalAnalysis: Number,
    totalFinancialInflow: Number,
    totalFinancialOutflow: Number,
    animalsReportChart: Object,
    appointmentsReportChart: Object,
    financialReportChart: Object,
    initialDate: String,
    finalDate: String,
  },
  data() {
      return {
          periodInitial: '',
          periodFinal: '',
      }
  },
  mounted() {
    this.periodInitial = this.initialDate;
    this.periodFinal = this.finalDate;
  },
  methods: {
    filterByDate() {
      this.$inertia.get('/dashboard', {
        initialDate: this.periodInitial,
        finalDate: this.periodFinal,
      });
    },
  },
}
</script>

<style lang="scss" scoped>
</style>