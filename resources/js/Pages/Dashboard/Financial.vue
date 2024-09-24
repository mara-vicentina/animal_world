<template>
  <VCard height="310">
    <VCardItem>
      <VCardTitle>Fluxo de Caixa</VCardTitle>

      <template #append>
        <div class="me-n3">
          <MoreBtn :menu-list="moreList" />
        </div>
      </template>
    </VCardItem>

    <VCardText>
      <div class="d-flex align-center">
        <h3 class="text-h3">
          R$ {{ totalFinancialInflow - totalFinancialOutflow }}
        </h3>

        <VIcon
          size="24"
          icon="ri-arrow-up-s-line"
          color="success"
        />
      </div>
      <div class="text-body-1 mb-12">
        Total em caixa no mês/período
      </div>

      <VList class="card-list">
        <VListItem
          v-for="earning in earnings"
          :key="earning.title"
        >
          <template #prepend>
            <VAvatar
              rounded
              :icon="earning.avatar"
              variant="tonal"
            />
          </template>

          <VListItemTitle class="font-weight-medium">
            {{ earning.title }}
          </VListItemTitle>
          <VListItemSubtitle class="text-body-1">
            <VProgressLinear
                :color="earning.progress"
                model-value="80"
                rounded-bar
                rounded
              />
          </VListItemSubtitle>

          <template #append>
            <div>
              <h6 class="text-h6 mb-2">
                {{ earning.amount }}
              </h6>
            </div>
          </template>
        </VListItem>
      </VList>
    </VCardText>
  </VCard>
</template>

<script>
  export default {
    components: {},
    props: {
      totalFinancialInflow: Number,
      totalFinancialOutflow: Number,
    },
    data() {
        return {
          earnings: [
            {
              avatar: 'mdi-cash-check',
              title: 'Faturamento',
              subtitle: 'Vuejs, React & HTML',
              amount: 'R$'+ this.totalFinancialInflow,
              progress: 'success',
            },
            {
              avatar: 'mdi-cash-remove',
              title: 'Gastos',
              subtitle: 'Sketch, Figma & XD',
              amount: 'R$' + this.totalFinancialOutflow,
              progress: 'red',
            },
          ]
        }
    },
  }
</script>

<style lang="scss" scoped>
  .card-list {
    --v-card-list-gap: 1.5rem;
  }
</style>