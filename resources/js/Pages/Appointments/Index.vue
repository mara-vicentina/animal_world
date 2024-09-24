<template>
  <div>
    <v-container>
      <Head title="Consultas" />
      <v-row class="mb-8">
        <v-col cols="6">
          <h1 class="text-3xl font-bold">Consultas</h1>
        </v-col>
        <v-col cols="6" class="d-flex align-center justify-end">
          <ModalCreateAppointments :clients="clients" :animals="animals"/>
        </v-col>
      </v-row>

      <v-card class="overflow-hidden">
        <template v-slot:text>
          <v-text-field
            v-model="search"
            label="Search"
            prepend-inner-icon="mdi-magnify"
            variant="outlined"
            hide-details
            color="800"
            single-line
          ></v-text-field>
          <tr class="d-flex justify-space-between equal-width-columns">
            <td class="column">
              <span>Total de Consultas Cadastrados: {{ appointments.length }} </span>
            </td>
          </tr>
        </template>

        <v-data-table
          :headers="headers"
          :items="appointments"
          item-value="id"
          hover
          class="elevation-1"
          height="500"
          :search="search"
          fixed-header
        >
          <template v-slot:item.actions="{ item }">
            <v-icon
              class="me-2"
              color="800"
              size="large"
              @click="$refs.modalEditAppointment.openModal(item)"
            >
              mdi-pencil
            </v-icon>
            <v-icon
              size="large"
              color="red"
              @click="$refs.modalDeleteAppointment.openModal(item.id)"
            >
              mdi-delete
            </v-icon>
          </template>

          <template v-slot:no-data>
            <span>No data available</span>
          </template>
        </v-data-table>
      </v-card>

      <template v-slot:footer>
        <v-row class="justify-center">
          <v-pagination
            v-model="page"
            :length="totalPages"
            class="my-2"
            @input="updatePaginatedAppointments"
          ></v-pagination>
        </v-row>
      </template>
    </v-container>

    <ModalEditAppointments ref="modalEditAppointment" :clients="clients" :animals="animals" :appointments="appointments"/>
    <ModalDeleteAppointments ref="modalDeleteAppointment" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Components/Layout.vue'
import ModalCreateAppointments from '@/Pages/Appointments/Create.vue'
import ModalEditAppointments from '@/Pages/Appointments/Edit.vue';
import ModalDeleteAppointments from '@/Pages/Appointments/Delete.vue';

export default {
  components: {
    Head,
    Link,
    ModalCreateAppointments,
    ModalEditAppointments,
    ModalDeleteAppointments,
  },
  layout: Layout,
  props: {
    appointments: Array,
    clients: Array,
    animals: Array,
  },
  data() {
    return {
      search: '',
      headers: [
        { title: "Tutor", value: "client_name" },
        { title: "Animal", value: "animal_name" },
        { title: "Motivo da Consulta", value: "reason_formated" },
        { title: "Valor da Consulta", value: "value" },
        { title: "Ações", value: "actions", sortable: false },
      ],
      page: 1,
      itemsPerPage: 10,
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.appointments.length / this.itemsPerPage);
    },
    updatePaginatedAppointments() {
      const start = (this.page - 1) * this.itemsPerPage;
      const end = this.page * this.itemsPerPage;
      this.paginatedAppointments = this.appointments.slice(start, end);
    },
  },
}
</script>

<style lang="scss" scoped>
 .equal-width-columns {
    width: 100%;
  }

  .equal-width-columns .column {
    flex: 1;
    text-align: center;
    padding: 8px;
  }
</style>