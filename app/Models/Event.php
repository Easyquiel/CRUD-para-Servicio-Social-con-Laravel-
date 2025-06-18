<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'social_service_student_id',
        'title',
        'description',
        'start',
        'end',
        'location',
        'color',
        'type',
        'status'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class)->withPivot('attendance');
    }

    public function socialServiceStudent()
    {
        return $this->belongsTo(SocialServiceStudent::class);
    }
}
