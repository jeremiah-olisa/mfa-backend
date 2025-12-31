<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import { usePage } from '@inertiajs/vue3';

const props: any = usePage().props;
</script>

<template>
    <div class="flex min-h-screen bg-background text-foreground font-sans antialiased">
        <!-- Sidebar -->
        <AppSidebar />

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-background/50">
             <!-- Header (Optional, for mobile trigger or breadcrumbs if needed, keeping simple for now) -->
             <!-- <header class="h-14 border-b border-border flex items-center px-4 lg:hidden">
                 Mobile Trigger here
             </header> -->

            <div class="flex-1 p-6 lg:p-8 space-y-6">
                <!-- Page Heading -->
                <header v-if="$slots.header" class="mb-4">
                     <slot name="header" />
                </header>

                <slot />
            </div>
        </main>
    </div>
</template>
