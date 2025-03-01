<template>
  <div>
    <!-- Filters and Sorting -->
    <v-card class="mb-4">
      <v-card-text>
        <v-row dense>
          <!-- Type and Price Range -->
          <v-col cols="12" sm="8">
            <v-row dense>
              <v-col cols="12" sm="6">
                <v-select
                  v-model="filters.type"
                  :items="eventTypes"
                  :label="t('events.filters.type')"
                  density="compact"
                  hide-details
                  clearable
                  prepend-inner-icon="mdi-shape"
                ></v-select>
              </v-col>
              <v-col cols="12" sm="6">
                <v-select
                  v-model="filters.priceRange"
                  :items="priceRanges"
                  :label="t('events.filters.price')"
                  density="compact"
                  hide-details
                  clearable
                  prepend-inner-icon="mdi-currency-rub"
                ></v-select>
              </v-col>
            </v-row>
          </v-col>

          <!-- Sort -->
          <v-col cols="12" sm="4">
            <v-select
              v-model="sorting.field"
              :items="sortFields"
              :label="t('events.sorting.field')"
              density="compact"
              hide-details
              prepend-inner-icon="mdi-sort"
            ></v-select>
          </v-col>

          <!-- Date Range -->
          <v-col cols="12">
            <v-row dense>
              <v-col cols="12" sm="6">
                <v-text-field
                  v-model="filters.fromDate"
                  type="date"
                  :label="t('events.filters.fromDate')"
                  density="compact"
                  hide-details
                  prepend-inner-icon="mdi-calendar"
                  :hint="t('events.filters.dateHint')"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field
                  v-model="filters.toDate"
                  type="date"
                  :label="t('events.filters.toDate')"
                  density="compact"
                  hide-details
                  prepend-inner-icon="mdi-calendar"
                  :hint="t('events.filters.dateHint')"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-col>

          <!-- Actions -->
          <v-col cols="12" class="d-flex justify-end mt-2">
            <v-btn
              color="primary"
              prepend-icon="mdi-filter"
              @click="loadEvents"
            >
              {{ t('events.filters.apply') }}
            </v-btn>
            <v-btn
              variant="text"
              class="ml-2"
              prepend-icon="mdi-refresh"
              @click="clearFilters"
            >
              {{ t('events.filters.clear') }}
            </v-btn>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>

    <!-- Events List -->
    <v-row>
      <v-col cols="12">
        <v-card class="pa-4">
          <v-row v-if="events.length">
            <v-col
              v-for="event in events"
              :key="event.id"
              cols="12"
              sm="6"
              md="4"
              lg="3"
              class="pa-2"
            >
              <v-card
                :to="{ name: 'event-details', params: { id: event.id }}"
                class="h-100"
              >
                <v-img
                  :src="getEventImage(event)"
                  height="200"
                  cover
                  class="bg-grey-lighten-2"
                >
                  <template v-slot:error>
                    <div class="d-flex align-center justify-center fill-height bg-grey-lighten-2">
                      <v-avatar
                        size="96"
                        :color="getEventTypeColor(event.type)"
                      >
                        <v-icon
                          size="48"
                          :icon="t(`categories.${event.type}Icon`)"
                          color="white"
                        ></v-icon>
                      </v-avatar>
                    </div>
                  </template>
                  <template v-slot:placeholder>
                    <div class="d-flex align-center justify-center fill-height">
                      <v-progress-circular
                        color="grey-lighten-4"
                        indeterminate
                      ></v-progress-circular>
                    </div>
                  </template>
                </v-img>

                <v-card-title class="text-truncate">
                  {{ event.title }}
                </v-card-title>

                <v-card-subtitle>
                  <div class="d-flex align-center mb-1">
                    <v-icon size="small" icon="mdi-map-marker" class="mr-1"></v-icon>
                    {{ event.location }}
                  </div>
                  <div class="d-flex align-center">
                    <v-icon size="small" icon="mdi-calendar" class="mr-1"></v-icon>
                    {{ formatDate(event.start_date) }}
                    <v-icon size="small" icon="mdi-clock" class="mx-1"></v-icon>
                    {{ formatTime(event.start_time) }}
                  </div>
                  <div class="d-flex align-center">
                    <v-icon size="small" icon="mdi-currency-rub" class="mr-1"></v-icon>
                    {{ event.price ? `${event.price} ₽` : t('events.free') }}
                  </div>
                </v-card-subtitle>

                <v-card-text class="text-truncate">
                  {{ event.description }}
                </v-card-text>

                <v-card-actions class="mt-auto">
                  <v-spacer></v-spacer>
                  <v-chip
                    :color="getEventTypeColor(event.type)"
                    size="small"
                    variant="flat"
                    class="text-caption"
                  >
                    <v-icon start :icon="t(`categories.${event.type}Icon`)" size="small"></v-icon>
                    {{ t(`categories.${event.type}`) }}
                  </v-chip>
                </v-card-actions>
              </v-card>
            </v-col>
          </v-row>

          <div v-else class="text-center py-4">
            {{ t('events.noEvents') }}
          </div>

          <!-- Pagination -->
          <div class="d-flex justify-center mt-4">
            <v-pagination
              v-model="currentPage"
              :length="totalPages"
              :total-visible="7"
              rounded="circle"
            ></v-pagination>
          </div>
        </v-card>
      </v-col>
    </v-row>

    <!-- Loading State -->
    <v-row v-if="loading">
      <v-col cols="12" class="text-center">
        <v-progress-circular
          indeterminate
          color="primary"
        ></v-progress-circular>
      </v-col>
    </v-row>

    <!-- No Results -->
    <v-row v-if="!loading && events.length === 0">
      <v-col cols="12">
        <v-alert
          type="info"
          text="Нет событий, соответствующих выбранным фильтрам"
          variant="tonal"
          density="compact"
        ></v-alert>
      </v-col>
    </v-row>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const { t } = useI18n();
