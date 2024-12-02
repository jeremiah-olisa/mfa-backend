<script setup lang="ts">
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Question } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Eye } from 'lucide-vue-next';

const props = defineProps<{ questions: Question[] }>();

const formatDate = (date: string): string => {
    const options: Intl.DateTimeFormatOptions = {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    };
    return new Date(date).toLocaleDateString(undefined, options);
};
</script>

<template>
    <Table>
        <!--        <TableCaption>A list of questions</TableCaption>-->
        <TableHeader>
            <TableRow>
                <TableHead>Test Type</TableHead>
                <TableHead>Subject</TableHead>
                <TableHead>Question</TableHead>
                <TableHead>Uploaded At</TableHead>
                <TableHead class="text-center">Actions</TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="(question, key) in questions" :key="key">
                <TableCell>{{ question.test_type }}</TableCell>
                <TableCell>{{ question.subject.label }}</TableCell>
                <TableCell>{{ question.question }}</TableCell>
                <TableCell>{{ formatDate(question.created_at) }}</TableCell>
                <TableCell class="flex items-center justify-center">
                    <Link
                        :href="
                            route('questions.details', {
                                question_id: question.question_id,
                            })
                        "
                    >
                        <Eye />
                    </Link>
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
