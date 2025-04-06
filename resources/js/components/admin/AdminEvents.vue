<template>
  <div>
    <div class="d-flex justify-space-between align-center mb-6">
      <h2 class="text-h4">{{ t('admin.events.title') }}</h2>
      <v-btn
        color="primary"
        prepend-icon="mdi-plus"
        @click="openEventDialog()"
      >
        {{ t('admin.events.add') }}
      </v-btn>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center my-8">
      <v-progress-circular indeterminate color="primary"></v-progress-circular>
    </div>

    <!-- Events Table -->
    <v-card v-else>
      <v-data-table
        :headers="headers"
        :items="events"
        :items-per-page="10"
        class="elevation-1"
      >
        <template v-slot:item.type="{ item }">
          <v-chip
            :color="getEventTypeColor(item.type)"
            text-color="white"
            size="small"
          >
            {{ t(`categories.${item.type}`) }}
          </v-chip>
        </template>

        <template v-slot:item.start_date="{ item }">
          {{ formatDate(item.start_date) }}
        </template>

        <template v-slot:item.price="{ item }">
          {{ item.price ? `${item.price} ₽` : t('events.free') }}
        </template>

        <template v-slot:item.actions="{ item }">
          <v-btn
            icon="mdi-pencil"
            size="small"
            color="primary"
            variant="text"
            @click="openEventDialog(item)"
          ></v-btn>
          <v-btn
            icon="mdi-delete"
            size="small"
            color="error"
            variant="text"
            @click="confirmDeleteEvent(item)"
          ></v-btn>
        </template>
      </v-data-table>
    </v-card>

    <!-- Event Form Dialog -->
    <v-dialog v-model="eventDialog" max-width="800px">
      <v-card>
        <v-card-title>
          <span class="text-h5">{{ editedEvent.id ? t('admin.events.edit') : t('admin.events.add') }}</span>
        </v-card-title>

        <v-card-text>
          <v-form ref="form" @submit.prevent="saveEvent">
            <v-container>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    v-model="editedEvent.title"
                    :label="t('admin.events.form.title')"
                    required
                    :rules="[v => !!v || t('admin.events.validation.titleRequired')]"
                  ></v-text-field>
                </v-col>

                <v-col cols="12">
                  <v-textarea
                    v-model="editedEvent.description"
                    :label="t('admin.events.form.description')"
                    required
                    :rules="[v => !!v || t('admin.events.validation.descriptionRequired')]"
                    rows="3"
                  ></v-textarea>
                </v-col>

                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="editedEvent.location"
                    :label="t('admin.events.form.location')"
                    required
                    :rules="[v => !!v || t('admin.events.validation.locationRequired')]"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                  <v-select
                    v-model="editedEvent.type"
                    :items="eventTypes"
                    item-title="title"
                    item-value="value"
                    :label="t('admin.events.form.type')"
                    required
                    :rules="[v => !!v || t('admin.events.validation.typeRequired')]"
                  ></v-select>
                </v-col>

                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="editedEvent.price"
                    :label="t('admin.events.form.price')"
                    type="number"
                    :hint="t('admin.events.form.priceHint')"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="editedEvent.capacity"
                    :label="t('admin.events.form.capacity')"
                    type="number"
                    required
                    :rules="[v => !!v || t('admin.events.validation.capacityRequired')]"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="editedEvent.start_date"
                    :label="t('admin.events.form.date')"
                    type="date"
                    required
                    :rules="[v => !!v || t('admin.events.validation.dateRequired')]"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="editedEvent.start_time"
                    :label="t('admin.events.form.time')"
                    type="time"
                    required
                    :rules="[v => !!v || t('admin.events.validation.timeRequired')]"
                  ></v-text-field>
                </v-col>

                <v-col cols="12">
                  <v-text-field
                    v-model="editedEvent.image"
                    :label="t('admin.events.form.image')"
                    :hint="t('admin.events.form.imageHint')"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-container>
          </v-form>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="grey"
            variant="text"
            @click="eventDialog = false"
          >
            {{ t('common.cancel') }}
          </v-btn>
          <v-btn
            color="primary"
            variant="text"
            @click="saveEvent"
            :loading="saving"
          >
            {{ t('common.save') }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Delete Confirmation Dialog -->
    <v-dialog v-model="deleteDialog" max-width="500px">
      <v-card>
        <v-card-title class="text-h5">{{ t('admin.events.deleteTitle') }}</v-card-title>
        <v-card-text>
          {{ t('admin.events.deleteConfirm', { title: eventToDelete?.title }) }}
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="grey"
            variant="text"
            @click="deleteDialog = false"
          >
            {{ t('common.cancel') }}
          </v-btn>
          <v-btn
            color="error"
            variant="text"
            @click="deleteEvent"
            :loading="deleting"
          >
            {{ t('common.delete') }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { formatDate, getEventTypeColor } from '../events/eventData';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

// Data
const events = ref([]);
const loading = ref(true);
const eventDialog = ref(false);
const deleteDialog = ref(false);
const saving = ref(false);
const deleting = ref(false);
const eventToDelete = ref(null);
const form = ref(null);

const defaultEvent = {
  title: '',
  description: '',
  location: '',
  type: 'cultural',
  price: null,
  capacity: 100,
  start_date: new Date().toISOString().substr(0, 10),
  start_time: '18:00',
  image: ''
};

const editedEvent = ref({ ...defaultEvent });

const headers = [
  { title: t('admin.events.table.id'), key: 'id', sortable: true },
  { title: t('admin.events.table.title'), key: 'title', sortable: true },
  { title: t('admin.events.table.type'), key: 'type', sortable: true },
  { title: t('admin.events.table.date'), key: 'start_date', sortable: true },
  { title: t('admin.events.table.location'), key: 'location', sortable: true },
  { title: t('admin.events.table.price'), key: 'price', sortable: true },
  { title: t('admin.events.table.actions'), key: 'actions', sortable: false }
];

const eventTypes = [
  { value: 'cultural', title: t('categories.cultural') },
  { value: 'sports', title: t('categories.sports') },
  { value: 'educational', title: t('categories.educational') },
  { value: 'entertainment', title: t('categories.entertainment') },
  { value: 'other', title: t('categories.other') }
];

// Methods
const fetchEvents = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/admin/events');
    events.value = response.data.data || [];
  } catch (error) {
    console.error('Error fetching events:', error);
  } finally {
    loading.value = false;
  }
};

