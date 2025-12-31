<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { formatDate } from '@/lib/utils';
import { Head, router } from '@inertiajs/vue3';
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';

import UploadQuestionButton from '@/components/UploadQuestionButton.vue';
import { ref } from 'vue';

const props = defineProps<{
    question: any;
}>();
// Sample question data (replace with your actual data fetching logic)
const question = ref(props.question);

const deleteQuestion = async () => {
    if (confirm('Are you sure you want to delete this question?')) {
        try {
            // Send the DELETE request via Inertia
            router.delete(
                route('questions.destroy', {
                    question_id: props.question.question_id,
                }),
            );
            console.log('Question deleted successfully');

            // Optionally redirect after deletion
            router.visit(route('questions.list')); // Replace with your question list route
        } catch (error) {
            console.error('Error deleting question:', error);
        }
    }
};
</script>

<template>
    <Head title="Questions List" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-3xl font-bold tracking-tight text-foreground">Questions</h2>
                <div class="flex items-center gap-2">
                     <Button variant="destructive" @click="deleteQuestion">Delete Question</Button>
                     <UploadQuestionButton />
                </div>
            </div>
        </template>

        <div class="space-y-4">
            <Card>
                <CardHeader>
                    <CardTitle>Question Details</CardTitle>
                    <CardDescription>View detailed information about this question.</CardDescription>
                </CardHeader>
                <CardContent class="grid gap-6 md:grid-cols-2">
                     <div class="col-span-full space-y-1">
                        <span class="text-sm font-medium leading-none text-muted-foreground">Question</span>
                        <p class="text-lg font-semibold">{{ question.question }}</p>
                    </div>

                    <div class="space-y-1">
                         <span class="text-sm font-medium leading-none text-muted-foreground">Exam Type</span>
                        <p class="text-base">{{ question.test_type }}</p>
                    </div>

                    <div class="space-y-1">
                         <span class="text-sm font-medium leading-none text-muted-foreground">Subject</span>
                        <p class="text-base">{{ question.subject.label }}</p>
                    </div>

                    <div v-if="question.section" class="space-y-1">
                         <span class="text-sm font-medium leading-none text-muted-foreground">Section</span>
                        <p class="text-base">{{ question.section }}</p>
                    </div>

                    <div class="space-y-1">
                         <span class="text-sm font-medium leading-none text-muted-foreground">Uploaded At</span>
                        <p class="text-base">{{ formatDate(question.created_at) }}</p>
                    </div>

                    <div class="space-y-1">
                         <span class="text-sm font-medium leading-none text-muted-foreground">Updated At</span>
                        <p class="text-base">{{ formatDate(question.updated_at) }}</p>
                    </div>

                    <div v-if="question.options.length > 0" class="col-span-full space-y-3">
                         <span class="text-sm font-medium leading-none text-muted-foreground">Options</span>
                        <div class="space-y-2">
                            <div
                                v-for="option in question.options"
                                :key="option.id"
                                class="flex items-center justify-between rounded-md border p-3"
                            >
                                <span class="text-sm">{{ option.option }}</span>
                                <Badge v-if="option.is_correct" variant="default" class="bg-green-600 hover:bg-green-700">Correct</Badge>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
