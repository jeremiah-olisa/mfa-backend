<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { 
    Users, 
    BookOpenCheck, 
    Clock
} from 'lucide-vue-next';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';

const props = defineProps<{
    questions: number;
    WAEC: number;
    NECO: number;
    JAMB: number;
    users: number;
    students: number;
    admins: number;
    content_managers: number;
}>();
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-3xl font-bold tracking-tight text-foreground">Dashboard</h2>
                <div class="flex items-center space-x-2">
                    <Button>
                        <Clock class="mr-2 h-4 w-4" />
                        Last 24 Hours
                    </Button>
                </div>
            </div>
        </template>

        <div class="space-y-4">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
                <Card class="col-span-4 bg-card border-border">
                    <CardHeader>
                        <CardTitle class="text-base font-medium">Platform Stats</CardTitle>
                        <CardDescription>
                            Current system overview
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <Users class="mr-4 h-4 w-4 text-muted-foreground" />
                                    <div class="flex-1 space-y-1">
                                        <p class="text-sm font-medium leading-none">Total Users</p>
                                        <p class="text-xs text-muted-foreground">Admins, Students, Managers</p>
                                    </div>
                                    <div class="font-bold">{{ props.users }}</div>
                                </div>
                                <div class="flex items-center">
                                    <BookOpenCheck class="mr-4 h-4 w-4 text-muted-foreground" />
                                    <div class="flex-1 space-y-1">
                                        <p class="text-sm font-medium leading-none">Questions</p>
                                        <p class="text-xs text-muted-foreground">Across all subjects</p>
                                    </div>
                                    <div class="font-bold">{{ props.questions }}</div>
                                </div>
                            </div>
                            <Separator class="my-4" />
                            <div class="grid grid-cols-3 gap-2 text-center text-xs">
                                <div class="bg-secondary/50 p-2 rounded">
                                    <div class="font-bold text-foreground">{{ props.WAEC }}</div>
                                    <div class="text-muted-foreground">WAEC</div>
                                </div>
                                <div class="bg-secondary/50 p-2 rounded">
                                    <div class="font-bold text-foreground">{{ props.NECO }}</div>
                                    <div class="text-muted-foreground">NECO</div>
                                </div>
                                <div class="bg-secondary/50 p-2 rounded">
                                    <div class="font-bold text-foreground">{{ props.JAMB }}</div>
                                    <div class="text-muted-foreground">JAMB</div>
                                </div>
                            </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
