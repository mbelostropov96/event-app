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

        <!-- Временно скрыто
        <v-list-item
          prepend-icon="mdi-calendar-month"
          :to="{ name: 'calendar' }"
        >
          <v-list-item-title>{{ t('navigation.calendar') }}</v-list-item-title>
        </v-list-item>

        <v-list-item
          prepend-icon="mdi-shape"
          :to="{ name: 'event-types' }"
        >
          <v-list-item-title>{{ t('navigation.eventTypes') }}</v-list-item-title>
        </v-list-item>

        <v-list-item
          prepend-icon="mdi-star"
          :to="{ name: 'reviews' }"
        >
          <v-list-item-title>{{ t('navigation.reviews') }}</v-list-item-title>
        </v-list-item>

        <v-list-item
          prepend-icon="mdi-ticket"
          :to="{ name: 'my-registrations' }"
        >
          <v-list-item-title>{{ t('navigation.myRegistrations') }}</v-list-item-title>
        </v-list-item>

        <v-divider class="my-2"></v-divider>
        -->
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
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const navigationDrawer = ref(true);
const navigationRail = ref(false);
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
