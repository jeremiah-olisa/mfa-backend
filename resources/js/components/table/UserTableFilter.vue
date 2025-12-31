<script setup lang="ts">
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
    SheetFooter,
    SheetClose,
} from '@/components/ui/sheet';
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
import { ListFilter, Search, X } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const props = defineProps<{
    roles: string[];
    apps: string[];
}>();

// Initialize refs with current route params to persist state on reload
const params = route().params as Record<string, string>;
const search = ref(params.search || undefined);
const name = ref(params.name || undefined);
const email = ref(params.email || undefined);
const role = ref(params.role || undefined);
const app = ref(params.app || undefined);

// Apply filters
const applyFilters = () => {
    const _route = route(String(route().current()), {
        ...route().params,
        search: search.value ?? undefined,
        name: name.value ?? undefined,
        email: email.value ?? undefined,
        role: role.value ?? undefined,
        app: app.value ?? undefined,
    });
    router.visit(
        _route,
        {
            replace: true,
            preserveState: true,
            preserveScroll: true,
        },
    );
};

// Reset filters
const resetFilters = () => {
    name.value = undefined;
    email.value = undefined;
    role.value = undefined;
    app.value = undefined;
    applyFilters(); // Optional: apply immediately or wait for user. Let's apply immediately for "Reset".
};

// Watch search separately for live feedback (standard UX)
watch(search, (new_search) => {
     const _route = route(String(route().current()), {
        ...route().params,
        search: new_search ?? undefined,
    });
    router.visit(_route, { replace: true, preserveState: true, preserveScroll: true });
});
</script>

<template>
    <div class="flex justify-between items-center gap-2">
        <div class="relative w-full items-center md:max-w-[300px] lg:max-w-[500px]">
            <Input
                v-model.lazy.trim="search"
                type="search"
                placeholder="Filter by Name, Email, Role or App"
                class="w-full pl-10"
            />
            <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                <Search class="size-4 text-muted-foreground" />
            </span>
        </div>

        <Sheet>
            <SheetTrigger as-child>
                <Button variant="outline">
                    <ListFilter class="mr-2 h-4 w-4" />
                    Filters
                </Button>
            </SheetTrigger>
            <SheetContent class="w-[400px] sm:w-[540px]">
                <SheetHeader>
                    <SheetTitle>Filter Users</SheetTitle>
                    <SheetDescription>
                        Narrow down the user list using the specific fields below.
                    </SheetDescription>
                </SheetHeader>
                
                <div class="grid gap-4 py-4 px-4">
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

                <SheetFooter>
                    <SheetClose as-child>
                         <Button type="button" variant="ghost" @click="resetFilters">
                            Reset
                         </Button>
                    </SheetClose>
                    <SheetClose as-child>
                        <Button type="button" @click="applyFilters">Done</Button>
                    </SheetClose>
                </SheetFooter>
            </SheetContent>
        </Sheet>
    </div>
</template>

<style scoped></style>
