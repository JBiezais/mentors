<?php

namespace src\Domain\Student\Models;

use Database\Factories\StudentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Student\Builder\StudentBuilder;

/**
 * @property Mentor $mentor
 */
class Student extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected static function newFactory()
    {
        return StudentFactory::new();
    }

    protected $fillable = [
        'faculty_id',
        'program_id',
        'mentor_id',
        'name',
        'lastName',
        'phone',
        'email',
        'comment',
        'lang',
        'privacy',
    ];

    public function newEloquentBuilder($query): StudentBuilder
    {
        return new StudentBuilder($query);
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }
}
