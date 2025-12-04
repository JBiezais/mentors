<?php

namespace src\Domain\Program\Models;

use Database\Factories\ProgramFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Student\Models\Student;

class Program extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory()
    {
        return ProgramFactory::new();
    }

    protected $table = 'study_programs';

    protected $fillable = [
      'faculty_id',
      'title',
      'code',
      'level',
    ];

    public function mentors():HasMany
    {
        return $this->hasMany(Mentor::class, 'program_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'program_id');
    }

    public function spotsTotal(): HasMany
    {
        return $this->hasMany(Mentor::class, 'program_id')
            ->selectRaw('program_id, SUM(mentees) as total_spots')
            ->groupBy('program_id');
    }
}
