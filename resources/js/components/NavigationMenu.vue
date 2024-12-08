<script setup lang="ts">
defineProps<{
  customComponent: any;
}>();
</script>

<template>
  <component
    :is="customComponent"
    :href="route('dashboard')"
    :active="route().current('dashboard')"
  >
    Dashboard
  </component>
  <component
    :is="customComponent"
    :href="route('questions.list')"
    :active="route().current('questions.list')"
  >
    Questions
  </component>
</template>

<style scoped></style>
