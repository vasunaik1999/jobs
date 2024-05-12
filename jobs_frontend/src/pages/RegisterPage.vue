<template>
  <div class="q-pa-md row items-center justify-center q-gutter-md flex flex-center" style="height: 95vh;">
    <q-card class="my-card my-auto" :style="$q.screen.gt.sm ? 'width: 25vw' : 'width: 95vw'">
      <q-card-section>
        <h4 class="text-h4 q-ma-md q-mb-lg text-center text-bold">Sign Up</h4>
        <q-input dense outlined v-model="name" label="Name" class="q-mt-md"
          :rules="[val => !!val || 'Name is required']" />
        <q-input dense outlined v-model="email" label="Email" class="q-mt-md"
          :rules="[val => !!val || 'Email is required', val => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val) || 'Invalid email']" />
        <q-input dense outlined v-model="password" label="Password" class="q-mt-md"
          :rules="[val => !!val || 'Password is required', val => val.length >= 8 || 'Password must be at least 8 characters long']"
          type="password" />
        <q-input dense outlined v-model="confirmPassword" label="Confirm Password" class="q-mt-md"
          :rules="[val => !!val || 'Confirmation password is required', val => val === password || 'Passwords do not match']"
          type="password" />
        <div class="q-pa-xs row items-center justify-center">
          <q-btn color="dark" label="Sign Up" class="q-mt-md flex justify-center" @click="register" />
        </div>
      </q-card-section>
      <q-card-section class="text-center q-mt-none">
        <p>Already have an account? <a class="text-primary" @click="goToLoginPage" style="cursor: pointer;">Sign In</a>
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
      name: '',
      email: '',
      password: '',
      confirmPassword: '',
      authStore,
      apiUrl: authStore.apiUrl,
    };
  },
  methods: {
    async register() {
      try {
        const response = await axios.post(this.apiUrl + '/register', {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.confirmPassword,
        });

        this.$q.notify({
          type: 'positive',
          message: 'Registration Successfully!',
        });

        this.$router.push('/login');
      } catch (error) {
        if (error.response.status === 422) {
          const errors = error.response.data.errors;
          Object.keys(errors).forEach(key => {
            const errorMessage = errors[key][0];
            this[`${key}Error`] = errorMessage;
            this.$q.notify({
              type: 'negative',
              message: errorMessage,
            });
          });
        } else {
          const errorMessage = error.response.data.message || 'Registration Failed. Please try again later.';
          this.$q.notify({
            type: 'negative',
            message: errorMessage,
          });
        }
      }
    },
    goToLoginPage() {
      this.$router.push('/login');
    },
    showAlert(message, type = 'positive') {
      this.$q.notify({
        message: message,
        color: type === 'positive' ? 'positive' : 'negative',
      });
    },
  },
};
</script>

<style lang="sass" scoped>
</style>
