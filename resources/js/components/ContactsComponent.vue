<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="6">
        <h1 class="text-h3 mb-6">{{ t('contacts.title') }}</h1>
        <v-card class="mb-6">
          <v-card-text>
            <div class="text-h6 mb-4">{{ t('contacts.getInTouch') }}</div>
            <div class="d-flex align-center mb-4">
              <v-icon icon="mdi-phone" class="mr-4"></v-icon>
              <div>
                <div class="text-subtitle-1">{{ t('contacts.phone') }}</div>
                <div class="text-body-2">+1 (555) 123-4567</div>
              </div>
            </div>
            <div class="d-flex align-center mb-4">
              <v-icon icon="mdi-email" class="mr-4"></v-icon>
              <div>
                <div class="text-subtitle-1">{{ t('contacts.email') }}</div>
                <div class="text-body-2">contact@eventapp.com</div>
              </div>
            </div>
            <div class="d-flex align-center mb-4">
              <v-icon icon="mdi-map-marker" class="mr-4"></v-icon>
              <div>
                <div class="text-subtitle-1">{{ t('contacts.address') }}</div>
                <div class="text-body-2">Адрес не указан</div>
              </div>
            </div>
          </v-card-text>
        </v-card>
      </v-col>
      <!--
      <v-col cols="12" md="6">
        <v-card>
          <v-card-text>
            <div class="text-h6 mb-4">{{ t('contacts.sendMessage') }}</div>
            <v-form @submit.prevent="submitForm">
              <v-text-field
                v-model="form.name"
                :label="t('contacts.form.name')"
                required
              ></v-text-field>

              <v-text-field
                v-model="form.email"
                :label="t('contacts.form.email')"
                type="email"
                required
              ></v-text-field>

              <v-text-field
                v-model="form.subject"
                :label="t('contacts.form.subject')"
                required
              ></v-text-field>

              <v-textarea
                v-model="form.message"
                :label="t('contacts.form.message')"
                required
                rows="4"
              ></v-textarea>

              <v-btn
                type="submit"
                color="primary"
                block
                :loading="loading"
              >
                {{ t('contacts.form.send') }}
              </v-btn>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
      -->
    </v-row>
  </v-container>
</template>

<script setup>
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const loading = ref(false);

const form = ref({
  name: '',
  email: '',
  subject: '',
  message: ''
});

const submitForm = async () => {
  loading.value = true;
  try {
    // TODO: Implement form submission
    await new Promise(resolve => setTimeout(resolve, 1000));
    // Show success message
    form.value = {
      name: '',
      email: '',
      subject: '',
      message: ''
    };
  } catch (error) {
    console.error('Error submitting form:', error);
  } finally {
    loading.value = false;
  }
};
</script>
