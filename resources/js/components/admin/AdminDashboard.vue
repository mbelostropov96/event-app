<template>
  <v-container>
    <h1 class="text-h3 mb-6">{{ $t('admin.dashboard.title') }}</h1>
    <v-row>
      <v-col cols="12" sm="4">
        <v-card
          class="mb-4"
          :to="{ name: 'admin-events' }"
          hover
        >
          <v-card-title class="text-h5">
            <v-icon icon="mdi-calendar" class="mr-2"></v-icon>
            {{ $t('admin.dashboard.events') }}
          </v-card-title>
          <v-card-text>
            <p>Добавление, редактирование и удаление мероприятий</p>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary"
              variant="text"
              :to="{ name: 'admin-events' }"
            >
              Перейти
              <v-icon icon="mdi-arrow-right" class="ml-1"></v-icon>
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
      
      <v-col cols="12" sm="4">
        <v-card
          class="mb-4"
          :to="{ name: 'admin-reviews' }"
          hover
        >
          <v-card-title class="text-h5">
            <v-icon icon="mdi-star" class="mr-2"></v-icon>
            {{ $t('admin.dashboard.reviews') }}
          </v-card-title>
          <v-card-text>
            <p>Модерация отзывов пользователей на мероприятия</p>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary"
              variant="text"
              :to="{ name: 'admin-reviews' }"
            >
              Перейти
              <v-icon icon="mdi-arrow-right" class="ml-1"></v-icon>
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
      
      <v-col cols="12" sm="4">
        <v-card
          class="mb-4"
          :to="{ name: 'admin-users' }"
          hover
        >
          <v-card-title class="text-h5">
            <v-icon icon="mdi-account-group" class="mr-2"></v-icon>
            {{ $t('admin.dashboard.users') }}
          </v-card-title>
          <v-card-text>
            <p>Просмотр информации о пользователях системы</p>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary"
              variant="text"
              :to="{ name: 'admin-users' }"
            >
              Перейти
              <v-icon icon="mdi-arrow-right" class="ml-1"></v-icon>
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
    
    <router-view></router-view>
  </v-container>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const { t } = useI18n();
const router = useRouter();

onMounted(async () => {
  try {
    const response = await axios.get('/api/user');
    if (response.data.role !== 'admin') {
      router.push({ name: 'home' });
    }
  } catch (error) {
    console.error('Error checking admin status:', error);
    router.push({ name: 'login' });
  }
});
</script>
