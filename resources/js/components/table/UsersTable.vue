<script setup lang="ts">
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { UserTable } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { MoreHorizontal } from 'lucide-vue-next';

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

const appBadgeClass = (app: string) => {
    const apps: Record<string, string> = {
        'WAEC': 'bg-purple-100 text-purple-800 hover:bg-purple-100 dark:bg-purple-900/30 dark:text-purple-300',
        'NECO': 'bg-blue-100 text-blue-800 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-300',
        'JAMB': 'bg-green-100 text-green-800 hover:bg-green-100 dark:bg-green-900/30 dark:text-green-300',
        'OYO': 'bg-yellow-100 text-yellow-800 hover:bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-300',
        'WEB': 'bg-indigo-100 text-indigo-800 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-300',
        'ADMIN': 'bg-red-100 text-red-800 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-300'
    };
    return apps[app] || 'bg-gray-100 text-gray-800 hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-300';
};

const copyId = (id: number) => {
    navigator.clipboard.writeText(id.toString());
};

const revokeAccess = (id: number) => {
    if (confirm('Are you sure you want to revoke access for this user?')) {
        router.post(route('users.revoke', id));
    }
};

const logoutUser = (id: number) => {
    if (confirm('Are you sure you want to log this user out of all devices?')) {
        router.post(route('users.logout', id));
    }
};
</script>

<template>
    <Table>
        <TableHeader>
            <TableRow>
                <TableHead>User</TableHead>
                <TableHead>Phone Number</TableHead>
                <TableHead>Role</TableHead>
                <TableHead>App</TableHead>
                <TableHead>Referral Code</TableHead>
                <TableHead>Current Plan</TableHead>
                <TableHead>Registered At</TableHead>
                <TableHead class="text-right">Actions</TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="(user, key) in users" :key="key">
                <TableCell>
                    <div class="flex flex-col">
                        <span class="font-medium">{{ user?.name }}</span>
                        <span class="text-xs text-muted-foreground">{{ user?.email }}</span>
                    </div>
                </TableCell>
                <TableCell>{{ user?.profile?.phone ?? "N/A" }}</TableCell>
                <TableCell>
                    <Badge variant="outline" class="font-medium capitalize"
                        :class="{
                            'bg-purple-100 text-purple-800 border-transparent hover:bg-purple-100 dark:bg-purple-900/30 dark:text-purple-300': user?.role === 'admin',
                            'bg-blue-100 text-blue-800 border-transparent hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-300': user?.role === 'user',
                            'bg-green-100 text-green-800 border-transparent hover:bg-green-100 dark:bg-green-900/30 dark:text-green-300': user?.role === 'moderator',
                            'bg-gray-100 text-gray-800 border-transparent hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-300': !['admin', 'user', 'moderator'].includes(user?.role)
                        }">
                        {{ user?.role }}
                    </Badge>
                </TableCell>
                <TableCell>
                    <Badge variant="outline" class="font-medium capitalize"
                        :class="appBadgeClass(user?.user_app) + ' border-transparent'">
                        {{ user?.user_app || 'N/A' }}
                    </Badge>
                </TableCell>
                <TableCell>{{ user?.referral_code || 'N/A' }}</TableCell>
                <TableCell>
                    {{ new Date(user?.plan_expires_at) < new Date() ? (user?.plan || 'N/A') : 'N/A' }} 
                </TableCell>
                <TableCell>{{ formatDate(user?.created_at) }}</TableCell>
                <TableCell class="text-right">
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <Button variant="ghost" class="h-8 w-8 p-0">
                                <span class="sr-only">Open menu</span>
                                <MoreHorizontal class="h-4 w-4" />
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                            <DropdownMenuLabel>Actions</DropdownMenuLabel>
                            <DropdownMenuItem @click="copyId(user.id)">
                                Copy User ID
                            </DropdownMenuItem>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem as-child>
                                <!-- @vue-ignore -->
                                <Link :href="route('users.edit', user.id)">Edit User</Link>
                            </DropdownMenuItem>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem @click="revokeAccess(user.id)" class="text-red-600 focus:text-red-600">
                                Revoke Access
                            </DropdownMenuItem>
                            <DropdownMenuItem @click="logoutUser(user.id)" class="text-orange-600 focus:text-orange-600">
                                Logout User
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>

<style scoped></style>
