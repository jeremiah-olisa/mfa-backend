<script setup lang="ts">
import CursorPagination from '@/components/CursorPagination.vue';
import QuestionsTable from '@/components/table/QuestionsTable.vue';
import QuestionTableFilter from '@/components/table/QuestionTableFilter.vue';
import UploadQuestionButton from '@/components/UploadQuestionButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { PaginationProps, Question } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

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
            <div class="flex items-center justify-between">
                <h2 class="text-3xl font-bold tracking-tight text-foreground">Questions</h2>
                <UploadQuestionButton />
            </div>
        </template>

        <div class="space-y-4">
            <Card class="bg-card border-border">
                <CardHeader>
                    <CardTitle>Question Management</CardTitle>
                </CardHeader>
                <CardContent>
                    <!-- Filters -->
                    <QuestionTableFilter :subjects="subjects" :exams="exams" />
                    
                    <div class="rounded-md border border-border mt-4">
                        <div class="overflow-x-auto">
                            <QuestionsTable :questions="questions" />
                        </div>
                    </div>

                    <div class="mt-4">
                        <CursorPagination v-bind="pagination" />
                    </div>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
