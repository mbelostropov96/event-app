<template>
  <div>
    <v-container v-if="!loading && event.id">
      <!-- Event Image -->
      <v-row>
        <v-col cols="12">
          <v-img
            :src="getEventImage(event)"
            height="400"
            cover
            class="rounded-lg"
          >
            <template v-slot:error>
              <div class="d-flex align-center justify-center fill-height bg-grey-lighten-2 rounded-lg">
                <v-avatar
                  size="160"
                  :color="getEventTypeColor(event.type)"
                >
                  <v-icon
                    size="80"
                    :icon="t(`categories.${event.type}Icon`)"
                    color="white"
                  ></v-icon>
                </v-avatar>
              </div>
            </template>
          </v-img>
        </v-col>
      </v-row>

      <!-- Event Details -->
      <v-row class="mt-6">
        <v-col cols="12" md="8">
          <div class="d-flex align-center mb-4">
            <h1 class="text-h3 font-weight-bold">{{ event.title }}</h1>
            <v-chip
              :color="getEventTypeColor(event.type)"
              class="ml-4"
              size="large"
              label
            >
              <v-icon start :icon="t(`categories.${event.type}Icon`)"></v-icon>
              {{ t(`categories.${event.type}`) }}
            </v-chip>
          </div>

          <v-card class="mb-6" variant="flat">
            <v-card-text class="text-body-1">
              <div class="d-flex flex-column gap-4">
                <!-- Date and Time -->
                <div class="d-flex align-center">
                  <v-icon size="28" icon="mdi-calendar" class="mr-4"></v-icon>
                  <div>
                    <div class="text-h6">{{ formatDate(event.start_date) }}</div>
                    <div class="text-subtitle-1">{{ formatTime(event.start_time) }}</div>
                  </div>
                </div>

                <!-- Location -->
                <div class="d-flex align-center">
                  <v-icon size="28" icon="mdi-map-marker" class="mr-4"></v-icon>
                  <div>
                    <div class="text-h6">{{ event.location }}</div>
                    <div class="text-subtitle-1">
                      <v-btn
                        variant="text"
                        color="primary"
                        prepend-icon="mdi-map"
                        @click="openMap"
                      >
                        {{ t('events.showOnMap') }}
                      </v-btn>
                    </div>
                  </div>
                </div>

                <!-- Price -->
                <div class="d-flex align-center">
                  <v-icon size="28" icon="mdi-currency-rub" class="mr-4"></v-icon>
                  <div>
                    <div class="text-h6">
                      {{ event.price ? `${event.price} ₽` : t('events.free') }}
                    </div>
                    <div v-if="event.price" class="text-subtitle-1">
                      {{ t('events.pricePerPerson') }}
                    </div>
                  </div>
                </div>
              </div>
            </v-card-text>
          </v-card>

          <!-- Description -->
          <div class="text-h5 font-weight-bold mb-4">{{ t('events.description') }}</div>
          <div class="text-body-1 mb-6" style="white-space: pre-line">{{ event.description }}</div>

          <!-- Organizer 
          <div class="text-h5 font-weight-bold mb-4">{{ t('events.organizer') }}</div>
          <v-card variant="outlined" class="mb-6">
            <v-card-text>
              <div class="d-flex align-center">
                <v-avatar color="primary" size="48" class="mr-4">
                  <v-icon icon="mdi-account" color="white"></v-icon>
                </v-avatar>
                <div>
                  <div class="text-h6">{{ event.organizer?.name || t('events.organizerUnknown') }}</div>
                  <div class="text-subtitle-1">{{ event.organizer?.email }}</div>
                </div>
              </div>
            </v-card-text>
          </v-card>
          -->
          
          <!-- Reviews Section -->
          <div v-if="isAuthenticated" class="mt-8">
            <div class="d-flex align-center justify-space-between mb-4">
              <div class="text-h5 font-weight-bold">{{ t('events.reviews.title') }}</div>
              <div v-if="reviews.length">
                <v-chip color="warning" class="mr-2">
                  <template v-slot:prepend>
                    <v-icon start icon="mdi-star"></v-icon>
                  </template>
                  {{ averageRating.toFixed(1) }}
                </v-chip>
                <span class="text-body-2">{{ t('reviews.count', { n: reviews.length }) }}</span>
              </div>
            </div>
            
            <!-- Review Form -->
            <v-card v-if="isAuthenticated && isPastEvent && event.can_review && event.registered" class="mb-6" variant="outlined">
              <v-card-text>
                <v-form @submit.prevent="submitReview">
                  <v-rating
                    v-model="newReview.rating"
                    color="warning"
                    hover
                    half-increments
                    class="mb-3"
                  ></v-rating>
                  <v-textarea
                    v-model="newReview.comment"
                    :label="t('events.reviews.form.text')"
                    rows="3"
                    auto-grow
                    variant="outlined"
                    class="mb-3"
                  ></v-textarea>
                  <v-btn
                    type="submit"
                    color="primary"
                    :loading="submittingReview"
                    :disabled="!newReview.rating || !newReview.comment"
                  >
                    {{ t('events.reviews.form.submit') }}
                  </v-btn>
                </v-form>
              </v-card-text>
            </v-card>
            <v-alert
              v-else-if="!isAuthenticated"
              type="info"
              variant="tonal"
              class="mb-6"
            >
              {{ t('events.reviews.auth_required') }}
            </v-alert>
            <v-alert
              v-else-if="!isPastEvent"
              type="info"
              variant="tonal"
              class="mb-6"
            >
              {{ t('events.reviews.future_event') }}
            </v-alert>
            <v-alert
              v-else-if="!event.registered"
              type="info"
              variant="tonal"
              class="mb-6"
            >
              {{ t('events.reviews.not_registered') }}
            </v-alert>
            <v-alert
              v-else-if="!event.can_review"
              type="info"
              variant="tonal"
              class="mb-6"
            >
              {{ t('events.reviews.cannot_review') }}
            </v-alert>
            
            <!-- Reviews List -->
            <div v-if="loadingReviews" class="text-center my-4">
              <v-progress-circular indeterminate color="primary"></v-progress-circular>
            </div>
            <div v-else-if="reviews.length === 0" class="text-center my-4">
              <v-icon icon="mdi-star-outline" size="64" color="grey-lighten-1"></v-icon>
              <div class="text-h6 mt-2">{{ t('events.reviews.empty') }}</div>
            </div>
            <div v-else>
              <v-card
                v-for="review in reviews"
                :key="review.id"
                class="mb-4"
                variant="outlined"
              >
                <v-card-item>
                  <template v-slot:prepend>
                    <v-avatar color="primary">
                      <v-icon icon="mdi-account" color="white"></v-icon>
                    </v-avatar>
                  </template>
                  <v-card-title>
                    {{ review.user.first_name }} {{ review.user.last_name }}
                    <span class="text-caption ml-2 text-grey">
                      {{ formatDate(review.created_at) }}
                    </span>
                  </v-card-title>
                  <v-card-subtitle class="mt-2">
                    <v-rating
                      v-model="review.rating"
                      color="warning"
                      density="compact"
                      half-increments
                      readonly
                    ></v-rating>
                  </v-card-subtitle>
                  <v-card-text>
                    {{ review.comment }}
                  </v-card-text>
                </v-card-item>
              </v-card>
              
              <!-- Pagination -->
              <div v-if="totalPages > 1" class="text-center mt-4">
                <v-pagination
                  v-model="currentPage"
                  :length="totalPages"
                  :total-visible="5"
                  @update:modelValue="loadReviews"
                ></v-pagination>
              </div>
            </div>
          </div>
        </v-col>
  
        <!-- Action Card -->
        <v-col cols="12" md="4">
          <div class="register-btn-wrap">
            <v-btn
              block
              color="primary"
              size="x-large"
              elevation="8"
              class="register-btn mb-4"
              :loading="loading"
              :disabled="!isAuthenticated"
              v-if="!event.registered"
              @click="register"
            >
              <v-icon start icon="mdi-check-circle"></v-icon>
              <span class="text-subtitle-1">{{ t('events.register') }}</span>
            </v-btn>
            <v-btn
              block
              color="error"
              size="x-large"
              elevation="8"
              class="register-btn mb-4"
              :loading="loading"
              v-else
              @click="unregister"
            >
              <v-icon start icon="mdi-close-circle"></v-icon>
              <span class="text-subtitle-1">{{ t('events.unregister') }}</span>
            </v-btn>
          </div>
        </v-col>
      </v-row>
    </v-container>

    <!-- Loading State -->
    <v-container v-else-if="loading">
      <v-row>
        <!-- Event Image Skeleton -->
        <v-col cols="12">
          <v-skeleton-loader
            type="image"
            height="400"
            class="rounded-lg"
          ></v-skeleton-loader>
        </v-col>
      </v-row>

      <!-- Event Details Skeleton -->
      <v-row class="mt-6">
        <v-col cols="12" md="8">
          <!-- Title and Type -->
          <div class="d-flex align-center mb-4">
            <v-skeleton-loader
              type="text"
              width="400"
              class="mr-4"
            ></v-skeleton-loader>
            <v-skeleton-loader
              type="chip"
              width="120"
            ></v-skeleton-loader>
          </div>

          <!-- Event Info Card -->
          <v-card class="mb-6" variant="flat">
            <v-card-text>
              <div class="d-flex flex-column gap-4">
                <!-- Date and Time -->
                <div class="d-flex align-center">
                  <v-skeleton-loader
                    type="avatar"
                    size="28"
                    class="mr-4"
                  ></v-skeleton-loader>
                  <div>
                    <v-skeleton-loader
                      type="text"
                      width="200"
                      class="mb-2"
                    ></v-skeleton-loader>
                    <v-skeleton-loader
                      type="text"
                      width="100"
                    ></v-skeleton-loader>
                  </div>
                </div>

                <!-- Location -->
                <div class="d-flex align-center">
                  <v-skeleton-loader
                    type="avatar"
                    size="28"
                    class="mr-4"
                  ></v-skeleton-loader>
                  <div>
                    <v-skeleton-loader
                      type="text"
                      width="300"
                      class="mb-2"
                    ></v-skeleton-loader>
                    <v-skeleton-loader
                      type="button"
                      width="150"
                    ></v-skeleton-loader>
                  </div>
                </div>

                <!-- Price -->
                <div class="d-flex align-center">
                  <v-skeleton-loader
                    type="avatar"
                    size="28"
                    class="mr-4"
                  ></v-skeleton-loader>
                  <div>
                    <v-skeleton-loader
                      type="text"
                      width="150"
                      class="mb-2"
                    ></v-skeleton-loader>
                    <v-skeleton-loader
                      type="text"
                      width="200"
                    ></v-skeleton-loader>
                  </div>
                </div>
              </div>
            </v-card-text>
          </v-card>

          <!-- Description -->
          <v-skeleton-loader
            type="text"
            width="200"
            class="mb-4"
          ></v-skeleton-loader>
          <v-skeleton-loader
            type="paragraph"
            class="mb-6"
          ></v-skeleton-loader>
        </v-col>

        <!-- Action Card -->
        <v-col cols="12" md="4">
          <div class="register-btn-wrap">
            <v-skeleton-loader
              type="button"
              class="mb-4"
            ></v-skeleton-loader>
          </div>
        </v-col>
      </v-row>
    </v-container>

    <!-- Error State -->
    <v-container v-else>
      <v-alert
        type="error"
        variant="tonal"
        :text="t('events.detail.error')"
      ></v-alert>
    </v-container>

    <!-- Share Dialog
    <v-dialog v-model="shareDialog" max-width="500">
      <v-card>
        <v-card-title>{{ t('events.actions.share') }}</v-card-title>
        <v-card-text>
          <v-text-field
            v-model="shareUrl"
            readonly
            append-inner-icon="mdi-content-copy"
            @click:append-inner="copyShareUrl"
          ></v-text-field>
        </v-card-text>
      </v-card>
    </v-dialog>
   -->
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { useRoute } from 'vue-router';
import axios from 'axios';
import { getEventTypeColor, getEventImage, formatDate, formatTime } from './eventData';

