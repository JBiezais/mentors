<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyProgram extends Model
{
    use HasFactory;

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

    public function spotsTotal()
    {
        return $this->hasMany(Mentor::class, 'program_id')
            ->selectRaw('program_id, SUM(mentees) as total_spots')
            ->groupBy('program_id');
    }
}
