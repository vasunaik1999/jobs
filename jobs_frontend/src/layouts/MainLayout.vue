<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated class="bg-dark">
      <q-toolbar>
        <q-btn flat dense round icon="menu" aria-label="Menu" @click="toggleLeftDrawer" />

        <q-toolbar-title>
          Jobs
        </q-toolbar-title>

        <div>
          <div v-if="!authStore.isAuthenticated">
            <q-btn color="white" size="sm" class="q-px-sm" text-color="black" label="Sign In"
              @click="$router.push('/login')" />
            <q-btn color="white" size="sm" outline class="q-ml-sm q-px-sm" text-color="white" label="Register"
              @click="$router.push('/register')" />
          </div>
          <div v-else>
            <q-btn color="white" size="sm" class="q-px-sm" text-color="black" label="Logout" icon="logout"
              @click="logout" />
          </div>
        </div>
      </q-toolbar>
    </q-header>

    <!-- Side bar -->
    <q-drawer v-model="leftDrawerOpen" show-if-above :width="200" :breakpoint="400">
      <q-scroll-area style="height: calc(100% - 150px); margin-top: 150px; border-right: 1px solid #ddd">
        <q-list padding>
          <q-item clickable v-ripple @click="$router.push('/')" :active="isActive('/')">
            <q-item-section avatar>
              <q-icon name="reorder" />
            </q-item-section>

            <q-item-section>
              Job Listings
            </q-item-section>
          </q-item>

          <q-item clickable v-ripple @click="$router.push('/my-applications')" :active="isActive('/my-applications')">
            <q-item-section avatar>
              <q-icon name="apps" />
            </q-item-section>

            <q-item-section>
              My Applications
            </q-item-section>
          </q-item>

          <q-item clickable v-ripple @click="$router.push('/post-job')" :active="isActive('/post-job')">
            <q-item-section avatar>
              <q-icon name="send" />
            </q-item-section>

            <q-item-section>
              Post Job
            </q-item-section>
          </q-item>

          <q-item clickable v-ripple @click="$router.push('/my-jobs')" :active="isActive('/my-jobs')">
            <q-item-section avatar>
              <q-icon name="apps" />
            </q-item-section>

            <q-item-section>
              My Job Listing
            </q-item-section>
          </q-item>

          <q-item clickable v-ripple @click="$router.push('/profile')" :active="isActive('/profile')">
            <q-item-section avatar>
              <q-icon name="manage_accounts" />
            </q-item-section>

            <q-item-section>
              Profile
            </q-item-section>
          </q-item>

          <q-item v-if="authStore.isAuthenticated" clickable v-ripple @click="logout">
            <q-item-section avatar>
              <q-icon name="logout" />
            </q-item-section>

            <q-item-section>
              Logout
            </q-item-section>
          </q-item>
        </q-list>
      </q-scroll-area>

      <q-img class="absolute-top" src="https://cdn.quasar.dev/img/material.png" style="height: 150px">
        <div class="absolute-bottom bg-transparent" v-if="authStore.user">
          <q-avatar size="56px" class="q-mb-sm">
            <img src="https://cdn.quasar.dev/img/boy-avatar.png">
          </q-avatar>
          <div class="text-weight-bold">{{ authStore.user.name }}</div>
          <div>{{ authStore.user.email }}</div>
        </div>
      </q-img>
    </q-drawer>
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import axios from 'axios';
import { useAuthStore } from 'src/stores/auth';

const authStore = useAuthStore();
export default {
  name: 'MainLayout',
  data() {
    return {
      leftDrawerOpen: false,
      authStore,
    };
  },
  methods: {
    toggleLeftDrawer() {
      this.leftDrawerOpen = !this.leftDrawerOpen;
    },
    isActive(path) {
      return this.$route.path === path;
    },
    async logout() {
      this.authStore.logout();
      this.$q.notify({
        type: 'positive',
        message: 'Logout Successfull!',
      });
      this.$router.push('/login');
    }

  },
  mounted() {
    this.leftDrawerOpen = false;
  },
  computed: {
  }
}
</script>
