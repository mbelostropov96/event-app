<template>
  <v-container>
    <v-row>
      <v-col>
        <v-card>
          <v-card-title>
            {{ title }}
          </v-card-title>
          <v-card-text>
            This is a Vue component with Vuetify
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      title: 'Example Component'
    }
  }
}
</script>
