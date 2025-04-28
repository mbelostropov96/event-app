<template>
  <div class="mt-8">
    <v-card class="mb-6">
      <v-card-title class="text-h5">
        <v-icon icon="mdi-information" class="mr-2"></v-icon>
        Информационная панель
      </v-card-title>
      <v-card-text>
        <v-row>
          <v-col cols="12" md="4">
            <v-card variant="outlined" class="text-center pa-4">
              <div class="text-h3 mb-2">
                <v-progress-circular v-if="loading" indeterminate color="primary" class="mb-1"></v-progress-circular>
                <span v-else>{{ stats.events }}</span>
              </div>
              <div class="text-subtitle-1">Мероприятий</div>
            </v-card>
          </v-col>
          <v-col cols="12" md="4">
            <v-card variant="outlined" class="text-center pa-4">
              <div class="text-h3 mb-2">
                <v-progress-circular v-if="loading" indeterminate color="primary" class="mb-1"></v-progress-circular>
                <span v-else>{{ stats.users }}</span>
              </div>
              <div class="text-subtitle-1">Пользователей</div>
            </v-card>
          </v-col>
          <v-col cols="12" md="4">
            <v-card variant="outlined" class="text-center pa-4">
              <div class="text-h3 mb-2">
                <v-progress-circular v-if="loading" indeterminate color="primary" class="mb-1"></v-progress-circular>
                <span v-else>{{ stats.reviews }}</span>
              </div>
              <div class="text-subtitle-1">Отзывов</div>
            </v-card>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>

    <v-card v-if="pendingReviews.length > 0 || loading" class="mb-6">
      <v-card-title class="text-h5">
        <v-icon icon="mdi-star-outline" class="mr-2"></v-icon>
        Отзывы, ожидающие модерации
      </v-card-title>
      <v-card-text>
        <div v-if="loading" class="text-center py-4">
          <v-progress-circular indeterminate color="primary" class="mb-2"></v-progress-circular>
          <p class="text-body-1">Загрузка отзывов...</p>
        </div>
        <v-list v-else>
          <v-list-item
            v-for="review in pendingReviews"
            :key="review.id"
            :title="`${review.user.first_name} ${review.user.last_name}`"
            :subtitle="review.event.title"
          >
            <template v-slot:prepend>
              <v-avatar color="primary">
                <v-icon color="white">mdi-account</v-icon>
              </v-avatar>
            </template>
            <template v-slot:append>
              <v-btn
                color="primary"
                variant="text"
                :to="{ name: 'admin-reviews' }"
              >
                Модерировать
              </v-btn>
            </template>
          </v-list-item>
        </v-list>
      </v-card-text>
    </v-card>

    <v-card>
      <v-card-title class="text-h5">
        <v-icon icon="mdi-calendar-check" class="mr-2"></v-icon>
        Ближайшие мероприятия
      </v-card-title>
      <v-card-text>
        <v-list v-if="upcomingEvents.length > 0">
          <v-list-item
            v-for="event in upcomingEvents"
            :key="event.id"
            :title="event.title"
            :subtitle="formatDate(event.date)"
          >
            <template v-slot:prepend>
              <v-avatar color="primary" variant="tonal">
                <v-icon>mdi-calendar</v-icon>
              </v-avatar>
            </template>
            <template v-slot:append>
              <v-btn
                color="primary"
                variant="text"
                :to="{ name: 'event-details', params: { id: event.id } }"
              >
                Просмотреть
              </v-btn>
            </template>
          </v-list-item>
        </v-list>
        <div v-else-if="loading" class="text-center py-4">
          <v-progress-circular indeterminate color="primary" class="mb-2"></v-progress-circular>
          <p class="text-body-1">Загрузка мероприятий...</p>
        </div>
        <div v-else class="text-center py-4">
          <p class="text-body-1">Нет предстоящих мероприятий</p>
        </div>
      </v-card-text>
    </v-card>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const loading = ref(true);
const stats = ref({
  events: 0,
  users: 0,
  reviews: 0
});

const pendingReviews = ref([]);
const upcomingEvents = ref([]);

const fetchDashboardData = async () => {
  loading.value = true;
  try {
    const [statsResponse, reviewsResponse, eventsResponse] = await Promise.all([
      axios.get('/api/admin/stats'),
      axios.get('/api/admin/reviews?status=pending'),
      axios.get('/api/admin/events?upcoming=true&limit=5')
    ]);
    
    stats.value = statsResponse.data;
    pendingReviews.value = reviewsResponse.data.filter(review => review.status === 'pending').slice(0, 5);
    upcomingEvents.value = eventsResponse.data.data ? eventsResponse.data.data.slice(0, 5) : [];
  } catch (error) {
    console.error('Error fetching dashboard data:', error);
    // Use placeholder data if API fails
    stats.value = {
      events: '...',
      users: '...',
      reviews: '...'
    };
  } finally {
    loading.value = false;
  }
};

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('ru-RU', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
};

onMounted(() => {
  fetchDashboardData();
});
</script>
