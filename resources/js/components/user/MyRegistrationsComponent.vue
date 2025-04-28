<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <h2 class="text-h4 mb-6">{{ t('navigation.myRegistrations') }}</h2>
      </v-col>
    </v-row>

    <!-- Loading State -->
    <v-row v-if="loading">
      <v-col cols="12" class="text-center">
        <v-progress-circular indeterminate color="primary"></v-progress-circular>
      </v-col>
    </v-row>

    <!-- Registrations List -->
    <v-row v-else-if="registrations.length">
      <v-col cols="12">
        <h3 class="text-h5 mb-4">{{ t('registrations.tabs.active') }}</h3>
      </v-col>

      <!-- Active Events -->
      <template v-if="activeRegistrations.length">
        <v-col v-for="registration in sortedActiveRegistrations" :key="registration.id" cols="12" md="6">
          <v-card>
            <v-row no-gutters>
              <v-col cols="4">
                <v-img
                  :src="getEventImage(registration.event)"
                  height="100%"
                  cover
                >
                  <template v-slot:error>
                    <div class="d-flex align-center justify-center fill-height bg-grey-lighten-2">
                      <v-avatar
                        size="64"
                        :color="getEventTypeColor(registration.event.type)"
                      >
                        <v-icon
                          size="32"
                          :icon="getEventTypeIcon(registration.event.type)"
                          color="white"
                        ></v-icon>
                      </v-avatar>
                    </div>
                  </template>
                </v-img>
              </v-col>
              <v-col cols="8">
                <v-card-item>
                  <v-card-title>{{ registration.event.title }}</v-card-title>
                  <v-card-subtitle>
                    <v-icon icon="mdi-calendar" size="small" class="mr-1"></v-icon>
                    {{ formatDate(registration.event.start_date) }}
                    <v-icon icon="mdi-clock" size="small" class="ml-4 mr-1"></v-icon>
                    {{ formatTime(registration.event.start_time) }}
                  </v-card-subtitle>

                  <v-card-text>
                    <p class="mb-2">
                      <v-icon icon="mdi-map-marker" size="small" class="mr-1"></v-icon>
                      {{ registration.event.location }}
                    </p>
                    <p class="mb-2">
                      <v-icon icon="mdi-calendar-clock" size="small" class="mr-1"></v-icon>
                      {{ t('registrations.registered_at') }}: {{ formatDate(registration.registered_at) }}
                    </p>
                  </v-card-text>

                  <v-card-actions>
                    <v-btn
                      variant="text"
                      :to="{ name: 'event-details', params: { id: registration.event.id }}"
                    >
                      {{ t('events.actions.details') }}
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn
                      color="error"
                      variant="text"
                      @click="cancelRegistration(registration.event.id)"
                      :loading="cancelling === registration.event.id"
                    >
                      {{ t('registrations.actions.cancel') }}
                    </v-btn>
                  </v-card-actions>
                </v-card-item>
              </v-col>
            </v-row>
          </v-card>
        </v-col>
      </template>
      <v-col v-else cols="12" class="text-center py-4">
        <v-alert type="info" variant="tonal">
          {{ t('registrations.empty.active') }}
        </v-alert>
      </v-col>

      <!-- Past Events -->
      <v-col cols="12" class="mt-8">
        <h3 class="text-h5 mb-4">{{ t('registrations.tabs.past') }}</h3>
      </v-col>

      <template v-if="pastRegistrations.length">
        <v-col v-for="registration in sortedPastRegistrations" :key="registration.id" cols="12" md="6">
          <v-card>
            <v-row no-gutters>
              <v-col cols="4">
                <v-img
                  :src="getEventImage(registration.event)"
                  height="100%"
                  cover
                  class="opacity-75"
                >
                  <template v-slot:error>
                    <div class="d-flex align-center justify-center fill-height bg-grey-lighten-2">
                      <v-avatar
                        size="64"
                        :color="getEventTypeColor(registration.event.type)"
                      >
                        <v-icon
                          size="32"
                          :icon="getEventTypeIcon(registration.event.type)"
                          color="white"
                        ></v-icon>
                      </v-avatar>
                    </div>
                  </template>
                </v-img>
              </v-col>
              <v-col cols="8">
                <v-card-item>
                  <v-card-title class="d-flex align-center">
                    {{ registration.event.title }}
                  </v-card-title>
                  <v-card-subtitle>
                    <v-icon icon="mdi-calendar" size="small" class="mr-1"></v-icon>
                    {{ formatDate(registration.event.start_date) }}
                    <v-icon icon="mdi-clock" size="small" class="ml-4 mr-1"></v-icon>
                    {{ formatTime(registration.event.start_time) }}
                  </v-card-subtitle>

                  <v-card-text>
                    <p class="mb-2">
                      <v-icon icon="mdi-map-marker" size="small" class="mr-1"></v-icon>
                      {{ registration.event.location }}
                    </p>
                    <p class="mb-2">
                      <v-icon icon="mdi-calendar-clock" size="small" class="mr-1"></v-icon>
                      {{ t('registrations.registered_at') }}: {{ formatDate(registration.registered_at) }}
                    </p>
                  </v-card-text>

                  <v-card-actions>
                    <v-btn
                      variant="text"
                      :to="{ name: 'event-details', params: { id: registration.event.id }}"
                    >
                      {{ t('events.actions.details') }}
                    </v-btn>
                  </v-card-actions>
                </v-card-item>
              </v-col>
            </v-row>
          </v-card>
        </v-col>
      </template>
      <v-col v-else cols="12" class="text-center py-4">
        <v-alert type="info" variant="tonal">
          {{ t('registrations.empty.past') }}
        </v-alert>
      </v-col>
    </v-row>

    <!-- Empty State -->
    <v-row v-else>
      <v-col cols="12" class="text-center">
        <v-icon icon="mdi-ticket-outline" size="64" color="grey-lighten-1"></v-icon>
        <h3 class="text-h6 mt-4 text-grey-darken-1">{{ t('registrations.empty.title') }}</h3>
        <p class="text-body-2 text-grey-darken-1">{{ t('registrations.empty.description') }}</p>
        <v-btn
          color="primary"
          class="mt-4"
          :to="{ name: 'events' }"
        >
          {{ t('registrations.empty.action') }}
        </v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';
