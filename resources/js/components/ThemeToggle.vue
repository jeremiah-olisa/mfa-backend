<template>
    <div class="relative ms-3">
        <!-- Dropdown to toggle modes -->
        <Dropdown align="right" width="48">
            <template #trigger>
                <span class="inline-flex rounded-md">
                    <button
                        title="Theme"
                        type="button"
                        class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-300"
                    >
                        <component
                            :size="20"
                            class="mx-2"
                            :is="Icons[currentMode]"
                        />
                        {{ currentMode }}
                    </button>
                </span>
            </template>

            <template #content>
                <!-- Dropdown options for mode selection -->
                <Button class="w-full justify-start" variant="link" @click="setMode('Light')" as="button">
                    <component class="ms-2 h-4 w-4" :is="Icons.Light" />
                    Light
                </Button>
                <Button class="w-full justify-start" variant="link" @click="setMode('Dark')" as="button">
                    <component class="ms-2 h-4 w-4" :is="Icons.Dark" />
                    Dark
                </Button>
                <Button class="w-full justify-start" variant="link" @click="setMode('System')" as="button">
                    <component class="ms-2 h-4 w-4" :is="Icons.System" />
                    System
                </Button>
            </template>
        </Dropdown>
    </div>
</template>

<script setup lang="ts">
import Dropdown from '@/components/Dropdown.vue';
import { Button } from '@/components/ui/button';
import { Laptop, Moon, Sun } from 'lucide-vue-next';
import { ref, watchEffect } from 'vue';

type Modes = 'Dark' | 'Light' | 'System';
// Reactive state for the current mode
const currentMode = ref<Modes>('System');

const Icons = {
    System: Laptop,
    Dark: Moon,
    Light: Sun,
};

// Function to update the mode
const setMode = (mode: Modes) => {
    currentMode.value = mode;

    // You can use localStorage to persist the mode or apply it directly
    if (mode === 'Light') {
        document.documentElement.classList.add('light');
        document.documentElement.classList.remove('dark');
    } else if (mode === 'Dark') {
        document.documentElement.classList.add('dark');
        document.documentElement.classList.remove('light');
    } else {
        // 'System' mode: detect the user's system preference
        const isDarkMode = window.matchMedia(
            '(prefers-color-scheme: dark)',
        ).matches;
        if (isDarkMode) {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
        } else {
            document.documentElement.classList.add('light');
            document.documentElement.classList.remove('dark');
        }
    }
};

// Watch for system preference changes (for "System" mode)
watchEffect(() => {
    if (currentMode.value === 'System') {
        const isDarkMode = window.matchMedia(
            '(prefers-color-scheme: dark)',
        ).matches;
        if (isDarkMode) {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
        } else {
            document.documentElement.classList.add('light');
            document.documentElement.classList.remove('dark');
        }
    }
});
</script>
