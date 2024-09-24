<template>
  <div>
    <v-snackbar
      v-if="toastMessage"
      v-model="toastMessage"
      location="top right"
      :timeout="3000"
      :color="$page.props.flash?.success ? 'green' : 'red'"
    >
        <div v-if="$page.props?.errors">
          <div v-for="(error, index) in Object.values($page.props.errors)" :key="index">
            <div>{{ error }}</div>
          </div>
        </div>
        <div v-if="$page.props.flash?.error">{{ $page.props.flash.error }}</div>
        <div v-if="$page.props.flash?.success">{{ $page.props.flash.success }}</div>
      <template v-slot:action="{ attrs }">
        <v-btn
          color="red"
          text
          v-bind="attrs"
          @click="toastMessage = false"
        >
          Fechar
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>
  
  <script>
  export default {
    data() {
      return {
        toastMessage: false,
      };
    },
    watch: {
        '$page.props.flash': {
        handler() {
            if (Object.keys(this.$page.props?.errors).length > 0 || this.$page.props.flash?.error != null || this.$page.props.flash?.success != null) {
                this.toastMessage = true
            }
        },
        deep: true,
        },
    },
  };
  </script> 