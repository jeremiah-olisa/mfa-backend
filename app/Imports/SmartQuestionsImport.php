<?php

namespace App\Imports;

use App\Imports\QuestionsImport;
use App\Imports\QuestionsImportV2;
use Illuminate\Support\Facades\Log;

class SmartQuestionsImport
{
    protected $filePath;
    protected $headers;
    public $selectedImporter;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->determineHeaders();
        $this->selectedImporter = $this->determineImporter();
    }

    protected function determineHeaders()
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $spreadsheet = $reader->load($this->filePath);
        $this->headers = $spreadsheet->getActiveSheet()->toArray()[0];
    }

    protected function determineImporter()
    {
        $normalizedHeaders = array_map('strtolower', $this->headers);
        $normalizedHeaders = array_map('trim', $normalizedHeaders);

        if (in_array('question', $normalizedHeaders) && in_array('options', $normalizedHeaders)) {
            Log::info('Detected V2 format');
            return new QuestionsImportV2();
        }

        if (count(array_filter($normalizedHeaders, 'is_numeric')) > 5) {
            Log::info('Detected V1 format');
            return new QuestionsImport();
        }

        Log::warning('Unable to detect format, defaulting to V1');
        return new QuestionsImport();
    }
}