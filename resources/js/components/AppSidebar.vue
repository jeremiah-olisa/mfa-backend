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
import { 
    Sidebar, 
    SidebarContent, 
    SidebarFooter, 
    SidebarHeader, 
    SidebarMenu, 
    SidebarMenuItem, 
    SidebarMenuButton,
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel,
} from '@/components/ui/sidebar';
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
// @ts-ignore
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
    <Sidebar collapsible="icon">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child class="md:h-16 md:p-0 hover:bg-transparent group-data-[collapsible=icon]:!p-2">
                        <Link :href="route('dashboard')">
                            <div class="flex items-center justify-center rounded-lg text-sidebar-primary-foreground w-full h-full">
                                <ApplicationLogo class="h-10 w-auto" />
                            </div>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <SidebarGroup>
                <SidebarGroupLabel>Platform</SidebarGroupLabel>
                <SidebarGroupContent>
                    <SidebarMenu>
                        <SidebarMenuItem v-for="item in navItems" :key="item.label">
                            <SidebarMenuButton :isActive="currentRoute === item.route" as-child>
                                <Link :href="route(item.route)">
                                    <component :is="item.icon" />
                                    <span>{{ item.label }}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroupContent>
            </SidebarGroup>
        </SidebarContent>

        <SidebarFooter>
            <SidebarMenu>
                <SidebarMenuItem>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <SidebarMenuButton
                                size="lg"
                                class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
                            >
                                <div class="flex aspect-square size-8 items-center justify-center rounded-lg bg-sidebar-primary text-sidebar-primary-foreground">
                                    {{ user.name.charAt(0).toUpperCase() }}
                                </div>
                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ user.name }}</span>
                                    <span class="truncate text-xs">{{ user.email }}</span>
                                </div>
                                <MoreHorizontal class="ml-auto size-4" />
                            </SidebarMenuButton>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="w-[--radix-dropdown-menu-trigger-width] min-w-56 rounded-lg" align="start" :side-offset="4">
                            <DropdownMenuLabel class="p-0 font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-sidebar-primary text-sidebar-primary-foreground">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div class="grid flex-1 text-left text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ user.name }}</span>
                                        <span class="truncate text-xs">{{ user.email }}</span>
                                    </div>
                                </div>
                            </DropdownMenuLabel>
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
                            <DropdownMenuItem as-child>
                                <Link :href="route('profile.edit')" class="w-full cursor-pointer">
                                    <Settings class="mr-2 h-4 w-4" />
                                    <span>Profile</span>
                                </Link>
                            </DropdownMenuItem>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem as-child>
                                <Link :href="route('logout')" method="post" as="button" class="w-full cursor-pointer text-destructive focus:bg-destructive/10 focus:text-destructive">
                                    <LogOut class="mr-2 h-4 w-4" />
                                    <span>Log out</span>
                                </Link>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarFooter>
    </Sidebar>
</template>
