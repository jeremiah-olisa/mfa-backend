<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    // protected $fillable = ['question_id', 'test_type', 'subject_id', 'question', 'answer_id'];
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