import { formatDate, formatTime, getEventTypeColor, getEventImage, getEventTypeIcon } from '../events/eventData';

const { t } = useI18n();

// State
const registrations = ref([]);
const loading = ref(true);
const cancelling = ref(null);

// Computed properties
const activeRegistrations = computed(() => {
  return registrations.value.filter(registration => {
    if (!registration.event.start_date) return false;
    const eventDate = new Date(registration.event.start_date);
    return eventDate >= new Date();
  });
});

const pastRegistrations = computed(() => {
  return registrations.value.filter(registration => {
    if (!registration.event.start_date) return false;
    const eventDate = new Date(registration.event.start_date);
    return eventDate < new Date();
  });
});

const sortedActiveRegistrations = computed(() => {
  return [...activeRegistrations.value].sort((a, b) => {
    const dateA = new Date(a.event.start_date);
    const dateB = new Date(b.event.start_date);
    return dateA - dateB; // Sort by ascending date (closest first)
  });
});

const sortedPastRegistrations = computed(() => {
  return [...pastRegistrations.value].sort((a, b) => {
    const dateA = new Date(a.event.start_date);
    const dateB = new Date(b.event.start_date);
    return dateB - dateA; // Sort by descending date (most recent first)
  });
});

// Methods
const fetchRegistrations = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/profile/registrations');
    registrations.value = response.data;
  } catch (error) {
    console.error('Error fetching registrations:', error);
    registrations.value = [];
  } finally {
    loading.value = false;
  }
};

const cancelRegistration = async (eventId) => {
  cancelling.value = eventId;
  try {
    await axios.post(`/api/events/${eventId}/unregister`);
    // Remove the cancelled registration from the list
    registrations.value = registrations.value.filter(reg => reg.event.id !== eventId);
  } catch (error) {
    console.error('Error cancelling registration:', error);
  } finally {
    cancelling.value = null;
  }
};

// Lifecycle
onMounted(() => {
  fetchRegistrations();
});
</script>
