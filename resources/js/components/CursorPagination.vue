<template>
    <div class="flex flex-col items-center justify-between gap-4 px-4 py-3 sm:flex-row">
        <!-- Combined record info -->
        <div class="text-sm text-muted-foreground">
            <span v-if="showRange">
                Showing <span class="font-medium">{{ from }}–{{ to }}</span>
                (<span class="font-medium">{{ itemsCount }}</span> records) of {{ total }}
            </span>
            <span v-else>
                Showing <span class="font-medium">{{ itemsCount }}</span> records
            </span>
        </div>

        <!-- Pagination Controls -->
        <div class="flex items-center space-x-6 lg:space-x-8">
            <!-- Per Page Selector -->
            <div class="flex items-center space-x-2">
                <label for="per-page" class="text-sm font-medium">Rows per page</label>
                <select id="per-page" v-model="selectedPerPage"
                    class="h-8 rounded-md border border-input bg-background pl-2 pr-8 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    <option v-for="option in perPageOptions" :key="option" :value="option">
                        {{ option }}
                    </option>
                </select>
            </div>

            <!-- Page Navigation -->
            <div class="flex items-center space-x-2">
                <Button variant="outline" size="sm" :disabled="!hasPrevPage" @click="goToPrevPage" class="h-8 w-8 p-0"
                    title="Previous page">
                    <span class="sr-only">Previous page</span>
                    <ChevronLeft class="h-4 w-4" />
                </Button>
                <Button variant="outline" size="sm" :disabled="!hasNextPage" @click="goToNextPage" class="h-8 w-8 p-0"
                    title="Next page">
                    <span class="sr-only">Next page</span>
                    <ChevronRight class="h-4 w-4" />
                </Button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';

interface PaginationProps {
    next_page_url: string | null;
    prev_page_url: string | null;
    path: string;
    per_page: number;
    items_count: number;
    from?: number;
    to?: number;
    total?: number;
}

const props = defineProps<PaginationProps>();

// Internal state
const selectedPerPage = ref(props.per_page);
const perPageOptions = ref([5, 10, 15, 30, 50, 100]);

// Computed properties
const hasPrevPage = computed(() => !!props.prev_page_url);
const hasNextPage = computed(() => !!props.next_page_url);
const itemsCount = computed(() => props.items_count);
const showRange = computed(() => props.from !== undefined && props.to !== undefined);

// Watch for per_page changes
watch(selectedPerPage, (newValue) => {
    const url = route(String(route().current() || ''), {
        ...route().params,
        per_page: newValue,
        cursor: null,
    });

    router.visit(url, {
        preserveScroll: false,
        preserveState: false,
    });
});

// Add current per_page value to options if not present
onMounted(() => {
    if (!perPageOptions.value.includes(selectedPerPage.value)) {
        perPageOptions.value = [
            selectedPerPage.value,
            ...perPageOptions.value,
        ].sort((a, b) => a - b);
    }
});

// Navigation methods
const redirectPage = (url: string | null) => {
    if (!url) return;
    router.visit(url, { preserveScroll: false, preserveState: false });
};

const goToPrevPage = () => redirectPage(props.prev_page_url);
const goToNextPage = () => redirectPage(props.next_page_url);
</script>