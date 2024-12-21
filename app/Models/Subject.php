<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'label', 'icon_url'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

     /**
     * Relationship with ExamSubjectSyllabus model.
     */
    public function syllabi()
    {
        return $this->hasMany(ExamSubjectSyllabus::class);
    }

    /**
     * Get the syllabus link for a specific exam.
     *
     * @param string $exam
     * @return string|null
     */
    public function getSyllabiForExam(string $exam): ?string
    {
        $syllabus = $this->syllabi()->where('exam', $exam)->get()->toArray();
        return $syllabus;
    }
}
