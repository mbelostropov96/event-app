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
                  color="primary"
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
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

// Sample event types data - replace with actual API call
const eventTypes = ref([
  {
    id: 1,
    name: 'Conferences',
    description: 'Professional gatherings and conferences across various industries',
    eventCount: 15,
    image: 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=800'
  },
  {
    id: 2,
    name: 'Concerts',
    description: 'Live music performances and concerts',
    eventCount: 8,
    image: 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?auto=format&fit=crop&w=800'
  },
  // Add more event types as needed
]);
</script>

<style scoped>
.transition-swing {
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.5, 1);
}
</style>
