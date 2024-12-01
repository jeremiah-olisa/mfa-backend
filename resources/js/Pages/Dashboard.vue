<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { BookOpenCheck, Users } from 'lucide-vue-next';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <Card>
                        <CardHeader
                            class="flex flex-row items-center justify-between space-y-0 pb-2"
                        >
                            <CardTitle class="text-sm font-medium">
                                Total Students
                            </CardTitle>
                            <Users :size="24" />
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">45</div>
                            <!--                            <p class="text-xs text-muted-foreground">-->
                            <!--                                +20.1% from last month-->
                            <!--                            </p>-->
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader
                            class="flex flex-row items-center justify-between space-y-0 pb-2"
                        >
                            <CardTitle class="text-sm font-medium">
                                Total Questions
                            </CardTitle>
                            <BookOpenCheck :size="24" />
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">45</div>
                            <!--                            <p class="text-xs text-muted-foreground">-->
                            <!--                                +20.1% from last month-->
                            <!--                            </p>-->
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
