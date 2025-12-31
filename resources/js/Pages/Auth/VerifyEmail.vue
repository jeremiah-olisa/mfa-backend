<script setup lang="ts">
import { Button } from '@/components/ui/button';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    status?: string;
}>();

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Verify your email</h2>
            <div class="mt-2 text-sm text-muted-foreground">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
            </div>
            <div class="mt-2 text-sm text-muted-foreground">
                 If you didn't receive the email, we will gladly send you another.
            </div>
        </div>

        <div
            class="mb-6 text-center text-sm font-medium text-green-600 dark:text-green-500 bg-green-50 dark:bg-green-900/20 p-4 rounded-md border border-green-200 dark:border-green-900/50"
            v-if="verificationLinkSent"
        >
            A new verification link has been sent to the email address you provided during registration.
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <Button
                class="w-full bg-primary py-6 text-base font-semibold text-primary-foreground hover:bg-primary/90 transition-all shadow-lg shadow-primary/25"
                :class="{ 'opacity-75': form.processing }"
                :disabled="form.processing"
            >
                Resend Verification Email
            </Button>

            <div class="text-center">
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="text-sm font-medium text-muted-foreground hover:text-foreground transition-colors underline decoration-border hover:decoration-foreground underline-offset-4"
                >
                    Log Out
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