const { t } = useI18n();
const route = useRoute();

const event = ref({});
const loading = ref(true);
const shareDialog = ref(false);
const shareUrl = ref('');

// Reviews state
const reviews = ref([]);
const loadingReviews = ref(false);
const currentPage = ref(1);
const totalPages = ref(1);
const submittingReview = ref(false);
const newReview = ref({
  rating: 0,
  comment: ''
});

// Authentication state (would normally come from a store)
const isAuthenticated = ref(false);

// Check if event is in the past
const isPastEvent = computed(() => {
  if (!event.value.start_date) return false;
  const eventDate = new Date(event.value.start_date);
  return eventDate < new Date();
});

// Calculate average rating
const averageRating = computed(() => {
  if (!reviews.value.length) return 0;
  const sum = reviews.value.reduce((acc, review) => acc + review.rating, 0);
  return sum / reviews.value.length;
});

const loadEvent = async () => {
  try {
    loading.value = true;
    const response = await axios.get(`/api/events/${route.params.id}`);
    event.value = response.data;
    event.value.registered = false; // Сбрасываем статус регистрации по умолчанию
    shareUrl.value = window.location.href;
    
    // Check authentication status
    const token = localStorage.getItem('token');
    if (token) {
      try {
        const authResponse = await axios.get('/api/user');
        isAuthenticated.value = true;
        
        // Check if user is already registered for this event
        try {
          const registrationResponse = await axios.get('/api/profile/registrations');
          const userRegistrations = registrationResponse.data;
          
          // Отладочная информация
          console.log('Event ID:', event.value.id);
          console.log('User Registrations:', userRegistrations);
          
          // Проверяем, есть ли среди регистраций пользователя регистрация на текущее событие
          const isRegistered = userRegistrations.some(registration => {
            // Преобразуем ID к числу для корректного сравнения
            const eventId = Number(registration.event?.id);
            const currentEventId = Number(event.value.id);
            const match = registration.event && eventId === currentEventId;
            
            // Отладочная информация для каждой регистрации
            console.log(`Checking registration:`, {
              registrationId: registration.id,
              eventId: registration.event?.id,
              eventIdType: typeof registration.event?.id,
              currentEventId: event.value.id,
              currentEventIdType: typeof event.value.id,
              match: match
            });
            
            return match;
          });
          
          console.log('Is Registered:', isRegistered);
          event.value.registered = isRegistered;
        } catch (error) {
          console.error('Error checking registration status:', error);
        }
      } catch (error) {
        console.error('Error checking authentication status:', error);
        isAuthenticated.value = false;
      }
    } else {
      isAuthenticated.value = false;
    }
    
    // Load reviews after event is loaded
    if (isAuthenticated.value) {
      loadReviews();
    }
  } catch (error) {
    console.error('Error loading event:', error);
  } finally {
    loading.value = false;
  }
};

