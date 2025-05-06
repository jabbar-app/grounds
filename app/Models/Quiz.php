<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'open_gate_time' => 'datetime',
        'close_gate_time' => 'datetime',
        'announcement_time' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function results()
    {
        return $this->hasMany(QuizResult::class);
    }

    public function registrations()
    {
        return $this->hasMany(QuizRegistration::class);
    }

    public function registeredUsers()
    {
        return $this->belongsToMany(User::class, 'quiz_registrations')
            ->withTimestamps();
    }
}
