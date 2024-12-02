<?php

namespace App\Repositories;

use App\Models\Subject;
use App\Models\User;

/**
 * @template T of User
 * @template-inherits BaseRepository<T>
 */
class SubjectRepository extends BaseRepository
{
    public function __construct(Subject $subject)
    {
        parent::__construct($subject);
    }
}
