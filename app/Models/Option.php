<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['option', 'option_key', 'question_id'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
