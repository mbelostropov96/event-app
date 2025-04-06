<template>
  <div>
    <h2 class="text-h4 mb-6">Управление пользователями</h2>

    <!-- Loading State -->
    <div v-if="loading" class="text-center my-8">
      <v-progress-circular indeterminate color="primary"></v-progress-circular>
    </div>

    <!-- Users Table -->
    <v-card v-else>
      <v-data-table
        :headers="headers"
        :items="users"
        :items-per-page="10"
        class="elevation-1"
      >
        <template v-slot:item.name="{ item }">
          {{ item.first_name }} {{ item.last_name }}
        </template>

        <template v-slot:item.role="{ item }">
          <v-chip
            :color="item.role === 'admin' ? 'purple' : 'blue'"
            text-color="white"
            size="small"
          >
            {{ item.role }}
          </v-chip>
        </template>

        <template v-slot:item.created_at="{ item }">
          {{ formatDate(item.created_at) }}
        </template>

        <template v-slot:item.actions="{ item }">
          <v-btn
            icon="mdi-eye"
            size="small"
            color="primary"
            variant="text"
            @click="viewUserDetails(item)"
          ></v-btn>
        </template>
      </v-data-table>
    </v-card>

    <!-- User Detail Dialog -->
    <v-dialog v-model="userDialog" max-width="600px">
      <v-card v-if="selectedUser">
        <v-card-title class="text-h5">
          Информация о пользователе
        </v-card-title>

        <v-card-text>
          <v-row>
            <v-col cols="12" md="6">
              <v-list>
                <v-list-item>
                  <template v-slot:prepend>
                    <v-avatar color="primary">
                      <v-icon color="white">mdi-account</v-icon>
                    </v-avatar>
                  </template>
                  <v-list-item-title class="text-h6">
                    {{ selectedUser.first_name }} {{ selectedUser.middle_name }} {{ selectedUser.last_name }}
                  </v-list-item-title>
                  <v-list-item-subtitle>
                    {{ selectedUser.email }}
                  </v-list-item-subtitle>
                </v-list-item>

                <v-divider class="my-2"></v-divider>

                <v-list-item>
                  <v-list-item-title>Роль</v-list-item-title>
                  <template v-slot:append>
                    <v-chip
                      :color="selectedUser.role === 'admin' ? 'purple' : 'blue'"
                      text-color="white"
                    >
                      {{ selectedUser.role }}
                    </v-chip>
                  </template>
                </v-list-item>

                <v-list-item>
                  <v-list-item-title>Дата регистрации</v-list-item-title>
                  <template v-slot:append>
                    {{ formatDate(selectedUser.created_at) }}
                  </template>
                </v-list-item>
              </v-list>
            </v-col>

            <v-col cols="12" md="6">
              <v-card variant="outlined" class="mb-4">
                <v-card-title class="text-subtitle-1">
                  <v-icon icon="mdi-ticket" class="mr-2"></v-icon>
                  Регистрации на мероприятия
                </v-card-title>
                <v-card-text>
                  <div v-if="userStats.registrations_count > 0">
                    <p>Всего регистраций: {{ userStats.registrations_count }}</p>
                  </div>
                  <div v-else class="text-center py-4 text-grey">
                    Нет регистраций на мероприятия
                  </div>
                </v-card-text>
              </v-card>

              <v-card variant="outlined">
                <v-card-title class="text-subtitle-1">
                  <v-icon icon="mdi-star" class="mr-2"></v-icon>
                  Отзывы
                </v-card-title>
                <v-card-text>
                  <div v-if="userStats.reviews_count > 0">
                    <p>Всего отзывов: {{ userStats.reviews_count }}</p>
                    <p>Средняя оценка: {{ userStats.average_rating.toFixed(1) }}</p>
                  </div>
                  <div v-else class="text-center py-4 text-grey">
                    Нет отзывов
                  </div>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="grey"
            variant="text"
            @click="userDialog = false"
          >
            Закрыть
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Data
const users = ref([]);
const loading = ref(true);
const userDialog = ref(false);
const selectedUser = ref(null);
const userStats = ref({
  registrations_count: 0,
  reviews_count: 0,
  average_rating: 0
});

const headers = [
  { title: 'ID', key: 'id', sortable: true },
  { title: 'Имя', key: 'name', sortable: true },
  { title: 'Email', key: 'email', sortable: true },
  { title: 'Роль', key: 'role', sortable: true },
  { title: 'Дата регистрации', key: 'created_at', sortable: true },
  { title: 'Действия', key: 'actions', sortable: false }
];

// Methods
const fetchUsers = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/admin/users');
    users.value = response.data;
  } catch (error) {
    console.error('Error fetching users:', error);
  } finally {
    loading.value = false;
  }
};

const viewUserDetails = async (user) => {
  selectedUser.value = user;
  userDialog.value = true;
  
  try {
    const response = await axios.get(`/api/admin/users/${user.id}/stats`);
    userStats.value = response.data;
  } catch (error) {
    console.error('Error fetching user stats:', error);
    userStats.value = {
      registrations_count: 0,
      reviews_count: 0,
      average_rating: 0
    };
  }
};

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('ru-RU', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
};

// Lifecycle
onMounted(() => {
  fetchUsers();
});
</script>