const loadReviews = async () => {
  if (!isAuthenticated.value) return;
  
  try {
    loadingReviews.value = true;
    const response = await axios.get(`/api/events/${event.value.id}/reviews?page=${currentPage.value}`);
    reviews.value = response.data.data;
    
    // Проверяем, есть ли метаданные пагинации в ответе
    if (response.data.meta) {
      totalPages.value = response.data.meta.last_page || 1;
    } else {
      // Если метаданных нет, устанавливаем totalPages в 1
      totalPages.value = 1;
      // Выводим предупреждение в консоль для отладки
      console.warn('No pagination metadata found in response:', response.data);
    }
  } catch (error) {
    console.error('Error loading reviews:', error);
    reviews.value = [];
  } finally {
    loadingReviews.value = false;
  }
};

const submitReview = async () => {
  if (!newReview.value.rating || !newReview.value.comment) return;
  
  try {
    submittingReview.value = true;
    await axios.post(`/api/events/${event.value.id}/reviews`, {
      rating: newReview.value.rating,
      comment: newReview.value.comment
    });
    
    // Reset form and reload reviews
    newReview.value = { rating: 0, comment: '' };
    loadReviews();
  } catch (error) {
    console.error('Error submitting review:', error);
  } finally {
    submittingReview.value = false;
  }
};

