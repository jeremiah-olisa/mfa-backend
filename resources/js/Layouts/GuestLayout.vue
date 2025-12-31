<script setup lang="ts">
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import { Card } from '@/components/ui/card';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="relative flex min-h-screen flex-col items-center justify-center bg-background pt-6 sm:pt-0 overflow-hidden"
    >
        <!-- Background Pattern Layer (Masked) -->
        <div class="absolute inset-0 z-0 bg-dot-pattern pointer-events-none"></div>

        <!-- Content Layer (Unmasked) -->
        <div class="relative z-10 mb-8 flex flex-col items-center">
            <Link href="/" class="flex flex-col items-center gap-2">
                <ApplicationLogo
                    class="h-20 w-auto fill-current text-foreground"
                />
            </Link>
        </div>

        <div class="relative z-10 w-full sm:max-w-md"> 
             <Card
                class="w-full overflow-hidden border-border bg-card px-8 py-8 shadow-2xl relative z-10"
            >
                <slot />
            </Card>
        </div>
        
        <div class="relative z-10 mt-8 flex items-center justify-center gap-2 text-sm text-muted-foreground">
             <ApplicationLogo class="h-4 w-4 fill-current opacity-50" />
            <span>&copy; {{ new Date().getFullYear() }} {{ $page?.props?.appName || "My First Attempt" }}</span>
        </div>
    </div>
</template>
