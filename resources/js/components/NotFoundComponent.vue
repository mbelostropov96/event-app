<template>
  <v-container class="fill-height">
    <v-row align="center" justify="center">
      <v-col cols="12" sm="8" md="6" class="text-center">
        <h1 class="text-h2 mb-4">404</h1>
        <p class="text-h5 mb-6">{{ t('errors.pageNotFound') }}</p>
        <v-btn
          color="primary"
          :to="{ name: 'home' }"
        >
          {{ t('navigation.backToHome') }}
        </v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>
