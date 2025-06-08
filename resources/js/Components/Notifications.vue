<template>
  <v-dialog v-model="isVisible" width="600">
    <v-card class="text-center">
      <template v-slot:title>
        <span class="title">Notificações de novos Check-ups</span>
      </template>

      <v-card-text class="py-0 px-4">
        <div v-if="!notificacoes.length">
          Nenhuma notificação no momento.
        </div>

        <div v-else class="notification-list">
          <v-card 
          v-for="(n, i) in localNotificacoes"
            :color="n.read ? '' : 'blue-lighten-5'"
            :key="i"
            class="mb-4 px-4 py-3 notification-card"
            elevation="2"
            @click="marcarComoLida(n.id)"
          >
            <v-row align="center" no-gutters>
              <v-col cols="1" class="text-left">
                <v-icon :color="n.read ? 'grey' : 'blue-darken-2'">
                  {{ n.read ? 'mdi-bell-outline' : 'mdi-bell-alert' }}
                </v-icon>
              </v-col>
              <v-col cols="11">
                <div class="text-left font-weight-medium">
                  {{ n.message }}
                </div>
                <div class="text-left text-caption text-grey-darken-1">
                  Última consulta: {{ formatarData(n.last_appointment_date) }}
                </div>
              </v-col>
            </v-row>
          </v-card>
        </div>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>

export default {
  props: {
    notificacoes: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      isVisible: false,
       localNotificacoes: [...this.notificacoes],
    };
  },
  methods: {
    openModal() {
      this.isVisible = true;
    },
    closeModal() {
      this.isVisible = false;
    },
    async marcarComoLida(id) {
      await axios.put(`/notifications/${id}/read`)
      this.localNotificacoes = this.localNotificacoes.map(n =>
        n.id === id ? { ...n, read: true } : n
      );
    },
    formatarData(data) {
      if (!data) return ''
      const d = new Date(data)
      return d.toLocaleDateString('pt-BR')
    }
  },
};
</script>

<style lang="scss" scoped>
.title {
  color: #344C92;
  font-size: 1.5rem;
  text-align: center;
  font-weight: 600;
}

.notification-card {
  border-radius: 12px;
  background-color: #F5F7FF;
  transition: box-shadow 0.2s;
  &:hover {
    box-shadow: 0 4px 12px rgba(52, 76, 146, 0.1);
  }
}

.notification-list {
  margin-top: 1rem;
}
</style>
