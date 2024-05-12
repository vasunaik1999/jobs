<template>
  <q-page :class="$q.screen.gt.sm ? 'q-pa-lg' : 'q-pa-md'">
    <div class="q-gutter-md">
      <!-- Filter -->
      <div class="">
        <q-list bordered class="rounded-borders">
          <q-expansion-item expand-separator icon="filter_list" label="Filter Jobs"
            caption="Based on Location, Category & Keywords">
            <q-card>
              <q-card-section>
                <div class="row">
                  <div class="col-md-4 col-12 q-pa-sm">
                    <q-select outlined dense v-model="selectedCategory" :options="categories" label="Filter by Category"
                      emit-value map-options use-input clearable />
                  </div>
                  <div class="col-md-4 col-12 q-pa-sm">
                    <q-input outlined dense v-model="locationFilter" label="Filter by Location" />
                  </div>
                  <div class="col-md-4 col-12 q-pa-sm">
                    <q-input outlined dense v-model="keywordFilter" label="Filter by Keyword" />
                  </div>
                </div>
              </q-card-section>
            </q-card>
          </q-expansion-item>
        </q-list>
      </div>

      <!-- Display Jobs -->
      <div class="" v-if="paginatedJobs.length > 0">
        <q-card v-for="job in paginatedJobs" :key="job.id" class="q-mb-md shadow-0" bordered>
          <q-card-section>
            <q-item>
              <q-item-section>
                <q-item-label class="text-h6">{{ job.title }}</q-item-label>
                <q-item-label class="text-subtitle2">{{ job.company_name }}</q-item-label>
                <q-item-label>
                  <q-badge class="q-px-sm q-py-xs" color="grey">
                    {{ job.location }}
                  </q-badge>
                </q-item-label>
                <q-item-label class="text-caption text-grey">Posted {{ formatTimeAgo(job.created_at)
                  }}</q-item-label>
              </q-item-section>
              <q-item-section side v-if="$q.screen.gt.sm">
                <q-btn class="shadow-0" label="Details" color="dark" @click="viewDetails(job)" />
              </q-item-section>
            </q-item>
            <q-item v-if="!$q.screen.gt.sm">
              <q-item-section>
                <q-btn dense class="shadow-0" label="Details" color="dark" @click="viewDetails(job)" />
              </q-item-section>
            </q-item>
          </q-card-section>
        </q-card>
      </div>
      <div v-else>
        <h6 class="text-center text-h6 text-grey">No Data Found!</h6>
      </div>

      <!-- pagination -->
      <div class="flex flex-center">
        <q-pagination v-model="currentPage" :max="totalPages" direction-links flat color="grey" active-color="dark" />
      </div>

      <q-dialog v-model="showJobDetailsDialog" persistent backdrop-filter="blur(4px)">
        <q-card :style="$q.screen.gt.sm ? 'min-width: 35vw;' : 'min-width: 90vw;'">
          <q-card-section class="row items-center q-pb-none">
            <div class="text-h6">{{ selectedJob.title }}</div>
            <q-space />
            <q-btn icon="close" flat round dense @click="showJobDetailsDialog = false" />
          </q-card-section>
          <q-card-section>
            <p>{{ selectedJob.description }}</p>
            <p class="q-mt-xs">Company: {{ selectedJob.company_name }}</p>
            <p class="q-mt-xs">Category: {{ selectedJob.category }}</p>
            <p class="q-mt-xs">Location: {{ selectedJob.location }}</p>
            <p class="q-mt-xs text-caption text-grey">Posted {{ formatTimeAgo(selectedJob.created_at) }}</p>
          </q-card-section>
          <q-card-actions align="right">
            <q-btn v-if="hasApplied" disabled label="Already Applied" color="dark"
              @click="applyForJob(selectedJob.id)" />
            <q-btn v-else label="Apply Now" color="dark" @click="applyForJob(selectedJob.id)" />
          </q-card-actions>
        </q-card>
      </q-dialog>

      <!-- Force Login Popup -->
      <q-dialog v-model="loginPopup" persistent>
        <q-card>
          <q-card-section class="row items-center">
            <q-avatar icon="lock" color="dark" text-color="white" />
            <span class="q-ml-sm">Please Login to view the details!</span>
          </q-card-section>

          <q-card-actions align="right">
            <q-btn flat label="Cancel" color="dark" v-close-popup />
            <q-btn flat label="Login" color="dark" @click="redirectToLogin()" />
          </q-card-actions>
        </q-card>
      </q-dialog>
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
      jobs: [],
      categories: [],
      selectedCategory: '',
      locationFilter: '',
      keywordFilter: '',
      perPage: 10,
      currentPage: 1,
      selectedJob: null,
      showJobDetailsDialog: false,
      loginPopup: false,
      hasApplied: false,
      authStore,
      apiUrl: authStore.apiUrl,
    };
  },
  computed: {
    paginatedJobs() {
      const startIndex = (this.currentPage - 1) * this.perPage;
      const endIndex = startIndex + this.perPage;
      return this.filteredJobs.slice(startIndex, endIndex);
    },
    filteredJobs() {
      let filtered = this.jobs;
      if (this.selectedCategory) {
        filtered = filtered.filter(job => job.category === this.selectedCategory);
      }
      if (this.locationFilter) {
        filtered = filtered.filter(job => job.location.toLowerCase().includes(this.locationFilter.toLowerCase()));
      }
      if (this.keywordFilter) {
        filtered = filtered.filter(job => job.keywords.includes(this.keywordFilter.toLowerCase()));
      }
      return filtered;
    },
    totalPages() {
      return Math.ceil(this.filteredJobs.length / this.perPage);
    }
  },
  methods: {
    async fetchJobListings() {
      try {
        const response = await fetch(this.apiUrl + '/job-listings');
        if (!response.ok) {
          throw new Error('Failed to fetch job listings');
        }
        const data = await response.json();
        this.jobs = data.jobs;
        this.categories = data.categories;
      } catch (error) {
        console.error(error.message);
      }
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
    async viewDetails(job) {
      this.selectedJob = job;
      if (useAuthStore().isAuthenticated) {
        await this.checkIfApplied(this.selectedJob.id);
        this.showJobDetailsDialog = true;
      } else {
        this.loginPopup = true;
      }
    },
    redirectToLogin() {
      this.$router.push('/login');
    },
    applyForJob(jobId) {
      this.$router.push({ name: 'job-apply', params: { id: jobId } });
    },
    async checkIfApplied(jobId) {
      const authToken = this.authStore.token;

      await axios.get(this.apiUrl + `/job-applications/check/${jobId}`, {
        headers: {
          Authorization: `Bearer ${authToken}`,
        },
      })
        .then(response => {
          this.hasApplied = response.data.already_applied;
        })
        .catch(error => {
          console.error('Error checking if applied:', error);
        });
    }
  },
  mounted() {
    this.fetchJobListings();
  }
};
</script>

<style scoped></style>
