<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import ApplicationLogo from '@/components/ApplicationLogo.vue';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion: string;
    phpVersion: string;
}>();
</script>

<template>
    <Head title="Welcome" />

    <div class="relative min-h-screen bg-background text-foreground overflow-hidden flex flex-col items-center justify-center">
        <!-- Background Pattern -->
         <div class="absolute inset-0 z-0 bg-dot-pattern opacity-100 pointer-events-none"></div>

         <!-- Header / Nav -->
        <header class="absolute top-0 w-full z-10 p-6 flex justify-between items-center max-w-7xl mx-auto">
             <div class="flex items-center gap-2">
                 <ApplicationLogo class="h-8 w-8 fill-current text-foreground" />
                 <span class="font-bold text-lg tracking-tight">My First Attempt</span>
             </div>
             <nav v-if="canLogin" class="flex gap-4">
                 <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                >
                    <Button variant="outline">Dashboard</Button>
                </Link>
                <template v-else>
                     <Link :href="route('login')">
                        <Button variant="ghost">Log in</Button>
                     </Link>
                     <Link v-if="canRegister" :href="route('register')">
                        <Button>Get Started</Button>
                     </Link>
                </template>
             </nav>
        </header>

        <!-- Hero Content -->
        <main class="relative z-10 flex flex-col items-center text-center max-w-3xl px-6">
            <div class="mb-6 flex justify-center">
                 <div class="bg-secondary/50 p-4 rounded-full border border-border/50 backdrop-blur-sm shadow-xl">
                      <ApplicationLogo class="h-16 w-16 fill-current text-primary" />
                 </div>
            </div>
            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-foreground mb-6">
                Build faster with <span class="text-primary">My First Attempt</span>
            </h1>
            <p class="text-lg sm:text-xl text-muted-foreground mb-10 max-w-2xl">
                A powerful, modern application built with Laravel and Vue.js. 
                Experience the next generation of web development.
            </p>
             <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                <Link :href="route('login')">
                    <Button size="lg" class="w-full sm:w-auto min-w-[120px]">
                        Sign In
                    </Button>
                </Link>
                <Link v-if="canRegister" :href="route('register')">
                    <Button size="lg" variant="secondary" class="w-full sm:w-auto min-w-[120px]">
                        Create Account
                    </Button>
                </Link>
            </div>
        </main>

        <!-- Footer -->
        <footer class="absolute bottom-0 w-full z-10 py-6 text-center text-sm text-muted-foreground">
             &copy; {{ new Date().getFullYear() }} My First Attempt. All rights reserved.
        </footer>
    </div>
</template>
