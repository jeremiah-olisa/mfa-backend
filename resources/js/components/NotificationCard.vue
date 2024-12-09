<template>
    <div class="text-center">
        <!-- Status Icon -->
        <div class="mb-6">
            <div
                class="inline-flex h-16 w-16 items-center justify-center rounded-full border-4 transition-colors"
                :class="[
                    status === 'success'
                        ? 'border-green-500 bg-green-50 text-green-500 dark:bg-green-900/50'
                        : 'border-red-500 bg-red-50 text-red-500 dark:bg-red-900/50',
                ]"
            >
                <CheckIcon v-if="status === 'success'" class="h-8 w-8" />
                <XIcon v-else class="h-8 w-8" />
            </div>
        </div>

        <!-- Title and Message -->
        <div class="space-y-4">
            <h2
                class="flex items-center justify-center gap-2 text-3xl font-semibold text-gray-900 transition-colors dark:text-gray-100"
            >
                {{ title }}
                <span v-if="status === 'success'" class="text-2xl">🎉</span>
            </h2>

            <p
                class="text-xl text-gray-600 transition-colors dark:text-gray-300"
            >
                {{ message }}
            </p>

            <!-- Optional Footer Content -->
            <div
                v-if="footerText"
                class="text-sm text-gray-500 dark:text-gray-400"
            >
                {{ footerText }}
            </div>

            <!-- Button -->
            <div class="pt-6">
                <Link
                    :href="route"
                    class="w-full rounded-lg px-6 py-3 text-lg font-semibold text-white transition-colors"
                    :class="
                        status === 'success'
                            ? 'bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700'
                            : 'bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700'
                    "
                >
                    {{ linkText }}
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { CheckIcon, XIcon } from 'lucide-vue-next';

interface Props {
    status: 'success' | 'error'; // Status of the notification
    title: string; // Title text
    message: string; // Main message text
    linkText?: string; // Optional button text
    footerText?: string; // Optional footer text for additional context
    route: string; // Optional footer text for additional context
}

defineProps<Props>();
</script>
