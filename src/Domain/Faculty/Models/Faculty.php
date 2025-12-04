<?php

namespace src\Domain\Faculty\Models;

use Database\Factories\FacultyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\Student\Models\Student;

class Faculty extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory()
    {
        return FacultyFactory::new();
    }

    protected $fillable = [
        'title',
        'code'
    ];

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    public function mentors(): HasMany
    {
        return $this->hasMany(Mentor::class, 'faculty_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'faculty_id');
    }
}
