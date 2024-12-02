<script setup>
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { defineProps } from 'vue';

defineProps({
    title: String,
    icon: Object,
    mainValue: [String, Number],
    stats: Array, // [{ label: "Students", value: "10" }, { label: "Admins", value: "5" }]
});
</script>

<template>
    <Card>
        <CardHeader
            class="flex flex-row items-center justify-between space-y-0 pb-2"
        >
            <CardTitle class="text-sm font-medium">
                {{ title }}
            </CardTitle>
            <component :is="icon" size="24" />
        </CardHeader>
        <CardContent>
            <div class="text-2xl font-bold">
                {{ mainValue }}
            </div>
            <p class="text-xs text-muted-foreground">
                <span
                    v-for="(stat, index) in stats"
                    :key="index"
                    class="block sm:inline"
                >
                    <b>{{ stat.label }}</b
                    >: {{ stat.value
                    }}<template v-if="index < stats.length - 1">, </template>
                </span>
            </p>
        </CardContent>
    </Card>
</template>
