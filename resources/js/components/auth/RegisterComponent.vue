<template>
  <v-container>
    <v-row justify="center">
      <v-col cols="12" sm="10" md="8" lg="6">
        <v-card class="pa-4">
          <v-card-title class="text-center text-h4 mb-4">
            {{ t('auth.register') }}
          </v-card-title>
          
          <v-alert
            v-if="error"
            type="error"
            variant="tonal"
            class="mb-4"
          >
            {{ error }}
          </v-alert>

          <v-form @submit.prevent="register" ref="form">
            <v-row>
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="firstName"
                  label="Имя"
                  required
                  :rules="[v => !!v || 'Имя обязательно']"
                  variant="outlined"
                  class="mb-3"
                ></v-text-field>
              </v-col>
              
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="middleName"
                  label="Отчество"
                  required
                  :rules="[v => !!v || 'Отчество обязательно']"
                  variant="outlined"
                  class="mb-3"
                ></v-text-field>
              </v-col>
              
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="lastName"
                  label="Фамилия"
                  required
                  :rules="[v => !!v || 'Фамилия обязательна']"
                  variant="outlined"
                  class="mb-3"
                ></v-text-field>
              </v-col>
            </v-row>

            <v-text-field
              v-model="email"
              :label="t('auth.email')"
              type="email"
              required
              :rules="[v => !!v || 'Email обязателен', v => /.+@.+\..+/.test(v) || 'Email должен быть корректным']"
              variant="outlined"
              prepend-inner-icon="mdi-email"
              class="mb-3"
            ></v-text-field>

            <v-text-field
              v-model="password"
              :label="t('auth.password')"
              type="password"
              required
              :rules="[v => !!v || 'Пароль обязателен', v => v.length >= 8 || 'Пароль должен быть не менее 8 символов']"
              variant="outlined"
              prepend-inner-icon="mdi-lock"
              class="mb-3"
            ></v-text-field>

            <v-text-field
              v-model="passwordConfirmation"
              label="Подтверждение пароля"
              type="password"
              required
              :rules="[
                v => !!v || 'Подтверждение пароля обязательно',
                v => v === password || 'Пароли не совпадают'
              ]"
              variant="outlined"
              prepend-inner-icon="mdi-lock-check"
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
              {{ t('auth.signUp') }}
            </v-btn>
          </v-form>

          <v-divider class="my-4"></v-divider>

          <div class="text-center">
            <p class="mb-2">{{ t('auth.hasAccount') }}</p>
            <v-btn
              color="secondary"
              variant="text"
              :to="{ name: 'login' }"
            >
              {{ t('auth.signIn') }}
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
const firstName = ref('');
const middleName = ref('');
const lastName = ref('');
const email = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const loading = ref(false);
const error = ref('');

const register = async () => {
  const { valid } = await form.value.validate();
  
  if (!valid) return;
  
  loading.value = true;
  error.value = '';
  
  try {
    const response = await axios.post('/api/register', {
      first_name: firstName.value,
      middle_name: middleName.value,
      last_name: lastName.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value
    });
    
    // Store token in localStorage
    localStorage.setItem('token', response.data.token);
    
    // Set default Authorization header for all future requests
    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
    
    // Обновляем глобальное состояние авторизации
    authState.setUser(response.data.user);
    
    // Redirect to home page
    router.push({ name: 'home' });
  } catch (err) {
    if (err.response && err.response.data && err.response.data.errors) {
      const firstError = Object.values(err.response.data.errors)[0];
      error.value = Array.isArray(firstError) ? firstError[0] : firstError;
    } else {
      error.value = 'Ошибка при регистрации. Пожалуйста, попробуйте снова.';
    }
  } finally {
    loading.value = false;
  }
};
</script>
