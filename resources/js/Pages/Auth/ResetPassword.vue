<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    email: string;
    token: string;
}>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Set New Password</h2>
            <div class="mt-2 text-sm text-muted-foreground">
                Please enter your new password below.
            </div>
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
                    autocomplete="username"
                    disabled
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="space-y-2">
                <Label for="password" class="text-foreground/80">Password</Label>
                <Input
                    id="password"
                    type="password"
                    class="block w-full border-border bg-secondary/50 text-foreground placeholder:text-muted-foreground/50 focus:bg-background transition-colors"
                    v-model="form.password"
                    required
                    placeholder="New password"
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="space-y-2">
                <Label for="password_confirmation" class="text-foreground/80">Confirm Password</Label>
                <Input
                    id="password_confirmation"
                    type="password"
                    class="block w-full border-border bg-secondary/50 text-foreground placeholder:text-muted-foreground/50 focus:bg-background transition-colors"
                    v-model="form.password_confirmation"
                    required
                    placeholder="Confirm new password"
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <Button
                class="w-full bg-primary py-6 text-base font-semibold text-primary-foreground hover:bg-primary/90 transition-all shadow-lg shadow-primary/25"
                :class="{ 'opacity-75': form.processing }"
                :disabled="form.processing"
            >
                Reset Password
            </Button>
        </form>
    </GuestLayout>
</template>
