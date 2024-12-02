<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Upload, X } from 'lucide-vue-next';

import FormErrorAlert from '@/components/FormErrorAlert.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// Create a ref for the file input element
const fileInput = ref<HTMLInputElement | null>(null);

// Reactive variable to store the file name
const fileName = ref<string | null>(null);

const form = useForm({
    file: null as File | null,
});
// Function to trigger the file input dialog
const triggerFileInput = () => {
    if (fileInput.value) {
        fileInput.value.click(); // Programmatically open the file input
    }
};

const reset = () => {
    fileName.value = null;
    form.file = null;
};

// Handle file selection
const handleFileChange = (event: Event) => {
    form.file = null;
    const fileInputElement = event.target as HTMLInputElement;
    if (fileInputElement.files && fileInputElement.files[0]) {
        fileName.value = fileInputElement.files[0].name; // Set the file name
        // Optionally, you can upload the file here
        uploadFile(fileInputElement.files[0]);
        return;
    }
    fileName.value = null;
};

const submitForm = () => {
    if (form.file) {
        // You can send the form data to your server
        form.post(route('questions.upload'), {
            onSuccess: () => {
                // You can handle successful upload here
                console.log('File uploaded successfully');
                fileName.value = null; // Reset after successful upload
            },
            onError: (errors) => {
                // Handle errors if any
                console.log('Upload failed:', errors);
                fileName.value = null; // Reset after successful upload
            },
        });
    }
};

// Example file upload function (customize as needed)
const uploadFile = (file: File) => {
    // Handle file upload, e.g., send the file to a server or store it locally
    console.log('Uploading file:', file.name);
    form.file = file;
    submitForm();
};
</script>

<template>
    <Button
        variant="outline"
        @click="triggerFileInput"
        :disabled="form.processing"
        class="h-auto text-wrap"
    >
        <Upload class="mr-2" />
        {{ form.processing ? 'Uploading' : 'Upload' }}
        {{ fileName ?? 'Questions' }}

        <X
            v-if="form.file"
            :disabled="form.processing"
            @click="reset"
            class="mr-2 text-red-500 dark:text-red-300"
        />
    </Button>

    <!-- Hidden file input triggered by the button -->
    <input
        ref="fileInput"
        type="file"
        accept=".xls,.xlsx,.csv"
        @change="handleFileChange"
        class="hidden"
    />

    <Teleport defer to="#teleport-alert">
        <FormErrorAlert
            :show-field-name="false"
            :errors="form.errors"
            :error-title="`File Upload Error (${Object.keys(form.errors).length} Errors)`"
        />
    </Teleport>
</template>

<style scoped></style>
