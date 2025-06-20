<?php

namespace App\Imports;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class SmartMultipleQuestionsImport
{
    protected $files;
    protected $errors = [];
    protected $successCount = 0;
    protected $importers = []; // Add this property

    public function __construct(array $files)
    {
        $this->files = $files;
    }


    public function importFiles()
    {
        foreach ($this->files as $file) {
            try {
                $smartImport = new SmartQuestionsImport($file->getRealPath());
                $importer = $smartImport->selectedImporter;

                // Add method to set filename in your base importers
                if (method_exists($importer, 'setFileName')) {
                    $importer->setFileName($file->getClientOriginalName());
                }

                Excel::import($importer, $file);

                $importer->handleErrorsAndFailures();

                $this->importers[] = $importer; // Track successful importers
                $this->successCount++;

            } catch (\Exception $e) {
                Log::error($e);
                
                $this->errors[] = [
                    'file' => $file->getClientOriginalName(),
                    'message' => $e->getMessage(),
                    'errors' => ($e instanceof ValidationException) ? $e->errors() : []
                ];
            }
        }

        return $this;
    }

    public function handleErrorsAndFailures(): void
    {
        $allErrors = [];
        $allFailures = [];

        // Process each file's errors
        foreach ($this->errors as $fileError) {
            if (isset($fileError['errors']) && is_array($fileError['errors'])) {
                foreach ($fileError['errors'] as $error) {
                    $allErrors[] = [
                        'file' => $fileError['file'] ?? 'Unknown file',
                        'message' => $this->normalizeErrorMessage($error)
                    ];
                }
            } else {
                $allErrors[] = [
                    'file' => $fileError['file'] ?? 'Unknown file',
                    'message' => $this->normalizeErrorMessage($fileError['message'] ?? 'Unknown error')
                ];
            }
        }

        // Process failures from individual importers
        foreach ($this->importers as $importer) {
            if (method_exists($importer, 'getFailures')) {
                $failures = $importer->getFailures();
                foreach ($failures as $failure) {
                    $allFailures[] = [
                        'file' => method_exists($importer, 'getFileName') ? $importer->getFileName() : 'Unknown file',
                        'row' => $failure->row(),
                        'attribute' => $failure->attribute(),
                        'errors' => $failure->errors(),
                        'values' => $failure->values()
                    ];
                }
            }
        }

        if (!empty($allErrors) || !empty($allFailures)) {
            $flattenedMessages = [];

            // Format errors
            foreach ($allErrors as $error) {
                $file = $error['file'];
                $message = $error['message'];
                $flattenedMessages[] = "[File: $file] Error: $message";
            }

            // Format failures
            foreach ($allFailures as $failure) {
                $file = $failure['file'] ?? 'Unknown file';
                $row = $failure['row'] ?? 'Unknown row';
                $attribute = $failure['attribute'] ?? 'Unknown attribute';

                foreach ($failure['errors'] ?? [] as $message) {
                    $flattenedMessages[] = "[File: $file] Row $row, $attribute: $message";
                }
            }

            // Filter out specific messages if needed
            $flattenedMessages = array_filter(
                $flattenedMessages,
                fn($message) => !strpos($message, 'The Question Text has already been used.')
            );

            session()->flash('message', sprintf(
                '%d files processed successfully. %d errors encountered.',
                $this->successCount,
                count($flattenedMessages)
            ));

            throw ValidationException::withMessages($flattenedMessages);
        }
    }


    /**
     * Safely convert any error type to a readable string
     * Handles all possible data types without throwing errors
     */
    protected function normalizeErrorMessage($error): string
    {
        try {
            // Handle null explicitly first
            if ($error === null) {
                return 'Unknown error';
            }

            // Handle Throwable objects
            if ($error instanceof \Throwable) {
                return $error->getMessage() ?: 'Exception occurred';
            }

            // Handle arrays
            if (is_array($error)) {
                $messages = [];
                foreach ($error as $key => $value) {
                    // Recursively normalize nested values
                    $normalized = $this->normalizeErrorMessage($value);
                    if ($normalized !== '') {
                        // Add key if it's a named array (like validation errors)
                        $prefix = is_string($key) && $key !== '0' ? "$key: " : '';
                        $messages[] = $prefix . $normalized;
                    }
                }
                return implode(' ', $messages);
            }

            // Handle objects with __toString()
            if (is_object($error) && method_exists($error, '__toString')) {
                return (string) $error;
            }

            // Handle scalar types
            if (is_scalar($error) || (is_object($error) && !$error instanceof \Throwable)) {
                $stringVal = strval($error);
                return $stringVal !== '' ? $stringVal : 'Empty error message';
            }

            // Handle resources and other types
            return 'Unreadable error (' . gettype($error) . ')';
        } catch (\Throwable $e) {
            // Fallback if something goes wrong during normalization
            return 'Error message could not be processed';
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }
}