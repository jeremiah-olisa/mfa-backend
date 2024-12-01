<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Card } from '@/components/ui/card';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 dark:bg-gray-900 sm:justify-center sm:pt-0"
    >
        <div>
            <Link href="/">
                <ApplicationLogo
                    class="h-20 w-auto fill-current text-gray-500"
                />
            </Link>
        </div>

        <Card
            class="mt-6 w-full overflow-hidden  px-6 py-4 dark:bg-gray-800 sm:max-w-md"
        >
            <slot />
        </Card>
    </div>
</template>
