<template>
  <v-app>
    <v-navigation-drawer
      v-model="navigationDrawer"
      :rail="navigationRail"
      permanent
      @click="navigationRail = false"
    >
      <v-list-item
        :title="t('app.name')"
        class="header-item"
      >
        <template v-slot:prepend>
          <v-avatar
            color="primary"
            class="header-avatar"
          >
            <v-icon icon="mdi-city" color="white"></v-icon>
          </v-avatar>
        </template>
        <template v-slot:title>
          <div class="text-wrap">{{ t('app.name') }}</div>
        </template>
        <template v-slot:append>
          <v-btn
            variant="text"
            icon="mdi-chevron-left"
            @click.stop="navigationRail = !navigationRail"
          ></v-btn>
        </template>
      </v-list-item>

      <v-divider></v-divider>

      <v-list density="compact" nav>
        <v-list-item
          prepend-icon="mdi-home"
          :title="t('navigation.home')"
          :to="{ name: 'home' }"
          exact
        ></v-list-item>
        <v-list-item
          prepend-icon="mdi-calendar"
          :title="t('navigation.events')"
          :to="{ name: 'events' }"
        ></v-list-item>
        <v-list-item
          prepend-icon="mdi-ticket"
          :title="t('navigation.myRegistrations')"
          :to="{ name: 'my-registrations' }"
        ></v-list-item>
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
