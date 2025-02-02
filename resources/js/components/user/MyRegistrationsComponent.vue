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
      <v-col v-for="registration in registrations" :key="registration.id" cols="12" md="6">
        <v-card>
          <v-row no-gutters>
            <v-col cols="4">
              <v-img
                :src="registration.event.image || 'https://picsum.photos/200/200'"
                height="100%"
                cover
              ></v-img>
            </v-col>
            <v-col cols="8">
              <v-card-item>
                <v-card-title>{{ registration.event.title }}</v-card-title>
                <v-card-subtitle>
                  <v-icon icon="mdi-calendar" size="small" class="mr-1"></v-icon>
                  {{ formatDate(registration.event.date) }}
                  <v-icon icon="mdi-clock" size="small" class="ml-4 mr-1"></v-icon>
                  {{ registration.event.time }}
                </v-card-subtitle>

                <v-card-text>
                  <p class="mb-2">
                    <v-icon icon="mdi-map-marker" size="small" class="mr-1"></v-icon>
                    {{ registration.event.location }}
                  </p>
                  <v-chip
                    :color="getStatusColor(registration.status)"
                    size="small"
                  >
                    {{ t(`registrations.status.${registration.status}`) }}
                  </v-chip>
                </v-card-text>

                <v-card-actions>
                  <v-btn
                    variant="text"
                    :to="{ name: 'event-detail', params: { id: registration.event.id }}"
                  >
                    {{ t('events.actions.details') }}
                  </v-btn>
                  <v-spacer></v-spacer>
                  <v-btn
                    v-if="canCancel(registration)"
                    color="error"
                    variant="text"
                    @click="cancelRegistration(registration.id)"
                    :loading="cancelling === registration.id"
                  >
                    {{ t('registrations.actions.cancel') }}
                  </v-btn>
                </v-card-actions>
              </v-card-item>
            </v-col>
          </v-row>
        </v-card>
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
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

// State
const registrations = ref([]);
const loading = ref(true);
const cancelling = ref(null);

// Methods
const fetchRegistrations = async () => {
  loading.value = true;
  try {
    // TODO: Replace with actual API call
    await new Promise(resolve => setTimeout(resolve, 1000));
    registrations.value = [
      {
        id: 1,
        event: {
          id: 1,
          title: 'Выставка современного искусства',
          date: '2025-02-15',
          time: '18:00',
          location: 'Городской музей',
          image: null
        },
        status: 'confirmed',
        created_at: '2025-02-01'
      }
    ];
  } catch (error) {
    console.error('Error fetching registrations:', error);
  } finally {
    loading.value = false;
  }
};

const cancelRegistration = async (id) => {
  cancelling.value = id;
  try {
    // TODO: Replace with actual API call
    await new Promise(resolve => setTimeout(resolve, 1000));
    registrations.value = registrations.value.filter(reg => reg.id !== id);
  } catch (error) {
    console.error('Error cancelling registration:', error);
  } finally {
    cancelling.value = null;
  }
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('ru-RU', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const getStatusColor = (status) => {
  const colors = {
    confirmed: 'success',
    pending: 'warning',
    cancelled: 'error'
  };
  return colors[status] || 'grey';
};

const canCancel = (registration) => {
  return registration.status === 'confirmed' || registration.status === 'pending';
};

// Lifecycle
onMounted(() => {
  fetchRegistrations();
});
</script>
