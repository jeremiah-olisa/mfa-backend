<?php

namespace App\DTOs;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class QuestionResponseDto implements Arrayable, JsonSerializable
{
    public function __construct(
        public readonly array $data,
        public readonly array $pagination
    ) {}

    public static function fromPaginatedResult(array $result): self
    {
        return new self(
            data: $result['data'],
            pagination: $result['pagination']
        );
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'pagination' => $this->pagination,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
