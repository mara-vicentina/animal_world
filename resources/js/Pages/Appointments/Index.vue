<template>
  <div>
    <v-container>
      <Head title="Consultas" />
      <v-row class="mb-8">
        <v-col cols="6">
          <h1 class="text-3xl font-bold">Consultas</h1>
        </v-col>
        <v-col cols="5" class="d-flex align-center justify-end">
          <v-btn
              color="green"
              text="Exportar"
              variant="flat"
              @click="exportAllToExcel(appointments)"
          ></v-btn>
        </v-col>
        <v-col cols="1" class="d-flex align-center justify-end">
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
              class="me-2"
              color="red"
              @click="$refs.modalDeleteAppointment.openModal(item.id)"
            >
              mdi-delete
            </v-icon>
            <v-icon
              size="large"
              class="me-2"
              color="blue"
              @click="exportToPDF(item)"
            >
              mdi-file-pdf-box
            </v-icon>
            <v-icon
              size="large"
              color="green"
              @click="exportToExcel(item)"
            >
              mdi-file-excel
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
import * as XLSX from 'xlsx';
import jsPDF from 'jspdf';
import 'jspdf-autotable';

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
  methods: {
    exportToPDF(item) {
      const doc = new jsPDF();
      const logo = new Image();
      logo.src = '/img/logo.png';

      logo.onload = function () {
        const logoWidth = 25;
        const logoHeight = 25;
        const pageWidth = doc.internal.pageSize.getWidth();
        const centerX = (pageWidth - logoWidth) / 2;

        // Adicionar logo centralizada no topo
        doc.addImage(logo, 'PNG', centerX, 10, logoWidth, logoHeight);

        // Adicionar cabeçalho
        doc.setFontSize(16);
        doc.setFont('Inter', 'bold');
        doc.text('Relatório da Consulta', pageWidth / 2, 45, { align: 'center' });

        // Informações do cliente e animal
        doc.setFontSize(12);
        doc.setFont('Inter', 'normal');

        // Tutor
        doc.setFont('Inter', 'bold');
        doc.text(`Tutor: ${item.client_name}             Animal: ${item.animal_name}             Valor da Consulta: R$ ${item.value}`, 20, 65);

        // Motivo da Consulta
        doc.setFont('Inter', 'bold');
        doc.text('Detalhes da Consulta:', 20, 75);
        doc.setFont('Inter', 'normal');
        doc.text(`Motivo: ${item.reason_formated}`, 20, 85);

        if(item.reason === 0) {
          doc.setFont('Inter', 'normal');
          doc.text(`Vacinas aplicadas: ${item.which_vaccines}`, 20, 95);
          doc.setDrawColor(150, 150, 150); // Cor cinza para a caixa
          doc.rect(15, 50, 180, 80); // (x, y, largura, altura)
        } else if (item.reason === 1) {
          doc.setFont('Inter', 'normal');
          doc.text(`Vacinas em dia: ${item.up_to_date_vaccines === 1 ? 'Sim   ' : 'Não   '}  Vermifugação em dia: ${item.up_to_date_deworming === 1 ? 'Sim   ' : 'Não   '}  Uso de antiparasitários: ${item.use_antiparasitics === 1 ? 'Sim   ' : 'Não   '}`, 20, 95);
          doc.text(`Observações/Descrição: ${item.observations_description}`, 20, 105);
          doc.text(`Cirurgias anteriores: ${item.previous_surgeries}`, 20, 115);
          doc.text(`Doenças ou condições crônicas: ${item.illnesses_chronic_conditions}`, 20, 125);
          doc.text(`Medicações: ${item.medication}`, 20, 135);
          doc.text(`Exames: ${item.exams}`, 20, 145);
          doc.setDrawColor(150, 150, 150); // Cor cinza para a caixa
          doc.rect(15, 50, 180, 120); // (x, y, largura, altura)
        } else if (item.reason === 2) {
          doc.setFont('Inter', 'normal');
          doc.text(`Vacinas em dia: ${item.up_to_date_vaccines === 1 ? 'Sim   ' : 'Não   '}  Vermifugação em dia: ${item.up_to_date_deworming === 1 ? 'Sim   ' : 'Não   '}  Uso de antiparasitários: ${item.use_antiparasitics === 1 ? 'Sim   ' : 'Não   '}`, 20, 95);
          doc.text(`Cirurgias anteriores: ${item.previous_surgeries}`, 20, 105);
          doc.text(`Doenças ou condições crônicas: ${item.illnesses_chronic_conditions}`, 20, 115);
          doc.text(`Medicações: ${item.medication}`, 20, 125);
          doc.text(`Sintomas principais: ${item.main_symptoms}`, 20, 135);
          doc.text(`Mudança no comportamento: ${item.change_behavior}`, 20, 145);
          doc.text(`Temperatura Corporal: ${item.temperature}`, 20, 155);
          doc.text(`Frequência Cardíaca e Respiratória: ${item.heart_respiratory_rate}`, 20, 165);
          doc.text(`Mucosas e Hidratação: ${item.mucous_membranes_hydration}`, 20, 175);
          doc.text(`Peso: ${item.weight}`, 20, 185);
          doc.text(`Escala de Dor: ${item.pain_scale}`, 20, 195);
          doc.text(`Pele e Pelagem: ${item.skin_coat}  Olhos e Ouvidos: ${item.eyes_ears}`, 20, 205);
          doc.text(`Cavidade Oral: ${item.oral_cavity}  Abdômen e Locomoção: ${item.abdomen_locomotion}`, 20, 215);
          doc.text(`Sistema Reprodutivo: ${item.reproductive_system}`, 20, 225);
          doc.text(`Exames: ${item.exams}`, 20, 235);
          doc.setDrawColor(150, 150, 150); // Cor cinza para a caixa
          doc.rect(15, 50, 180, 200); // (x, y, largura, altura)
        }

        // Salvar o PDF
        doc.save(`consulta_${item.client_name}.pdf`);
        };
    },
    exportToExcel(item) {
      // Preparando os dados específicos do animal selecionado
      let data = {
        'Tutor': item.client_name,
        'Animal': item.animal_name,
        'Motivo da Consulta': item.reason_formated,
        'Valor da Consulta': item.value,
      };

      // Condições para incluir dados específicos com base no motivo da consulta
      if (item.reason === 0) {
        data['Vacinas Aplicadas'] = item.which_vaccines;
      } else if (item.reason === 1) {
        data['Vacinas em Dia'] = item.up_to_date_vaccines === 1 ? 'Sim' : 'Não';
        data['Vermifugação em Dia'] = item.up_to_date_deworming === 1 ? 'Sim' : 'Não';
        data['Uso de Antiparasitários'] = item.use_antiparasitics === 1 ? 'Sim' : 'Não';
        data['Observações'] = item.observations_description;
        data['Cirurgias Anteriores'] = item.previous_surgeries;
        data['Doenças Crônicas'] = item.illnesses_chronic_conditions;
        data['Medicações'] = item.medication;
        data['Exames'] = item.exams;
      } else if (item.reason === 2) {
        data['Vacinas em Dia'] = item.up_to_date_vaccines === 1 ? 'Sim' : 'Não';
        data['Vermifugação em Dia'] = item.up_to_date_deworming === 1 ? 'Sim' : 'Não';
        data['Uso de Antiparasitários'] = item.use_antiparasitics === 1 ? 'Sim' : 'Não';
        data['Cirurgias Anteriores'] = item.previous_surgeries;
        data['Doenças Crônicas'] = item.illnesses_chronic_conditions;
        data['Medicações'] = item.medication;
        data['Sintomas Principais'] = item.main_symptoms;
        data['Mudança no Comportamento'] = item.change_behavior;
        data['Temperatura Corporal'] = item.temperature;
        data['Frequência Cardíaca/Respiratória'] = item.heart_respiratory_rate;
        data['Mucosas e Hidratação'] = item.mucous_membranes_hydration;
        data['Peso'] = item.weight;
        data['Escala de Dor'] = item.pain_scale;
        data['Pele/Pelagem'] = item.skin_coat;
        data['Olhos/Ouvidos'] = item.eyes_ears;
        data['Cavidade Oral'] = item.oral_cavity;
        data['Abdômen/Locomoção'] = item.abdomen_locomotion;
        data['Sistema Reprodutivo'] = item.reproductive_system;
        data['Exames'] = item.exams;
      }

      // Criando a planilha com os dados do animal específico
      const worksheet = XLSX.utils.json_to_sheet([data]);
      const workbook = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Consulta');
      XLSX.writeFile(workbook, `consulta_${item.client_name}_${item.animal_name}.xlsx`);
    },
    exportAllToExcel(appointments) {
      if (!appointments || appointments.length === 0) {
        alert("Nenhuma consulta disponível para exportação.");
        return;
      }

      // Preparando os dados para exportação
      const data = appointments.map(item => {
        const entry = {
          'Tutor': item.client_name,
          'Animal': item.animal_name,
          'Motivo da Consulta': item.reason_formated,
          'Valor da Consulta': item.value,
        };

        if (item.reason === 0) {
          entry['Vacinas Aplicadas'] = item.which_vaccines;
        } else if (item.reason === 1 || item.reason === 2) {
          entry['Vacinas em Dia'] = item.up_to_date_vaccines === 1 ? 'Sim' : 'Não';
          entry['Vermifugação em Dia'] = item.up_to_date_deworming === 1 ? 'Sim' : 'Não';
          entry['Uso de Antiparasitários'] = item.use_antiparasitics === 1 ? 'Sim' : 'Não';

          if (item.reason === 1) {
            entry['Observações'] = item.observations_description;
            entry['Cirurgias Anteriores'] = item.previous_surgeries;
            entry['Doenças Crônicas'] = item.illnesses_chronic_conditions;
            entry['Medicações'] = item.medication;
            entry['Exames'] = item.exams;
          } else if (item.reason === 2) {
            entry['Sintomas Principais'] = item.main_symptoms;
            entry['Mudança no Comportamento'] = item.change_behavior;
            entry['Temperatura Corporal'] = item.temperature;
            entry['Frequência Cardíaca/Respiratória'] = item.heart_respiratory_rate;
            entry['Mucosas e Hidratação'] = item.mucous_membranes_hydration;
            entry['Peso'] = item.weight;
            entry['Escala de Dor'] = item.pain_scale;
            entry['Pele/Pelagem'] = item.skin_coat;
            entry['Olhos/Ouvidos'] = item.eyes_ears;
            entry['Cavidade Oral'] = item.oral_cavity;
            entry['Abdômen/Locomoção'] = item.abdomen_locomotion;
            entry['Sistema Reprodutivo'] = item.reproductive_system;
            entry['Exames'] = item.exams;
          }
        }

        return entry;
      });

      // Criando a planilha
      const worksheet = XLSX.utils.json_to_sheet(data);
      const workbook = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Consultas');

      // Exportando o arquivo Excel
      XLSX.writeFile(workbook, `consultas_${new Date().toISOString().slice(0, 10)}.xlsx`);
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

  h1{
    color: #1C2751 !important;
  }
</style>