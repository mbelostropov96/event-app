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

      <!-- Loading State -->
      <v-col v-if="loading" cols="12">
        <v-sheet class="pa-4 rounded-lg">
          <v-row>
            <v-col v-for="n in 3" :key="n" cols="12" md="4">
              <v-card variant="outlined" class="h-100">
                <v-skeleton-loader
                  type="image"
                  height="180"
                ></v-skeleton-loader>
                <v-card-item>
                  <v-skeleton-loader
                    type="heading"
                    class="mb-2"
                  ></v-skeleton-loader>
                  <v-skeleton-loader
                    type="text@2"
                  ></v-skeleton-loader>
                </v-card-item>
              </v-card>
            </v-col>
          </v-row>
        </v-sheet>
      </v-col>

      <!-- Events Carousel -->
      <v-col v-else-if="upcomingEvents.length" cols="12">
        <v-sheet class="rounded-lg overflow-hidden">
          <v-slide-group
            show-arrows
          >
            <v-slide-group-item
              v-for="event in upcomingEvents"
              :key="event.id"
            >
              <v-card
                :class="['ma-2', 'upcoming-event-card']"
                width="300"
                height="350"
                :to="{ name: 'event-details', params: { id: event.id }}"
              >
                <v-img
                  :src="getEventImage(event)"
                  height="180"
                  cover
                  class="bg-grey-lighten-2"
                >
                  <!-- Тип события -->
                  <div class="d-flex justify-end pa-2">
                    <v-chip
                      :color="getEventTypeColor(event.type)"
                      size="small"
                      label
                      class="text-caption event-type-chip"
                      variant="elevated"
                      elevation="3"
                    >
                      <v-icon start :icon="t(`categories.${event.type}Icon`)" size="small"></v-icon>
                      {{ t(`categories.${event.type}`) }}
                    </v-chip>
                  </div>
                  
                  <!-- Цена -->
                  <div class="d-flex justify-start align-end fill-height">
                    <v-chip
                      color="primary"
                      size="small"
                      class="ma-2 font-weight-bold price-chip"
                      variant="elevated"
                      elevation="3"
                    >
                      {{ event.price ? `${event.price} ₽` : t('events.free') }}
                    </v-chip>
                  </div>
                </v-img>

                <v-card-item>
                  <v-card-title class="text-truncate text-subtitle-1 font-weight-bold">
                    {{ event.title }}
                  </v-card-title>
                  
                  <v-card-subtitle class="pa-0 pt-2 pb-1">
                    <!-- Дата и время -->
                    <div class="d-flex align-center mb-1">
                      <v-icon size="small" icon="mdi-calendar" class="mr-1"></v-icon>
                      <span class="text-body-2">{{ formatDate(event.start_date) }}</span>
                      <v-divider vertical class="mx-2"></v-divider>
                      <v-icon size="small" icon="mdi-clock" class="mr-1"></v-icon>
                      <span class="text-body-2">{{ formatTime(event.start_time) }}</span>
                    </div>
                    
                    <!-- Местоположение -->
                    <div class="d-flex align-center">
                      <v-icon size="small" icon="mdi-map-marker" class="mr-1"></v-icon>
                      <span class="text-body-2 text-truncate">{{ formatLocation(event.location) }}</span>
                    </div>
                  </v-card-subtitle>
                </v-card-item>

                <v-card-text class="pt-0">
                  <p class="text-caption text-grey text-truncate-2">{{ event.description }}</p>
                </v-card-text>
              </v-card>
            </v-slide-group-item>
          </v-slide-group>
        </v-sheet>
      </v-col>

      <!-- No Events -->
      <v-col v-else cols="12">
        <v-card class="pa-4">
          <div class="text-center py-4">
            {{ t('home.upcomingEvents.noEvents') }}
          </div>
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
import { getEventTypeColor, getEventImage, formatDate, formatTime } from './events/eventData';

const { t } = useI18n();

const loading = ref(true);
const upcomingEvents = ref([]);

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

const loadUpcomingEvents = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/events', {
      params: {
        upcoming: true,
        limit: 7
      }
    });
    upcomingEvents.value = response.data;
  } catch (error) {
    console.error('Error loading upcoming events:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadUpcomingEvents();
});

const formatLocation = (location) => {
  if (!location) return t('events.locationNotSpecified');
  
  // Извлекаем название места в скобках
  const match = location.match(/\((.*?)\)/);
  if (match) {
    return match[1];
  }
  // Если скобок нет, возвращаем первые 20 символов
  return location.length > 20 ? location.substring(0, 20) + '...' : location;
};
</script>

<style scoped>
.h-100 {
  height: 100%;
}

.w-100 {
  width: 100%;
}

.text-truncate-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

.upcoming-event-card {
  transition: all 0.3s ease;
}

.upcoming-event-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.event-type-chip {
  font-weight: bold;
  opacity: 0.95;
  text-shadow: 0px 0px 1px rgba(0, 0, 0, 0.3);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.price-chip {
  opacity: 0.95;
  text-shadow: 0px 0px 1px rgba(0, 0, 0, 0.3);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}
</style>
