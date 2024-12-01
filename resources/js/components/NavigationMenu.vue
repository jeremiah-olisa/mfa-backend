<script setup lang="ts">
import { FunctionalComponent } from 'vue';

defineProps<{
    customComponent: FunctionalComponent;
}>();
</script>

<template>
    <component :is="customComponent" :href="route('dashboard')" :active="route().current('dashboard')">
        Dashboard
    </component>
    <component :is="customComponent" :href="route('questions.list')" :active="route().current('questions.list')">
        Questions
    </component>
</template>

<style scoped></style>
