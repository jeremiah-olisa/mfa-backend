<script setup lang="ts">
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
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
                <Search class="size-4 text-muted-foreground" />
            </span>
        </div>

        <!-- Dropdown Filter -->
        <DropdownMenu>
            <DropdownMenuTrigger as-child>
                <Button variant="outline" size="icon">
                    <ListFilter class="h-4 w-4" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent :avoid-collisions="true" class="w-80">
                <div class="p-4 space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="space-y-2">
                            <Label for="test-type">Exam Type</Label>
                            <Select v-model="test_type">
                                <SelectTrigger>
                                    <SelectValue placeholder="All Exam Types" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">All Exam Types</SelectItem>
                                    <SelectItem v-for="(exam, key) in exams" :key="key" :value="exam">
                                         {{ exam }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <div class="space-y-2">
                            <Label for="subject">Subject</Label>
                            <Select v-model="subject">
                                <SelectTrigger>
                                    <SelectValue placeholder="All Subjects" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">All Subjects</SelectItem>
                                    <SelectItem
                                        v-for="(subject, key) in subjects"
                                        :key="key"
                                        :value="subject.label"
                                    >
                                        {{ subject.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <div class="space-y-2">
                             <Label for="question-id">Question ID</Label>
                            <Input
                                id="question-id"
                                v-model="question_id"
                                type="text"
                                placeholder="Enter Question ID"
                            />
                        </div>

                        <div class="space-y-2">
                             <Label for="question">Question</Label>
                            <Input
                                id="question"
                                v-model="question"
                                type="text"
                                placeholder="Search questions"
                            />
                        </div>
                    </div>
                </div>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>

<style scoped></style>
