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
        $data = $result['data'];

        if (is_object($data) && method_exists($data, 'toArray')) {
            $data = $data->toArray();
        }

        return new self(
            data: (array) $data,
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
