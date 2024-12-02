<?php

namespace App\Repositories;

use App\Models\Question;

/**
 * @template T of Question
 * @template-inherits BaseRepository<T>
 */
class QuestionRepository extends BaseRepository
{
    public function __construct(Question $question)
    {
        parent::__construct($question);
    }
}