const openEventDialog = (event = null) => {
  if (event) {
    editedEvent.value = { ...event };
  } else {
    editedEvent.value = { ...defaultEvent };
  }
  eventDialog.value = true;
};

const saveEvent = async () => {
  if (!form.value.validate()) return;
  
  saving.value = true;
  try {
    if (editedEvent.value.id) {
      // Update existing event
      await axios.put(`/api/admin/events/${editedEvent.value.id}`, editedEvent.value);
    } else {
      // Create new event
      await axios.post('/api/admin/events', editedEvent.value);
    }
    
    eventDialog.value = false;
    fetchEvents();
  } catch (error) {
    console.error('Error saving event:', error);
  } finally {
    saving.value = false;
  }
};

const confirmDeleteEvent = (event) => {
  eventToDelete.value = event;
  deleteDialog.value = true;
};

const deleteEvent = async () => {
  if (!eventToDelete.value) return;
  
  deleting.value = true;
  try {
    await axios.delete(`/api/admin/events/${eventToDelete.value.id}`);
    deleteDialog.value = false;
    fetchEvents();
  } catch (error) {
    console.error('Error deleting event:', error);
  } finally {
    deleting.value = false;
  }
};

// Lifecycle
onMounted(() => {
  fetchEvents();
});
</script>
