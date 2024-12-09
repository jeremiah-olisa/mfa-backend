<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import NotificationCard from '@/components/NotificationCard.vue';

const props = defineProps<{
    message?: string;
    title?: string;
    status?: 'success' | 'error';
}>();
</script>

<template>
    <GuestLayout>
        <Head title="Payment Success" />
        <NotificationCard
            :status="status ?? 'success'"
            :title="title ?? 'Success!'"
            :message="message ?? 'Payment Successful'"
            linkText="Go To Dashboard"
            :route="route('dashboard')"
        />
    </GuestLayout>
</template>

<style scoped></style>
