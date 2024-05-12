<template>
  <q-page :class="$q.screen.gt.sm ? 'q-pa-lg' : 'q-pa-md'">
    <div class="flex flex-center">
      <q-card style="width: 100%;">
        <q-card-section>
          <h5 class="text-center q-ma-none q-my-md text-h5">Apply for Job</h5>
          <q-form @submit.prevent="applyForJob">
            <div class="row">
              <div class="col-12 col-md-6 q-pa-sm">
                <q-input dense outlined v-model="name" readonly label="Name"
                  :rules="[val => !!val || 'Name is required']" />
              </div>
              <div class="col-12 col-md-6 q-pa-sm">
                <q-input dense outlined v-model="email" readonly label="Email"
                  :rules="[val => !!val || 'Email is required', val => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val) || 'Invalid email address']" />
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-md-6 q-pa-sm">
                <q-input dense outlined v-model="contactNumber" label="Contact Number"
                  :rules="[val => !!val || 'Contact number is required', val => /^[0-9]{10}$/.test(val) || 'Please enter a 10-digit phone number']" />
              </div>
              <div class="col-12 col-md-6 q-pa-sm">
                <q-input dense outlined v-model="currentCompany" label="Current company name"
                  :rules="[val => !!val || 'Current company is required']" />
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-md-6 q-pa-sm">
                <q-input dense outlined v-model="currentCtc" type="number" label="Current CTC in rupees"
                  :rules="[val => !!val || 'Current CTC is required']" />
              </div>
              <div class="col-12 col-md-6 q-pa-sm">
                <q-input dense outlined v-model="totalExperience" type="number" label="Total experience in years"
                  :rules="[val => !!val || 'Total experience is required']" />
              </div>
            </div>
            <div class="row">
              <div class="col q-pa-sm">
                <q-btn type="submit" :label="hasApplied ? 'Already Applied' : 'Apply Now'" color="dark"
                  :disable="hasApplied" />
              </div>
            </div>
          </q-form>
        </q-card-section>
      </q-card>
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
      name: '',
      email: '',
      contactNumber: '',
      currentCompany: '',
      currentCtc: null,
      totalExperience: null,
      authStore,
      hasApplied: true,
      apiUrl: authStore.apiUrl,
    };
  },
  computed: {},
  methods: {
    applyForJob() {
      const jobId = this.$route.params.id;
      const authToken = useAuthStore().token;

      const applicationData = {
        name: this.name,
        email: this.email,
        contact_number: this.contactNumber,
        current_company_name: this.currentCompany,
        current_ctc: this.currentCtc,
        total_years_of_exp: this.totalExperience,
        job_listing_id: jobId,
      };

      axios.post(this.apiUrl + '/job-applications', applicationData, {
        headers: {
          Authorization: `Bearer ${authToken}`,
        },
      })
        .then(response => {
          this.$q.notify({
            type: 'positive',
            message: 'Job application submitted successfully!',
          });
          this.$router.push('/');
        })
        .catch(error => {
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
            const errorMessage = error.response.data.message || 'Error applying for job. Please try again later.';
            this.$q.notify({
              type: 'negative',
              message: errorMessage,
            });
          }
        });
    },
    checkIfApplied() {
      const jobId = this.$route.params.id;
      const authToken = this.authStore.token;

      axios.get(this.apiUrl + `/job-applications/check/${jobId}`, {
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
    this.name = this.authStore.user.name;
    this.email = this.authStore.user.email;
    this.checkIfApplied();
  }
};
</script>

<style scoped></style>
