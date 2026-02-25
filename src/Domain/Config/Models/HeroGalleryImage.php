<?php

namespace src\Domain\Config\Models;

use Illuminate\Database\Eloquent\Model;

class HeroGalleryImage extends Model
{
    protected $fillable = ['path', 'sort_order'];
}
