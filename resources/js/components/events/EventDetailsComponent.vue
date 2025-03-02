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
        </v-col>
  
        <!-- Action Card -->
        <v-col cols="12" md="4">
          <v-card class="sticky-top" style="top: 24px">
            <v-card-text>
              <v-btn
                block
                color="primary"
                size="large"
                :loading="loading"
                :disabled="event.registered"
                @click="register"
              >
                <v-icon start icon="mdi-check-circle"></v-icon>
                {{ event.registered ? t('events.registered') : t('events.register') }}
              </v-btn>
              <!--
              <v-divider class="my-4"></v-divider>
              <div class="d-flex justify-space-between mb-2">
                <v-btn
                  variant="text"
                  prepend-icon="mdi-calendar-plus"
                  @click="addToCalendar"
                >
                  {{ t('events.actions.addToCalendar') }}
                </v-btn>
                <v-btn
                  variant="text"
                  prepend-icon="mdi-share-variant"
                  @click="shareEvent"
                >
                  {{ t('events.actions.share') }}
                </v-btn>
              </div>
              -->
            </v-card-text>
          </v-card>
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
          <v-card class="sticky-top" style="top: 24px">
            <v-card-text>
              <v-skeleton-loader
                type="button"
                class="mb-4"
              ></v-skeleton-loader>
            </v-card-text>
          </v-card>
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
import { ref, onMounted } from 'vue';
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

const loadEvent = async () => {
  try {
    loading.value = true;
    const response = await axios.get(`/api/events/${route.params.id}`);
    event.value = response.data;
    shareUrl.value = window.location.href;
  } catch (error) {
    console.error('Error loading event:', error);
  } finally {
    loading.value = false;
  }
};

const register = async () => {
  try {
    loading.value = true;
    await axios.post(`/api/events/${event.value.id}/register`);
    event.value.registered = true;
  } catch (error) {
    console.error('Error registering for event:', error);
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
.sticky-top {
  position: sticky;
  top: 24px;
}
</style>
