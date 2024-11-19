<template>
  <div>
    <v-container>
      <Head title="Agendamentos" />
      <v-row class="mb-8">
        <v-col cols="6">
          <h1 class="text-3xl font-bold">Agendamentos</h1>
        </v-col>
        <v-col cols="6" class="d-flex align-center justify-end">
          <ModalCreateSchedule :clients="clients" :animals="animals" :getEvents="getEvents" />
        </v-col>
      </v-row>

      <v-card class="overflow-hidden">
        <v-sheet class="ma-2">
          <vue-cal
            class="vuecal--blue-theme"
            style="height: 600px"
            :events="events"
            :selected-view="type"
            events-on-month-view="short"
            locale="pt-br"
            :disable-views="['years', 'year',]"
            @event-click="handleEventClick"
          />
        </v-sheet>
      </v-card>
    </v-container>

    <ModalEditSchedule ref="modalEditSchedule" :clients="clients" :animals="animals" :getEvents="getEvents"/>
  </div>
</template>

<style lang="scss">
.vuecal__menu {background-color: #3d77b6 !important;}
.vuecal__title-bar {background-color: #82A1D1 !important;}
.vuecal__event.vaccine {background-color: rgba(105, 172, 60, 0.897);border: 1px solid rgba(105, 172, 60, 0.897);color: #fff;}
.vuecal__event.routine {background-color: rgb(46, 90, 233);border: 1px solid rgb(46, 90, 233);color: #fff;}
.vuecal__event.warning {background-color: rgb(233, 136, 46);border: 1px solid rgb(233, 136, 46);color: #fff;}
.vuecal__event.danger {background-color: rgb(235, 82, 82);border: 1px solid rgb(235, 82, 82);color: #fff;}
.vuecal--month-view .vuecal__cell-content {
  justify-content: flex-start;
  height: 100%;
  align-items: flex-end;
}
.vuecal__event {
  cursor: pointer;
}

h1{
    color: #1C2751 !important;
  }
</style>

<script>
import VueCal from 'vue-cal';
import 'vue-cal/dist/vuecal.css';
import Layout from '@/Components/Layout.vue';
import { Head } from '@inertiajs/vue3';
import ModalCreateSchedule from '@/Pages/Schedule/Create.vue';
import ModalEditSchedule from '@/Pages/Schedule/Edit.vue';

export default {
  components: {
    VueCal,
    Head,
    ModalCreateSchedule,
    ModalEditSchedule,
  },
  layout: Layout,
  props: {
    schedules: Array,
    clients: Array,
    animals: Array,
  },
  data: () => ({
    type: 'month', // Mantido para selecionar a visualização
    types: [
      { title: 'Mensal', value: 'month' },
      { title: 'Semanal', value: 'week' },
      { title: 'Diário', value: 'day' },
    ],
    events: [],
  }),
  mounted() {
    this.getEvents();
  },
  methods: {
    getEvents() {
      this.events = [];

      this.schedules.forEach((schedule) => {
        this.events.push({
          title: `${schedule.start_time} ${schedule.title}`,
          start: new Date(schedule.start_event),
          end: new Date(schedule.end_event),
          class: schedule.color,
          only_title: schedule.title,
          id: schedule.id,
          animal_id: schedule.animal_id,
          client_id: schedule.client_id,
          color: schedule.color,
          start_date: schedule.start_date,
          start_time: schedule.start_time,
          end_date: schedule.end_date,
          end_time: schedule.end_time,
        });
      });
    },
    handleEventClick(event) {
      this.$refs.modalEditSchedule.openModal(event);
    },
  },
};
</script>