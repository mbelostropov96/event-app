<template>
  <div>
    <!-- Events List -->
    <v-row class="pa-3">
      <v-col
        v-for="event in events"
        :key="event.id"
        cols="12"
        sm="6"
        md="4"
        class="pa-3"
      >
        <v-card class="h-100">
          <v-card-title class="text-h6 text-wrap">
            {{ event.title }}
          </v-card-title>

          <v-card-text>
            <p class="mb-2 text-body-1">{{ event.description }}</p>
            <v-tooltip :text="event.location">
              <template v-slot:activator="{ props }">
                <v-chip
                  class="mr-2 mb-2"
                  color="primary"
                  variant="outlined"
                  v-bind="props"
                >
                  <v-icon start icon="mdi-map-marker"></v-icon>
                  {{ formatLocation(event.location) }}
                </v-chip>
              </template>
            </v-tooltip>
            <v-chip
              class="mr-2 mb-2"
              color="primary"
              variant="outlined"
            >
              <v-icon start icon="mdi-calendar"></v-icon>
              {{ formatDate(event.startDate) }}
            </v-chip>
            <v-chip
              class="mb-2"
              color="primary"
              variant="outlined"
            >
              <v-icon start icon="mdi-clock"></v-icon>
              {{ event.startTime }}
            </v-chip>
            <div class="mt-2">
              <v-chip
                color="success"
                variant="elevated"
              >
                {{ formatPrice(event.price) }}
              </v-chip>
            </div>
          </v-card-text>

          <v-card-actions class="mt-auto">
            <v-btn
              color="primary"
              variant="tonal"
              :to="{ name: 'event-detail', params: { id: event.id }}"
            >
              {{ t('events.details') }}
            </v-btn>
          </v-card-actions>
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
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const { t } = useI18n();

const events = ref([]);
const loading = ref(false);

const fetchEvents = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/events');
    events.value = response.data;
  } catch (error) {
    console.error('Error fetching events:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchEvents();
});

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('ru-RU');
};

const formatPrice = (price) => {
  if (price === 0) {
    return t('events.free');
  }
  // Округляем до целых и форматируем без копеек
  const roundedPrice = Math.round(price);
  return new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(roundedPrice);
};

const formatLocation = (location) => {
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

.text-wrap {
  white-space: normal;
  word-wrap: break-word;
}
</style>
