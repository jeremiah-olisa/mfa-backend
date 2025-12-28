<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QuestionFilterDto
{
    public function __construct(
        public ?string $test_type = null,
        public ?int $subject_id = null,
        public ?string $subject_name = null,
        public ?string $subject_label = null,
        public ?string $subject = null,
        public ?string $search = null,
        public ?string $question_id = null,
        public ?string $question = null,
        public ?string $sort = null,
    ) {}

    public static function fromRequest(Request $request, ?string $type = null): self
    {
        $validated = $request->validated(); // Or use $request->validated() if available, but here generic input is fine or specific

        return new self(
            test_type: $type ?? $request->input('test_type'),
            subject_id: $request->input('subject_id'),
            subject_name: $request->input('subject_name'),
            subject_label: $request->input('subject_label'),
            subject: $request->input('subject'),
            search: $request->input('search'),
            question_id: $request->input('question_id'),
            question: $request->input('question'),
            sort: $request->input('sort'),
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'test_type' => $this->test_type,
            'subject_id' => $this->subject_id,
            'subject_name' => $this->subject_name,
            'subject_label' => $this->subject_label,
            'subject' => $this->subject,
            'search' => $this->search,
            'question_id' => $this->question_id,
            'question' => $this->question,
            'sort' => $this->sort,
        ], fn($value) => !is_null($value));
    }

    public function buildQuery(Builder $query): Builder
    {
        if ($this->test_type) {
            $query->where('test_type', $this->test_type);
        }

        if ($this->subject_id) {
            $query->where('subject_id', $this->subject_id);
        }

        if ($this->subject_name) {
            $query->whereHas('subject', function ($q) {
                $q->where('name', 'LIKE', '%' . $this->subject_name . '%');
            });
        }

        if ($this->subject_label) {
            $query->whereHas('subject', function ($q) {
                $q->where('label', 'LIKE', '%' . $this->subject_label . '%');
            });
        }

        if ($this->subject) {
             $query->whereHas('subject', function ($q) {
                $q->where('name', 'LIKE', '%' . $this->subject . '%')
                    ->orWhere('label', 'LIKE', '%' . $this->subject . '%');
            });
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('subject', function ($sq) {
                    $sq->where('name', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('label', 'LIKE', '%' . $this->search . '%');
                })
                    ->orWhere('question_id', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('question', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('test_type', 'LIKE', '%' . $this->search . '%');
            });
        }

        // Generic field filters handled safely if properties exist on model, but simple logic here:
        if ($this->question_id) {
            $query->where('question_id', $this->question_id);
        }
        
        if ($this->question) {
            $query->where('question', 'LIKE', '%' . $this->question . '%');
        }

        return $query;
    }
}
