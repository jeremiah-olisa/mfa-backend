<script setup lang="ts">
import CursorPagination from '@/components/CursorPagination.vue';
import UsersTable from '@/components/table/UsersTable.vue';
import UserTableFilter from '@/components/table/UserTableFilter.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { PaginationProps, UserTable as User } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

const props = defineProps<{
    users: User[];
    pagination: PaginationProps;
    roles: string[];
    apps: string[];
}>();

const users = ref(props.users ?? []);
const pagination = ref({
    ...props.pagination,
    items_count: props.users?.length || 0,
});
</script>

<template>
    <Head title="Users List" />

    <AuthenticatedLayout>
        <template #header>
             <div class="flex items-center justify-between">
                <h2 class="text-3xl font-bold tracking-tight text-foreground">Users</h2>
            </div>
        </template>

        <div class="space-y-4">
             <Card class="bg-card border-border">
                <CardHeader>
                    <CardTitle>User Management</CardTitle>
                </CardHeader>
                <CardContent>
                     <!-- Filters -->
                    <UserTableFilter :roles="roles" :apps="apps" />
                    
                    <div class="rounded-md border border-border mt-4">
                         <div class="overflow-x-auto">
                            <UsersTable :users="users" />
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
