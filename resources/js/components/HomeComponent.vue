<template>
  <v-container>
    <!-- Welcome Section -->
    <v-row class="mb-8">
      <v-col cols="12">
        <v-card class="mx-auto">
          <v-img
            src="https://images.unsplash.com/photo-1508997449629-303059a039c0?auto=format&fit=crop&w=1200&q=80"
            height="400"
            cover
            class="bg-grey-darken-4"
          >
            <div class="d-flex flex-column fill-height justify-center align-center text-white" style="background: rgba(0, 0, 0, 0.4)">
              <div class="text-h3 font-weight-bold text-center mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5)">
                {{ t('home.welcome.title') }}
              </div>
              <div class="text-h6 text-center mx-4" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5); max-width: 800px">
                {{ t('home.welcome.description') }}
              </div>
              <v-btn
                color="primary"
                size="x-large"
                variant="elevated"
                :to="{ name: 'events' }"
                class="mt-8 px-8"
              >
                {{ t('home.welcome.action') }}
                <v-icon end icon="mdi-arrow-right" class="ml-2"></v-icon>
              </v-btn>
            </div>
          </v-img>
        </v-card>
      </v-col>
    </v-row>

    <!-- Upcoming Events Section -->
    <v-row class="mb-8">
      <v-col cols="12">
        <h2 class="text-h4 mb-4">{{ t('home.upcomingEvents.title') }}</h2>
      </v-col>

      <v-col cols="12">
        <v-card class="pa-4">
          <v-list lines="two">
            <template v-if="upcomingEvents.length">
              <v-list-item
                v-for="event in upcomingEvents"
                :key="event.id"
                :to="{ name: 'event-details', params: { id: event.id }}"
                :subtitle="event.description"
                class="mb-2"
                rounded="lg"
              >
                <template v-slot:prepend>
                  <v-avatar
                    :color="getEventTypeColor(event.type)"
                    :image="getEventImage(event)"
                    class="mr-3"
                  >
                    <v-icon
                      v-if="!event.image"
                      :icon="t(`categories.${event.type}Icon`)"
                      color="white"
                    ></v-icon>
                  </v-avatar>
                </template>

                <v-list-item-title class="mb-1">
                  {{ event.title }}
                </v-list-item-title>

                <v-list-item-subtitle>
                  <div class="d-flex flex-wrap gap-3">
                    <div class="d-flex align-center">
                      <v-icon size="small" icon="mdi-map-marker" class="mr-1"></v-icon>
                      {{ event.location }}
                    </div>
                    <div class="d-flex align-center">
                      <v-icon size="small" icon="mdi-calendar" class="mr-1"></v-icon>
                      {{ formatDate(event.start_date) }}
                      <v-icon size="small" icon="mdi-clock" class="mx-1"></v-icon>
                      {{ formatTime(event.start_time) }}
                    </div>
                  </div>
                </v-list-item-subtitle>
              </v-list-item>
            </template>
            <template v-else>
              <v-list-item>
                <div class="text-center py-4">
                  {{ t('home.upcomingEvents.noEvents') }}
                </div>
              </v-list-item>
            </template>
          </v-list>
        </v-card>
      </v-col>
    </v-row>

    <!-- Categories Section -->
    <v-row class="mb-8">
      <v-col cols="12">
        <h2 class="text-h4 mb-4">{{ t('home.categories.title') }}</h2>
      </v-col>

      <v-col
        v-for="type in ['cultural', 'sports', 'educational', 'entertainment']"
        :key="type"
        cols="12"
        sm="6"
        md="3"
      >
        <v-card
          :to="{ name: 'events', query: { type } }"
          class="h-100"
          variant="outlined"
        >
          <v-card-item>
            <template v-slot:prepend>
              <v-icon
                :icon="t(`categories.${type}Icon`)"
                :color="getEventTypeColor(type)"
                size="x-large"
              ></v-icon>
            </template>
            <v-card-title>{{ t(`categories.${type}`) }}</v-card-title>
            <v-card-subtitle>{{ t(`categories.${type}Desc`) }}</v-card-subtitle>
          </v-card-item>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const { t } = useI18n();

