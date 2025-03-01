<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <h1 class="text-h3 mb-6">{{ t('calendar.title') }}</h1>
      </v-col>

      <v-col cols="12" md="8">
        <v-sheet height="600">
          <v-calendar
            v-model="selectedDate"
            :show-adjacent-months="true"
            :first-day-of-week="1"
            class="w-100"
            @click:date="handleDateSelect"
          ></v-calendar>
        </v-sheet>
      </v-col>

      <v-col cols="12" md="4">
        <v-card>
          <v-card-title class="text-h6">
            {{ t('calendar.eventsForDate') }}
          </v-card-title>
          <v-card-text>
            <template v-if="selectedDateEvents.length">
              <v-list>
                <v-list-item
                  v-for="event in selectedDateEvents"
                  :key="event.id"
                  :to="{ name: 'event-detail', params: { id: event.id }}"
                >
                  <template v-slot:prepend>
                    <v-avatar color="primary" size="36">
                      <v-icon icon="mdi-calendar-text" color="white"></v-icon>
                    </v-avatar>
                  </template>

                  <v-list-item-title>{{ event.title }}</v-list-item-title>
                  <v-list-item-subtitle>
                    <v-icon icon="mdi-clock-outline" size="small" class="mr-1"></v-icon>
                    {{ event.time }}
                  </v-list-item-subtitle>

                  <template v-slot:append>
                    <v-btn
                      variant="text"
                      color="primary"
                      size="small"
                      :to="{ name: 'event-detail', params: { id: event.id }}"
                    >
                      {{ t('calendar.viewDetails') }}
                      <v-icon end icon="mdi-arrow-right"></v-icon>
                    </v-btn>
                  </template>
                </v-list-item>
              </v-list>
            </template>
            <template v-else>
              <div class="text-center pa-4">
                <v-icon icon="mdi-calendar-blank" size="large" color="grey"></v-icon>
                <div class="text-body-1 mt-2">{{ t('calendar.noEvents') }}</div>
              </div>
            </template>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const { t } = useI18n();

// State
const selectedDate = ref(new Date());
const events = ref([]);

// Fetch events
const fetchEvents = async () => {
  try {
    const response = await axios.get('/api/events');
    events.value = response.data.map(event => ({
      ...event,
      date: new Date(event.startDate).toISOString().split('T')[0]
    }));
  } catch (error) {
    console.error('Error fetching events:', error);
  }
};

// Handle date selection
const handleDateSelect = (date) => {
  selectedDate.value = date;
};

// Format date to YYYY-MM-DD
const formatDate = (date) => {
  if (!date) return '';
  return date instanceof Date 
    ? date.toISOString().split('T')[0]
    : new Date(date).toISOString().split('T')[0];
};

// Computed
const selectedDateEvents = computed(() => {
  const currentDate = formatDate(selectedDate.value);
  return events.value.filter(event => event.date === currentDate);
});

// Initial fetch
fetchEvents();
</script>

<style scoped>
.w-100 {
  width: 100%;
}
</style>
