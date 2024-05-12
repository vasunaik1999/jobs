<template>
  <q-page :class="$q.screen.gt.sm ? 'q-pa-lg' : 'q-pa-md'">
    <h5 class="text-h5 q-my-md text-center">Profile</h5>
    <div class="row">
      <div class="col-12 col-md-6 q-pa-sm">
        <q-card class="q-mb-md shadow-0" bordered>
          <q-card-section>
            <p><strong>Name:</strong> {{ user.name }}</p>
            <p><strong>Email:</strong> {{ user.email }}</p>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
import { useAuthStore } from 'src/stores/auth';

export default {
  data() {
    return {
      user: null
    };
  },
  created() {
    this.user = useAuthStore().user;
  }
};
</script>

<style scoped>
.profile {
  max-width: 600px;
  margin: auto;
}

.profile-info {
  margin-top: 20px;
}
</style>
