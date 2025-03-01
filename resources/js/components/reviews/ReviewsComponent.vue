<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <h1 class="text-h3 mb-6">{{ t('reviews.title') }}</h1>
      </v-col>

      <v-col cols="12">
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
                    color="grey-lighten-1"
                    size="large"
                  >
                    <v-img
                      :src="review.userAvatar"
                      alt="User Avatar"
                    ></v-img>
                  </v-avatar>
                </template>
                <v-card-title>
                  {{ review.userName }}
                  <span class="text-caption ml-2 text-grey">
                    {{ formatDate(review.date) }}
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
                  <div class="text-primary mt-1">
                    {{ review.eventTitle }}
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
                    :to="{ name: 'event-detail', params: { id: review.eventId }}"
                  >
                    {{ t('reviews.viewEvent') }}
                  </v-btn>
                </v-card-actions>
              </v-card-item>
            </v-card>
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12" class="text-center">
            <v-pagination
              v-model="currentPage"
              :length="totalPages"
              :total-visible="7"
              rounded="circle"
            ></v-pagination>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const currentPage = ref(1);
const totalPages = ref(5);

// Sample reviews data - replace with actual API call
const reviews = ref([
  {
    id: 1,
    userName: 'John Doe',
    userAvatar: 'https://i.pravatar.cc/150?img=1',
    rating: 4.5,
    comment: 'Amazing event! The speakers were incredibly knowledgeable and the networking opportunities were fantastic.',
    date: '2025-02-20',
    eventId: 1,
    eventTitle: 'Tech Conference 2025'
  },
  {
    id: 2,
    userName: 'Jane Smith',
    userAvatar: 'https://i.pravatar.cc/150?img=2',
    rating: 5,
    comment: 'One of the best concerts I\'ve ever attended. The atmosphere was electric!',
    date: '2025-02-19',
    eventId: 2,
    eventTitle: 'Music Festival'
  },
  // Add more reviews as needed
]);

const formatDate = (date) => {
  return new Date(date).toLocaleDateString();
};
</script>
