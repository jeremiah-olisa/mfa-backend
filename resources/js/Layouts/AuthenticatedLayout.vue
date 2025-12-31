<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import { SidebarProvider, SidebarTrigger, SidebarInset } from '@/components/ui/sidebar';
import { usePage } from '@inertiajs/vue3';

const props: any = usePage().props;
</script>

<template>
    <SidebarProvider>
        <AppSidebar />
        <SidebarInset>
            <header class="flex h-16 shrink-0 items-center gap-2 border-b px-4">
                <SidebarTrigger class="-ml-1" />
                <div v-if="$slots.header" class="w-full">
                     <slot name="header" />
                </div>
            </header>
            <div class="flex flex-1 flex-col gap-4 p-4 lg:p-8">
                <slot />
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
