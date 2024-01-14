<?php
namespace src\Domain\Program\DTO;

use Spatie\LaravelData\Data;

class ProgramUpdateData extends Data
{
    public function __construct(
        public int $id,
        public int $faculty_id,
        public string $title,
        public string $code,
        public string $level,
    ) {
    }
}
