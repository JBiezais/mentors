<?php
namespace src\Domain\Faculty\DTO;

use Spatie\LaravelData\Data;

class FacultyData extends Data
{
    public function __construct(
        public string $title,
        public string $code
    ) {
    }
}
