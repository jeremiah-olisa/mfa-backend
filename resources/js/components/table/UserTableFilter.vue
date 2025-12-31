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
                            <Label for="name">Name</Label>
                            <Input
                                id="name"
                                v-model="name"
                                type="text"
                                placeholder="Search by name"
                            />
                        </div>

                        <div class="space-y-2">
                             <Label for="email">Email</Label>
                            <Input
                                id="email"
                                v-model="email"
                                type="text"
                                placeholder="Search by email"
                            />
                        </div>

                        <div class="space-y-2">
                             <Label for="role">Role</Label>
                             <Select v-model="role">
                                <SelectTrigger>
                                    <SelectValue placeholder="All Roles" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">All Roles</SelectItem>
                                    <SelectItem v-for="(r, key) in roles" :key="key" :value="r">
                                        {{ r }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <div class="space-y-2">
                             <Label for="app">App</Label>
                             <Select v-model="app">
                                <SelectTrigger>
                                    <SelectValue placeholder="All Apps" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">All Apps</SelectItem>
                                    <SelectItem v-for="(a, key) in apps" :key="key" :value="a">
                                        {{ a }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                </div>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>

<style scoped></style>
