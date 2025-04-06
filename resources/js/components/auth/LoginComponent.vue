<template>
  <v-container>
    <v-row justify="center">
      <v-col cols="12" sm="8" md="6" lg="4">
        <v-card class="pa-4">
          <v-card-title class="text-center text-h4 mb-4">
            {{ t('auth.login') }}
          </v-card-title>
          
          <v-alert
            v-if="error"
            type="error"
            variant="tonal"
            class="mb-4"
          >
            {{ error }}
          </v-alert>

          <v-form @submit.prevent="login" ref="form">
            <v-text-field
              v-model="email"
              :label="t('auth.email')"
              type="email"
              required
              :rules="[v => !!v || 'Email is required', v => /.+@.+\..+/.test(v) || 'Email must be valid']"
              variant="outlined"
              prepend-inner-icon="mdi-email"
              class="mb-3"
            ></v-text-field>

            <v-text-field
              v-model="password"
              :label="t('auth.password')"
              type="password"
              required
              :rules="[v => !!v || 'Password is required']"
              variant="outlined"
              prepend-inner-icon="mdi-lock"
              class="mb-4"
            ></v-text-field>

            <v-btn
              type="submit"
              color="primary"
              block
              size="large"
              :loading="loading"
              class="mb-3"
            >
              {{ t('auth.signIn') }}
            </v-btn>
          </v-form>

          <v-divider class="my-4"></v-divider>

          <div class="text-center">
            <p class="mb-2">{{ t('auth.noAccount') }}</p>
            <v-btn
              color="secondary"
              variant="text"
              :to="{ name: 'register' }"
            >
              {{ t('auth.signUp') }}
            </v-btn>
          </div>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { authState } from '../../eventBus';

const { t } = useI18n();
const router = useRouter();
const form = ref(null);
const email = ref('');
const password = ref('');
const loading = ref(false);
const error = ref('');

const login = async () => {
  const { valid } = await form.value.validate();
  
  if (!valid) return;
  
  loading.value = true;
  error.value = '';
  
  try {
    const response = await axios.post('/api/login', {
      email: email.value,
      password: password.value
    });
    
    // Store token in localStorage
    localStorage.setItem('token', response.data.token);
    
    // Set default Authorization header for all future requests
    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
    
    // Обновляем глобальное состояние авторизации
    authState.setUser(response.data.user);
    
    // Redirect based on user role
    if (response.data.user.role === 'admin') {
      router.push({ name: 'admin-dashboard' });
    } else {
      router.push({ name: 'home' });
    }
  } catch (err) {
    if (err.response && err.response.data && err.response.data.message) {
      error.value = err.response.data.message;
    } else if (err.response && err.response.data && err.response.data.errors) {
      error.value = Object.values(err.response.data.errors)[0][0];
    } else {
      error.value = 'Ошибка при входе. Пожалуйста, попробуйте снова.';
    }
  } finally {
    loading.value = false;
  }
};
</script>
