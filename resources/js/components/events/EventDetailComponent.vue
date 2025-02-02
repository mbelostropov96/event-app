<template>
  <v-container v-if="event">
    <v-row>
      <v-col cols="12" md="8">
        <!-- Event Main Info -->
        <v-card>
          <v-img
            :src="event.image || 'https://picsum.photos/1200/400'"
            height="400"
            cover
            class="bg-grey-lighten-2"
          >
            <template v-slot:placeholder>
              <v-row class="fill-height ma-0" align="center" justify="center">
                <v-progress-circular indeterminate color="primary"></v-progress-circular>
              </v-row>
            </template>
          </v-img>

          <v-card-item>
            <v-card-title class="text-h4">{{ event.title }}</v-card-title>
            <v-card-subtitle class="mt-2">
              <v-chip
                :color="getEventTypeColor(event.type)"
                class="mr-2"
              >
                {{ event.type }}
              </v-chip>
              <v-icon icon="mdi-calendar" size="small" class="mr-1"></v-icon>
              {{ formatDate(event.date) }}
              <v-icon icon="mdi-clock" size="small" class="ml-4 mr-1"></v-icon>
              {{ event.time }}
            </v-card-subtitle>
          </v-card-item>

          <v-card-text class="text-body-1">
            <p class="mb-4">{{ event.description }}</p>
            
            <v-divider class="my-4"></v-divider>
            
            <h3 class="text-h6 mb-2">{{ t('events.detail.location') }}</h3>
            <p class="d-flex align-center">
              <v-icon icon="mdi-map-marker" color="primary" class="mr-2"></v-icon>
              {{ event.location }}
            </p>
            
            <!-- TODO: Add map component here -->
          </v-card-text>
        </v-card>

        <!-- Reviews Section -->
        <v-card class="mt-6">
          <v-card-title class="d-flex align-center">
            {{ t('events.reviews.title') }}
            <v-chip class="ml-2">{{ event.reviews?.length || 0 }}</v-chip>
          </v-card-title>

          <v-card-text>
            <v-list v-if="event.reviews?.length">
              <v-list-item
                v-for="review in event.reviews"
                :key="review.id"
                class="mb-4"
              >
                <template v-slot:prepend>
                  <v-avatar color="primary">
                    {{ review.user.name.charAt(0) }}
                  </v-avatar>
                </template>

                <v-list-item-title>
                  {{ review.user.name }}
                  <span class="text-caption text-grey">
                    {{ formatDate(review.created_at) }}
                  </span>
                </v-list-item-title>

                <v-list-item-subtitle>
                  <v-rating
                    :model-value="review.rating"
                    color="amber"
                    density="compact"
                    readonly
                    half-increments
                  ></v-rating>
                </v-list-item-subtitle>

                <v-list-item-text>
                  {{ review.text }}
                </v-list-item-text>
              </v-list-item>
            </v-list>

            <div v-else class="text-center py-4">
              <v-icon icon="mdi-comment-outline" size="64" color="grey-lighten-1"></v-icon>
              <p class="text-body-1 mt-2 text-grey-darken-1">
                {{ t('events.reviews.empty') }}
              </p>
            </div>

            <!-- Add Review Form -->
            <v-form v-if="isAuthenticated" @submit.prevent="submitReview" class="mt-4">
              <v-rating
                v-model="newReview.rating"
                color="amber"
                hover
                half-increments
              ></v-rating>
              
              <v-textarea
                v-model="newReview.text"
                :label="t('events.reviews.form.text')"
                variant="outlined"
                rows="3"
              ></v-textarea>

              <v-btn
                color="primary"
                type="submit"
                :loading="submittingReview"
              >
                {{ t('events.reviews.form.submit') }}
              </v-btn>
            </v-form>

            <v-alert
              v-else
              type="info"
              variant="tonal"
              class="mt-4"
            >
              {{ t('events.reviews.auth_required') }}
            </v-alert>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="4">
        <!-- Registration Card -->
        <v-card>
          <v-card-title>{{ t('events.registration.title') }}</v-card-title>
          <v-card-text>
            <p class="text-h5 mb-2">
              <span v-if="event.price">{{ formatPrice(event.price) }}</span>
              <span v-else>{{ t('events.registration.free') }}</span>
            </p>

            <p class="mb-4">
              <v-icon icon="mdi-account-group" class="mr-1"></v-icon>
              {{ t('events.registration.spots_left', { count: event.available_spots }) }}
            </p>

            <v-btn
              block
              color="primary"
              size="large"
              :loading="registering"
              :disabled="!event.available_spots"
              @click="register"
            >
              {{ t('events.registration.button') }}
            </v-btn>

            <v-alert
              v-if="!isAuthenticated"
              type="info"
              variant="tonal"
              class="mt-4"
            >
              {{ t('events.registration.auth_required') }}
            </v-alert>
          </v-card-text>
        </v-card>

        <!-- Share Card -->
        <v-card class="mt-4">
          <v-card-title>{{ t('events.share.title') }}</v-card-title>
          <v-card-text>
            <v-btn-group divided>
              <v-btn prepend-icon="mdi-vk">VK</v-btn>
              <v-btn prepend-icon="mdi-telegram">Telegram</v-btn>
              <v-btn prepend-icon="mdi-link">{{ t('events.share.copy') }}</v-btn>
            </v-btn-group>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>

  <!-- Loading State -->
  <v-container v-else-if="loading">
    <v-row>
      <v-col cols="12" class="text-center">
        <v-progress-circular indeterminate color="primary"></v-progress-circular>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { useRoute } from 'vue-router';

