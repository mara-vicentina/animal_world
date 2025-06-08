<template>
  <v-app>
    <v-layout>
      <v-navigation-drawer
        permanent
        width="300"
        class="drawer"
      >
        <v-img
          src="/img/logo.png"
          alt="AnimalWorld"
          class="logo"
          center
        ></v-img>
  
        <v-list nav>
          <Link :class="['menu-item', $page.url === '/dashboard' ? 'active' : '']" href="/">
            <v-icon class="custom-icon" icon="mdi-view-dashboard" />
            DASHBOARD
          </Link>
  
          <Link :class="['menu-item', $page.url === '/schedule' ? 'active' : '']" href="/schedule">
            <!-- <v-icon class="custom-icon" icon="mdi-file-chart-outline" /> -->
            <v-icon class="custom-icon" icon="mdi mdi-calendar-multiselect-outline" />
            AGENDAMENTOS
          </Link>
          
          <Link :class="['menu-item', $page.url === '/clients' ? 'active' : '']" href="/clients">
            <v-icon class="custom-icon" icon="mdi-account-group" />
            CLIENTES
          </Link>
  
          <Link :class="['menu-item', $page.url === '/animals' ? 'active' : '']" href="/animals">
            <v-icon class="custom-icon" icon="mdi-heart-pulse" />
            ANIMAIS
          </Link>
  
          <Link :class="['menu-item', $page.url === '/appointments' ? 'active' : '']" href="/appointments">
            <v-icon class="custom-icon" icon="mdi-clipboard-check-outline" />
            CONSULTAS
          </Link>
  
          <Link :class="['menu-item', $page.url === '/financial' ? 'active' : '']" href="/financial">
            <v-icon class="custom-icon" icon="mdi-currency-usd" />
            FINANCEIRO
          </Link>
        </v-list>
            <template v-slot:append>
              <div class="pa-2">
                <div :class="['menu-item', 'active']" @click="logout()">
                  <v-icon class="custom-icon" icon="mdi-logout" />
                  DESCONECTAR
                </div>
              </div>
            </template>
      </v-navigation-drawer>
      <v-main class="main-content">
        <v-toolbar>
          <v-toolbar-title class="custom">Animal World</v-toolbar-title>
          <v-spacer></v-spacer>

          <v-btn icon @click="abrirModal">
            
            <v-icon class="color-icon">mdi-bell</v-icon>
            <v-badge
              v-if="notificacoes.length"
              color="red"
              content="●"
              offset-x="-4"
              offset-y="-12"
              dot
            />
          </v-btn>

          <v-btn icon>
            <Profile/>
          </v-btn>
        </v-toolbar>
        <Notifications
          ref="Notifications"
          :notificacoes="notificacoes"
        />
        <v-container
          class="py-8 px-6 main-content"
          fluid
        >
          <slot/>
        </v-container>
      </v-main>
    </v-layout>
  </v-app>

  <ToastMessages/>
</template>


<script>
import { Link } from '@inertiajs/vue3'
import ToastMessages from '@/Components/ToastMessages.vue'
import Profile from '@/Components/Profile.vue'
import Notifications from '@/Components/Notifications.vue';

export default {
  components: {
    Link,
    ToastMessages,
    Profile,
    Notifications,
  },
  props: {
    notificacoes: Array,
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length
    },
    logout() {
      this.$inertia.delete('/logout')
    },
    abrirModal() {
      this.$refs.Notifications.openModal();
    },
    fecharModal() {
      this.$refs.Notifications.closeModal();
    }
  },
}
</script>

<style lang="scss" scoped>
  .drawer {
    background-color: #82A1D1!important;
  }

  .logo {
    height: 125px;
    width: 125px;
    margin: 16px auto;
    border: 2px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    background-color: #F5F5F5;
  }

  .menu-item {
    display: flex;
    align-items: center;
    padding: 16px 16px;
    border-radius: 4px;
    transition: background-color 0.3s, color 0.3s;
    text-decoration: none;
    color: #1C2751;
    font-size: 15PX;
    margin-top: 0.3rem;
    font-weight: 500;
    overflow: hidden;
    
    &:hover {
      background-color: #F5F5F5;
      color: #5572B2!important;
    }
  }

  .active {
    background-color: #F5F5F5;
    color: #5572B2;
  }

  .main-content {
    height: 100vh;
    overflow: hidden;
  }

  .v-container {
    height: calc(100vh - 64px);
    overflow: auto;
  }

  .custom-icon {
    margin-right: 0.9rem;
    opacity: 0.6;
  }

  .color-icon{
    color: #5572B2;
  }

  .custom {
    color: #1C2751;
    font-size: 1.5rem!important;
    font-weight: 600!important;
  }
</style>
