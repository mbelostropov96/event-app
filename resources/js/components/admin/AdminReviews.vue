<template>
  <div>
    <h2 class="text-h4 mb-6">Модерация отзывов</h2>

    <!-- Loading State -->
    <div v-if="loading" class="text-center my-8">
      <v-progress-circular indeterminate color="primary"></v-progress-circular>
    </div>

    <!-- Reviews Table -->
    <v-card v-else>
      <v-data-table
        :headers="headers"
        :items="reviews"
        :items-per-page="10"
        class="elevation-1"
      >
        <template v-slot:item.user="{ item }">
          {{ item.user.first_name }} {{ item.user.last_name }}
        </template>

        <template v-slot:item.rating="{ item }">
          <v-rating
            v-model="item.rating"
            color="warning"
            density="compact"
            half-increments
            readonly
            size="small"
          ></v-rating>
        </template>

        <template v-slot:item.status="{ item }">
          <v-chip
            :color="getStatusColor(item.status)"
            text-color="white"
            size="small"
          >
            {{ t(`reviews.status.${item.status}`) }}
          </v-chip>
        </template>

        <template v-slot:item.created_at="{ item }">
          {{ formatDate(item.created_at) }}
        </template>

        <template v-slot:item.actions="{ item }">
          <v-btn
            v-if="item.status === 'pending'"
            icon="mdi-check"
            size="small"
            color="success"
            variant="text"
            @click="moderateReview(item.id, 'approved')"
            :loading="item.id === moderatingId"
          ></v-btn>
          <v-btn
            v-if="item.status === 'pending'"
            icon="mdi-close"
            size="small"
            color="error"
            variant="text"
            @click="moderateReview(item.id, 'rejected')"
            :loading="item.id === moderatingId"
          ></v-btn>
          <v-btn
            icon="mdi-eye"
            size="small"
            color="primary"
            variant="text"
            @click="viewReview(item)"
          ></v-btn>
          <v-btn
            icon="mdi-delete"
            size="small"
            color="error"
            variant="text"
            @click="confirmDeleteReview(item)"
            :loading="item.id === deletingId"
          ></v-btn>
        </template>
      </v-data-table>
    </v-card>

    <!-- Review Detail Dialog -->
    <v-dialog v-model="reviewDialog" max-width="600px">
      <v-card v-if="selectedReview">
        <v-card-title class="text-h5">
          Отзыв на мероприятие "{{ selectedReview.event?.title }}"
        </v-card-title>

        <v-card-text>
          <v-row>
            <v-col cols="12">
              <p class="text-subtitle-1">
                <v-icon icon="mdi-account" class="mr-1"></v-icon>
                {{ selectedReview.user?.first_name }} {{ selectedReview.user?.last_name }}
              </p>
              <p class="text-caption text-grey">
                <v-icon icon="mdi-calendar" size="small" class="mr-1"></v-icon>
                {{ formatDate(selectedReview.created_at) }}
              </p>
            </v-col>

            <v-col cols="12">
              <v-rating
                v-model="selectedReview.rating"
                color="warning"
                half-increments
                readonly
              ></v-rating>
            </v-col>

            <v-col cols="12">
              <v-card variant="outlined" class="pa-3">
                <p>{{ selectedReview.comment }}</p>
              </v-card>
            </v-col>

            <v-col cols="12">
              <v-chip
                :color="getStatusColor(selectedReview.status)"
                text-color="white"
              >
                {{ t(`reviews.status.${selectedReview.status}`) }}
              </v-chip>
            </v-col>
          </v-row>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="grey"
            variant="text"
            @click="reviewDialog = false"
          >
            {{ t('admin.reviews.close') }}
          </v-btn>
          <v-btn
            v-if="selectedReview.status === 'pending'"
            color="success"
            variant="text"
            @click="moderateReview(selectedReview.id, 'approved'); reviewDialog = false"
            :loading="selectedReview.id === moderatingId"
          >
            {{ t('reviews.actions.approve') }}
          </v-btn>
          <v-btn
            v-if="selectedReview.status === 'pending'"
            color="error"
            variant="text"
            @click="moderateReview(selectedReview.id, 'rejected'); reviewDialog = false"
            :loading="selectedReview.id === moderatingId"
          >
            {{ t('reviews.actions.reject') }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Delete Review Dialog -->
    <v-dialog v-model="deleteDialog" max-width="400px">
      <v-card>
        <v-card-title class="text-h5">
          {{ t('reviews.deleteConfirm.title') }}
        </v-card-title>

        <v-card-text>
          {{ t('reviews.deleteConfirm.message') }}
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="grey"
            variant="text"
            @click="deleteDialog = false"
          >
            {{ t('reviews.actions.cancel') }}
          </v-btn>
          <v-btn
            color="error"
            variant="text"
            @click="deleteReview()"
            :loading="deletingId !== null"
          >
            {{ t('reviews.actions.delete') }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

// Data
const reviews = ref([]);
const loading = ref(true);
const reviewDialog = ref(false);
const selectedReview = ref(null);
const moderatingId = ref(null);
const deletingId = ref(null);
const deleteDialog = ref(false);

const headers = [
  { title: t('admin.reviews.headers.id'), key: 'id', sortable: true },
  { title: t('admin.reviews.headers.user'), key: 'user', sortable: true },
  { title: t('admin.reviews.headers.event'), key: 'event.title', sortable: true },
  { title: t('admin.reviews.headers.rating'), key: 'rating', sortable: true },
  { title: t('admin.reviews.headers.status'), key: 'status', sortable: true },
  { title: t('admin.reviews.headers.date'), key: 'created_at', sortable: true },
  { title: t('admin.reviews.headers.actions'), key: 'actions', sortable: false }
];

// Methods
const fetchReviews = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/admin/reviews');
    reviews.value = response.data;
  } catch (error) {
    console.error('Error fetching reviews:', error);
  } finally {
    loading.value = false;
  }
};

const viewReview = (review) => {
  selectedReview.value = review;
  reviewDialog.value = true;
};

const moderateReview = async (id, status) => {
  moderatingId.value = id;
  try {
    await axios.post('/api/admin/reviews/moderate', {
      id,
      status
    });
    // Update the review status in the list
    const index = reviews.value.findIndex(r => r.id === id);
    if (index !== -1) {
      reviews.value[index].status = status;
    }
    // If the selected review is being moderated, update its status too
    if (selectedReview.value && selectedReview.value.id === id) {
      selectedReview.value.status = status;
    }
  } catch (error) {
    console.error('Error moderating review:', error);
  } finally {
    moderatingId.value = null;
  }
};

const confirmDeleteReview = (review) => {
  selectedReview.value = review;
  deleteDialog.value = true;
};

const deleteReview = async () => {
  deletingId.value = selectedReview.value.id;
  try {
    await axios.delete(`/api/admin/reviews/${selectedReview.value.id}`);
    // Remove the review from the list
    reviews.value = reviews.value.filter(r => r.id !== selectedReview.value.id);
  } catch (error) {
    console.error('Error deleting review:', error);
  } finally {
    deletingId.value = null;
    deleteDialog.value = false;
  }
};

const getStatusColor = (status) => {
  const colors = {
    pending: 'warning',
    approved: 'success',
    rejected: 'error'
  };
  return colors[status] || 'grey';
};

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('ru-RU', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
};

// Lifecycle
onMounted(() => {
  fetchReviews();
});
</script>
