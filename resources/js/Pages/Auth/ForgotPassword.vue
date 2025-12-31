<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Reset Password</h2>
            <div class="mt-2 text-sm text-muted-foreground">
                Enter your email address and we'll send you a link to reset your password.
            </div>
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-2">
                <Label for="email" class="text-foreground/80">Email</Label>
                <Input
                    id="email"
                    type="email"
                    class="block w-full border-border bg-secondary/50 text-foreground placeholder:text-muted-foreground/50 focus:bg-background transition-colors"
                    v-model="form.email"
                    required
                    autofocus
                    placeholder="Enter your email"
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <Button
                class="w-full bg-primary py-6 text-base font-semibold text-primary-foreground hover:bg-primary/90 transition-all shadow-lg shadow-primary/25"
                :class="{ 'opacity-75': form.processing }"
                :disabled="form.processing"
            >
                Email Password Reset Link
            </Button>

            <div class="text-center text-sm font-medium text-muted-foreground">
                Remember your password?
                <Link
                    :href="route('login')"
                    class="transition-colors hover:text-foreground"
                >
                    Sign in
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
