<template>
  <v-app>
    <v-navigation-drawer
      v-model="navigationDrawer"
      :rail="navigationRail"
      permanent
      @click="navigationRail = false"
    >
      <v-list density="compact" class="pa-0">
        <v-list-item
          class="header-item pa-4"
        >
          <v-avatar
            color="primary"
            class="header-avatar mr-3"
          >
            <v-icon icon="mdi-city" color="white"></v-icon>
          </v-avatar>
          <v-list-item-title class="text-wrap">{{ t('app.name') }}</v-list-item-title>
          <template #append>
            <v-btn
              variant="text"
              icon="mdi-chevron-left"
              @click.stop="navigationRail = !navigationRail"
            ></v-btn>
          </template>
        </v-list-item>
      </v-list>

      <v-divider></v-divider>

      <v-list density="compact" nav>
        <v-list-item
          prepend-icon="mdi-home"
          :to="{ name: 'home' }"
          exact
        >
          <v-list-item-title>{{ t('navigation.home') }}</v-list-item-title>
        </v-list-item>
        
        <v-list-item
          prepend-icon="mdi-calendar"
          :to="{ name: 'events' }"
        >
          <v-list-item-title>{{ t('navigation.events') }}</v-list-item-title>
        </v-list-item>

        <v-list-item
          v-if="isAuthenticated"
          prepend-icon="mdi-star"
          :to="{ name: 'reviews' }"
        >
          <v-list-item-title>{{ t('navigation.reviews') }}</v-list-item-title>
        </v-list-item>

        <v-list-item
          v-if="isAuthenticated"
          prepend-icon="mdi-ticket"
          :to="{ name: 'my-registrations' }"
        >
          <v-list-item-title>{{ t('navigation.myRegistrations') }}</v-list-item-title>
        </v-list-item>

        <!-- Admin section -->
        <template v-if="isAdmin">
          <v-divider class="my-2"></v-divider>
          <v-list-subheader>Администрирование</v-list-subheader>
          
          <v-list-item
            prepend-icon="mdi-view-dashboard"
            :to="{ name: 'admin-dashboard' }"
          >
            <v-list-item-title>Панель управления</v-list-item-title>
          </v-list-item>
        </template>

        <v-divider class="my-2"></v-divider>
        
        <v-list-item
          prepend-icon="mdi-phone"
          :to="{ name: 'contacts' }"
        >
          <v-list-item-title>{{ t('navigation.contacts') }}</v-list-item-title>
        </v-list-item>

        <v-list-item
          prepend-icon="mdi-information"
          :to="{ name: 'about' }"
        >
          <v-list-item-title>{{ t('navigation.about') }}</v-list-item-title>
        </v-list-item>
      </v-list>

      <!-- Authentication section -->
      <template v-slot:append>
        <v-divider></v-divider>
        <v-list density="compact">
          <template v-if="isAuthenticated">
            <v-list-item>
              <v-list-item-title>{{ user.first_name }} {{ user.last_name }}</v-list-item-title>
              <v-list-item-subtitle>{{ user.email }}</v-list-item-subtitle>
            </v-list-item>
            <v-list-item
              prepend-icon="mdi-logout"
              @click="logout"
              :disabled="loggingOut"
            >
              <v-list-item-title>{{ t('auth.logout') }}</v-list-item-title>
            </v-list-item>
          </template>
          <template v-else>
            <v-list-item
              prepend-icon="mdi-login"
              :to="{ name: 'login' }"
            >
              <v-list-item-title>{{ t('auth.login') }}</v-list-item-title>
            </v-list-item>
            <v-list-item
              prepend-icon="mdi-account-plus"
              :to="{ name: 'register' }"
            >
              <v-list-item-title>{{ t('auth.register') }}</v-list-item-title>
            </v-list-item>
          </template>
        </v-list>
      </template>
    </v-navigation-drawer>

    <v-main>
      <router-view></router-view>
    </v-main>

    <v-footer app class="footer">
      <v-container>
        <div class="d-flex justify-center align-center">
          <span class="text-caption">{{ t('app.footer.copyright', { year: new Date().getFullYear() }) }}</span>
        </div>
      </v-container>
    </v-footer>
  </v-app>
</template>

<script setup>
import { ref, onMounted, computed, onUnmounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { eventBus, authState } from './eventBus';

const { t } = useI18n();
const router = useRouter();
const navigationDrawer = ref(true);
const navigationRail = ref(false);
const user = ref(null);
const loggingOut = ref(false);

const isAuthenticated = computed(() => !!user.value);
const isAdmin = computed(() => user.value && user.value.role === 'admin');

// Слушатель событий авторизации
let unsubscribe;

const checkAuth = async () => {
  // Check if token exists in localStorage
  const token = localStorage.getItem('token');
  
  if (token) {
    // Set default Authorization header for all requests
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    
    try {
      const response = await axios.get('/api/user');
      user.value = response.data;
      // Обновляем глобальное состояние авторизации
      authState.setUser(response.data);
    } catch (error) {
      console.error('Error fetching user data:', error);
      // If token is invalid, remove it
      localStorage.removeItem('token');
      user.value = null;
      authState.clearUser();
    }
  } else {
    user.value = null;
    authState.clearUser();
  }
};

const logout = async () => {
  loggingOut.value = true;
  
  try {
    await axios.post('/api/logout');
    // Remove token from localStorage
    localStorage.removeItem('token');
    // Remove Authorization header
    delete axios.defaults.headers.common['Authorization'];
    // Clear user data
    user.value = null;
    // Обновляем глобальное состояние авторизации
    authState.clearUser();
    // Redirect to home page
    router.push({ name: 'home' });
  } catch (error) {
    console.error('Error logging out:', error);
  } finally {
    loggingOut.value = false;
  }
};

onMounted(() => {
  checkAuth();
  
  // Подписываемся на событие изменения состояния авторизации
  unsubscribe = eventBus.on('auth:changed', (userData) => {
    user.value = userData;
  });
});

onUnmounted(() => {
  // Отписываемся от события при удалении компонента
  if (unsubscribe) {
    unsubscribe();
  }
});
</script>

<style scoped>
.header-item {
  background-color: rgb(var(--v-theme-primary));
  color: white;
  min-height: 64px;
  padding: 12px !important;
}

.header-avatar {
  border: 2px solid white;
}

.text-wrap {
  white-space: normal;
  word-break: break-word;
  line-height: 1.2;
}

.footer {
  background-color: rgb(var(--v-theme-primary));
  color: white;
  min-height: 48px;
}
</style>
