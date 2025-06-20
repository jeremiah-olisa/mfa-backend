<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Upload, X, RefreshCw, CheckCircle, AlertTriangle, FileText } from 'lucide-vue-next';
import FormErrorAlert from '@/components/FormErrorAlert.vue';
import { useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
const MAX_FILES_UPLOAD = 5;
const fileInput = ref<HTMLInputElement | null>(null);
const fileNames = ref<string[]>([]);
const uploadProgress = ref<number>(0);
const showSuccess = ref<boolean>(false);
const isDragging = ref<boolean>(false);

const form = useForm({
    files: [] as File[],
});

// Computed property to check if we have files
const hasFiles = computed(() => form.files.length > 0);

const triggerFileInput = () => {
    if (fileInput.value && !form.processing) {
        fileInput.value.click();
    }
};

const reset = (resetErr = true) => {
    if (form.processing) return;

    fileNames.value = [];
    form.files = [];
    if (resetErr)
        form.errors = {};
    uploadProgress.value = 0;
    showSuccess.value = false;

    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const handleFileChange = (event: Event) => {
    const fileInputElement = event.target as HTMLInputElement;
    if (fileInputElement.files?.length) {
        processFiles(Array.from(fileInputElement.files));
    } else {
        reset();
    }
};

const processFiles = (files: File[]) => {
    const validFiles = files.filter(file =>
        ['.xls', '.xlsx', '.csv'].some(ext => file.name.toLowerCase().endsWith(ext)))
        .slice(0, MAX_FILES_UPLOAD); // Limit file upload

    if (validFiles.length) {
        form.files = validFiles;
        fileNames.value = validFiles.map(file => file.name);
        submitForm();
    }
};

const retryUpload = () => {
    if (form.files.length) {
        submitForm();
    }
};

const submitForm = () => {
    if (!form.files.length) return;

    showSuccess.value = false;

    form.post(route('questions.upload.multiple-smart'), {
        onProgress: (progress) => {
            const progressPercentage = progress?.percentage || 95;
            uploadProgress.value = Math.round(progressPercentage < 100 ? progressPercentage : 99);
        },
        onSuccess: () => {
            showSuccess.value = true;
            reset(false);
            setTimeout(() => {
                // Reload the page after showing success message
                showSuccess.value = false;

                // if (form.errors && Object.keys(form.errors).length > 0)
                window.location.reload();
            }, 5000);
        },
        onError: () => {
            // Errors are automatically handled by form.errors
        },
    });
};

// Drag and drop handlers
const handleDragEnter = (e: DragEvent) => {
    e.preventDefault();
    isDragging.value = true;
};

const handleDragLeave = (e: DragEvent) => {
    e.preventDefault();
    isDragging.value = false;
};

const handleDragOver = (e: DragEvent) => {
    e.preventDefault();
};

const handleDrop = (e: DragEvent) => {
    e.preventDefault();
    isDragging.value = false;

    if (e.dataTransfer?.files.length) {
        processFiles(Array.from(e.dataTransfer.files));
    }
};
</script>

<template>
    <div class="flex flex-col gap-3">
        <!-- Drag and drop zone -->
        <div @dragenter="handleDragEnter" @dragleave="handleDragLeave" @dragover="handleDragOver" @drop="handleDrop"
            :class="[
                'border-2 border-dashed rounded-lg p-6 text-center transition-colors',
                isDragging ? 'border-primary bg-primary/10' : 'border-muted-foreground/30',
                hasFiles ? 'hidden' : 'block'
            ]">
            <div class="flex flex-col items-center justify-center gap-2">
                <Upload class="h-10 w-10 text-muted-foreground" />
                <p class="font-medium">Drag and drop your files here</p>
                <p class="text-sm text-muted-foreground">or</p>
                <Button variant="outline" @click="triggerFileInput">
                    Browse Files
                </Button>
                <p class="text-xs text-muted-foreground mt-2">
                    Supported formats: .xls, .xlsx, .csv (Max {{ MAX_FILES_UPLOAD }} files)
                </p>
            </div>
        </div>

        <!-- File list and controls (shown when files are selected) -->
        <div v-if="hasFiles" class="space-y-4">
            <!-- File list -->
            <div class="space-y-2">
                <div v-for="(file, index) in form.files" :key="index"
                    class="flex items-center gap-3 p-2 border rounded">
                    <FileText class="h-5 w-5 text-muted-foreground" />
                    <span class="truncate flex-1">{{ file.name }}</span>
                    <span class="text-sm text-muted-foreground">{{ (file.size / 1024).toFixed(1) }} KB</span>
                </div>
            </div>

            <!-- Controls -->
            <div class="flex items-center gap-2 flex-wrap">
                <Button variant="outline" @click="triggerFileInput" :disabled="form.processing" class="h-auto">
                    <Upload class="mr-2 h-4 w-4" />
                    Add More Files
                </Button>

                <!-- Clear button -->
                <Button v-if="!form.processing" variant="outline" size="sm" @click="reset"
                    class="text-destructive dark:text-red-300 hover:text-destructive">
                    <X class="h-4 w-4 mr-1" />
                    Clear All
                </Button>

                <!-- Retry button -->
                <Button v-if="form.errors && Object.keys(form.errors).length > 0 && !form.processing" variant="outline"
                    size="sm" @click="retryUpload" class="text-amber-600 dark:text-amber-400">
                    <RefreshCw class="h-4 w-4 mr-1" />
                    Retry
                </Button>

                <!-- Upload button -->
                <Button @click="submitForm" :disabled="form.processing" class="ml-auto">
                    <Upload class="mr-2 h-4 w-4" />
                    {{ form.processing ? `Uploading (${uploadProgress}%)` : 'Upload All' }}
                </Button>
            </div>

            <!-- Progress indicator -->
            <div v-if="form.processing" class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                <div class="bg-primary h-2.5 rounded-full transition-all duration-300"
                    :style="{ width: `${uploadProgress}%` }"></div>
            </div>

            <!-- Success message -->
            <div v-if="showSuccess" class="flex items-center text-sm text-green-600 dark:text-green-400">
                <CheckCircle class="h-4 w-4 mr-1" />
                Files uploaded successfully!
            </div>
        </div>

        <!-- Hidden file input -->
        <input ref="fileInput" type="file" accept=".xls,.xlsx,.csv" @change="handleFileChange" multiple
            class="hidden" />
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