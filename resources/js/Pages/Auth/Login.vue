<template>
    <v-layout class="rounded rounded-md">
      <v-navigation-drawer :width="977">
        <v-img
            src="/img/animals.jpeg"
            alt="Animals"
            width="100%"
            cover 
            class="login-background"
        ></v-img>
      </v-navigation-drawer>
  
      <v-main class="d-flex align-center justify-center" style="min-height: 300px;">
        <div class="auth-wrapper d-flex flex-row-reverse align-center justify-center pa-4  h-100">
            <VCard
                class="auth-card pa-4 pt-7"
                max-width="540"
                outlined
                elevation="2"
            >
                <VCardItem class="justify-center">
                    <RouterLink
                    to="/"
                    class="d-flex align-center gap-3"
                    >
                    <div
                    class="d-flex"
                    v-html="logo"
                    />
                    <v-img
                        src="/img/logo.png"
                        alt="AnimalWorld"
                        class="logo"
                    ></v-img>
                </RouterLink>
            </VCardItem>
            
            <VCardText class="pt-2 text-center">
                <h4 class="custom-font text-h4 mb-1">
                    Seja bem-vindo ao Animal World!
                </h4>
                <p class="mb-0">
                    Faça login na sua conta e inicie sua jornada!
                </p>
            </VCardText>
        
            <VCardText>
                <VForm @submit.prevent="() => {}">
                    <VRow>
                    <VCol cols="12">
                        <VTextField
                        v-model="form.email"
                        label="Email"
                        variant="outlined"
                        placeholder="email@email.com"
                        persistent-placeholder
                        color="primary"
                        type="email"
                        />
                    </VCol>
        
                    <VCol cols="12">
                        <VTextField
                        v-model="form.password"
                        label="Senha"
                        placeholder="············"
                        variant="outlined"
                        persistent-placeholder
                        color="primary"
                        :type="isPasswordVisible ? 'text' : 'password'"
                        :append-inner-icon="isPasswordVisible ? 'mdi-eye-outline' : 'mdi-eye-off-outline'"
                        @click:append-inner="isPasswordVisible = !isPasswordVisible"
                        />
        
                        <VBtn
                        block
                        type="button"
                        color="primary"
                        @click="login()"
                        >
                        Entrar
                        </VBtn>
                    </VCol>
        
                    <VCol
                        cols="12"
                        class="text-center text-base"
                    >
                        <span>Novo em nossa plataforma? </span>
                        <Link class="group flex items-center py-3 register" href="/user">
                            Crie sua conta
                        </Link>
                    </VCol>
        
                    <VCol
                        cols="12"
                        class="text-center"
                    >
                        <AuthProvider />
                    </VCol>
                    </VRow>
                </VForm>
                </VCardText>
            </VCard>
        </div>
      </v-main>
    </v-layout>
    
    <ToastMessages/>
  </template>

<script>
    import { Link } from '@inertiajs/vue3'
    import ToastMessages from '@/Components/ToastMessages.vue'

    export default {
        components: {
            Link,
            ToastMessages,
        },
        data() {
            return {
                isPasswordVisible: '',
                form: this.$inertia.form({
                    email: '',
                    password: '',
                })
            }
        },
        methods: {
            login() {
                this.form.post('/login');
            },
        },
    }
</script>

<style lang="scss" scoped>
    .login-background {
    height: 100vh;
    }

    .logo{
    height: 125px;
    width: 125px;
    }

    .custom-font {
        font-family: 'Inter', sans-serif!important;
        font-weight: 500!important;
        color: #1C2751!important;
    }

    .register{
        text-decoration: underline;
    }
</style>
