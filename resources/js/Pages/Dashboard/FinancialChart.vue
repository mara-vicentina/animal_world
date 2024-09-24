<template>
  <Bar
    id="financial-chart"
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
      financialReportChart: Object,
    },
    data() {
      return {
        chartData: {
          labels: this.financialReportChart['labels'],
          datasets: [
            {
              label: 'Total Líquido',
              backgroundColor: '#1C2751',
              data: this.financialReportChart['total'],
            },
            {
              label: 'Faturamento',
              backgroundColor: '#4CAF50',
              data: this.financialReportChart['inflow'],
            },
            {
              label: 'Gastos',
              backgroundColor: '#F44336',
              data: this.financialReportChart['outflow'],
            },
          ],
        },
        chartOptions: {
          plugins: {
            title: {
              display: true,
              text: 'Relatório Financeiro',
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
  #financial-chart {
    height: 500px !important;
    margin: 0 auto;
  }
</style>