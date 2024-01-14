<?php

namespace src\Domain\Event\Models;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Shared\Concerns\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable =[
        'title',
        'description',
        'location',
        'date',
        'mentors_training',
        'mentees_applying',
        'link',
        'sent'
    ];
}
