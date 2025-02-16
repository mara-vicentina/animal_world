<template>
  <div>
    <v-container>
      <Head title="Animais" />
      <v-row class="mb-8">
        <v-col cols="6">
          <h1 class="text-3xl font-bold">Animais</h1>
        </v-col>
        <v-col cols="6" class="d-flex align-center justify-end">
          <ModalCreateAnimal :clients="clients" />
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
              <span>Total de Animais Cadastrados: {{ animals.length }} </span>
            </td>
          </tr>
        </template>

        <v-data-table
          :headers="headers"
          :items="animals"
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
              @click="$refs.modalEditAnimal.openModal(item)"
            >
              mdi-pencil
            </v-icon>
            <v-icon
              size="large"
              color="red"
              @click="$refs.modalDeleteAnimal.openModal(item.id)"
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
            @input="updatePaginatedAnimals"
          ></v-pagination>
        </v-row>
      </template>
    </v-container>

    <ModalEditAnimal ref="modalEditAnimal" :clients="clients" />
    <ModalDeleteAnimal ref="modalDeleteAnimal" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import Layout from '@/Components/Layout.vue';
import ModalCreateAnimal from '@/Pages/Animals/Create.vue';
import ModalEditAnimal from '@/Pages/Animals/Edit.vue';
import ModalDeleteAnimal from '@/Pages/Animals/Delete.vue';

export default {
  components: {
    Head,
    Link,
    ModalCreateAnimal,
    ModalEditAnimal,
    ModalDeleteAnimal,
  },
  layout: Layout,
  props: {
    animals: Array,
    clients: Array,
  },
  data() {
    return {
      search: '',
      headers: [
        { title: 'Nome Completo', value: 'name' },
        { title: 'Espécie', value: 'species' },
        { title: 'Raça', value: 'race' },
        { title: 'Sexo', value: 'sex_formated' },
        { title: 'Animal Castrado', value: 'castrated_animal_formated' },
        { title: 'Tutor', value: 'client_name' },
        { title: 'Data de Nascimento', value: 'birth_date_formated' },
        { title: 'Ações', value: 'actions', sortable: false },
      ],
      page: 1,
      itemsPerPage: 10,
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.animals.length / this.itemsPerPage);
    },
    updatePaginatedAnimals() {
      const start = (this.page - 1) * this.itemsPerPage;
      const end = this.page * this.itemsPerPage;
      this.paginatedAnimals = this.animals.slice(start, end);
    }
  },
};
</script>

<style lang="scss"scoped>
.equal-width-columns {
    width: 100%;
  }

  .equal-width-columns .column {
    flex: 1;
    text-align: center;
    padding: 8px;
  }
  
  h1{
    color: #1C2751 !important;
  }
</style>
