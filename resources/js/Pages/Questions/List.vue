<script setup lang="ts">
import UploadQuestionButton from '@/components/UploadQuestionButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { formatDate } from '@/lib/utils';
import { Head, Link } from '@inertiajs/vue3';
import { Eye } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    response: any;
}>();

// Sample data (replace with your actual data source)
const filters = ref({
    questionId: '',
    testType: '',
    subject: '',
});

const currentPage = ref(1);
const questions = ref(props.response['data'] ?? []);
const itemsPerPage = ref(15);

// const filteredQuestions = computed(() => {
//     return questions.value.filter(
//         (q) =>
//             (filters.value.questionId === '' ||
//                 q.question_id.toString().includes(filters.value.questionId)) &&
//             (filters.value.testType === '' ||
//                 q.test_type === filters.value.testType) &&
//             (filters.value.subject === '' ||
//                 q.subject === filters.value.subject),
//     );
// });
//
// const totalPages = computed(() =>
//     Math.ceil(filteredQuestions.value.length / itemsPerPage.value),
// );
//
// const paginatedQuestions = computed(() => {
//     const start = (currentPage.value - 1) * itemsPerPage.value;
//     const end = start + itemsPerPage.value;
//     return filteredQuestions.value.slice(start, end);
// });
//
// const uniqueTestTypes = computed(() => [
//     ...new Set(questions.value.map((q) => q.test_type)),
// ]);
// const uniqueSubjects = computed(() => [
//     ...new Set(questions.value.map((q) => q.subject)),
// ]);
//
// function prevPage() {
//     if (currentPage.value > 1) {
//         currentPage.value--;
//     }
// }
//
// function nextPage() {
//     if (currentPage.value < totalPages.value) {
//         currentPage.value++;
//     }
// }
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
                <div class="p-6">
                    <!-- Filters -->
                    <!--                    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">-->
                    <!--                        <input-->
                    <!--                            v-model="filters.questionId"-->
                    <!--                            placeholder="Filter by Question ID"-->
                    <!--                            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"-->
                    <!--                        />-->
                    <!--                        <select-->
                    <!--                            v-model="filters.testType"-->
                    <!--                            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"-->
                    <!--                        >-->
                    <!--                            <option value="">All Test Types</option>-->
                    <!--                            <option-->
                    <!--                                v-for="type in uniqueTestTypes"-->
                    <!--                                :key="type"-->
                    <!--                                :value="type"-->
                    <!--                            >-->
                    <!--                                {{ type }}-->
                    <!--                            </option>-->
                    <!--                        </select>-->
                    <!--                        <select-->
                    <!--                            v-model="filters.subject"-->
                    <!--                            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"-->
                    <!--                        >-->
                    <!--                            <option value="">All Subjects</option>-->
                    <!--                            <option-->
                    <!--                                v-for="subject in uniqueSubjects"-->
                    <!--                                :key="subject"-->
                    <!--                                :value="subject"-->
                    <!--                            >-->
                    <!--                                {{ subject }}-->
                    <!--                            </option>-->
                    <!--                        </select>-->
                    <!--                    </div>-->

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
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
                                    v-for="(question, key) in questions"
                                    :key="key"
                                    class="border-t border-gray-200 dark:border-gray-700"
                                >
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
                                        {{ formatDate(question.created_at) }}
                                    </td>
                                    <td
                                        class="flex items-center justify-center"
                                    >
                                        <Link
                                            :href="
                                                route('questions.details', {
                                                    question_id:
                                                        question?.question_id ??
                                                        1,
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
                    <!--                    <div class="mt-6 flex items-center justify-between">-->
                    <!--                        <button-->
                    <!--                            @click="prevPage"-->
                    <!--                            :disabled="currentPage === 1"-->
                    <!--                            class="rounded-md bg-blue-500 px-4 py-2 text-white disabled:cursor-not-allowed disabled:opacity-50 dark:bg-blue-600 dark:hover:bg-blue-700"-->
                    <!--                        >-->
                    <!--                            Previous-->
                    <!--                        </button>-->
                    <!--                        <span class="text-gray-600 dark:text-gray-300"-->
                    <!--                            >Page {{ currentPage }} of {{ totalPages }}</span-->
                    <!--                        >-->
                    <!--                        <button-->
                    <!--                            @click="nextPage"-->
                    <!--                            :disabled="currentPage === totalPages"-->
                    <!--                            class="rounded-md bg-blue-500 px-4 py-2 text-white disabled:cursor-not-allowed disabled:opacity-50 dark:bg-blue-600 dark:hover:bg-blue-700"-->
                    <!--                        >-->
                    <!--                            Next-->
                    <!--                        </button>-->
                    <!--                    </div>-->
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
