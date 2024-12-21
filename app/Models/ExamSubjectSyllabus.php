<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSubjectSyllabus extends Model
{
    protected $fillable = ['exam', 'subject_id', 'syllabus_link'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
