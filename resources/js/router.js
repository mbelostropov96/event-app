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

export default router;
