<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { formatDate } from '@/lib/utils';
import { Head } from '@inertiajs/vue3';
import { Upload } from 'lucide-vue-next';

import { ref } from 'vue';

// Sample question data (replace with your actual data fetching logic)
const question = ref({
    question_id: 1,
    test_type: 'Multiple Choice',
    subject: 'Science',
    question: 'What is the chemical symbol for water?',
    uploaded_at: '2023-05-01T10:00:00Z',
    updated_at: '2023-05-02T15:30:00Z',
    answer_id: 2,
    options: [
        { id: 1, text: 'H2O2' },
        { id: 2, text: 'H2O' },
        { id: 3, text: 'CO2' },
        { id: 4, text: 'NaCl' },
    ],
});

function deleteQuestion() {
    // Implement your delete logic here
    console.log('Deleting question:', question.value.question_id);
    // You might want to show a confirmation dialog before actually deleting
    // After deletion, you could redirect to a question list page
}
</script>

<template>
    <Head title="Questions List" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex w-full items-center justify-between">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Questions
                </h2>

                <Button variant="outline">
                    <Upload class="mr-2" />
                    Upload Questions
                </Button>
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

                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >Question ID</label
                                    >
                                    <p
                                        class="mt-1 text-lg text-gray-900 dark:text-gray-100"
                                    >
                                        {{ question.question_id }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >Test Type</label
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
                                        {{ question.subject }}
                                    </p>
                                </div>

                                <div>
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
                                        >Uploaded At</label
                                    >
                                    <p
                                        class="mt-1 text-lg text-gray-900 dark:text-gray-100"
                                    >
                                        {{ formatDate(question.uploaded_at) }}
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

                                <div>
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
                                                >{{ option.text }}</span
                                            >
                                            <span
                                                v-if="
                                                    option.id ===
                                                    question.answer_id
                                                "
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
