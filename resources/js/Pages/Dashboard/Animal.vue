
<template>
  <VCard height="310">
    <VCardItem>
      <VCardTitle>Animais</VCardTitle>

      <template #append>
        <div class="me-n3">
          <MoreBtn :menu-list="moreList" />
        </div>
      </template>
    </VCardItem>

    <VCardText>
      <div class="d-flex align-center">
        <h3 class="text-h3">
          {{ totalMalesAnimals + totalFemalesAnimals }}
        </h3>

        <VIcon
        size="24"
          icon="ri-arrow-up-s-line"
          color="success"
          />
      </div>
      <div class="text-body-1 mb-12">
        Total de Animais no mês/período
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
      totalMalesAnimals: Number,
      totalFemalesAnimals: Number,
    },
    data() {
        return {
          earnings: [
            {
              avatar: 'mdi-gender-male',
              title: 'Machos',
              subtitle: 'Vuejs, React & HTML',
              amount: this.totalMalesAnimals,
              progress: 'primary',
            },
            {
              avatar: 'mdi-gender-female',
              title: 'Fêmeas',
              subtitle: 'Sketch, Figma & XD',
              amount: this.totalFemalesAnimals,
              progress: 'info',
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
