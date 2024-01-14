<?php
namespace src\Domain\Mentor\DTO;

use Spatie\LaravelData\Data;

class MentorUpdateData extends Data
{
    public function __construct(
        public int $id,
        public int $faculty_id,
        public int $program_id,
        public string $phone,
        public string $email,
        public int $year,
        public string $about,
        public string $why,
        public bool $lv,
        public bool $ru,
        public bool $en,
    ) {
    }
}