const register = async () => {
  if (!isAuthenticated.value) {
    // Redirect to login page or show login modal
    alert(t('events.login_required'));
    return;
  }
  
  // Prevent duplicate registrations
  if (event.value.registered) {
    alert(t('events.already_registered'));
    return;
  }
  
  try {
    loading.value = true;
    await axios.post(`/api/events/${event.value.id}/register`);
    event.value.registered = true;
  } catch (error) {
    console.error('Error registering for event:', error);
    if (error.response && error.response.data && error.response.data.message) {
      alert(error.response.data.message);
      // If the error is about already being registered, update the UI state
      if (error.response.data.message.includes('already registered')) {
        event.value.registered = true;
      }
    } else {
      alert(t('events.registration_error'));
    }
  } finally {
    loading.value = false;
  }
};

const unregister = async () => {
  if (!isAuthenticated.value) {
    // Redirect to login page or show login modal
    alert(t('events.login_required'));
    return;
  }
  
  // Confirm unregistration
  if (!confirm(t('events.unregister_confirm'))) {
    return;
  }
  
  try {
    loading.value = true;
    await axios.post(`/api/events/${event.value.id}/unregister`);
    event.value.registered = false;
  } catch (error) {
    console.error('Error unregistering from event:', error);
    if (error.response && error.response.data && error.response.data.message) {
      alert(error.response.data.message);
    } else {
      alert(t('events.unregistration_error'));
    }
  } finally {
    loading.value = false;
  }
};

