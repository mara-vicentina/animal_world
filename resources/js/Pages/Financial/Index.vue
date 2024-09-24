<template>
  <div>
    <v-container>
      <Head title="Fluxo de Caixa" />
      <v-row class="mb-8">
        <v-col cols="6">
          <h1 class="text-3xl font-bold">Fluxo de Caixa</h1>
        </v-col>
        <v-col cols="6" class="d-flex align-center justify-end">
          <ModalCreateFinancial />
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
              <span>Entrada: R$ {{ inflow }} </span>
            </td>
            <td class="column">
              <span>Saída: R$ {{ outflow }} </span>
            </td>
            <td class="column">
              <span>Total em caixa: R$ {{ total }} </span>
            </td>
          </tr>
        </template>
        <v-data-table
          :headers="headers"
          :items="financial"
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
              @click="$refs.modalEditFinancial.openModal(item)"
            >
              mdi-pencil
            </v-icon>
            <v-icon
              size="large"
              color="red"
              @click="$refs.modalDeleteFinancial.openModal(item.id)"
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
            @input="updatePaginatedFinancial"
          ></v-pagination>
        </v-row>
      </template>
    </v-container>
    
    <ModalEditFinancial ref="modalEditFinancial" />
    <ModalDeleteFinancial ref="modalDeleteFinancial" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Components/Layout.vue'
import ModalCreateFinancial from '@/Pages/Financial/Create.vue'
import ModalEditFinancial from '@/Pages/Financial/Edit.vue'
import ModalDeleteFinancial from '@/Pages/Financial/Delete.vue'

export default {
  components: {
    Head,
    Link,
    ModalCreateFinancial,
    ModalEditFinancial,
    ModalDeleteFinancial,
  },
  layout: Layout,
  props: {
    financial: Array,
    outflow: Number,
    inflow: Number,
    total: Number,
  },
  data() {
    return {
      search: '',
      headers: [
        { title: "Nome da Entrada ou Saída", value: "name" },
        { title: "Tipo", value: "type_formated" },
        { title: "Valor", value: "value" },
        { title: "Data", value: "date_formated" },
        { title: "Ações", value: "actions", sortable: false },
      ],
      page: 1,
      itemsPerPage: 10,
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.financial.length / this.itemsPerPage);
    },
    updatePaginatedFinancial() {
      const start = (this.page - 1) * this.itemsPerPage;
      const end = this.page * this.itemsPerPage;
      this.paginatedFinancial = this.financial.slice(start, end);
    }
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