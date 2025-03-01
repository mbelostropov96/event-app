<template>
  <div>
    <v-container>
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

          <!-- Organizer -->
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
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>

    <!-- Share Dialog -->
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
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { useRoute } from 'vue-router';
import axios from 'axios';

const { t } = useI18n();
const route = useRoute();

const event = ref({});
const loading = ref(false);
const shareDialog = ref(false);
const shareUrl = ref('');

// Import event type images and colors from EventListComponent
const eventTypeImages = {
  cultural: [
    'https://images.unsplash.com/photo-1513364776144-60967b0f800f?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1507676184212-d03ab07a01bf?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1526478806334-5fd488fcaabc?auto=format&fit=crop&w=800&q=80',
  ],
  sports: [
    'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1517649763962-0c623066013b?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1526676338312-3b988d6aa25b?auto=format&fit=crop&w=800&q=80',
  ],
  educational: [
    'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=800&q=80',
  ],
  entertainment: [
    'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=800&q=80',
  ],
  other: [
    'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1527192491265-7e15c55b1ed2?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1527192491265-7e15c55b1ed2?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&w=800&q=80',
  ],
};

const getEventTypeColor = (type) => {
  const colors = {
    cultural: 'purple',
    sports: 'green',
    educational: 'blue',
    entertainment: 'pink',
    other: 'grey'
  };
  return colors[type] || 'grey';
};

const getEventImage = (event) => {
  if (event.image) return event.image;
  
  const typeImages = eventTypeImages[event.type] || eventTypeImages.other;
  const randomIndex = Math.floor(Math.random() * typeImages.length);
  return typeImages[randomIndex];
};

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('ru-RU', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
};

const formatTime = (time) => {
  if (!time) return '';
  const [hours, minutes] = time.split(':');
  return `${hours}:${minutes}`;
};

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
