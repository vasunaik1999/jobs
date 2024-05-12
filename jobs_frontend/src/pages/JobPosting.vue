<template>
  <q-page :class="$q.screen.gt.sm ? 'q-pa-lg' : 'q-pa-md'">
    <div class="flex flex-center">
      <q-card style="width: 100%;">
        <q-card-section>
          <h5 class="text-center q-ma-none q-my-md text-h5">Create Job Listing</h5>
          <q-form @submit.prevent="createJobListing">
            <div class="row">
              <div class="col-12 col-md-6 q-pa-sm">
                <q-input dense outlined v-model="category" label="Category"
                  :rules="[val => !!val || 'Category is required']" />
              </div>
              <div class="col-12 col-md-6 q-pa-sm">
                <q-input dense outlined v-model="title" label="Title" :rules="[val => !!val || 'Title is required']" />
              </div>
              <div class="col-12 col-md-6 q-pa-sm">
                <q-input dense outlined v-model="companyName" label="Company Name"
                  :rules="[val => !!val || 'Company name is required']" />
              </div>
              <div class="col-12 col-md-6 q-pa-sm">
                <q-input dense outlined v-model="location" label="Location"
                  :rules="[val => !!val || 'Location is required']" />
              </div>
              <div class="col-12 col-md-6 q-pa-sm">
                <q-input dense outlined v-model="description" label="Description"
                  :rules="[val => !!val || 'Description is required']" />
              </div>
              <div class="col-12 col-md-6 q-pa-sm">
                <q-input dense outlined v-model="keywords" label="Keywords" hint="Enter keywords separated by commas" />
              </div>
            </div>
            <div class="row">
              <div class="col q-pa-sm">
                <q-btn type="submit" label="Create Listing" color="dark" />
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
      category: '',
      title: '',
      companyName: '',
      location: '',
      description: '',
      keywords: '',
      isPublished: false,
      authStore,
      apiUrl: authStore.apiUrl,
    };
  },
  methods: {
    createJobListing() {

      const authToken = useAuthStore().token;

      const jobListingData = {
        category: this.category,
        title: this.title,
        company_name: this.companyName,
        location: this.location,
        description: this.description,
        keywords: this.keywords.split(',').map(keyword => keyword.trim()),
        isPublished: this.isPublished,
      };

      axios.post(this.apiUrl + '/job-listings', jobListingData, {
        headers: {
          Authorization: `Bearer ${authToken}`,
        },
      })
        .then(response => {
          this.$q.notify({
            type: 'positive',
            message: 'Job listing created successfully!',
          });
          this.clearFields();
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
            const errorMessage = error.response.data.message || 'Error creating job listing. Please try again later.';
            this.$q.notify({
              type: 'negative',
              message: errorMessage,
            });
          }
        });
    },
    clearFields() {
      this.category = '';
      this.title = '';
      this.companyName = '';
      this.location = '';
      this.description = '';
      this.keywords = '';
    }
  },
};
</script>

<style scoped></style>
