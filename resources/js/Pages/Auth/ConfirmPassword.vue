<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Secure Area</h2>
            <div class="mt-2 text-sm text-muted-foreground">
                This is a secure area of the application. Please confirm your password before continuing.
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-2">
                <Label for="password" class="text-foreground/80">Password</Label>
                <Input
                    id="password"
                    type="password"
                    class="block w-full border-border bg-secondary/50 text-foreground placeholder:text-muted-foreground/50 focus:bg-background transition-colors"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                    placeholder="Enter password to confirm"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <Button
                class="w-full bg-primary py-6 text-base font-semibold text-primary-foreground hover:bg-primary/90 transition-all shadow-lg shadow-primary/25"
                :class="{ 'opacity-75': form.processing }"
                :disabled="form.processing"
            >
                Confirm
            </Button>
        </form>
    </GuestLayout>
</template>
