<?php
namespace src\Domain\Student\DTO;

use Spatie\LaravelData\Data;

class StudentCreateData extends Data
{
    public function __construct(
        public int $faculty_id,
        public int $program_id,
        public ?int $mentor_id,
        public string $name,
        public string $lastName,
        public string $phone,
        public string $email,
        public ?string $comment,
        public string $lang,
        public bool $privacy,
    ) {
    }
}