// Тематические картинки для разных типов мероприятий
const eventTypeImages = {
  cultural: [
    'https://images.unsplash.com/photo-1513364776144-60967b0f800f?auto=format&fit=crop&w=800&q=80', // Театр
    'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?auto=format&fit=crop&w=800&q=80', // Музей
    'https://images.unsplash.com/photo-1577083552431-c0d45a5cb73b?auto=format&fit=crop&w=800&q=80', // Выставка
    'https://images.unsplash.com/photo-1509170051686-83f8efd35a31?auto=format&fit=crop&w=800&q=80', // Опера
    'https://images.unsplash.com/photo-1545987796-200677ee1011?auto=format&fit=crop&w=800&q=80', // Балет
  ],
  sports: [
    'https://images.unsplash.com/photo-1522778119026-d647f0596c20?auto=format&fit=crop&w=800&q=80', // Футбольное поле
    'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?auto=format&fit=crop&w=800&q=80', // Футбольный матч
    'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?auto=format&fit=crop&w=800&q=80', // Теннис
    'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=800&q=80', // Футбольный стадион
    'https://images.unsplash.com/photo-1577223625816-6599b462fce1?auto=format&fit=crop&w=800&q=80', // Бег
  ],
  educational: [
    'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=800&q=80', // Библиотека
    'https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=800&q=80', // Лекция
    'https://images.unsplash.com/photo-1544531586-fde5298cdd40?auto=format&fit=crop&w=800&q=80', // Мастер-класс
    'https://images.unsplash.com/photo-1488190211105-8b0e65b80b4e?auto=format&fit=crop&w=800&q=80', // Учебный класс
    'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=800&q=80', // Конференция
  ],
  entertainment: [
    'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=800&q=80', // Концерт
    'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=800&q=80', // Фестиваль
    'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=80', // Вечеринка
    'https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?auto=format&fit=crop&w=800&q=80', // DJ
    'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=800&q=80', // Караоке
  ],
  other: [
    'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80', // Общее
    'https://images.unsplash.com/photo-1517457373958-b7f3bade9f55?auto=format&fit=crop&w=800&q=80', // Встреча
    'https://images.unsplash.com/photo-1527192491265-7e15c55b1ed2?auto=format&fit=crop&w=800&q=80', // Networking
    'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=800&q=80', // Воркшоп
    'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&w=800&q=80', // Пикник
  ],
};

// Получаем случайную картинку для типа мероприятия
const getEventImage = (event) => {
  if (event.image) return event.image;
  
  const images = eventTypeImages[event.type] || eventTypeImages.other;
  // Используем id события для получения псевдослучайного индекса
  // Это обеспечит, что для одного и того же события всегда будет одна и та же картинка
  const index = event.id % images.length;
  return images[index];
};

const upcomingEvents = ref([]);
const loading = ref(false);

const categories = ref([
  {
    id: 'cultural',
    title: t('categories.cultural'),
    description: t('categories.culturalDesc'),
    icon: 'mdi-palette'
  },
  {
    id: 'sports',
    title: t('categories.sports'),
    description: t('categories.sportsDesc'),
    icon: 'mdi-basketball'
  },
  {
    id: 'educational',
    title: t('categories.educational'),
    description: t('categories.educationalDesc'),
    icon: 'mdi-school'
  },
  {
    id: 'entertainment',
    title: t('categories.entertainment'),
    description: t('categories.entertainmentDesc'),
    icon: 'mdi-party-popper'
  }
]);

const fetchUpcomingEvents = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/events', {
      params: {
        upcoming: true,
        limit: 5
      }
    });
    upcomingEvents.value = response.data;
  } catch (error) {
    console.error('Error fetching upcoming events:', error);
  } finally {
    loading.value = false;
  }
};

const getEventTypeColor = (type) => {
  const colors = {
    cultural: 'purple',
    sports: 'blue',
    educational: 'green',
    entertainment: 'orange',
    other: 'grey'
  };
  return colors[type] || 'grey';
};

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('ru-RU', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
};

const formatTime = (time) => {
  if (!time) return '';
  const [hours, minutes] = time.split(':');
  return `${hours}:${minutes}`;
};

onMounted(() => {
  fetchUpcomingEvents();
});
</script>

<style scoped>
.h-100 {
  height: 100%;
}

.w-100 {
  width: 100%;
}
</style>
