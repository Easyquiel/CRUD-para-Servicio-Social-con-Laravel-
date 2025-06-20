<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

class SocialServiceStudent extends Model
{
    protected $fillable = [
        'user_id',
        'university',
        'career',
        'student_id',
        'phone',
        'emergency_contact',
        'emergency_phone',
        'schedule',
        'start_date',
        'end_date',
        'activities',
        'status',
        'comments'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
