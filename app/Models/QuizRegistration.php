<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizRegistration extends Model
{
    protected $guarded = ['id'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
