<script setup lang="ts">
import { buttonVariants } from '@/components/ui/button';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { formatDate } from '@/lib/utils';
import { Head, Link } from '@inertiajs/vue3';
import { Eye, Upload } from 'lucide-vue-next';
import { computed, ref } from 'vue';

// Sample data (replace with your actual data source)
const questions = ref([
    {
        question_id: 1,
        test_type: 'Multiple Choice',
        subject: 'Math',
        question: 'What is 2+2?',
        uploaded_at: '2023-05-01T10:00:00Z',
    },
    {
        question_id: 2,
        test_type: 'Essay',
        subject: 'English',
        question: 'Describe your favorite book.',
        uploaded_at: '2023-05-02T11:30:00Z',
    },
    {
        question_id: 3,
        test_type: 'Multiple Choice',
        subject: 'Science',
        question: 'What is the chemical symbol for water?',
        uploaded_at: '2023-05-03T09:15:00Z',
    },
    {
        question_id: 4,
        test_type: 'True/False',
        subject: 'History',
        question: 'The American Revolutionary War ended in 1783.',
        uploaded_at: '2023-05-04T14:45:00Z',
    },
    {
        question_id: 5,
        test_type: 'Short Answer',
        subject: 'Geography',
        question: 'Name three countries in South America.',
        uploaded_at: '2023-05-05T08:30:00Z',
    },
    // Add more sample data here...
]);

const filters = ref({
    questionId: '',
    testType: '',
    subject: '',
});

const currentPage = ref(1);
const itemsPerPage = 10;

const filteredQuestions = computed(() => {
    return questions.value.filter(
        (q) =>
            (filters.value.questionId === '' ||
                q.question_id.toString().includes(filters.value.questionId)) &&
            (filters.value.testType === '' ||
                q.test_type === filters.value.testType) &&
            (filters.value.subject === '' ||
                q.subject === filters.value.subject),
    );
});

const totalPages = computed(() =>
    Math.ceil(filteredQuestions.value.length / itemsPerPage),
);

const paginatedQuestions = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredQuestions.value.slice(start, end);
});

const uniqueTestTypes = computed(() => [
    ...new Set(questions.value.map((q) => q.test_type)),
]);
const uniqueSubjects = computed(() => [
    ...new Set(questions.value.map((q) => q.subject)),
]);

function prevPage() {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
}

function nextPage() {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
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

                <Link
                    :class="buttonVariants({ variant: 'outline' })"
                    :href="route('questions.list')"
                >
                    <Upload class="mr-2" />
                    Upload Questions
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="p-6">
                    <!-- Filters -->
                    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                        <input
                            v-model="filters.questionId"
                            placeholder="Filter by Question ID"
                            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                        />
                        <select
                            v-model="filters.testType"
                            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                        >
                            <option value="">All Test Types</option>
                            <option
                                v-for="type in uniqueTestTypes"
                                :key="type"
                                :value="type"
                            >
                                {{ type }}
                            </option>
                        </select>
                        <select
                            v-model="filters.subject"
                            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                        >
                            <option value="">All Subjects</option>
                            <option
                                v-for="subject in uniqueSubjects"
                                :key="subject"
                                :value="subject"
                            >
                                {{ subject }}
                            </option>
                        </select>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th
                                        class="p-3 text-left font-semibold text-gray-600 dark:text-gray-200"
                                    >
                                        Question ID
                                    </th>
                                    <th
                                        class="p-3 text-left font-semibold text-gray-600 dark:text-gray-200"
                                    >
                                        Test Type
                                    </th>
                                    <th
                                        class="p-3 text-left font-semibold text-gray-600 dark:text-gray-200"
                                    >
                                        Subject
                                    </th>
                                    <th
                                        class="p-3 text-left font-semibold text-gray-600 dark:text-gray-200"
                                    >
                                        Question
                                    </th>
                                    <th
                                        class="p-3 text-left font-semibold text-gray-600 dark:text-gray-200"
                                    >
                                        Uploaded At
                                    </th>
                                    <th
                                        class="p-3 text-left font-semibold text-gray-600 dark:text-gray-200"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="question in paginatedQuestions"
                                    :key="question.question_id"
                                    class="border-t border-gray-200 dark:border-gray-700"
                                >
                                    <td
                                        class="p-3 text-gray-800 dark:text-gray-200"
                                    >
                                        {{ question.question_id }}
                                    </td>
                                    <td
                                        class="p-3 text-gray-800 dark:text-gray-200"
                                    >
                                        {{ question.test_type }}
                                    </td>
                                    <td
                                        class="p-3 text-gray-800 dark:text-gray-200"
                                    >
                                        {{ question.subject }}
                                    </td>
                                    <td
                                        class="p-3 text-gray-800 dark:text-gray-200"
                                    >
                                        {{ question.question }}
                                    </td>
                                    <td
                                        class="p-3 text-gray-800 dark:text-gray-200"
                                    >
                                        {{ formatDate(question.uploaded_at) }}
                                    </td>
                                    <td class="flex justify-center items-center">
                                        <Link
                                            :href="
                                                route('questions.details', {
                                                    question_id:
                                                        question.question_id,
                                                })
                                            "
                                        >
                                            <Eye />
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 flex items-center justify-between">
                        <button
                            @click="prevPage"
                            :disabled="currentPage === 1"
                            class="rounded-md bg-blue-500 px-4 py-2 text-white disabled:cursor-not-allowed disabled:opacity-50 dark:bg-blue-600 dark:hover:bg-blue-700"
                        >
                            Previous
                        </button>
                        <span class="text-gray-600 dark:text-gray-300"
                            >Page {{ currentPage }} of {{ totalPages }}</span
                        >
                        <button
                            @click="nextPage"
                            :disabled="currentPage === totalPages"
                            class="rounded-md bg-blue-500 px-4 py-2 text-white disabled:cursor-not-allowed disabled:opacity-50 dark:bg-blue-600 dark:hover:bg-blue-700"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
