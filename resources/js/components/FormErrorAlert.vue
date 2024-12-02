<script setup lang="ts">
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { AlertCircle } from 'lucide-vue-next';

withDefaults(
    defineProps<{
        errors: Record<string, string>;
        showFieldName?: boolean;
        errorTitle?: string;
    }>(),
    {
        showFieldName: true,
    },
);
</script>

<template>
    <div v-if="Object.keys(errors).length > 0" class="py-4  ">
        <Alert variant="destructive">
            <AlertCircle class="h-4 w-4" />
            <AlertTitle>{{ errorTitle ?? 'Error' }}</AlertTitle>
            <AlertDescription>
                <ul>
                    <li v-for="(error, field) in errors" :key="field">
                        <strong v-if="showFieldName" class="uppercase"
                            >{{ field }}:</strong
                        >
                        {{ error }}
                    </li>
                </ul>
            </AlertDescription>
        </Alert>
    </div>
</template>

<style scoped></style>
