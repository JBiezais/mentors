<?php

namespace src\Domain\Mail\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_ids',
        'student_ids',
        'content',
        'type',
        'sent'
    ];

    protected $casts = [
      'mentor_ids' => 'array',
      'student_ids' => 'array'
    ];
}
