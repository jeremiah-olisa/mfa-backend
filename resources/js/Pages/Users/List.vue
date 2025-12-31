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
             <Card>
                <CardContent>
                     <!-- Filters -->
                    <div class="py-4">
                        <UserTableFilter :roles="roles" :apps="apps" />
                    </div>
                    
                    <div class="border-t border-border">
                         <div class="overflow-x-auto">
                            <UsersTable :users="users" />
                        </div>
                    </div>

                    <div class="p-4 border-t border-border">
                        <CursorPagination v-bind="pagination" />
                    </div>
                </CardContent>
             </Card>
        </div>
    </AuthenticatedLayout>
</template>
