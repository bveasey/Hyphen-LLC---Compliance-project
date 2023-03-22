<script setup>
import { RouterView } from "vue-router";
</script>

<template>
  <RouterView v-slot="{ Component, route }">
    <h1>account layout</h1>
    <!-- <transition name="fade"> -->
    <component :is="Component" :key="route.path" />
    <!-- </transition> -->
  </RouterView>
</template>