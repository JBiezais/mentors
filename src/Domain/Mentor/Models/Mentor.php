<?php

namespace src\Domain\Mentor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Ramsey\Collection\Collection;
use src\Domain\Mentor\Builders\MentorBuilder;
use src\Domain\Student\Models\Student;

/**
 * @property Collection<int, Student> $students
 */
class Mentor extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'faculty_id',
        'program_id',
        'name',
        'lastName',
        'phone',
        'email',
        'mentees',
        'year',
        'about',
        'why',
        'lv',
        'ru',
        'en',
        'privacy',
        'img',
        'key',
        'status'
    ];

    public function newEloquentBuilder($query): MentorBuilder
    {
        return new MentorBuilder($query);
    }

    public function students():HasMany
    {
        return $this->hasMany(Student::class);
    }
}
