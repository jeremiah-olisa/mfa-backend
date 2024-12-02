<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { formatDate } from '@/lib/utils';
import { Head, router } from '@inertiajs/vue3';

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
            <div
                class="flex w-full flex-wrap items-center justify-between gap-y-3"
            >
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Questions
                </h2>

                <UploadQuestionButton />
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="min-h-screen bg-gray-100 px-4 py-12 dark:bg-gray-900 sm:px-6 lg:px-8"
                >
                    <div
                        class="mx-auto max-w-3xl overflow-hidden rounded-lg bg-white shadow-md dark:bg-gray-800"
                    >
                        <div class="p-6">
                            <div class="mb-6 flex items-center justify-between">
                                <h1
                                    class="text-3xl font-bold text-gray-900 dark:text-gray-100"
                                >
                                    Question Details
                                </h1>
                                <button
                                    @click="deleteQuestion"
                                    class="rounded-md bg-red-500 px-4 py-2 text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                                >
                                    Delete Question
                                </button>
                            </div>

                            <div class="grid gap-4 space-y-4 md:grid-cols-2">
                                <!--                                <div>-->
                                <!--                                    <label-->
                                <!--                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"-->
                                <!--                                        >Question ID</label-->
                                <!--                                    >-->
                                <!--                                    <p-->
                                <!--                                        class="mt-1 text-lg text-gray-900 dark:text-gray-100"-->
                                <!--                                    >-->
                                <!--                                        {{ question.question_id }}-->
                                <!--                                    </p>-->
                                <!--                                </div>-->

                                <div class="col-span-full">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >Question</label
                                    >
                                    <p
                                        class="mt-1 text-lg text-gray-900 dark:text-gray-100"
                                    >
                                        {{ question.question }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >Exam Type</label
                                    >
                                    <p
                                        class="mt-1 text-lg text-gray-900 dark:text-gray-100"
                                    >
                                        {{ question.test_type }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >Subject</label
                                    >
                                    <p
                                        class="mt-1 text-lg text-gray-900 dark:text-gray-100"
                                    >
                                        {{ question.subject.label }}
                                    </p>
                                </div>

                                <div v-if="question.section">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >Section</label
                                    >
                                    <p
                                        class="mt-1 text-lg text-gray-900 dark:text-gray-100"
                                    >
                                        {{ question.section }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >Uploaded At</label
                                    >
                                    <p
                                        class="mt-1 text-lg text-gray-900 dark:text-gray-100"
                                    >
                                        {{ formatDate(question.created_at) }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >Updated At</label
                                    >
                                    <p
                                        class="mt-1 text-lg text-gray-900 dark:text-gray-100"
                                    >
                                        {{ formatDate(question.updated_at) }}
                                    </p>
                                </div>

                                <div
                                    v-if="question.options.length > 0"
                                    class="col-span-full"
                                >
                                    <label
                                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >Options</label
                                    >
                                    <ul class="space-y-2">
                                        <li
                                            v-for="option in question.options"
                                            :key="option.id"
                                            class="flex items-center justify-between rounded-md bg-gray-50 p-3 dark:bg-gray-700"
                                        >
                                            <span
                                                class="text-gray-900 dark:text-gray-100"
                                                >{{ option.option }}</span
                                            >
                                            <span
                                                v-if="option.is_correct"
                                                class="rounded-full bg-green-500 px-2 py-1 text-sm text-white"
                                            >
                                                Correct
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
