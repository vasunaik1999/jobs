<template>
  <q-page :class="$q.screen.gt.sm ? 'q-pa-lg' : 'q-pa-md'">
    <h5 class="text-h5 q-my-md text-center">My Applications</h5>
    <div class="row" v-if="myApplications.length > 0">
      <div class="col-12 col-md-6 q-pa-sm" v-for="(application, index) in myApplications" :key="index">
        <q-card class="q-mb-md shadow-0" bordered>
          <q-card-section>
            <q-item>
              <q-item-section>
                <q-item-label class="text-h6">{{ application.job_listing.title }}</q-item-label>
                <q-item-label class="text-body1">Company: {{ application.job_listing.company_name }}</q-item-label>
                <q-item-label class="text-body1">Location:{{ application.job_listing.location }}</q-item-label>
                <q-item-label class="text-body1">
                  Applied: {{ formatTimeAgo(application.job_listing.created_at) }}
                </q-item-label>
              </q-item-section>
            </q-item>
          </q-card-section>
        </q-card>
      </div>
    </div>
    <div v-else>
      <h6 class="text-center text-h6 text-grey">No Data Found!</h6>
    </div>
  </q-page>

</template>

<script>
import axios from 'axios';
import { useAuthStore } from 'src/stores/auth';

const authStore = useAuthStore();

export default {
  data() {
    return {
      myApplications: [],
      authStore,
      apiUrl: authStore.apiUrl
    };
  },
  mounted() {
    this.fetchApplications();
  },
  methods: {
    async fetchApplications() {
      const authToken = this.authStore.token;
      axios.get(this.apiUrl + '/my-applications', {
        headers: {
          Authorization: `Bearer ${authToken}`,
        },
      })
        .then(response => {
          this.myApplications = response.data.myApplications;
        })
        .catch(error => {
          this.$q.notify({
            type: 'negative',
            message: error,
          });

        });
    },
    formatTimeAgo(dateString) {
      const date = new Date(dateString);
      const now = new Date();
      const diff = now - date;

      const seconds = Math.floor(diff / 1000);
      const minutes = Math.floor(seconds / 60);
      const hours = Math.floor(minutes / 60);
      const days = Math.floor(hours / 24);

      if (days > 0) {
        return `${days} days ago`;
      } else if (hours > 0) {
        return `${hours} hours ago`;
      } else if (minutes > 0) {
        return `${minutes} minutes ago`;
      } else {
        return 'just now';
      }
    },
  }
};
</script>
