<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <h1 class="text-h3 mb-6">{{ t('about.title') }}</h1>
      </v-col>

      <v-col cols="12" md="8">
        <v-card class="mb-6">
          <v-card-text>
            <div class="text-h5 mb-4">{{ t('about.mission.title') }}</div>
            <p class="text-body-1">{{ t('about.mission.description') }}</p>
          </v-card-text>
        </v-card>

        <v-card class="mb-6">
          <v-card-text>
            <div class="text-h5 mb-4">{{ t('about.features.title') }}</div>
            <v-row>
              <v-col
                v-for="feature in features"
                :key="feature.title"
                cols="12"
                sm="6"
              >
                <div class="d-flex align-start mb-6">
                  <v-icon
                    :icon="feature.icon"
                    color="primary"
                    size="large"
                    class="mr-4"
                  ></v-icon>
                  <div>
                    <div class="text-h6 mb-1">{{ feature.title }}</div>
                    <div class="text-body-2">{{ feature.description }}</div>
                  </div>
                </div>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="4">
        <v-card class="mb-6">
          <v-card-text>
            <div class="text-h5 mb-4">{{ t('about.stats.title') }}</div>
            <div
              v-for="stat in stats"
              :key="stat.label"
              class="text-center mb-6"
            >
              <div class="text-h4 font-weight-bold primary--text">
                {{ stat.value }}
              </div>
              <div class="text-body-1">{{ stat.label }}</div>
            </div>
          </v-card-text>
        </v-card>

        <v-card>
          <v-card-text>
            <div class="text-h5 mb-4">{{ t('about.team.title') }}</div>
            <div class="text-body-1 mb-4">
              {{ t('about.team.description') }}
            </div>
            <v-btn
              color="primary"
              block
              href="mailto:careers@eventapp.com"
            >
              {{ t('about.team.joinUs') }}
            </v-btn>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const features = ref([
  {
    icon: 'mdi-calendar-check',
    title: t('about.features.items.events'),
    description: t('about.features.items.eventsDesc')
  },
  {
    icon: 'mdi-account-group',
    title: t('about.features.items.community'),
    description: t('about.features.items.communityDesc')
  },
  {
    icon: 'mdi-ticket-confirmation',
    title: t('about.features.items.booking'),
    description: t('about.features.items.bookingDesc')
  },
  {
    icon: 'mdi-star',
    title: t('about.features.items.reviews'),
    description: t('about.features.items.reviewsDesc')
  }
]);

const stats = ref([
  {
    value: '10,000+',
    label: t('about.stats.events')
  },
  {
    value: '50,000+',
    label: t('about.stats.users')
  },
  {
    value: '100+',
    label: t('about.stats.organizers')
  }
]);
</script>
