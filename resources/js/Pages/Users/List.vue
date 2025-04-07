<script setup lang="ts">
import CursorPagination from '@/components/CursorPagination.vue';
import UsersTable from '@/components/table/UsersTable.vue';
import UserTableFilter from '@/components/table/UserTableFilter.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { PaginationProps, UserTable as User } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    users: User[];
    pagination: PaginationProps;
    roles: string[]; // Assuming you have roles for filtering
    statuses: string[]; // Assuming you have statuses for filtering
}>();

const users = ref(props.users ?? []);
const pagination = ref(props.pagination ?? {});
</script>

<template>
    <Head title="Users List" />

    <AuthenticatedLayout>
        <template #header>
            <div
                class="flex w-full flex-wrap items-center justify-between gap-y-3"
            >
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Users
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="p-6">
                    <!-- Filters -->
                    <UserTableFilter :roles="roles" :statuses="statuses" />

                    <CursorPagination v-bind="pagination" />

                    <!-- Table -->
                    <div class="overflow-x-auto py-6">
                        <UsersTable :users="users" />
                    </div>

                    <CursorPagination v-bind="pagination" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
