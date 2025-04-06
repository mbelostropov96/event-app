<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <h1 class="text-h3 mb-6">{{ t('reviews.title') }}</h1>
      </v-col>

      <!-- Loading State -->
      <v-col v-if="loading" cols="12" class="text-center">
        <v-progress-circular indeterminate color="primary"></v-progress-circular>
      </v-col>

      <!-- Empty State -->
      <v-col v-else-if="reviews.length === 0" cols="12" class="text-center">
        <v-icon icon="mdi-star-outline" size="64" color="grey-lighten-1"></v-icon>
        <h3 class="text-h6 mt-4 text-grey-darken-1">{{ t('reviews.empty.title') }}</h3>
        <p class="text-body-2 text-grey-darken-1">{{ t('reviews.empty.description') }}</p>
      </v-col>

      <!-- Reviews List -->
      <v-col v-else cols="12">
        <v-row>
          <v-col
            v-for="review in reviews"
            :key="review.id"
            cols="12"
            md="6"
          >
            <v-card class="mb-4">
              <v-card-item>
                <template v-slot:prepend>
                  <v-avatar
                    color="primary"
                    size="large"
                  >
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
                  <div class="d-flex align-center mt-1">
                    <div class="text-primary">
                      {{ review.event.title }}
                    </div>
                    <v-chip
                      class="ml-2"
                      size="x-small"
                      :color="getStatusColor(review.status)"
                      :text="getStatusText(review.status)"
                      density="compact"
                    ></v-chip>
                  </div>
                </v-card-subtitle>
                <v-card-text>
                  {{ review.comment }}
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn
                    variant="text"
                    color="primary"
                    :to="{ name: 'event-details', params: { id: review.event.id }}"
                  >
                    {{ t('reviews.viewEvent') }}
                  </v-btn>
                  <v-btn
                    v-if="isUserReview(review)"
                    variant="text"
                    color="error"
                    @click="confirmDeleteReview(review)"
                    :loading="deletingReview === review.id"
                  >
                    {{ t('reviews.delete') }}
                  </v-btn>
                </v-card-actions>
              </v-card-item>
            </v-card>
          </v-col>
        </v-row>

        <!-- Pagination -->
        <v-row v-if="totalPages > 1">
          <v-col cols="12" class="text-center">
            <v-pagination
              v-model="currentPage"
              :length="totalPages"
              :total-visible="7"
              rounded="circle"
              @update:modelValue="loadReviews"
            ></v-pagination>
          </v-col>
        </v-row>
      </v-col>
    </v-row>

    <!-- Delete Confirmation Dialog -->
    <v-dialog v-model="deleteDialog" max-width="500">
      <v-card>
        <v-card-title>{{ t('reviews.deleteConfirm.title') }}</v-card-title>
        <v-card-text>{{ t('reviews.deleteConfirm.message') }}</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey" variant="text" @click="deleteDialog = false">
            {{ t('common.cancel') }}
          </v-btn>
          <v-btn color="error" variant="text" @click="deleteReview" :loading="deletingReview !== null">
            {{ t('reviews.delete') }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const { t } = useI18n();

// State
const reviews = ref([]);
const loading = ref(true);
const currentPage = ref(1);
const totalPages = ref(1);
const deleteDialog = ref(false);
const deletingReview = ref(null);
const reviewToDelete = ref(null);
const currentUserId = ref(null);

// Methods
const loadReviews = async () => {
  loading.value = true;
  try {
    // Get current user info first
    try {
      const userResponse = await axios.get('/api/user');
      currentUserId.value = userResponse.data.id;
    } catch (error) {
      console.error('Error getting user info:', error);
      currentUserId.value = null;
    }
    
    // Get user reviews
    const response = await axios.get(`/api/profile/reviews?page=${currentPage.value}`);
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
    loading.value = false;
  }
};

const isUserReview = (review) => {
  return currentUserId.value && review.user && review.user.id === currentUserId.value;
};

const confirmDeleteReview = (review) => {
  reviewToDelete.value = review;
  deleteDialog.value = true;
};

const deleteReview = async () => {
  if (!reviewToDelete.value) return;
  
  deletingReview.value = reviewToDelete.value.id;
  try {
    await axios.delete(`/api/profile/reviews/${reviewToDelete.value.id}`);
    reviews.value = reviews.value.filter(r => r.id !== reviewToDelete.value.id);
    deleteDialog.value = false;
  } catch (error) {
    console.error('Error deleting review:', error);
  } finally {
    deletingReview.value = null;
    reviewToDelete.value = null;
  }
};

const getStatusColor = (status) => {
  switch (status) {
    case 'approved':
      return 'success';
    case 'pending':
      return 'warning';
    case 'rejected':
      return 'error';
    default:
      return 'grey';
  }
};

const getStatusText = (status) => {
  switch (status) {
    case 'approved':
      return t('reviews.status.published');
    case 'pending':
      return t('reviews.status.pending');
    case 'rejected':
      return t('reviews.status.rejected');
    default:
      return status; // Возвращаем сам статус, если он не соответствует известным значениям
  }
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('ru-RU', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

// Lifecycle
onMounted(() => {
  loadReviews();
});
</script>
