<template>
  <div class="q-pa-md row items-center justify-center q-gutter-md flex flex-center" style="height: 95vh;">
    <q-card class="my-card my-auto" :style="$q.screen.gt.sm ? 'width: 25vw' : 'width: 95vw'">
      <q-card-section>
        <h4 class="text-h4 q-ma-md q-mb-lg text-center text-bold">Sign In</h4>
        <q-input dense outlined v-model="email" label="Email" class="q-mt-md"
          :rules="[val => !!val || 'Email is required', val => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val) || 'Invalid email']"
          required />
        <q-input dense outlined v-model="password" label="Password" class="q-mt-md"
          :rules="[val => !!val || 'Password is required']" required />
        <div class="q-pa-xs row items-center justify-center">
          <q-btn color="dark" label="Sign In" class="q-mt-md flex justify-center" @click="login" />
        </div>
      </q-card-section>
      <q-card-section class="text-center q-mt-none">
        <p>Dont have an account? <a class="text-primary" @click="goToRegisterPage" style="cursor: pointer;">Sign Up</a>
        </p>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import axios from 'axios';
import { useAuthStore } from 'src/stores/auth';

const authStore = useAuthStore();
export default {
  data() {
    return {
      email: '',
      password: '',
      authStore,
      apiUrl: authStore.apiUrl,
    };
  },
  methods: {
    async login() {
      try {
        if (this.email == '') {
          this.showAlert('Please enter email', 'negative');
        } else if (this.password == '') {
          this.showAlert('Please enter password', 'negative');
        } else {
          const response = await axios.post(this.apiUrl + '/login', {
            email: this.email,
            password: this.password,
          });

          this.showAlert('Login Successfull!', 'positive');

          const { user, token } = response.data;
          this.authStore.login({ user, token });

          this.$router.push('/');
        }
      } catch (error) {
        const errorMessage = error.response.data.error || 'Login failed. Please try again.';
        this.showAlert(errorMessage, 'negative');
      }
    },
    goToRegisterPage() {
      this.$router.push('/register');
    },
    showAlert(message, type = 'positive') {
      this.$q.notify({
        message: message,
        color: type === 'positive' ? 'positive' : 'negative',
      });
    },
  },
  mounted() {
  }
};
</script>

<style lang="sass" scoped>
</style>
