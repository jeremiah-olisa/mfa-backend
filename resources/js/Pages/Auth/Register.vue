<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    app: '',
    phone: '',
    role: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};

const selectClass =
    'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50';
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Create an account</h2>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div class="space-y-2">
                <Label for="name" class="text-foreground/80">Name</Label>
                <Input
                    id="name"
                    type="text"
                    class="block w-full border-border bg-secondary/50 text-foreground placeholder:text-muted-foreground/50 focus:bg-background transition-colors"
                    v-model="form.name"
                    required
                    autofocus
                    placeholder="Enter your name"
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="space-y-2">
                <Label for="email" class="text-foreground/80">Email</Label>
                <Input
                    id="email"
                    type="email"
                    class="block w-full border-border bg-secondary/50 text-foreground placeholder:text-muted-foreground/50 focus:bg-background transition-colors"
                    v-model="form.email"
                    required
                    placeholder="Enter your email"
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="space-y-2">
                <Label for="phone" class="text-foreground/80">Phone Number</Label>
                <Input
                    id="phone"
                    type="tel"
                    class="block w-full border-border bg-secondary/50 text-foreground placeholder:text-muted-foreground/50 focus:bg-background transition-colors"
                    v-model="form.phone"
                    required
                    placeholder="Enter phone number"
                    autocomplete="tel"
                />
                <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="space-y-2">
                    <Label for="password" class="text-foreground/80">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        class="block w-full border-border bg-secondary/50 text-foreground placeholder:text-muted-foreground/50 focus:bg-background transition-colors"
                        v-model="form.password"
                        required
                        placeholder="Create password"
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
                        placeholder="Confirm password"
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="space-y-2">
                    <Label for="app" class="text-foreground/80">App</Label>
                    <select
                        id="app"
                        v-model="form.app"
                        class="flex h-10 w-full rounded-md border border-border bg-secondary/50 px-3 py-2 text-sm text-foreground focus:bg-background focus:ring-2 focus:ring-ring focus:outline-hidden disabled:cursor-not-allowed disabled:opacity-50 transition-colors"
                        required
                    >
                        <option disabled value="">Select App</option>
                        <option value="JAMB">JAMB</option>
                        <option value="WAEC">WAEC</option>
                        <option value="NECO">NECO</option>
                        <option value="ADMIN">ADMIN</option>
                        <option value="OYO">OYO</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.app" />
                </div>

                <div class="space-y-2">
                    <Label for="role" class="text-foreground/80">Role</Label>
                    <select
                        id="role"
                        v-model="form.role"
                        class="flex h-10 w-full rounded-md border border-border bg-secondary/50 px-3 py-2 text-sm text-foreground focus:bg-background focus:ring-2 focus:ring-ring focus:outline-hidden disabled:cursor-not-allowed disabled:opacity-50 transition-colors"
                        required
                    >
                        <option disabled value="">Select Role</option>
                        <option>Admin</option>
                        <option>Student</option>
                        <option>Marketer</option>
                        <option>Content Manager</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.role" />
                </div>
            </div>

            <div class="pt-2">
                <Button
                    class="w-full bg-primary py-6 text-base font-semibold text-primary-foreground hover:bg-primary/90 transition-all shadow-lg shadow-primary/25"
                    :class="{ 'opacity-75': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </Button>
            </div>

            <div class="text-center text-sm font-medium text-muted-foreground">
                Already have an account?
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
