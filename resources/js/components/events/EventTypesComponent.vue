<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <h1 class="text-h3 mb-6">{{ t('eventTypes.title') }}</h1>
      </v-col>
      
      <v-col
        v-for="type in eventTypes"
        :key="type.id"
        cols="12"
        sm="6"
        md="4"
      >
        <v-hover v-slot="{ isHovering, props }">
          <v-card
            v-bind="props"
            :elevation="isHovering ? 8 : 2"
            :to="{ name: 'events', query: { type: type.id }}"
            class="h-100 transition-swing"
          >
            <v-img
              :src="type.image"
              height="200"
              cover
            >
              <div class="fill-height d-flex align-end">
                <v-chip
                  :color="getEventTypeColor(type.id)"
                  class="ma-2"
                >
                  {{ type.eventCount }} {{ t('eventTypes.events') }}
                </v-chip>
              </div>
            </v-img>
            <v-card-item>
              <v-card-title>{{ type.name }}</v-card-title>
              <v-card-text>{{ type.description }}</v-card-text>
            </v-card-item>
          </v-card>
        </v-hover>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';
import { getEventTypeColor, eventTypeImages } from './eventData';

const { t } = useI18n();

// Данные о типах событий
const eventTypes = ref([]);
const loading = ref(true);

// Загрузка типов событий с API
const loadEventTypes = async () => {
  loading.value = true;
  try {
    // Здесь должен быть запрос к API для получения типов событий
    // Пока используем моковые данные
    eventTypes.value = [
      {
        id: 'cultural',
        name: t('categories.cultural'),
        description: t('categories.culturalDesc'),
        eventCount: 15,
        image: eventTypeImages.cultural[0]
      },
      {
        id: 'sports',
        name: t('categories.sports'),
        description: t('categories.sportsDesc'),
        eventCount: 8,
        image: eventTypeImages.sports[0]
      },
      {
        id: 'educational',
        name: t('categories.educational'),
        description: t('categories.educationalDesc'),
        eventCount: 12,
        image: eventTypeImages.educational[0]
      },
      {
        id: 'entertainment',
        name: t('categories.entertainment'),
        description: t('categories.entertainmentDesc'),
        eventCount: 20,
        image: eventTypeImages.entertainment[0]
      },
      {
        id: 'other',
        name: t('categories.other'),
        description: t('categories.otherDesc'),
        eventCount: 5,
        image: eventTypeImages.other[0]
      }
    ];
  } catch (error) {
    console.error('Error loading event types:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadEventTypes();
});
</script>

<style scoped>
.transition-swing {
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.5, 1);
}
</style>