const route = useRoute();
const router = useRouter();

const eventTypes = [
  { title: t('categories.cultural'), value: 'cultural' },
  { title: t('categories.sports'), value: 'sports' },
  { title: t('categories.educational'), value: 'educational' },
  { title: t('categories.entertainment'), value: 'entertainment' },
  { title: t('categories.other'), value: 'other' },
];

const priceRanges = [
  { title: t('events.filters.priceRanges.free'), value: 'free' },
  { title: '0 - 1 000 ₽', value: '0-1000' },
  { title: '1 000 - 2 000 ₽', value: '1000-2000' },
  { title: '2 000 - 3 000 ₽', value: '2000-3000' },
  { title: '3 000 - 4 000 ₽', value: '3000-4000' },
  { title: '4 000+ ₽', value: '4000+' },
];

const sortFields = [
  { title: t('events.sorting.fields.date'), value: 'start_date' },
  { title: t('events.sorting.fields.price'), value: 'price' },
];

const filters = ref({
  type: route.query.type || null,
  priceRange: route.query.priceRange || null,
  fromDate: route.query.fromDate || null,
  toDate: route.query.toDate || null,
});

const sorting = ref({
  field: route.query.sortBy || 'start_date',
});

const events = ref([]);
const loading = ref(false);
const totalPages = ref(1);
const currentPage = ref(1);

const clearFilters = () => {
  filters.value = {
    type: null,
    priceRange: null,
    fromDate: null,
    toDate: null,
  };
  sorting.value.field = 'start_date';
  loadEvents();
};

const loadEvents = async () => {
  try {
    loading.value = true;

    // Prepare query parameters
    const params = new URLSearchParams();
    
    if (filters.value.type) {
      params.append('type', filters.value.type);
    }
    
    if (filters.value.priceRange) {
      const range = filters.value.priceRange;
      if (range === 'free') {
        params.append('price_max', '0');
      } else if (range === '4000+') {
        params.append('price_min', '4000');
      } else {
        const [min, max] = range.split('-').map(Number);
        params.append('price_min', min);
        params.append('price_max', max);
      }
    }
    
    if (filters.value.fromDate) {
      params.append('from_date', filters.value.fromDate);
    }
    
    if (filters.value.toDate) {
      params.append('to_date', filters.value.toDate);
    }
    
    params.append('sort_by', sorting.value.field);
    params.append('page', currentPage.value);

    // Update route
    router.push({ query: { 
      type: filters.value.type,
      priceRange: filters.value.priceRange,
      fromDate: filters.value.fromDate,
      toDate: filters.value.toDate,
      sortBy: sorting.value.field,
      page: currentPage.value,
    }});

    const response = await axios.get('/api/events', { params });
    events.value = response.data.data;
    totalPages.value = response.data.last_page;
    currentPage.value = response.data.current_page;
  } catch (error) {
    console.error('Error loading events:', error);
  } finally {
    loading.value = false;
  }
};

// Watch for route changes
watch(
  () => route.query,
  (newQuery) => {
    filters.value.type = newQuery.type || null;
    filters.value.priceRange = newQuery.priceRange || null;
    filters.value.fromDate = newQuery.fromDate || null;
    filters.value.toDate = newQuery.toDate || null;
    sorting.value.field = newQuery.sortBy || 'start_date';
    currentPage.value = parseInt(newQuery.page) || 1;
  },
  { immediate: true }
);

// Watch for page changes
watch(currentPage, () => {
  loadEvents();
});

onMounted(() => {
  loadEvents();
});

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

const formatPrice = (price) => {
  if (!price) return t('events.free');
  return new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB',
    maximumFractionDigits: 0
  }).format(price);
};

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

const defaultImage = 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=800&q=80';

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
    'https://images.unsplash.com/photo-1577223625816-6599b462fce1?auto=format&fit=crop&w=800&q=80', // Футбольный стадион
    'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=800&q=80', // Бег
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
    'https://images.unsplash.com/photo-1527192491265-7e15c55b1ed2?auto=format&fit=crop&w=800&q=80', // Встреча
    'https://images.unsplash.com/photo-1527192491265-7e15c55b1ed2?auto=format&fit=crop&w=800&q=80', // Networking
    'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=800&q=80', // Воркшоп
    'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&w=800&q=80', // Пикник
  ],
};

const getEventImage = (event) => {
  if (event.image) return event.image;
  
  const typeImages = eventTypeImages[event.type] || eventTypeImages.other;
  const randomIndex = Math.floor(Math.random() * typeImages.length);
  return typeImages[randomIndex];
};
</script>

<style scoped>
.h-100 {
  height: 100%;
}

.text-wrap {
  white-space: normal;
  word-wrap: break-word;
}
</style>
