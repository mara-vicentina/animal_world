<template>
  <Bar
    id="appointment-chart"
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
      appointmentsReportChart: Object,
    },
    data() {
      return {
        chartData: {
          labels: this.appointmentsReportChart['labels'],
          datasets: [
            {
              label: 'Total de Vacinação',
              backgroundColor: '#1C2751',
              data: this.appointmentsReportChart['totalVaccines'],
            },
            {
              label: 'Total de Rotinas',
              backgroundColor: '#82A1D1',
              data: this.appointmentsReportChart['totalRoutine'],
            },
            {
              label: 'Total de Análises Clínicas',
              backgroundColor: '#003777',
              data: this.appointmentsReportChart['totalClinicalAnalysis'],
            },
          ],
        },
        chartOptions: {
          plugins: {
            title: {
              display: true,
              text: 'Total de Consultas Realizadas',
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
  #appointment-chart {
    height: 400px !important;
  }
</style>