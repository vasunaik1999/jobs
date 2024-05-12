const routes = [
  // auth
  {
    path: '/login',
    name: 'login',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/LoginPage.vue') }
    ]
  },
  {
    path: '/register',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/RegisterPage.vue') }
    ]
  },
  //Job Listing
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/JobListing.vue') }
    ]
  },
  // apply for job
  {
    path: '/job-apply/:id',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/JobApply.vue'), name: 'job-apply' }
    ],
    meta: {
      requiresAuth: true,
    }
  },
  //My applications
  {
    path: '/my-applications',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/MyApplications.vue') }
    ],
    meta: {
      requiresAuth: true,
    }
  },
   //post job
   {
    path: '/post-job',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/JobPosting.vue') }
    ],
    meta: {
      requiresAuth: true,
    }
  },
  //My jobs
  {
    path: '/my-jobs',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/MyJobs.vue') }
    ],
    meta: {
      requiresAuth: true,
    }
  },
  //profile
  {
    path: '/profile',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/ProfilePage.vue') }
    ],
    meta: {
      requiresAuth: true,
    }
  },
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
