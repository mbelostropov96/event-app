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
          
          <!-- Show Past Events Switch -->
          <v-col cols="12">
            <v-switch
              v-model="filters.showPastEvents"
              :label="t('events.filters.showPastEvents')"
              color="primary"
              hide-details
              density="compact"
              @change="loadEvents"
            ></v-switch>
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
    <v-row v-if="!loading">
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
                :class="{ 'past-event': isPastEvent(event) }"
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
                  
                  <!-- Добавляем чип с типом события поверх изображения -->
                  <div class="d-flex justify-end pa-2">
                    <v-chip
                      :color="getEventTypeColor(event.type)"
                      size="small"
                      label
                      class="event-type-chip"
                      variant="elevated"
                      elevation="3"
                    >
                      <v-icon start :icon="t(`categories.${event.type}Icon`)" size="small"></v-icon>
                      {{ t(`categories.${event.type}`) }}
                    </v-chip>
                  </div>
                  
                  <!-- Добавляем цену поверх изображения внизу -->
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
                  
                  <!-- Метка для прошедших событий -->
                  <div v-if="isPastEvent(event)" class="past-event-badge">
                    <v-chip
                      color="grey-darken-2"
                      size="small"
                      label
                      class="past-event-chip"
                      variant="elevated"
                      elevation="3"
                    >
                      {{ t('events.pastEvent') }}
                    </v-chip>
                  </div>
                </v-img>

                <v-card-item>
                  <v-card-title class="text-truncate pa-0 text-subtitle-1 font-weight-bold">
                    {{ event.title }}
                  </v-card-title>
                  
                  <v-card-subtitle class="pa-0 pt-2 pb-1">
                    <div class="d-flex align-center">
                      <v-icon size="small" icon="mdi-calendar" class="mr-1"></v-icon>
                      <span class="text-body-2">{{ formatDate(event.start_date) }}</span>
                      <v-divider vertical class="mx-2"></v-divider>
                      <v-icon size="small" icon="mdi-clock" class="mr-1"></v-icon>
                      <span class="text-body-2">{{ formatTime(event.start_time) }}</span>
                    </div>
                    
                    <div class="d-flex align-center mt-1">
                      <v-icon size="small" icon="mdi-map-marker" class="mr-1"></v-icon>
                      <span class="text-body-2 text-truncate">{{ formatLocation(event.location) }}</span>
                    </div>
                  </v-card-subtitle>
                </v-card-item>

                <v-card-text class="pt-0">
                  <p class="text-caption text-grey text-truncate-2">{{ event.description }}</p>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>

          <div v-else class="text-center py-4">
            {{ t('events.noEvents') }}
          </div>

          <!-- Pagination -->
          <div v-if="events.length" class="d-flex justify-center mt-4">
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
      <v-col cols="12">
        <v-card class="pa-4">
          <v-row>
            <v-col v-for="n in 8" :key="n" cols="12" sm="6" md="4" lg="3" class="pa-2">
              <v-card>
                <v-skeleton-loader
                  type="image, article"
                  height="400"
                ></v-skeleton-loader>
              </v-card>
            </v-col>
          </v-row>
        </v-card>
      </v-col>
    </v-row>

    <!-- No Results -->
    <v-row v-if="!loading && events.length === 0">
      <v-col cols="12">
        <v-alert
          type="info"
          :text="t('events.noEventsFound')"
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
import { getEventTypeColor, getEventImage, formatDate, formatTime } from './eventData';

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
  showPastEvents: route.query.showPastEvents === 'true' || false,
});

const sorting = ref({
  field: route.query.sortBy || 'start_date',
});

const loading = ref(true);
const events = ref([]);
const totalPages = ref(1);
const currentPage = ref(1);

const clearFilters = () => {
  filters.value = {
    type: null,
    priceRange: null,
    fromDate: null,
    toDate: null,
    showPastEvents: false,
  };
  sorting.value.field = 'start_date';
  loadEvents();
};

const loadEvents = async () => {
  loading.value = true;
  
  try {
    // Обновляем URL с параметрами фильтрации
    router.push({
      query: {
        page: currentPage.value,
        type: filters.value.type || undefined,
        priceRange: filters.value.priceRange || undefined,
        fromDate: filters.value.fromDate || undefined,
        toDate: filters.value.toDate || undefined,
        showPastEvents: filters.value.showPastEvents || undefined,
        sortBy: sorting.value.field || undefined
      }
    });

    // Подготавливаем параметры для API запроса
    const params = {
      page: currentPage.value,
      type: filters.value.type,
      price_range: filters.value.priceRange,
      from_date: filters.value.fromDate,
      to_date: filters.value.toDate,
      sort: sorting.value.field,
      show_past_events: filters.value.showPastEvents,
    };

    // Преобразуем диапазон цен в min/max значения для API
    if (filters.value.priceRange) {
      if (filters.value.priceRange === 'free') {
        params.price_max = 0;
        delete params.price_range;
      } else if (filters.value.priceRange === '4000+') {
        params.price_min = 4000;
        delete params.price_range;
      } else {
        const [min, max] = filters.value.priceRange.split('-').map(Number);
        params.price_min = min;
        params.price_max = max;
        delete params.price_range;
      }
    }

    const response = await axios.get('/api/events', { params });
    
    // Проверяем формат ответа и извлекаем данные соответственно
    if (response.data.data) {
      // Формат с вложенным массивом data
      events.value = response.data.data;
    } else {
      // Формат без вложенного массива data
      events.value = response.data;
    }
    
    // Проверяем, где находятся метаданные пагинации
    if (response.data.meta && response.data.meta.last_page) {
      totalPages.value = response.data.meta.last_page;
    } else if (response.data.last_page) {
      totalPages.value = response.data.last_page;
    } else {
      // Если метаданных нет, устанавливаем totalPages в 1
      totalPages.value = 1;
      console.warn('No pagination metadata found in response:', response.data);
    }
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
    filters.value.showPastEvents = newQuery.showPastEvents === 'true' || false;
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

const defaultImage = 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=800&q=80';

const isPastEvent = (event) => {
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  const eventDate = new Date(event.start_date);
  eventDate.setHours(0, 0, 0, 0);
  return eventDate < today;
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

.text-truncate-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
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

.past-event {
  opacity: 0.8;
}

.past-event-badge {
  position: absolute;
  top: 10px;
  left: 10px;
  z-index: 2;
}

.past-event-chip {
  font-weight: bold;
  opacity: 0.95;
  text-shadow: 0px 0px 1px rgba(0, 0, 0, 0.3);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}
</style>
