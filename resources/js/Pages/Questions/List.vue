<script setup lang="ts">
import CursorPagination from '@/components/CursorPagination.vue';
import QuestionsTable from '@/components/table/QuestionsTable.vue';
import QuestionTableFilter from '@/components/table/QuestionTableFilter.vue';
import UploadQuestionButton from '@/components/UploadQuestionButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { PaginationProps, Question } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    questions: Question[];
    pagination: PaginationProps;
    subjects: Question['subject'][];
    exams: string[];
}>();

const questions = ref(props.questions ?? []);
const pagination = ref({
    ...props.pagination,
    items_count: props.questions.length ?? 0,
});
</script>

<template>

    <Head title="Questions List" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex w-full flex-wrap flex-col justify-between gap-y-3">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Questions
                </h2>

                <UploadQuestionButton />
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="p-6">
                    <!-- Filters -->
                    <QuestionTableFilter :subjects="subjects" :exams="exams" />

                    <CursorPagination v-bind="pagination" />

                    <!-- Table -->
                    <div class="overflow-x-auto py-6">
                        <QuestionsTable :questions="questions" />
                    </div>

                    <CursorPagination v-bind="pagination" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
