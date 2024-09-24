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
                  variant="text"
                  rounded
                >
                  Edit Account
                </v-btn>
                <v-divider class="my-3"></v-divider>
                <v-btn
                  variant="text"
                  rounded
                >
                  Disconnect
                </v-btn>
              </div>
            </v-card-text>
          </v-card>
        </v-menu>
      </v-row>
    </v-container>
  </template>

<script>
    export default {
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