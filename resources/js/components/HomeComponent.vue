<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <v-card class="mx-auto" max-width="1200">
          <v-img
            src="https://images.unsplash.com/photo-1508997449629-303059a039c0?auto=format&fit=crop&w=1200&q=80"
            height="400"
            cover
            class="bg-grey-darken-4"
          >
            <div class="d-flex flex-column fill-height justify-center align-center text-white" style="background: rgba(0, 0, 0, 0.4)">
              <div class="text-h3 font-weight-bold text-center mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5)">
                {{ t('home.welcome.title') }}
              </div>
              <div class="text-h6 text-center mx-4" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5); max-width: 800px">
                {{ t('home.welcome.description') }}
              </div>
              <v-btn
                color="primary"
                size="x-large"
                variant="elevated"
                to="/events"
                class="mt-8 px-8"
              >
                {{ t('home.actions.browseEvents') }}
                <v-icon end icon="mdi-arrow-right" class="ml-2"></v-icon>
              </v-btn>
            </div>
          </v-img>
        </v-card>
      </v-col>
    </v-row>

    <v-row class="mt-12">
      <v-col cols="12" md="4">
        <v-hover>
          <template v-slot:default="{ isHovering, props }">
            <v-card
              v-bind="props"
              :elevation="isHovering ? 12 : 2"
              class="mx-auto h-100"
            >
              <v-card-item>
                <v-card-title class="d-flex align-center mb-4">
                  <v-icon size="32" color="primary" class="mr-4">mdi-calendar-multiple</v-icon>
                  <span class="text-h5">{{ t('home.features.upcoming.title') }}</span>
                </v-card-title>
                <v-card-text class="text-body-1">
                  {{ t('home.features.upcoming.description') }}
                </v-card-text>
              </v-card-item>
            </v-card>
          </template>
        </v-hover>
      </v-col>

      <v-col cols="12" md="4">
        <v-hover>
          <template v-slot:default="{ isHovering, props }">
            <v-card
              v-bind="props"
              :elevation="isHovering ? 12 : 2"
              class="mx-auto h-100"
            >
              <v-card-item>
                <v-card-title class="d-flex align-center mb-4">
                  <v-icon size="32" color="primary" class="mr-4">mdi-star</v-icon>
                  <span class="text-h5">{{ t('home.features.community.title') }}</span>
                </v-card-title>
                <v-card-text class="text-body-1">
                  {{ t('home.features.community.description') }}
                </v-card-text>
              </v-card-item>
            </v-card>
          </template>
        </v-hover>
      </v-col>

      <v-col cols="12" md="4">
        <v-hover>
          <template v-slot:default="{ isHovering, props }">
            <v-card
              v-bind="props"
              :elevation="isHovering ? 12 : 2"
              class="mx-auto h-100"
            >
              <v-card-item>
                <v-card-title class="d-flex align-center mb-4">
                  <v-icon size="32" color="primary" class="mr-4">mdi-devices</v-icon>
                  <span class="text-h5">{{ t('home.features.featured.title') }}</span>
                </v-card-title>
                <v-card-text class="text-body-1">
                  {{ t('home.features.featured.description') }}
                </v-card-text>
              </v-card-item>
            </v-card>
          </template>
        </v-hover>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<style scoped>
.h-100 {
  height: 100%;
}
</style>
