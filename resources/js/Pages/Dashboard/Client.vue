<template>
  <VCard height="310">
    <VCardItem>
      <VCardTitle>Clientes</VCardTitle>

      <template #append>
        <div class="me-n3">
          <MoreBtn :menu-list="moreList" />
        </div>
      </template>
    </VCardItem>

    <VCardText>
      <div class="d-flex align-center">
        <h3 class="text-h3">
          {{ totalClients }}
        </h3>

        <VIcon
          size="24"
          icon="ri-arrow-up-s-line"
          color="success"
        />
      </div>
      <div class="text-body-1 mb-12">
        Total de clientes no mês/período
      </div>
    </VCardText>
  </VCard>
</template>

<script setup>

  defineProps({
    totalClients: Number,
  })
</script>

<style lang="scss" scoped>
  .card-list {
    --v-card-list-gap: 1.5rem;
  }
</style>