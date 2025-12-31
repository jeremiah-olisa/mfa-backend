<script setup lang="ts">
import FormErrorAlert from '@/components/FormErrorAlert.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    app: 'ADMIN',
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Sign in" />

        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Sign in</h2>
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <FormErrorAlert :errors="form.errors" />

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
                    placeholder="Enter email"
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <Label for="password" class="text-foreground/80">Password</Label>
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors"
                    >
                        Forgot password?
                    </Link>
                </div>
                <Input
                    id="password"
                    type="password"
                    class="block w-full border-border bg-secondary/50 text-foreground placeholder:text-muted-foreground/50 focus:bg-background transition-colors"
                    v-model="form.password"
                    required
                    placeholder="Enter password"
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block">
                <label class="flex items-center space-x-2">
                    <Checkbox 
                        name="remember" 
                        v-model:checked="form.remember" 
                        class="border-border bg-secondary data-[state=checked]:bg-primary" 
                    />
                    <span class="text-sm font-medium text-muted-foreground">
                        Remember me
                    </span>
                </label>
            </div>

            <Button
                class="w-full bg-primary py-6 text-base font-semibold text-primary-foreground hover:bg-primary/90 transition-all shadow-lg shadow-primary/25"
                :class="{ 'opacity-75': form.processing }"
                :disabled="form.processing"
            >
                Sign in
            </Button>

            <div class="text-center text-sm font-medium text-muted-foreground">
                Don't have an account?
                <Link
                    :href="route('register')"
                    class="transition-colors hover:text-foreground"
                >
                    Sign up
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
