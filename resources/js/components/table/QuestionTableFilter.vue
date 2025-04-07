<script setup lang="ts">
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Question } from '@/types';
import { router } from '@inertiajs/vue3';
import { ListFilter, Search } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const props = defineProps<{
    subjects: Question['subject'][];
    exams: string[];
}>();

// Initialize refs with current route parameters
const search = ref(route().params.search ?? undefined);
const subject = ref(route().params.subject ?? undefined);
const question_id = ref(route().params.question_id ?? undefined);
const test_type = ref(route().params.test_type ?? undefined);
const question = ref(route().params.question ?? undefined);


watch(
    [search, subject, question_id, test_type, question],
    ([
        new_search,
        new_subject,
        new_question_id,
        new_test_type,
        new_question,
    ]) => {
        console.log('CHANGES');
        const _route = route(String(route().current()), {
            ...route().params,
            search: new_search ?? undefined,
            question_id: new_question_id ?? undefined,
            test_type: new_test_type ?? undefined,
            subject: new_subject ?? undefined,
            question: new_question ?? undefined,
        });
        router.visit(
            _route, // Keep the current route
            {
                replace: false, // Avoid adding a new history entry
                preserveState: false, // Preserve the current page state
            },
        );
    },
);
</script>

<template>
    <div class="mb-6 flex justify-end gap-2">
        <div
            class="relative w-full items-center md:max-w-[300px] lg:max-w-[500px]"
        >
            <Input
                v-model.lazy.trim="search"
                type="search"
                placeholder="Filter by Question ID, Question, Test Type or Subject"
                class="w-full pl-10"
            />
            <span
                class="absolute inset-y-0 start-0 flex items-center justify-center px-2"
            >
                <Search class="size-6 text-muted-foreground" />
            </span>
        </div>

        <!-- Dropdown Filter -->
        <DropdownMenu>
            <DropdownMenuTrigger title="Filter" as="button">
                <ListFilter />
            </DropdownMenuTrigger>
            <DropdownMenuContent :avoid-collisions="true">
                <div class="rounded-md p-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label
                                for="test-type"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Exam Type
                            </label>
                            <select
                                id="test-type"
                                v-model="test_type"
                                class="h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                            >
                                <option value="">All Exam Types</option>
                                <option v-for="(exam, key) in exams">
                                    {{ exam }}
                                </option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label
                                for="subject"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Subject
                            </label>
                            <select
                                id="subject"
                                v-model="subject"
                                class="h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                            >
                                <option value="">All Subjects</option>
                                <option
                                    v-for="(subject, key) in subjects"
                                    :key="key"
                                    :value="subject.label"
                                >
                                    {{ subject.label }}
                                </option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label
                                for="question-id"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Question ID
                            </label>
                            <Input
                                id="question-id"
                                v-model="question_id"
                                type="text"
                                placeholder="Enter Question ID"
                                class="h-10 w-full"
                            />
                        </div>

                        <div class="space-y-2">
                            <label
                                for="question"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Question
                            </label>
                            <Input
                                id="question"
                                v-model="question"
                                type="text"
                                placeholder="Search questions"
                                class="h-10 w-full"
                            />
                        </div>
                    </div>
                </div>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>

<style scoped></style>
