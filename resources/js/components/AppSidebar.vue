<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { 
    LayoutDashboard, 
    BookOpenCheck,
    Users as UsersIcon,
    MoreHorizontal,
    Settings,
    LogOut,
    Moon,
    Sun,
    Monitor
} from 'lucide-vue-next';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { 
    DropdownMenu, 
    DropdownMenuContent, 
    DropdownMenuItem, 
    DropdownMenuLabel, 
    DropdownMenuSeparator, 
    DropdownMenuTrigger,
    DropdownMenuSub,
    DropdownMenuSubTrigger,
    DropdownMenuSubContent
} from '@/components/ui/dropdown-menu';
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import { useColorMode } from '@vueuse/core';

const page = usePage();
const user = computed(() => page.props.auth?.user || { name: 'User', email: 'user@example.com' });

const currentRoute = computed(() => route().current());

const navItems = [
    { label: 'Dashboard', icon: LayoutDashboard, route: 'dashboard' },
    { label: 'Questions', icon: BookOpenCheck, route: 'questions.list' },
    { label: 'Users', icon: UsersIcon, route: 'users.list' },
];

const mode = useColorMode({ emitAuto: true });
</script>

<template>
    <div class="flex h-screen w-64 flex-col border-r border-border bg-card">
        <!-- Team/App Switcher -->
        <div class="p-4">
            <Link :href="route('dashboard')" class="flex items-center gap-2 px-2">
                <ApplicationLogo class="h-20 w-auto" />
            </Link>
        </div>

        <!-- Main Navigation -->
        <div class="flex-1 overflow-y-auto px-4 py-2 custom-scrollbar">
            <nav class="space-y-1">
                <div v-for="item in navItems" :key="item.label">
                    <Link 
                        :href="route(item.route)" 
                        class="flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium transition-colors hover:bg-secondary/50"
                        :class="[
                            currentRoute === item.route 
                                ? 'bg-secondary text-primary' 
                                : 'text-muted-foreground hover:text-foreground'
                        ]"
                    >
                        <component :is="item.icon" class="h-4 w-4" />
                        {{ item.label }}
                    </Link>
                </div>
            </nav>
        </div>

        <!-- User Profile & Theme -->
        <div class="border-t border-border p-4 space-y-2">
             <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <Button variant="ghost" class="w-full justify-start px-2 hover:bg-muted/50 h-auto py-2">
                         <div class="flex items-center gap-2 text-left w-full">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-secondary text-foreground">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </div>
                             <div class="flex flex-col flex-1 overflow-hidden">
                                <span class="truncate text-sm font-medium text-foreground">{{ user.name }}</span>
                                <span class="truncate text-xs text-muted-foreground">{{ user.email }}</span>
                            </div>
                            <MoreHorizontal class="h-4 w-4 text-muted-foreground" />
                        </div>
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent class="w-56" align="start" :side-offset="8">
                     <DropdownMenuLabel>My Account</DropdownMenuLabel>
                     <DropdownMenuSeparator />
                     
                     <DropdownMenuSub>
                        <DropdownMenuSubTrigger>
                            <Moon class="mr-2 h-4 w-4 rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0" />
                            <Sun class="absolute mr-2 h-4 w-4 rotate-90 scale-0 transition-all dark:rotate-0 dark:scale-100" />
                            <span>Theme</span>
                        </DropdownMenuSubTrigger>
                        <DropdownMenuSubContent>
                            <DropdownMenuItem @click="mode = 'light'">
                                <Sun class="mr-2 h-4 w-4" />
                                <span>Light</span>
                            </DropdownMenuItem>
                            <DropdownMenuItem @click="mode = 'dark'">
                                <Moon class="mr-2 h-4 w-4" />
                                <span>Dark</span>
                            </DropdownMenuItem>
                            <DropdownMenuItem @click="mode = 'auto'">
                                <Monitor class="mr-2 h-4 w-4" />
                                <span>System</span>
                            </DropdownMenuItem>
                        </DropdownMenuSubContent>
                     </DropdownMenuSub>

                     <DropdownMenuSeparator />
                     <DropdownMenuItem>
                        <Settings class="mr-2 h-4 w-4" />
                        <Link :href="route('profile.edit')" class="w-full">Profile</Link>
                     </DropdownMenuItem>
                     <DropdownMenuSeparator />
                     <DropdownMenuItem>
                         <LogOut class="mr-2 h-4 w-4 text-destructive" />
                         <Link :href="route('logout')" method="post" as="button" class="w-full text-left text-destructive">
                            Log out
                         </Link>
                     </DropdownMenuItem>

                </DropdownMenuContent>
             </DropdownMenu>
        </div>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 4px;
}
</style>
