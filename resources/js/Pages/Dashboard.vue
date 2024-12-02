<script setup lang="ts">
import StatCard from '@/components/StatCard.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { BookOpenCheck, Users } from 'lucide-vue-next';

const $props = defineProps<{
    questions: number;
    WAEC: number;
    NECO: number;
    JAMB: number;
    users: number;
    students: number;
    admins: number;
    content_managers: number;
}>();

const cardStats = [
    {
        title: 'Total Users',
        icon: Users,
        mainValue: $props.users,
        stats: [
            { label: 'Students', value: $props.students },
            { label: 'Admins', value: $props.admins },
            { label: 'Content Managers', value: $props.content_managers },
        ],
    },
    {
        title: 'Total Questions',
        icon: BookOpenCheck,
        mainValue: $props.questions,
        stats: [
            { label: 'WAEC', value: $props.WAEC },
            { label: 'NECO', value: $props.NECO },
            { label: 'JAMB', value: $props.JAMB },
        ],
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <StatCard
                        v-for="(card, index) in cardStats"
                        :key="index"
                        :title="card.title"
                        :icon="card.icon"
                        :mainValue="card.mainValue"
                        :stats="card.stats"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