const { t } = useI18n();
const route = useRoute();

// State
const event = ref(null);
const loading = ref(true);
const registering = ref(false);
const submittingReview = ref(false);
const isAuthenticated = ref(false); // TODO: Replace with actual auth state
const newReview = ref({
  rating: 0,
  text: ''
});

// Methods
const fetchEvent = async () => {
  loading.value = true;
  try {
    // TODO: Replace with actual API call
    await new Promise(resolve => setTimeout(resolve, 1000));
    event.value = {
      id: route.params.id,
      title: 'Выставка современного искусства',
      description: 'Подробное описание выставки...',
      date: '2025-02-15',
      time: '18:00',
      location: 'Городской музей',
      type: 'exhibition',
      price: 500,
      available_spots: 50,
      reviews: [
        {
          id: 1,
          user: { name: 'Иван Петров' },
          rating: 4.5,
          text: 'Отличная выставка!',
          created_at: '2025-02-01'
        }
      ]
    };
  } catch (error) {
    console.error('Error fetching event:', error);
  } finally {
    loading.value = false;
  }
};

const register = async () => {
  if (!isAuthenticated.value) return;
  
  registering.value = true;
  try {
    // TODO: Replace with actual API call
    await new Promise(resolve => setTimeout(resolve, 1000));
    // Handle successful registration
  } catch (error) {
    console.error('Error registering for event:', error);
  } finally {
    registering.value = false;
  }
};

const submitReview = async () => {
  if (!isAuthenticated.value) return;
  
  submittingReview.value = true;
  try {
    // TODO: Replace with actual API call
    await new Promise(resolve => setTimeout(resolve, 1000));
    // Handle successful review submission
    newReview.value = { rating: 0, text: '' };
  } catch (error) {
    console.error('Error submitting review:', error);
  } finally {
    submittingReview.value = false;
  }
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('ru-RU', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB'
  }).format(price);
};

const getEventTypeColor = (type) => {
  const colors = {
    exhibition: 'purple',
    concert: 'blue',
    theater: 'deep-orange',
    festival: 'green'
  };
  return colors[type] || 'grey';
};

// Lifecycle
onMounted(() => {
  fetchEvent();
});
</script>
