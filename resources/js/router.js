import { createRouter, createWebHistory } from 'vue-router';
import HomeComponent from './components/HomeComponent.vue';
import EventListComponent from './components/events/EventListComponent.vue';
import EventDetailComponent from './components/events/EventDetailComponent.vue';
import MyRegistrationsComponent from './components/user/MyRegistrationsComponent.vue';

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
        name: 'event-detail',
        component: EventDetailComponent,
        props: true
    },
    {
        path: '/my-registrations',
        name: 'my-registrations',
        component: MyRegistrationsComponent,
        meta: { requiresAuth: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        }
        return { top: 0 };
    }
});

export default router;
