import { createRouter, createWebHistory } from 'vue-router';
import HomeComponent from './components/HomeComponent.vue';
import EventListComponent from './components/events/EventListComponent.vue';
import EventDetailsComponent from './components/events/EventDetailsComponent.vue';
import MyRegistrationsComponent from './components/user/MyRegistrationsComponent.vue';
import CalendarComponent from './components/calendar/CalendarComponent.vue';
import EventTypesComponent from './components/events/EventTypesComponent.vue';
import ReviewsComponent from './components/reviews/ReviewsComponent.vue';
import ContactsComponent from './components/ContactsComponent.vue';
import AboutComponent from './components/AboutComponent.vue';
import NotFoundComponent from './components/NotFoundComponent.vue';
import LoginComponent from './components/auth/LoginComponent.vue';
import RegisterComponent from './components/auth/RegisterComponent.vue';
import AdminDashboard from './components/admin/AdminDashboard.vue';
import AdminEvents from './components/admin/AdminEvents.vue';
import AdminReviews from './components/admin/AdminReviews.vue';
import AdminUsers from './components/admin/AdminUsers.vue';

const routes = [
    {
        path: '/',
        name: 'home',
        component: HomeComponent
    },
    {
        path: '/events',
        name: 'events',
        component: EventListComponent
    },
    {
        path: '/events/:id',
        name: 'event-details',
        component: EventDetailsComponent,
        props: true
    },
    {
        path: '/my-registrations',
        name: 'my-registrations',
        component: MyRegistrationsComponent,
        meta: { requiresAuth: true }
    },
    {
        path: '/calendar',
        name: 'calendar',
        component: CalendarComponent
    },
    {
        path: '/event-types',
        name: 'event-types',
        component: EventTypesComponent
    },
    {
        path: '/reviews',
        name: 'reviews',
        component: ReviewsComponent
    },
    {
        path: '/contacts',
        name: 'contacts',
        component: ContactsComponent
    },
    {
        path: '/about',
        name: 'about',
        component: AboutComponent
    },
    {
        path: '/login',
        name: 'login',
        component: LoginComponent,
        meta: { guest: true }
    },
    {
        path: '/register',
        name: 'register',
        component: RegisterComponent,
        meta: { guest: true }
    },
    {
        path: '/admin',
        component: AdminDashboard,
        meta: { requiresAuth: true, requiresAdmin: true },
        children: [
            {
                path: '',
                name: 'admin-dashboard',
                component: () => import('./components/admin/AdminDashboardHome.vue'),
                meta: { requiresAuth: true, requiresAdmin: true }
            },
            {
                path: 'events',
                name: 'admin-events',
                component: AdminEvents,
                meta: { requiresAuth: true, requiresAdmin: true }
            },
            {
                path: 'reviews',
                name: 'admin-reviews',
                component: AdminReviews,
                meta: { requiresAuth: true, requiresAdmin: true }
            },
            {
                path: 'users',
                name: 'admin-users',
                component: AdminUsers,
                meta: { requiresAuth: true, requiresAdmin: true }
            }
        ]
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: NotFoundComponent
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { top: 0 };
        }
    }
});

// Navigation guards
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    const isAuthenticated = !!token;
    
    // Check if the route requires authentication
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!isAuthenticated) {
            next({ name: 'login', query: { redirect: to.fullPath } });
            return;
        }
        
        // Check if the route requires admin role
        if (to.matched.some(record => record.meta.requiresAdmin)) {
            // We'll check the admin status in the component itself
            // since we need to make an API call to get the user role
        }
    }
    
    // If the route is for guests only (login, register) and user is authenticated
    if (to.matched.some(record => record.meta.guest) && isAuthenticated) {
        next({ name: 'home' });
        return;
    }
    
    next();
});

export default router;
