<script setup>
import { RouterView } from "vue-router";
</script>

<template>
  <v-app class="bg-grey-lighten-2 pa-4">

    <v-main>
      <RouterView v-slot="{ Component, route }">
        <!-- <transition name="fade"> -->
        <component :is="Component" :key="route.path" />
        <!-- </transition> -->
      </RouterView>
    </v-main>
  </v-app>
</template>