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
      'lriCode',
      'level',
    ];

    public function mentors():HasMany
    {
        return $this->hasMany(Mentor::class);
    }
}
