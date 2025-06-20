<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Upload, X, RefreshCw, CheckCircle, AlertTriangle } from 'lucide-vue-next';
import FormErrorAlert from '@/components/FormErrorAlert.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const fileInput = ref<HTMLInputElement | null>(null);
const fileName = ref<string | null>(null);
const uploadProgress = ref<number>(0);
const showSuccess = ref<boolean>(false);

const form = useForm({
    file: null as File | null,
});

const triggerFileInput = () => {
    if (fileInput.value && !form.processing) {
        fileInput.value.click();
    }
};

const reset = () => {
    if (form.processing) return;

    fileName.value = null;
    form.file = null;
    form.errors = {};
    uploadProgress.value = 0;
    showSuccess.value = false;

    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const handleFileChange = (event: Event) => {
    const fileInputElement = event.target as HTMLInputElement;
    if (fileInputElement.files?.[0]) {
        fileName.value = fileInputElement.files[0].name;
        form.file = fileInputElement.files[0];
        submitForm();
    } else {
        reset();
    }
};

const retryUpload = () => {
    if (form.file) {
        submitForm();
    }
};

const submitForm = () => {
    if (!form.file) return;

    showSuccess.value = false;

    form.post(route('questions.upload'), {
        onProgress: (progress) => {
            uploadProgress.value = Math.round(progress?.percentage || 75);
        },
        onSuccess: () => {
            showSuccess.value = true;
            setTimeout(() => showSuccess.value = false, 3000);
            reset();
        },
        onError: () => {
            // Errors are automatically handled by form.errors
        },
    });
};
</script>

<template>
    <div class="flex flex-col gap-3">
        <div class="flex items-center gap-2 flex-wrap">
            <!-- Main upload button -->
            <Button variant="outline" @click="triggerFileInput" :disabled="form.processing"
                class="h-auto text-wrap min-w-[120px] relative">
                <Upload class="mr-2 h-4 w-4" />
                <span class="truncate max-w-[180px]">
                    {{ form.processing ? 'Uploading...' : fileName ?? 'Upload Questions' }}
                </span>
            </Button>

            <!-- Clear button -->
            <Button v-if="form.file && !form.processing" variant="ghost" size="sm" @click="reset"
                class="text-destructive hover:text-destructive">
                <X class="h-4 w-4 mr-1" />
                Clear
            </Button>

            <!-- Retry button -->
            <Button v-if="form.errors && Object.keys(form.errors).length > 0 && !form.processing" variant="outline"
                size="sm" @click="retryUpload" class="text-amber-600 dark:text-amber-400">
                <RefreshCw class="h-4 w-4 mr-1" />
                Retry
            </Button>
        </div>

        <!-- Progress indicator -->
        <div v-if="form.processing" class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
            <div class="bg-primary h-2.5 rounded-full" :style="{ width: `${uploadProgress}%` }"></div>
        </div>

        <!-- Success message -->
        <div v-if="showSuccess" class="flex items-center text-sm text-green-600 dark:text-green-400">
            <CheckCircle class="h-4 w-4 mr-1" />
            File uploaded successfully!
        </div>

        <!-- Hidden file input -->
        <input ref="fileInput" type="file" accept=".xls,.xlsx,.csv" @change="handleFileChange" class="hidden" />
    </div>

    <!-- Error display -->
    <Teleport defer to="#teleport-alert">
        <FormErrorAlert v-if="Object.keys(form.errors).length > 0" :show-field-name="false" :errors="form.errors"
            :error-title="`Upload Error (${Object.keys(form.errors).length} ${Object.keys(form.errors).length === 1 ? 'Error' : 'Errors'})`" />
    </Teleport>
</template>

<style scoped>
.truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>