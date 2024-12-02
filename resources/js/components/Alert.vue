<script setup lang="ts">
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { AlertCircle } from 'lucide-vue-next';

withDefaults(
    defineProps<{
        status: string;
        message?: string;
        title: string;
    }>(),
    {},
);
</script>

<template>
    <div v-if="message" class="py-4">
        <Alert :variant="status == 'success' ? 'default' : 'destructive'">
            <AlertCircle class="h-4 w-4" />
            <AlertTitle>{{ title ?? 'Alert' }}</AlertTitle>
            <AlertDescription>
                {{ message }}
            </AlertDescription>
        </Alert>
    </div>
</template>

<style scoped></style>
