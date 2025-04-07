<script setup lang="ts">
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { router } from '@inertiajs/vue3';
import { ListFilter, Search } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const props = defineProps<{
    roles: string[];
    apps: string[]; // Assuming you have different apps in your system
}>();

const search = ref(undefined);
const name = ref(undefined);
const email = ref(undefined);
const role = ref(undefined);
const app = ref(undefined);

watch(
    [search, name, email, role, app],
    ([new_search, new_name, new_email, new_role, new_app]) => {
        const _route = route(String(route().current()), {
            ...route().params,
            search: new_search ?? undefined,
            name: new_name ?? undefined,
            email: new_email ?? undefined,
            role: new_role ?? undefined,
            app: new_app ?? undefined,
        });
        router.visit(
            _route,
            {
                replace: false,
                preserveState: false,
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
                placeholder="Filter by Name, Email, Role or App"
                class="w-full pl-10"
            />
            <span
                class="absolute inset-y-0 start-0 flex items-center justify-center px-2"
            >
                <Search class="size-6 text-muted-foreground" />
            </span>
        </div>

        <!-- Dropdown Filter -->
        <DropdownMenu>
            <DropdownMenuTrigger title="Filter" as="button">
                <ListFilter />
            </DropdownMenuTrigger>
            <DropdownMenuContent :avoid-collisions="true">
                <div class="rounded-md p-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label
                                for="name"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Name
                            </label>
                            <Input
                                id="name"
                                v-model="name"
                                type="text"
                                placeholder="Search by name"
                                class="h-10 w-full"
                            />
                        </div>

                        <div class="space-y-2">
                            <label
                                for="email"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Email
                            </label>
                            <Input
                                id="email"
                                v-model="email"
                                type="text"
                                placeholder="Search by email"
                                class="h-10 w-full"
                            />
                        </div>

                        <div class="space-y-2">
                            <label
                                for="role"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Role
                            </label>
                            <select
                                id="role"
                                v-model="role"
                                class="h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                            >
                                <option value="">All Roles</option>
                                <option v-for="(role, key) in roles" :key="key">
                                    {{ role }}
                                </option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label
                                for="app"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                App
                            </label>
                            <select
                                id="app"
                                v-model="app"
                                class="h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                            >
                                <option value="">All Apps</option>
                                <option v-for="(app, key) in apps" :key="key">
                                    {{ app }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>

<style scoped></style>