const openMap = () => {
  const query = encodeURIComponent(event.value.location);
  window.open(`https://www.google.com/maps/search/?api=1&query=${query}`, '_blank');
};

const addToCalendar = () => {
  const title = encodeURIComponent(event.value.title);
  const details = encodeURIComponent(event.value.description);
  const location = encodeURIComponent(event.value.location);
  const date = new Date(event.value.start_date + 'T' + event.value.start_time);
  const endDate = new Date(date.getTime() + 2 * 60 * 60 * 1000); // +2 hours

  const url = `https://www.google.com/calendar/render?action=TEMPLATE&text=${title}&details=${details}&location=${location}&dates=${date.toISOString().replace(/[-:]/g, '').split('.')[0]}Z/${endDate.toISOString().replace(/[-:]/g, '').split('.')[0]}Z`;
  window.open(url, '_blank');
};

const shareEvent = () => {
  shareDialog.value = true;
};

const copyShareUrl = async () => {
  try {
    await navigator.clipboard.writeText(shareUrl.value);
  } catch (error) {
    console.error('Error copying to clipboard:', error);
  }
};

onMounted(() => {
  loadEvent();
});
</script>

<style scoped>
.register-btn-wrap {
  display: flex;
  flex-direction: column;
  align-items: stretch;
  margin-top: 32px;
}
.register-btn {
  font-size: 1.5rem;
  padding: 24px 0;
  border-radius: 16px;
  font-weight: bold;
  box-shadow: 0 4px 16px rgba(60,60,60,0.10);
}
</style>
