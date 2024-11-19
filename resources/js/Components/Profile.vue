<template>
    <v-container
      style="height: 300px; padding: 10px;"
      fluid
    >
      <v-row justify="center">
        <v-menu
          min-width="200px"
          rounded
        >
        <template v-slot:activator="{ props }">
            <v-avatar
                v-bind="props" 
                color="none"
                size="large"
            >
                <v-icon>mdi-dots-vertical</v-icon>
            </v-avatar>
        </template>
          <v-card>
            <v-card-text>
              <div class="mx-auto text-center">
                <h3 class="custom-font ">{{ $page.props.auth.user.full_name }}</h3>
                <p class="text-caption mt-1 custom-font ">
                    {{ $page.props.auth.user.email }}
                </p>
                <v-divider class="my-3"></v-divider>
                <v-btn
                    color="800"
                    text="EDITAR"
                    variant="flat"
                    @click="$refs.modalEditProfile.openModal()"
                ></v-btn>
                <v-divider class="my-3"></v-divider>
              </div>
            </v-card-text>
          </v-card>
        </v-menu>
      </v-row>
    </v-container>
  <ModalEditProfile ref="modalEditProfile" />
  </template>

<script>
  import ModalEditProfile from '@/Components/EditProfile.vue'
  export default {
    components: {
        ModalEditProfile,
      },
      props: {
          user: Object,
      },
  }
</script>

<style lang="scss" scoped>
  .custom-font {
      font-family: 'Inter', sans-serif!important;
      font-weight: 500!important;
      color: #1C2751!important;
  }

</style>