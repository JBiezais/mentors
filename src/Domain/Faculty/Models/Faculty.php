<?php

namespace src\Domain\Faculty\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use src\Domain\Program\Models\Program;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'code'
    ];

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }
}
