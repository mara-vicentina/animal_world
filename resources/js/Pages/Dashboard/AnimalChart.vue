<template>
  <Bar
    id="animal-chart"
    :options="chartOptions"
    :data="chartData"
  />
</template>
  
<script>
  import { Bar } from 'vue-chartjs'
  import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
  
  ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)
  
  export default {
    components: {
      Bar
    },
    props: {
      animalsReportChart: Object,
    },
    data() {
      return {
        chartData: {
          labels: this.animalsReportChart['labels'],
          datasets: [
            {
              label: 'Total de Animais Cadastrados',
              backgroundColor: '#1C2751',
              data: this.animalsReportChart['total'],
            },
            {
              label: 'Total de Fêmeas Cadastradas',
              backgroundColor: '#82A1D1',
              data: this.animalsReportChart['femaleTotal'],
            },
            {
              label: 'Total de Machos Cadastrados',
              backgroundColor: '#003777',
              data: this.animalsReportChart['maleTotal'],
            },
          ],
        },
        chartOptions: {
          plugins: {
            title: {
              display: true,
              text: 'Relatório de Animais Cadastrados',
              font: {
                size: 24,
              },
            }
          },
          responsive: true
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  #animal-chart {
    height: 400px !important;
  }
</style>