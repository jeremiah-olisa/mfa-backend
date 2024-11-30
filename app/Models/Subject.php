<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'label'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
