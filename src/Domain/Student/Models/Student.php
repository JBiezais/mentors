<?php

namespace src\Domain\Student\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Student\Builder\StudentBuilder;

/**
 * @property Mentor $mentor
 */
class Student extends Model
{
    use HasFactory;

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
