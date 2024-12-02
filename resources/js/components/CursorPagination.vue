<template>
    <div
        class="flex flex-col items-center justify-between space-y-4 sm:flex-row sm:space-y-0"
    >
        <div class="flex items-center space-x-2">
            <!-- First Page Button -->
            <Button
                @click="goToFirstPage"
                :disabled="!hasPrevPage"
                variant="outline"
                size="sm"
                title="First"
            >
                <ChevronsLeft class="mr-2 h-4 w-4" />
            </Button>

            <!-- Previous Page Button -->
            <Button
                @click="goToPrevPage"
                :disabled="!hasPrevPage"
                variant="outline"
                size="sm"
                title="Previous"
            >
                <ChevronLeft class="mr-2 h-4 w-4" />
            </Button>
        </div>

        <div class="flex items-center space-x-2">
            <!-- Per Page Selector -->
            <label for="per-page" class="text-sm font-medium">
                Per Page ({{ selectedPerPage }})
            </label>
            <select
                id="per-page"
                v-model="selectedPerPage"
                :class="selectClass"
            >
                <option
                    v-for="option in perPageOptions"
                    :key="option"
                    :value="option"
                >
                    {{ option }}
                </option>
            </select>
        </div>

        <div>
            <!-- Next Page Button -->
            <Button
                @click="goToNextPage"
                :disabled="!hasNextPage"
                variant="outline"
                size="sm"
                title="Next"
            >
                <ChevronRight class="ml-2 h-4 w-4" />
            </Button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { PaginationProps } from '@/types';
import { router } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight, ChevronsLeft } from 'lucide-vue-next';
import { onMounted, ref, watch } from 'vue';

// Define Props

const props = defineProps<PaginationProps>();

// Internal state
const selectedPerPage = ref(props.per_page);
const perPageOptions = ref([15, 30, 50, 100]);

const hasPrevPage = ref(!!props.prev_page_url);
const hasNextPage = ref(!!props.next_page_url);

// Update `selectedPerPage` on prop change
watch(
    () => props.per_page,
    (newValue) => {
        selectedPerPage.value = newValue;
    },
);

watch([selectedPerPage], (newValue) => {
    const _route = route(String(route().current()), {
        ...route().params,
        per_page: newValue[0],
    });
    router.visit(
        _route, // Keep the current route
        {
            replace: true, // Avoid adding a new history entry
            preserveState: true, // Preserve the current page state
        },
    );
});

onMounted(() => {
    if (perPageOptions.value.includes(selectedPerPage.value)) return;

    perPageOptions.value = [
        selectedPerPage.value,
        ...perPageOptions.value,
    ].sort((a, b) => a - b);
});

// Methods
const redirectPage = (url: string | null) => {
    if (!url) return;

    try {
        // Perform the redirection using Inertia
        router.visit(url);
    } catch (error) {
        console.error('Error redirecting to page:', error);
    }
};

const goToFirstPage = () => redirectPage(props.current_page);
const goToPrevPage = () => redirectPage(props.prev_page_url);
const goToNextPage = () => redirectPage(props.next_page_url);

const updatePerPage = () => {
    const newUrl = `${props.path}?per_page=${selectedPerPage.value}`;
    redirectPage(newUrl);
};

const selectClass =
    'flex h-10 w-[70px] rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50';
</script>
