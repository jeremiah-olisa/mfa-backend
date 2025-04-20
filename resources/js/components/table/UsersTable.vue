<script setup lang="ts">
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { UserTable } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Eye, Pencil } from 'lucide-vue-next';

const props = defineProps<{ users: UserTable[] }>();

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

const appBadgeClass = (app) => {
    const apps = {
        'WAEC': 'bg-purple-100 text-purple-800',
        'NECO': 'bg-blue-100 text-blue-800',
        'JAMB': 'bg-green-100 text-green-800',
        'OYO': 'bg-yellow-100 text-yellow-800',
        'WEB': 'bg-indigo-100 text-indigo-800',
        'ADMIN': 'bg-red-100 text-red-800'
    };
    return apps[app] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Table>
        <TableHeader>
            <TableRow>
                <TableHead>Name</TableHead>
                <TableHead>Email</TableHead>
                <TableHead>Phone Number</TableHead>
                <TableHead>Role</TableHead>
                <TableHead>App</TableHead>
                <TableHead>Registered At</TableHead>
                <TableHead class="text-center">Actions</TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="(user, key) in users" :key="key">
                <TableCell>{{ user?.name }}</TableCell>
                <TableCell>{{ user?.email }}</TableCell>
                <TableCell>{{ user?.profile?.phone ?? "N/A" }}</TableCell>
                <TableCell>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize"
                        :class="{
                            'bg-purple-100 text-purple-800': user?.role === 'admin',
                            'bg-blue-100 text-blue-800': user?.role === 'user',
                            'bg-green-100 text-green-800': user?.role === 'moderator',
                            'bg-gray-100 text-gray-800': !['admin', 'user', 'moderator'].includes(user?.role)
                        }">
                        {{ user?.role }}
                    </span>
                </TableCell>
                <TableCell>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize"
                        :class="appBadgeClass(user?.user_app)">
                        {{ user?.user_app || 'N/A' }}
                    </span>
                </TableCell>
                <TableCell>{{ formatDate(user?.created_at) }}</TableCell>
                <TableCell class="flex items-center justify-center gap-2">
                    <Link href="" class="text-green-600 hover:text-green-800">
                    <Pencil class="h-4 w-4" />
                    </Link>
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>

<style scoped></style>
